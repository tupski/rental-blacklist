<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TopupStatusNotification;

class TopupController extends Controller
{
    public function index(Request $request)
    {
        $query = TopupRequest::with('user');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nomor invoice
        if ($request->has('invoice') && $request->invoice) {
            $query->where('invoice_number', 'like', '%' . $request->invoice . '%');
        }

        // Filter berdasarkan nama user
        if ($request->has('user') && $request->user) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Filter berdasarkan jumlah
        if ($request->has('jumlah_min') && $request->jumlah_min) {
            $query->where('amount', '>=', $request->jumlah_min);
        }

        if ($request->has('jumlah_max') && $request->jumlah_max) {
            $query->where('amount', '<=', $request->jumlah_max);
        }

        $topups = $query->latest()->paginate(10)->appends($request->query());

        // Get statistics for all topups (not just filtered ones)
        $allTopups = TopupRequest::with('user')->get();

        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.topup.partials.table', compact('topups'))->render(),
                'pagination_html' => view('admin.topup.partials.pagination', compact('topups'))->render(),
                'pagination' => [
                    'current_page' => $topups->currentPage(),
                    'last_page' => $topups->lastPage(),
                    'per_page' => $topups->perPage(),
                    'total' => $topups->total(),
                    'from' => $topups->firstItem(),
                    'to' => $topups->lastItem()
                ]
            ]);
        }

        return view('admin.topup.index', compact('topups', 'allTopups'));
    }

    public function show(TopupRequest $topup)
    {
        return view('admin.topup.show', compact('topup'));
    }

    public function approve(TopupRequest $topup)
    {
        $topup->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'admin_notes' => 'Disetujui oleh admin',
        ]);

        // Add balance to user using proper method
        $topup->user->addBalance(
            $topup->amount,
            'Topup disetujui - Invoice: ' . $topup->invoice_number,
            TopupRequest::class,
            $topup->id
        );

        // Send notification to user about approval
        try {
            $topup->user->notify(new TopupStatusNotification($topup, 'approved'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send topup approval notification: ' . $e->getMessage());
        }

        // Send notification to all admins about the approval
        try {
            $admins = User::where('role', 'admin')->where('id', '!=', auth()->id())->get();
            Notification::send($admins, new TopupStatusNotification($topup, 'approved'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send topup approval notification to admins: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Topup berhasil disetujui dan saldo user telah ditambahkan.');
    }

    public function reject(Request $request, TopupRequest $topup)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $topup->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        // Send notification to user about rejection
        try {
            $topup->user->notify(new TopupStatusNotification($topup, 'rejected'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send topup rejection notification: ' . $e->getMessage());
        }

        // Send notification to all admins about the rejection
        try {
            $admins = User::where('role', 'admin')->where('id', '!=', auth()->id())->get();
            Notification::send($admins, new TopupStatusNotification($topup, 'rejected'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send topup rejection notification to admins: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Topup berhasil ditolak.');
    }

    public function destroy(TopupRequest $topup)
    {
        $topup->delete();

        return redirect()->route('admin.isi-saldo.indeks')
            ->with('success', 'Data topup berhasil dihapus.');
    }
}
