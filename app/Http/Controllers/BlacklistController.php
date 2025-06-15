<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesFileWatermark;

class BlacklistController extends Controller
{
    use HandlesFileWatermark;

    public function index(Request $request)
    {
        // Filter hanya laporan dari user yang sedang login
        $query = RentalBlacklist::with('user')->where('user_id', Auth::id());

        if ($request->has('search') && $request->cari) {
            $query->search($request->cari);
        }

        if ($request->has('jenis_rental') && $request->jenis_rental) {
            $query->where('jenis_rental', $request->jenis_rental);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status_validitas', $request->status);
        }

        $blacklists = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $blacklists->items(),
                'pagination' => [
                    'current_page' => $blacklists->currentPage(),
                    'last_page' => $blacklists->lastPage(),
                    'total' => $blacklists->total()
                ]
            ]);
        }

        return view('dashboard.blacklist.index', compact('blacklists'));
    }

    public function create()
    {
        return view('dashboard.blacklist.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'jenis_rental' => 'required|string|max:100',
            'jenis_laporan' => 'required|array|min:1',
            'jenis_laporan.*' => 'in:Tidak Mengembalikan,Merusak Barang,Tidak Bayar,Kabur,Lainnya',
            'kronologi' => 'required|string',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,avi,mov|max:10240'
        ]);

        $buktiFiles = [];
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $path = $file->store('bukti', 'public');
                $buktiFiles[] = $path;
            }
        }

        $blacklist = RentalBlacklist::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jenis_rental' => $request->jenis_rental,
            'jenis_laporan' => $request->jenis_laporan,
            'kronologi' => $request->kronologi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'bukti' => $buktiFiles,
            'user_id' => Auth::id()
        ]);

        // Process files for watermarking
        if (!empty($buktiFiles)) {
            $this->processUploadedFiles($buktiFiles, $blacklist);
        }

        // Check if should be validated
        $this->checkValidation($blacklist);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan blacklist berhasil ditambahkan'
            ]);
        }

        return redirect()->route('dasbor.daftar-hitam.indeks')
            ->with('success', 'Laporan blacklist berhasil ditambahkan');
    }

    public function show($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        if (request()->ajax()) {
            // Return complete data for modal popup
            $data = [
                'id' => $blacklist->id,
                'nama_lengkap' => $blacklist->nama_lengkap,
                'nik' => $blacklist->nik,
                'jenis_kelamin' => $blacklist->jenis_kelamin,
                'no_hp' => $blacklist->no_hp,
                'alamat' => $blacklist->alamat,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'status_validitas' => $blacklist->status_validitas,
                'kronologi' => $blacklist->kronologi,
                'bukti' => $blacklist->bukti,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian,
                'tanggal_kejadian_formatted' => $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : null,

                // Informasi Pelapor
                'nama_perusahaan_rental' => $blacklist->nama_perusahaan_rental,
                'nama_penanggung_jawab' => $blacklist->nama_penanggung_jawab,
                'no_wa_pelapor' => $blacklist->no_wa_pelapor,
                'email_pelapor' => $blacklist->email_pelapor,
                'alamat_usaha' => $blacklist->alamat_usaha,
                'website_usaha' => $blacklist->website_usaha,

                // Data Penyewa
                'foto_penyewa' => $blacklist->foto_penyewa,
                'foto_ktp_sim' => $blacklist->foto_ktp_sim,

                // Detail Masalah
                'tanggal_sewa' => $blacklist->tanggal_sewa,
                'tanggal_sewa_formatted' => $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : null,
                'jenis_kendaraan' => $blacklist->jenis_kendaraan,
                'nomor_polisi' => $blacklist->nomor_polisi,
                'nilai_kerugian' => $blacklist->nilai_kerugian,
                'nilai_kerugian_formatted' => $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : null,

                // Status Penanganan
                'status_penanganan' => $blacklist->status_penanganan,
                'status_lainnya' => $blacklist->status_lainnya,

                // Persetujuan
                'persetujuan' => $blacklist->persetujuan,
                'nama_pelapor_ttd' => $blacklist->nama_pelapor_ttd,
                'tanggal_pelaporan' => $blacklist->tanggal_pelaporan,
                'tanggal_pelaporan_formatted' => $blacklist->tanggal_pelaporan ? $blacklist->tanggal_pelaporan->format('d/m/Y H:i') : null,
                'tipe_pelapor' => $blacklist->tipe_pelapor,

                // Meta data
                'pelapor' => $blacklist->user->name,
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'created_at' => $blacklist->created_at->format('d/m/Y H:i'),
                'can_edit' => $blacklist->user_id === Auth::id()
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('dashboard.blacklist.show', compact('blacklist'));
    }

    public function edit($id)
    {
        $blacklist = RentalBlacklist::findOrFail($id);

        // Only allow editing own reports
        if ($blacklist->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengedit laporan Anda sendiri');
        }

        return view('dashboard.blacklist.edit', compact('blacklist'));
    }

    public function update(Request $request, $id)
    {
        $blacklist = RentalBlacklist::findOrFail($id);

        // Only allow editing own reports
        if ($blacklist->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat mengedit laporan Anda sendiri');
        }

        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'jenis_rental' => 'required|string|max:100',
            'jenis_laporan' => 'required|array|min:1',
            'jenis_laporan.*' => 'in:Tidak Mengembalikan,Merusak Barang,Tidak Bayar,Kabur,Lainnya',
            'kronologi' => 'required|string',
            'tanggal_kejadian' => 'required|date|before_or_equal:today',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4,avi,mov|max:10240'
        ]);

        $buktiFiles = $blacklist->bukti ?? [];

        $newFiles = [];

        // Handle new files
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $path = $file->store('bukti', 'public');
                $buktiFiles[] = $path;
                $newFiles[] = $path;
            }
        }

        // Handle removed files
        if ($request->has('removed_files') && $request->removed_files) {
            $removedFiles = json_decode($request->removed_files, true);
            if (is_array($removedFiles)) {
                // Remove files with watermark cleanup
                $this->removeFilesWithWatermark($removedFiles, $blacklist);

                foreach ($removedFiles as $removedFile) {
                    if (($key = array_search($removedFile, $buktiFiles)) !== false) {
                        unset($buktiFiles[$key]);
                    }
                }
                $buktiFiles = array_values($buktiFiles);
            }
        }

        $blacklist->update([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jenis_rental' => $request->jenis_rental,
            'jenis_laporan' => $request->jenis_laporan,
            'kronologi' => $request->kronologi,
            'tanggal_kejadian' => $request->tanggal_kejadian,
            'bukti' => $buktiFiles
        ]);

        // Process new files for watermarking
        if (!empty($newFiles)) {
            $this->processUploadedFiles($newFiles, $blacklist);
        }

        // Check if should be validated
        $this->checkValidation($blacklist);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan blacklist berhasil diperbarui'
            ]);
        }

        return redirect()->route('dasbor.daftar-hitam.indeks')
            ->with('success', 'Laporan blacklist berhasil diperbarui');
    }

    public function destroy($id)
    {
        $blacklist = RentalBlacklist::findOrFail($id);

        // Only allow deleting own reports
        if ($blacklist->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat menghapus laporan Anda sendiri');
        }

        // Delete associated files with watermark cleanup
        if ($blacklist->bukti) {
            $this->removeFilesWithWatermark($blacklist->bukti, $blacklist);
        }

        $blacklist->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan blacklist berhasil dihapus'
            ]);
        }

        return redirect()->route('dasbor.daftar-hitam.indeks')
            ->with('success', 'Laporan blacklist berhasil dihapus');
    }

    public function searchForDashboard(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3',
            'page' => 'nullable|integer|min:1'
        ]);

        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 5;

        $query = RentalBlacklist::search($search)
            ->with('user');

        // Get total count for pagination
        $total = $query->count();

        // Apply pagination
        $results = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(function ($item) {
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
                    'can_edit' => $item->user_id === Auth::id()
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

    public function generatePDF($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Generate PDF using DomPDF or similar
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('dashboard.blacklist.pdf', compact('blacklist'));

        return $pdf->download('laporan-blacklist-' . $blacklist->id . '.pdf');
    }

    public function generateShareLink($id)
    {
        $blacklist = RentalBlacklist::findOrFail($id);

        // Generate unique token for one-time access
        $token = \Str::random(32);

        // Store in cache with expiration (24 hours)
        \Cache::put('share_token_' . $token, [
            'blacklist_id' => $id,
            'created_at' => now(),
            'accessed' => false
        ], now()->addHours(24));

        $shareUrl = route('publik.share', $token);

        return response()->json([
            'success' => true,
            'share_url' => $shareUrl,
            'expires_at' => now()->addHours(24)->format('d/m/Y H:i')
        ]);
    }

    private function checkValidation($blacklist)
    {
        $uniqueUserReports = RentalBlacklist::countUniqueUserReportsByNik($blacklist->nik);

        if ($uniqueUserReports >= 2) {
            // Update all reports for this NIK to Valid
            RentalBlacklist::where('nik', $blacklist->nik)
                ->update(['status_validitas' => 'Valid']);
        }
    }
}
