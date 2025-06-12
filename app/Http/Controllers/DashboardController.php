<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laporan' => RentalBlacklist::count(),
            'laporan_saya' => RentalBlacklist::where('user_id', Auth::id())->count(),
            'laporan_valid' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'laporan_pending' => RentalBlacklist::where('status_validitas', 'Pending')->count(),
        ];

        $recentReports = RentalBlacklist::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentReports'));
    }
}
