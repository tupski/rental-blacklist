<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Helpers\PhoneHelper;

class PublicController extends Controller
{
    public function index()
    {
        // Statistik untuk halaman utama
        $stats = [
            'total_laporan' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'total_pelanggan_bermasalah' => RentalBlacklist::where('status_validitas', 'Valid')->distinct('nik')->count(),
            'rental_terdaftar' => RentalBlacklist::distinct('user_id')->count(),
            'laporan_bulan_ini' => RentalBlacklist::where('status_validitas', 'Valid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get settings untuk tampilan
        $settings = [
            'site_name' => Setting::get('site_name', 'RentalGuard'),
            'site_tagline' => Setting::get('site_tagline', 'Sistem Blacklist Rental Indonesia'),
            'hero_title' => Setting::get('hero_title', 'Lindungi Bisnis Rental Anda'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'Cek data blacklist pelanggan sebelum menyewakan barang. 100% Gratis untuk pengusaha rental!'),
            'meta_title' => Setting::get('meta_title', 'RentalGuard - Sistem Blacklist Rental Indonesia'),
            'meta_description' => Setting::get('meta_description', 'Sistem blacklist rental terpercaya di Indonesia. Cek data pelanggan bermasalah sebelum menyewakan barang Anda. Gratis untuk pengusaha rental.'),
            'meta_keywords' => Setting::get('meta_keywords', 'blacklist rental, rental indonesia, cek pelanggan rental, sistem blacklist, rental bermasalah'),
            'contact_email' => Setting::get('contact_email', 'support@rentalguard.id'),
            'facebook_url' => Setting::get('facebook_url', ''),
            'twitter_url' => Setting::get('twitter_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'whatsapp_number' => Setting::get('whatsapp_number', ''),
        ];

        // Get sponsors
        $homeTopSponsors = Sponsor::active()->position('home_top')->orderBy('sort_order')->get();
        $homeBottomSponsors = Sponsor::active()->position('home_bottom')->orderBy('sort_order')->get();

        return view('home', compact('stats', 'settings', 'homeTopSponsors', 'homeBottomSponsors'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3'
        ]);

        $search = $request->input('search');

        $results = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user')
            ->get()
            ->map(function ($item) use ($search) {
                // Cek apakah user sudah login (rental terverifikasi)
                $isAuthenticated = auth()->check();

                // Normalisasi search untuk nomor HP
                $normalizedSearch = PhoneHelper::normalize($search);

                // Cek apakah search cocok dengan data
                $isSearchingNik = $item->nik === $search;
                $isSearchingName = stripos($item->nama_lengkap, $search) !== false;
                $isSearchingPhone = $item->no_hp === $search || $item->no_hp === $normalizedSearch;

                // Jika user sudah login (rental terverifikasi), tampilkan data lengkap
                if ($isAuthenticated) {
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'no_hp' => $item->no_hp,
                        'jenis_rental' => $item->jenis_rental,
                        'jenis_laporan' => $item->jenis_laporan,
                        'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                        'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                        'pelapor' => $item->user->name,
                        'is_verified' => true // Menandakan rental terverifikasi
                    ];
                }

                // Untuk user tidak login, gunakan logika sensor dengan highlighting
                $displayNama = $item->sensored_nama;
                $displayNik = $item->sensored_nik;
                $displayPhone = $item->sensored_no_hp;

                // Jika search cocok dengan nama, tampilkan bagian yang cocok
                if ($isSearchingName) {
                    $displayNama = $this->highlightSearchInName($item->nama_lengkap, $search);
                }

                // Jika search cocok dengan NIK, tampilkan bagian yang cocok
                if ($isSearchingNik) {
                    $displayNik = $this->highlightSearchInNik($item->nik, $search);
                }

                // Jika search cocok dengan nomor HP, tampilkan bagian yang cocok
                if ($isSearchingPhone) {
                    $displayPhone = $this->highlightSearchInPhone($item->no_hp, $search, $normalizedSearch);
                }

                return [
                    'id' => $item->id,
                    'nama_lengkap' => $displayNama,
                    'nik' => $displayNik,
                    'no_hp' => $displayPhone,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name,
                    'is_verified' => false
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => $results->count()
        ]);
    }

    public function detail($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Untuk melihat data lengkap, silakan login sebagai pengusaha rental (GRATIS) atau beli kredit untuk akses sekali lihat.',
            'data' => [
                'nama_lengkap' => $blacklist->sensored_nama,
                'nik' => $blacklist->sensored_nik,
                'no_hp' => $blacklist->sensored_no_hp,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'pelapor' => $blacklist->user->name
            ]
        ]);
    }

    /**
     * Highlight search term in name while keeping other parts censored
     */
    private function highlightSearchInName($fullName, $search)
    {
        $words = explode(' ', $fullName);
        $result = [];

        foreach ($words as $word) {
            if (stripos($word, $search) !== false) {
                // Jika kata mengandung search term, tampilkan utuh
                $result[] = $word;
            } else {
                // Sensor kata yang tidak mengandung search term
                if (strlen($word) <= 2) {
                    $result[] = $word;
                } else {
                    $first = substr($word, 0, 1);
                    $last = substr($word, -1);
                    $middle = str_repeat('*', strlen($word) - 2);
                    $result[] = $first . $middle . $last;
                }
            }
        }

        return implode(' ', $result);
    }

    /**
     * Highlight search term in NIK while keeping other parts censored
     */
    private function highlightSearchInNik($fullNik, $search)
    {
        $searchPos = strpos($fullNik, $search);
        if ($searchPos !== false) {
            $before = substr($fullNik, 0, $searchPos);
            $after = substr($fullNik, $searchPos + strlen($search));

            // Sensor bagian sebelum dan sesudah search
            $sensoredBefore = str_repeat('*', strlen($before));
            $sensoredAfter = str_repeat('*', strlen($after));

            return $sensoredBefore . $search . $sensoredAfter;
        }

        // Fallback ke sensor biasa
        if (strlen($fullNik) >= 8) {
            $start = substr($fullNik, 0, 4);
            $end = substr($fullNik, -4);
            $middle = str_repeat('*', strlen($fullNik) - 8);
            return $start . $middle . $end;
        }
        return $fullNik;
    }

    /**
     * Highlight search term in phone while keeping other parts censored
     */
    private function highlightSearchInPhone($fullPhone, $originalSearch, $normalizedSearch)
    {
        // Cari posisi search term
        $searchTerm = $originalSearch;
        $searchPos = strpos($fullPhone, $searchTerm);

        // Jika tidak ditemukan dengan original search, coba dengan normalized
        if ($searchPos === false && $normalizedSearch !== $originalSearch) {
            $searchTerm = $normalizedSearch;
            $searchPos = strpos($fullPhone, $searchTerm);
        }

        if ($searchPos !== false) {
            $before = substr($fullPhone, 0, $searchPos);
            $after = substr($fullPhone, $searchPos + strlen($searchTerm));

            // Sensor bagian sebelum dan sesudah search
            $sensoredBefore = str_repeat('*', strlen($before));
            $sensoredAfter = str_repeat('*', strlen($after));

            return $sensoredBefore . $searchTerm . $sensoredAfter;
        }

        // Fallback ke sensor biasa
        if (strlen($fullPhone) >= 6) {
            $start = substr($fullPhone, 0, 4);
            $end = substr($fullPhone, -2);
            $middle = str_repeat('*', strlen($fullPhone) - 6);
            return $start . $middle . $end;
        }
        return $fullPhone;
    }
}
