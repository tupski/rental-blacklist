<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        // Handle search from URL parameter
        $searchQuery = $request->get('cari');

        return view('dashboard', compact('stats', 'recentReports', 'searchQuery'));
    }
}
