<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function printDetail($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Only rental owners can access this
        if (Auth::user()->role !== 'pengusaha_rental') {
            abort(403, 'Akses ditolak');
        }

        return view('rental.print-detail', compact('blacklist'));
    }

    public function downloadPDF($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Only rental owners can access this
        if (Auth::user()->role !== 'pengusaha_rental') {
            abort(403, 'Akses ditolak');
        }

        $pdf = Pdf::loadView('rental.pdf-detail', compact('blacklist'));
        return $pdf->download('laporan-blacklist-' . $blacklist->nik . '-' . date('Y-m-d') . '.pdf');
    }
}
