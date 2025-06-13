<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestReport;
use Illuminate\Http\Request;

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

        return redirect()->route('admin.guest-reports.index')
            ->with('success', 'Laporan guest berhasil diperbarui.');
    }

    public function destroy(GuestReport $guestReport)
    {
        $guestReport->delete();

        return redirect()->route('admin.guest-reports.index')
            ->with('success', 'Laporan guest berhasil dihapus.');
    }

    public function approve(GuestReport $guestReport)
    {
        $guestReport->update(['status' => 'approved']);

        return redirect()->back()
            ->with('success', 'Laporan guest berhasil disetujui.');
    }

    public function reject(GuestReport $guestReport)
    {
        $guestReport->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Laporan guest berhasil ditolak.');
    }
}
