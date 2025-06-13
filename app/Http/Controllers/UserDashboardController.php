<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use App\Models\UserUnlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
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
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'nama_lengkap' => $report->sensored_nama,
                    'nik' => $report->sensored_nik,
                    'no_hp' => $report->sensored_no_hp,
                    'jenis_rental' => $report->jenis_rental,
                    'status_validitas' => $report->status_validitas,
                    'created_at' => $report->created_at,
                    'user' => $report->user,
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($report->nik),
                ];
            });

        return view('user.dashboard', compact('stats', 'recentReports'));
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
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $item->sensored_nama,
                    'nik' => $item->sensored_nik,
                    'no_hp' => $item->sensored_no_hp,
                    'alamat' => $item->sensored_alamat,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'status_validitas' => $item->status_validitas,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name,
                    'price' => $this->getDetailPrice($item->jenis_rental),
                    'is_unlocked' => UserUnlock::where('user_id', Auth::id())
                                              ->where('blacklist_id', $item->id)
                                              ->exists()
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    public function unlock(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $blacklist = RentalBlacklist::findOrFail($id);

            // Ensure user has balance record
            if (!$user->balance) {
                $user->balance()->create(['balance' => 0]);
                $user->refresh();
            }

            // Check if already unlocked
            $existingUnlock = UserUnlock::where('user_id', $user->id)
                                       ->where('blacklist_id', $id)
                                       ->first();

            if ($existingUnlock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah pernah dibuka sebelumnya'
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
        // Map jenis rental to price
        $priceMap = [
            'Rental Mobil' => 1500,
            'Rental Motor' => 1500,
            'Rental Kamera' => 1000,
            'Rental Alat Musik' => 800,
            'Rental Elektronik' => 800,
        ];

        // Return specific price or default
        return $priceMap[$jenisRental] ?? 800;
    }
}
