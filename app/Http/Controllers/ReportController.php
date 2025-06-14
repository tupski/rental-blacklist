<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use App\Http\Requests\StoreBlacklistReportRequest;
use App\Traits\HandlesFileWatermark;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use HandlesFileWatermark;

    public function create()
    {
        return view('report.create-new');
    }

    public function store(StoreBlacklistReportRequest $request)
    {
        $validated = $request->validated();

        // Handle file uploads with watermarking
        $allFiles = [];

        // Process foto penyewa
        if ($request->hasFile('foto_penyewa')) {
            $fotoPenyewaFiles = [];
            foreach ($request->file('foto_penyewa') as $file) {
                $path = $file->store('foto-penyewa', 'public');
                $fotoPenyewaFiles[] = $path;
                $allFiles[] = $path;
            }
            $validated['foto_penyewa'] = $fotoPenyewaFiles;
        }

        // Process foto KTP/SIM
        if ($request->hasFile('foto_ktp_sim')) {
            $fotoKtpFiles = [];
            foreach ($request->file('foto_ktp_sim') as $file) {
                $path = $file->store('foto-ktp-sim', 'public');
                $fotoKtpFiles[] = $path;
                $allFiles[] = $path;
            }
            $validated['foto_ktp_sim'] = $fotoKtpFiles;
        }

        // Process bukti pendukung
        if ($request->hasFile('bukti')) {
            $buktiFiles = [];
            foreach ($request->file('bukti') as $file) {
                $path = $file->store('bukti-pendukung', 'public');
                $buktiFiles[] = $path;
                $allFiles[] = $path;
            }
            $validated['bukti'] = $buktiFiles;
        }

        // Set user_id if authenticated, otherwise null for guest reports
        $validated['user_id'] = Auth::id();

        // Set default status
        $validated['status_validitas'] = 'Pending';

        // Create blacklist record
        $blacklist = RentalBlacklist::create($validated);

        // Process files for watermarking
        if (!empty($allFiles)) {
            $this->processUploadedFiles($allFiles, $blacklist);
        }

        // Send email notification (if configured)
        if ($validated['email_pelapor'] ?? null) {
            try {
                // Mail::to($validated['email_pelapor'])->send(new ReportConfirmationMail($blacklist));
            } catch (\Exception $e) {
                \Log::error('Failed to send report confirmation email: ' . $e->getMessage());
            }
        }

        return redirect()->route('laporan.buat')
            ->with('success', 'Laporan berhasil dikirim! Tim kami akan memverifikasi dalam 1-3 hari kerja. Anda akan menerima notifikasi email setelah verifikasi selesai.');
    }
}
