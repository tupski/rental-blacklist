<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use App\Models\User;
use App\Models\GuestReport;
use App\Models\TopupRequest;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_blacklist' => RentalBlacklist::count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'pending_reports' => GuestReport::where('status', 'pending')->count(),
            'pending_topups' => TopupRequest::where('status', 'pending')->count(),
        ];

        // Get recent blacklist reports
        $recentBlacklist = RentalBlacklist::with('user')
                                        ->latest()
                                        ->limit(10)
                                        ->get();

        // Get pending guest reports
        $pendingReports = GuestReport::where('status', 'pending')
                                   ->latest()
                                   ->limit(10)
                                   ->get();

        return view('admin.dashboard', compact('stats', 'recentBlacklist', 'pendingReports'));
    }

    public function reports()
    {
        // TODO: Implement reports functionality
        return view('admin.reports');
    }

    public function analytics()
    {
        // TODO: Implement analytics functionality
        return view('admin.analytics');
    }

    public function maintenance()
    {
        // TODO: Implement maintenance functionality
        return view('admin.maintenance');
    }

    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');

            return redirect()->back()->with('success', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function optimize()
    {
        try {
            \Artisan::call('optimize');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');

            return redirect()->back()->with('success', 'Application optimized successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to optimize: ' . $e->getMessage());
        }
    }
}
