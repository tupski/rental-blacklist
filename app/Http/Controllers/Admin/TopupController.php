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

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $topups = $query->latest()->paginate(20);

        return view('admin.topup.index', compact('topups'));
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
