<?php

namespace App\Http\Controllers;

use App\Models\GuestReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create()
    {
        return view('report.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jenis_rental' => 'required|string|max:100',
            'jenis_laporan' => 'required|array|min:1',
            'jenis_laporan.*' => 'string|in:Tidak Mengembalikan,Merusak Barang,Tidak Bayar,Kabur,Lainnya',
            'kronologi' => 'required|string',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'email_pelapor' => 'required|email|max:255',
            'nama_pelapor' => 'required|string|max:255',
            'no_hp_pelapor' => 'required|string|max:20',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $data = $request->all();
        $data['status'] = GuestReport::STATUS_PENDING;

        // Handle file uploads
        if ($request->hasFile('bukti')) {
            $buktiFiles = [];
            foreach ($request->file('bukti') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('guest-reports', 'public');
                    $buktiFiles[] = $path;
                }
            }
            $data['bukti'] = $buktiFiles;
        }

        GuestReport::create($data);

        return redirect()->route('report.create')
            ->with('success', 'Laporan berhasil dikirim! Tim kami akan memverifikasi dalam 1-3 hari kerja.');
    }
}
