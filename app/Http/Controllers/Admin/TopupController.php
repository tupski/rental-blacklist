<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopupRequest;
use Illuminate\Http\Request;

class TopupController extends Controller
{
    public function index()
    {
        $topups = TopupRequest::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.topup.index', compact('topups'));
    }

    public function show(TopupRequest $topup)
    {
        return view('admin.topup.show', compact('topup'));
    }

    public function approve(TopupRequest $topup)
    {
        $topup->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Add balance to user
        $topup->user->increment('balance', $topup->amount);

        return redirect()->back()
            ->with('success', 'Topup berhasil disetujui dan saldo user telah ditambahkan.');
    }

    public function reject(Request $request, TopupRequest $topup)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $topup->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()
            ->with('success', 'Topup berhasil ditolak.');
    }

    public function destroy(TopupRequest $topup)
    {
        $topup->delete();

        return redirect()->route('admin.topup.index')
            ->with('success', 'Data topup berhasil dihapus.');
    }
}
