<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('account_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.account-approval.index', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        if ($user->account_status !== 'pending') {
            return redirect()->back()->with('error', 'Akun ini tidak dalam status pending.');
        }

        $user->approve(Auth::id());

        return redirect()->back()->with('success', "Akun {$user->name} berhasil disetujui dan diaktifkan. Email aktivasi telah dikirim.");
    }

    public function reject(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        if ($user->account_status !== 'pending') {
            return redirect()->back()->with('error', 'Akun ini tidak dalam status pending.');
        }

        // For now, we'll just delete the user. You could also add a rejection reason field
        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Akun {$userName} berhasil ditolak dan dihapus.");
    }

    public function suspend(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menonaktifkan akun admin.');
        }

        $user->suspend();

        return redirect()->back()->with('success', "Akun {$user->name} berhasil dinonaktifkan.");
    }

    public function activate(User $user)
    {
        if ($user->account_status === 'active') {
            return redirect()->back()->with('info', 'Akun ini sudah aktif.');
        }

        $user->approve(Auth::id());

        return redirect()->back()->with('success', "Akun {$user->name} berhasil diaktifkan. Email aktivasi telah dikirim.");
    }
}
