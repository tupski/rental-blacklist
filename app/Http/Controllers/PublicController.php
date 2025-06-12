<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use App\Models\Setting;

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

        return view('home', compact('stats', 'settings'));
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
                // Jangan sensor value yang dicari
                $isSearchingNik = $item->nik === $search;
                $isSearchingName = stripos($item->nama_lengkap, $search) !== false;

                return [
                    'id' => $item->id,
                    'nama_lengkap' => $isSearchingName ? $item->nama_lengkap : $item->sensored_nama,
                    'nik' => $isSearchingNik ? $item->nik : $item->sensored_nik,
                    'no_hp' => $item->sensored_no_hp,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name
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
}
