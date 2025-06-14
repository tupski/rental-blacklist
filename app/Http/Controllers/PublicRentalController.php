<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RentalBlacklist;

class PublicRentalController extends Controller
{
    /**
     * Tampilkan profil publik pemilik rental
     */
    public function profile($id)
    {
        $rental = User::where('role', 'pengusaha_rental')
                     ->where('id', $id)
                     ->firstOrFail();

        // Statistik laporan yang dibuat oleh rental ini
        $totalReports = RentalBlacklist::where('user_id', $rental->id)->count();
        $recentReports = RentalBlacklist::where('user_id', $rental->id)
                                      ->with('user')
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get();

        // Statistik berdasarkan jenis rental
        $reportsByType = RentalBlacklist::where('user_id', $rental->id)
                                       ->selectRaw('jenis_rental, COUNT(*) as total')
                                       ->groupBy('jenis_rental')
                                       ->get();

        // Statistik berdasarkan bulan (6 bulan terakhir)
        $monthlyStats = RentalBlacklist::where('user_id', $rental->id)
                                      ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
                                      ->where('created_at', '>=', now()->subMonths(6))
                                      ->groupBy('year', 'month')
                                      ->orderBy('year', 'desc')
                                      ->orderBy('month', 'desc')
                                      ->get();

        return view('public.rental-profile', compact(
            'rental',
            'totalReports',
            'recentReports',
            'reportsByType',
            'monthlyStats'
        ));
    }

    /**
     * Tampilkan timeline laporan untuk terlapor tertentu
     */
    public function reportTimeline($nik)
    {
        // Ambil semua laporan untuk NIK ini, diurutkan dari yang terlama
        $reports = RentalBlacklist::where('nik', $nik)
                                 ->where('status_validitas', 'Valid')
                                 ->with('user')
                                 ->orderBy('tanggal_kejadian', 'asc')
                                 ->get();

        if ($reports->isEmpty()) {
            abort(404, 'Tidak ada laporan ditemukan untuk NIK ini');
        }

        // Ambil data terlapor dari laporan pertama
        $terlapor = $reports->first();
        $totalReports = $reports->count();

        // Cek apakah user memiliki akses uncensored
        $showUncensored = false;
        if (auth()->check()) {
            $user = auth()->user();
            // Admin dan rental owner dapat melihat data tanpa sensor
            $showUncensored = in_array($user->role, ['admin', 'rental_owner']);
        }

        // Statistik berdasarkan jenis rental
        $reportsByType = $reports->groupBy('jenis_rental')->map(function ($group) {
            return $group->count();
        });

        // Statistik berdasarkan jenis laporan
        $reportsByCategory = $reports->flatMap(function ($report) {
            return is_array($report->jenis_laporan) ? $report->jenis_laporan : [$report->jenis_laporan];
        })->countBy();

        // Tentukan tingkat bahaya berdasarkan jumlah laporan
        $dangerLevel = 'low';
        if ($totalReports >= 5) {
            $dangerLevel = 'high';
        } elseif ($totalReports >= 3) {
            $dangerLevel = 'medium';
        }

        return view('public.report-timeline', compact(
            'reports',
            'terlapor',
            'totalReports',
            'reportsByType',
            'reportsByCategory',
            'dangerLevel',
            'showUncensored'
        ));
    }

    /**
     * Tampilkan detail lengkap laporan blacklist
     */
    public function reportDetail($id)
    {
        $report = RentalBlacklist::with('user')->findOrFail($id);

        // Cek apakah laporan valid
        if ($report->status_validitas !== 'Valid') {
            abort(404, 'Laporan tidak ditemukan atau belum divalidasi');
        }

        // Cek apakah user memiliki akses uncensored
        $showUncensored = false;
        if (auth()->check()) {
            $user = auth()->user();
            // Admin dan rental owner dapat melihat data tanpa sensor
            $showUncensored = in_array($user->role, ['admin', 'rental_owner']);
        }

        // Ambil laporan lain dengan NIK yang sama untuk konteks
        $relatedReports = RentalBlacklist::where('nik', $report->nik)
                                       ->where('id', '!=', $report->id)
                                       ->where('status_validitas', 'Valid')
                                       ->with('user')
                                       ->orderBy('tanggal_kejadian', 'desc')
                                       ->limit(5)
                                       ->get();

        $totalReports = RentalBlacklist::where('nik', $report->nik)
                                     ->where('status_validitas', 'Valid')
                                     ->count();

        // Tentukan tingkat bahaya berdasarkan jenis laporan
        $dangerLevel = 'low';
        $dangerText = 'Rendah';
        if ($report->jenis_laporan && is_array($report->jenis_laporan)) {
            $highRiskCategories = ['Tidak Mengembalikan', 'Merusak Barang', 'Tidak Bayar', 'Kabur'];
            $hasHighRisk = !empty(array_intersect($report->jenis_laporan, $highRiskCategories));
            if ($hasHighRisk) {
                $dangerLevel = 'high';
                $dangerText = 'Tinggi';
            } elseif (count($report->jenis_laporan) > 1) {
                $dangerLevel = 'medium';
                $dangerText = 'Sedang';
            }
        }

        return view('public.report-detail', compact(
            'report',
            'relatedReports',
            'totalReports',
            'showUncensored',
            'dangerLevel',
            'dangerText'
        ));
    }
}
