<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalBlacklist;
use App\Models\User;
use App\Models\GuestReport;
use App\Models\TopupRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports');
    }

    public function analytics()
    {
        $data = [
            'total_blacklists' => RentalBlacklist::count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'pending_reports' => GuestReport::where('status', 'pending')->count(),
            'pending_topups' => TopupRequest::where('status', 'pending')->count(),
            'monthly_blacklists' => $this->getMonthlyBlacklists(),
            'monthly_users' => $this->getMonthlyUsers(),
            'blacklist_by_type' => $this->getBlacklistByType(),
            'user_by_role' => $this->getUserByRole(),
        ];

        return view('admin.analytics', compact('data'));
    }

    public function generate(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now();
        $type = $request->report_type ?? 'all';

        $data = [];

        switch ($type) {
            case 'blacklist':
                $data = RentalBlacklist::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->with('user')
                    ->get();
                break;
            case 'users':
                $data = User::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->where('role', '!=', 'admin')
                    ->get();
                break;
            case 'topup':
                $data = TopupRequest::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->with('user')
                    ->get();
                break;
            default:
                $data = [
                    'blacklists' => RentalBlacklist::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                    'users' => User::whereBetween('created_at', [$dateFrom, $dateTo])->where('role', '!=', 'admin')->count(),
                    'topups' => TopupRequest::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                ];
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'period' => [
                'from' => $dateFrom->format('d/m/Y'),
                'to' => $dateTo->format('d/m/Y')
            ]
        ]);
    }

    public function export(Request $request)
    {
        $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->startOfMonth();
        $dateTo = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now();
        $type = $request->report_type ?? 'all';

        $filename = 'report_' . $type . '_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($dateFrom, $dateTo, $type) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'blacklist':
                    fputcsv($file, ['ID', 'Nama', 'NIK', 'No HP', 'Jenis Rental', 'Status', 'Tanggal']);
                    $data = RentalBlacklist::whereBetween('created_at', [$dateFrom, $dateTo])->get();
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->nama_lengkap,
                            $item->nik,
                            $item->no_hp,
                            $item->jenis_rental,
                            $item->status_validitas,
                            $item->created_at->format('d/m/Y H:i')
                        ]);
                    }
                    break;
                case 'users':
                    fputcsv($file, ['ID', 'Nama', 'Email', 'Role', 'Status Email', 'Tanggal Daftar']);
                    $data = User::whereBetween('created_at', [$dateFrom, $dateTo])->where('role', '!=', 'admin')->get();
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->name,
                            $item->email,
                            $item->role,
                            $item->email_verified_at ? 'Verified' : 'Not Verified',
                            $item->created_at->format('d/m/Y H:i')
                        ]);
                    }
                    break;
                default:
                    fputcsv($file, ['Jenis', 'Jumlah']);
                    fputcsv($file, ['Blacklist', RentalBlacklist::whereBetween('created_at', [$dateFrom, $dateTo])->count()]);
                    fputcsv($file, ['Users', User::whereBetween('created_at', [$dateFrom, $dateTo])->where('role', '!=', 'admin')->count()]);
                    fputcsv($file, ['Topup Requests', TopupRequest::whereBetween('created_at', [$dateFrom, $dateTo])->count()]);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getMonthlyBlacklists()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = RentalBlacklist::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    private function getMonthlyUsers()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('role', '!=', 'admin')
                ->count();
            $data[] = $count;
        }
        return $data;
    }

    private function getBlacklistByType()
    {
        return RentalBlacklist::selectRaw('jenis_rental, COUNT(*) as count')
            ->groupBy('jenis_rental')
            ->pluck('count', 'jenis_rental')
            ->toArray();
    }

    private function getUserByRole()
    {
        return User::selectRaw('role, COUNT(*) as count')
            ->where('role', '!=', 'admin')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
    }
}
