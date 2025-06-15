<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use App\Models\UserUnlock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $stats = [
            'total_laporan' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'data_dibuka' => UserUnlock::where('user_id', $user->id)->count(),
            'saldo_tersisa' => $user->getFormattedBalance(),
            'total_pengeluaran' => UserUnlock::where('user_id', $user->id)->sum('amount_paid'),
        ];

        $recentReports = RentalBlacklist::with('user')
            ->where('status_validitas', 'Valid')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($report) use ($user) {
                // Check if user has unlocked this data or NIK
                $isUnlocked = $user && ($user->hasUnlockedData($report->id) || $user->hasUnlockedNik($report->nik));

                return [
                    'id' => $report->id,
                    'nama_lengkap' => $report->nama_lengkap, // Tampilkan tanpa sensor
                    'nik' => $report->nik, // Tampilkan tanpa sensor
                    'no_hp' => $report->no_hp, // Tampilkan tanpa sensor
                    'jenis_rental' => $report->jenis_rental,
                    'status_validitas' => $report->status_validitas,
                    'created_at' => $report->created_at,
                    'user' => $report->user,
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($report->nik),
                    'is_unlocked' => $isUnlocked,
                    'price' => $this->getDetailPrice($report->jenis_rental)
                ];
            });

        // Handle search from URL parameter
        $searchQuery = $request->get('cari');

        return view('user.dashboard', compact('stats', 'recentReports', 'searchQuery'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();

        // Check if user can search
        if (!$user->canSearchData()) {
            $message = 'Akses pencarian ditolak.';
            if (!$user->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan fitur pencarian.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk menggunakan fitur pencarian.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'account_status' => $user->account_status,
                'email_verified' => $user->hasVerifiedEmail()
            ], 403);
        }

        $request->validate([
            'search' => 'required|string|min:3',
            'page' => 'nullable|integer|min:1'
        ]);

        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 5;

        $query = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user');

        // Get total count for pagination
        $total = $query->count();

        // Apply pagination
        $results = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($item) {
                $user = Auth::user();

                // Cek apakah user sudah unlock data ini atau NIK ini
                $isUnlocked = $user->hasUnlockedData($item->id) || $user->hasUnlockedNik($item->nik);

                // Tampilkan data tanpa sensor untuk semua user
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->nama_lengkap,
                    'nik' => $item->nik,
                    'no_hp' => $item->no_hp,
                    'alamat' => $item->alamat,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'status_validitas' => $item->status_validitas,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name,
                    'price' => $this->getDetailPrice($item->jenis_rental),
                    'is_unlocked' => $isUnlocked
                ];
            });

        $hasMore = ($page * $perPage) < $total;

        return response()->json([
            'success' => true,
            'data' => $results,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'has_more' => $hasMore
            ]
        ]);
    }

    public function unlock($id)
    {
        try {
            $user = Auth::user();

            // Check if user can access full features
            if (!$user->canAccessFullFeatures()) {
                $message = 'Akses ditolak.';
                if (!$user->isActive()) {
                    $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan fitur ini.';
                } elseif ($user->requiresEmailVerification()) {
                    $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk menggunakan fitur ini.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'account_status' => $user->account_status,
                    'email_verified' => $user->hasVerifiedEmail()
                ], 403);
            }

            $blacklist = RentalBlacklist::findOrFail($id);

            // Ensure user has balance record
            if (!$user->balance) {
                $user->balance()->create(['balance' => 0]);
                $user->refresh();
            }

            // Check if already unlocked (this specific report or any report with same NIK)
            $existingUnlock = UserUnlock::where('user_id', $user->id)
                                       ->where('blacklist_id', $id)
                                       ->first();

            $existingNikUnlock = $user->hasUnlockedNik($blacklist->nik);

            if ($existingUnlock || $existingNikUnlock) {
                return response()->json([
                    'success' => false,
                    'message' => $existingNikUnlock ?
                        'Anda sudah pernah membuka data dengan NIK yang sama sebelumnya' :
                        'Data sudah pernah dibuka sebelumnya'
                ]);
            }

            $price = $this->getDetailPrice($blacklist->jenis_rental);

            // Check balance
            if ($user->balance->balance < $price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tidak mencukupi. Silakan topup terlebih dahulu.',
                    'required_balance' => $price,
                    'current_balance' => $user->balance->balance
                ]);
            }

            DB::transaction(function () use ($user, $blacklist, $price, $id) {
                // Get balance before transaction
                $balanceBefore = $user->getCurrentBalance();
                $balanceAfter = $balanceBefore - $price;

                // Deduct balance
                $user->balance->decrement('balance', $price);

                // Record unlock
                UserUnlock::create([
                    'user_id' => $user->id,
                    'blacklist_id' => $id,
                    'amount_paid' => $price,
                    'unlocked_at' => now()
                ]);

                // Record balance transaction
                $user->balanceTransactions()->create([
                    'type' => 'usage',
                    'amount' => $price,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => "Membuka detail blacklist: {$blacklist->nama_lengkap}",
                    'reference_type' => 'blacklist_unlock',
                    'reference_id' => $id
                ]);
            });

            // Refresh user balance
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => "Berhasil membuka detail. Saldo terpotong Rp " . number_format($price, 0, ',', '.'),
                'data' => [
                    'id' => $blacklist->id,
                    'nama_lengkap' => $blacklist->nama_lengkap,
                    'nik' => $blacklist->nik,
                    'no_hp' => $blacklist->no_hp,
                    'alamat' => $blacklist->alamat,
                    'jenis_rental' => $blacklist->jenis_rental,
                    'jenis_laporan' => $blacklist->jenis_laporan,
                    'status_validitas' => $blacklist->status_validitas,
                    'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                    'kronologi' => $blacklist->kronologi,
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                    'pelapor' => $blacklist->user->name
                ],
                'remaining_balance' => $user->balance->balance
            ]);
        } catch (\Exception $e) {
            \Log::error('Unlock error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    private function getDetailPrice($jenisRental)
    {
        // Get prices from settings
        $priceMap = [
            'Rental Mobil' => (int) \App\Models\Setting::get('price_rental_mobil', 1500),
            'Rental Motor' => (int) \App\Models\Setting::get('price_rental_motor', 1500),
            'Kamera' => (int) \App\Models\Setting::get('price_kamera', 1000),
        ];

        // Return specific price or default from settings
        return $priceMap[$jenisRental] ?? (int) \App\Models\Setting::get('price_lainnya', 800);
    }
}
