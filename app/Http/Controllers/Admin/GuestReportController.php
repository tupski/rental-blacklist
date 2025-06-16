<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestReport;
use App\Models\User;
use App\Notifications\BlacklistReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class GuestReportController extends Controller
{
    public function index()
    {
        $reports = GuestReport::latest()
            ->paginate(20);

        return view('admin.guest-reports.index', compact('reports'));
    }

    public function show(GuestReport $guestReport)
    {
        return view('admin.guest-reports.show', compact('guestReport'));
    }

    public function edit(GuestReport $guestReport)
    {
        return view('admin.guest-reports.edit', compact('guestReport'));
    }

    public function update(Request $request, GuestReport $guestReport)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $guestReport->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->route('admin.laporan-tamu.indeks')
            ->with('success', 'Laporan guest berhasil diperbarui.');
    }

    public function destroy(GuestReport $guestReport)
    {
        $guestReport->delete();

        return redirect()->route('admin.laporan-tamu.indeks')
            ->with('success', 'Laporan guest berhasil dihapus.');
    }

    public function approve(GuestReport $guestReport)
    {
        $guestReport->update(['status' => 'approved']);

        // Send notification to all admins
        try {
            $admins = User::where('role', 'admin')->where('id', '!=', auth()->id())->get();
            Notification::send($admins, new BlacklistReportNotification($guestReport, 'approved'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send blacklist report approval notification: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Laporan guest berhasil disetujui.');
    }

    public function reject(Request $request, GuestReport $guestReport)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000',
        ]);

        $guestReport->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Send notification to all admins
        try {
            $admins = User::where('role', 'admin')->where('id', '!=', auth()->id())->get();
            Notification::send($admins, new BlacklistReportNotification($guestReport, 'rejected'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send blacklist report rejection notification: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Laporan guest berhasil ditolak.');
    }
}
