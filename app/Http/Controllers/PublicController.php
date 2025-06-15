<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Helpers\PhoneHelper;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'cari' => 'required|string|min:3'
        ]);

        $search = $request->input('cari');

        $results = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user')
            ->get()
            ->map(function ($item) use ($search) {
                // Cek apakah user sudah login (rental terverifikasi)
                $isAuthenticated = auth()->check();
                $user = auth()->user();

                // Cek apakah user adalah admin atau pemilik rental
                $isAdmin = $isAuthenticated && $user->role === 'admin';
                $isRentalOwner = $isAuthenticated && $user->role === 'pengusaha_rental';
                $shouldShowFullData = $isAdmin || $isRentalOwner;

                // Normalisasi search untuk nomor HP
                $normalizedSearch = PhoneHelper::normalize($search);

                // Cek apakah search cocok dengan data
                $isSearchingNik = $item->nik === $search;
                $isSearchingName = stripos($item->nama_lengkap, $search) !== false;
                $isSearchingPhone = $item->no_hp === $search || $item->no_hp === $normalizedSearch;

                // Cek apakah query lengkap cocok (exact match)
                $isExactNameMatch = strcasecmp($item->nama_lengkap, $search) === 0;
                $isExactNikMatch = $item->nik === $search;
                $isExactPhoneMatch = $item->no_hp === $search || $item->no_hp === $normalizedSearch;
                $isExactMatch = $isExactNameMatch || $isExactNikMatch || $isExactPhoneMatch;

                // Cek apakah user sudah unlock data ini
                $isUnlocked = $isAuthenticated && $user->hasUnlockedData($item->id);

                // Jika user adalah admin, pengusaha rental, sudah unlock, atau query lengkap cocok, tampilkan data lengkap
                if ($shouldShowFullData || ($isAuthenticated && $isUnlocked) || $isExactMatch) {
                    return [
                        'id' => $item->id,
                        'nama_lengkap' => $item->nama_lengkap,
                        'nik' => $item->nik,
                        'no_hp' => $item->no_hp,
                        'alamat' => $item->alamat,
                        'jenis_rental' => $item->jenis_rental,
                        'jenis_laporan' => $item->jenis_laporan,
                        'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                        'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                        'pelapor' => $item->user->name,
                        'is_verified' => true // Menandakan data sudah terverifikasi/unlocked
                    ];
                }

                // Untuk user tidak login atau query parsial, gunakan logika sensor dengan highlighting
                $displayNama = $item->sensored_nama;
                $displayNik = $item->sensored_nik;
                $displayPhone = $item->sensored_no_hp;
                $displayAlamat = $item->sensored_alamat;

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
                    'alamat' => $displayAlamat,
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

        // Cek apakah user sudah login
        $isAuthenticated = auth()->check();
        $user = auth()->user();

        // Cek apakah user adalah admin atau pemilik rental
        $isAdmin = $isAuthenticated && $user->role === 'admin';
        $isRentalOwner = $isAuthenticated && $user->role === 'pengusaha_rental';
        $shouldShowFullData = $isAdmin || $isRentalOwner;

        // Cek apakah user sudah unlock data ini (untuk user biasa)
        $isUnlocked = $isAuthenticated && $user->hasUnlockedData($id);

        // Jika admin, pemilik rental, atau sudah unlock, tampilkan data lengkap
        if ($shouldShowFullData || $isUnlocked) {
            return response()->json([
                'success' => true,
                'message' => $shouldShowFullData ? 'Data lengkap tersedia untuk admin/pemilik rental.' : 'Data lengkap karena sudah di-unlock.',
                'data' => [
                    'nama_lengkap' => $blacklist->nama_lengkap,
                    'nik' => $blacklist->nik,
                    'no_hp' => $blacklist->no_hp,
                    'alamat' => $blacklist->alamat,
                    'jenis_kelamin' => $blacklist->jenis_kelamin,
                    'jenis_rental' => $blacklist->jenis_rental,
                    'jenis_laporan' => $blacklist->jenis_laporan,
                    'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                    'kronologi' => $blacklist->kronologi,
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                    'pelapor' => $blacklist->user->name,
                    'pelapor_id' => $blacklist->user->id,
                    'pelapor_role' => $blacklist->user->role,
                    'is_verified' => true,
                    'is_full_access' => true
                ]
            ]);
        }

        // Untuk user tidak login atau user biasa yang belum unlock, tampilkan data sensor
        return response()->json([
            'success' => true,
            'message' => 'Untuk melihat data lengkap, silakan login sebagai pengusaha rental (GRATIS) atau beli kredit untuk akses sekali lihat.',
            'data' => [
                'nama_lengkap' => $blacklist->sensored_nama,
                'nik' => $blacklist->sensored_nik,
                'no_hp' => $blacklist->sensored_no_hp,
                'alamat' => $blacklist->sensored_alamat,
                'jenis_kelamin' => $blacklist->jenis_kelamin,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'pelapor' => $blacklist->user->name,
                'pelapor_id' => $blacklist->user->id,
                'pelapor_role' => $blacklist->user->role,
                'is_verified' => false,
                'is_full_access' => false
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

    /**
     * Unlock blacklist data for authenticated user
     */
    public function unlockData($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::findOrFail($id);

        // Check if user is pengusaha_rental (they get free access)
        if ($user->role === 'pengusaha_rental') {
            return response()->json([
                'success' => false,
                'message' => 'Pengusaha rental mendapat akses gratis ke semua data'
            ]);
        }

        // Check if already unlocked
        if ($user->hasUnlockedData($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Data sudah dibuka sebelumnya'
            ]);
        }

        // Determine price based on rental type
        $detailPrices = [
            'Rental Mobil' => 1500,
            'Rental Motor' => 1500,
            'Rental Kamera' => 1000,
            'Rental Lainnya' => 800,
        ];

        $price = $detailPrices[$blacklist->jenis_rental] ?? 800;

        try {
            $user->unlockData($id, $price, "Unlock data blacklist: {$blacklist->nama_lengkap}");

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuka!',
                'data' => [
                    'amount_paid' => $price,
                    'remaining_balance' => $user->getCurrentBalance()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function fullDetail($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access (pengusaha_rental or has unlocked)
        if (!$user || ($user->role !== 'pengusaha_rental' && !$user->hasUnlockedData($id))) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data lengkap ini'
            ], 403);
        }

        // Format data lengkap sesuai dengan semua field dari form lapor
        $data = [
            'id' => $blacklist->id,
            // Informasi Penyewa
            'nama_lengkap' => $blacklist->nama_lengkap,
            'nik' => $blacklist->nik,
            'jenis_kelamin' => $blacklist->jenis_kelamin,
            'jenis_kelamin_formatted' => $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'no_hp' => $blacklist->no_hp,
            'alamat' => $blacklist->alamat,

            // Foto Penyewa dan KTP/SIM
            'foto_penyewa' => $blacklist->foto_penyewa ? json_decode($blacklist->foto_penyewa, true) : [],
            'foto_ktp_sim' => $blacklist->foto_ktp_sim ? json_decode($blacklist->foto_ktp_sim, true) : [],

            // Informasi Pelapor
            'nama_perusahaan_rental' => $blacklist->nama_perusahaan_rental,
            'nama_penanggung_jawab' => $blacklist->nama_penanggung_jawab,
            'no_wa_pelapor' => $blacklist->no_wa_pelapor,
            'email_pelapor' => $blacklist->email_pelapor,
            'alamat_usaha' => $blacklist->alamat_usaha,
            'website_usaha' => $blacklist->website_usaha,

            // Detail Masalah
            'jenis_rental' => $blacklist->jenis_rental,
            'tanggal_sewa' => $blacklist->tanggal_sewa,
            'tanggal_sewa_formatted' => $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : null,
            'tanggal_kejadian' => $blacklist->tanggal_kejadian,
            'tanggal_kejadian_formatted' => $blacklist->tanggal_kejadian->format('d/m/Y'),
            'jenis_kendaraan' => $blacklist->jenis_kendaraan,
            'nomor_polisi' => $blacklist->nomor_polisi,
            'nilai_kerugian' => $blacklist->nilai_kerugian,
            'nilai_kerugian_formatted' => $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : null,
            'jenis_laporan' => $blacklist->jenis_laporan ? json_decode($blacklist->jenis_laporan, true) : [],
            'kronologi' => $blacklist->kronologi,

            // Status Penanganan
            'status_penanganan' => $blacklist->status_penanganan ? json_decode($blacklist->status_penanganan, true) : [],
            'status_lainnya' => $blacklist->status_lainnya,

            // Bukti Pendukung
            'bukti' => $blacklist->bukti ? json_decode($blacklist->bukti, true) : [],

            // Persetujuan
            'persetujuan' => $blacklist->persetujuan,
            'nama_pelapor_ttd' => $blacklist->nama_pelapor_ttd,
            'tanggal_pelaporan' => $blacklist->tanggal_pelaporan,
            'tanggal_pelaporan_formatted' => $blacklist->tanggal_pelaporan ? $blacklist->tanggal_pelaporan->format('d/m/Y') : null,
            'tipe_pelapor' => $blacklist->tipe_pelapor,

            // Informasi Sistem
            'status_validitas' => $blacklist->status_validitas,
            'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
            'pelapor' => $blacklist->user->name,
            'created_at' => $blacklist->created_at->format('d/m/Y H:i'),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function printDetail($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access
        if (!$user || ($user->role !== 'pengusaha_rental' && !$user->hasUnlockedData($id))) {
            abort(403, 'Anda tidak memiliki akses ke data lengkap ini');
        }

        return view('public.print-detail', compact('blacklist'));
    }

    public function downloadPDF($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access
        if (!$user || ($user->role !== 'pengusaha_rental' && !$user->hasUnlockedData($id))) {
            abort(403, 'Anda tidak memiliki akses ke data lengkap ini');
        }

        $pdf = Pdf::loadView('public.pdf-detail', compact('blacklist'));
        return $pdf->download('laporan-blacklist-' . $blacklist->nik . '-' . date('Y-m-d') . '.pdf');
    }
}
