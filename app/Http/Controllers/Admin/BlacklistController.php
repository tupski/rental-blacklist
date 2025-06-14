<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalBlacklist;
use App\Models\GuestReport;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index(Request $request)
    {
        $query = RentalBlacklist::with('user');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_rental')) {
            $query->where('jenis_rental', $request->get('jenis_rental'));
        }

        if ($request->filled('status_validitas')) {
            $query->where('status_validitas', $request->get('status_validitas'));
        }

        $blacklists = $query->latest()->paginate(20)->appends($request->query());

        // Get report counts for each blacklist (from guest reports)
        $reportCounts = GuestReport::whereIn('nik', $blacklists->pluck('nik'))
                                  ->selectRaw('nik, COUNT(*) as total_reports')
                                  ->groupBy('nik')
                                  ->pluck('total_reports', 'nik');

        // Also count reports from same NIK in blacklist table (different rental types)
        $blacklistCounts = RentalBlacklist::whereIn('nik', $blacklists->pluck('nik'))
                                         ->selectRaw('nik, COUNT(*) as total_reports')
                                         ->groupBy('nik')
                                         ->pluck('total_reports', 'nik');

        // Merge counts
        foreach ($blacklistCounts as $nik => $count) {
            $reportCounts[$nik] = ($reportCounts[$nik] ?? 0) + $count;
        }

        return view('admin.blacklist.index', compact('blacklists', 'reportCounts'));
    }

    public function create()
    {
        return view('admin.blacklist.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:rental_blacklists,nik',
            'no_hp' => 'required|string|max:20',
            'jenis_rental' => 'required|in:Rental Mobil,Rental Motor,Kamera,Lainnya',
            'alamat' => 'nullable|string|max:500',
            'deskripsi_masalah' => 'required|string',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status_validitas' => 'required|in:Pending,Valid,Invalid',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        // Handle file uploads
        if ($request->hasFile('bukti')) {
            $buktiFiles = [];
            foreach ($request->file('bukti') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bukti', $filename);
                $buktiFiles[] = $filename;
            }
            $data['bukti'] = $buktiFiles;
        }

        RentalBlacklist::create($data);

        return redirect()->route('admin.daftar-hitam.indeks')
            ->with('success', 'Data blacklist berhasil ditambahkan.');
    }

    public function show(RentalBlacklist $blacklist)
    {
        // Get ALL reports for this NIK including same rental type and same user
        $relatedReports = RentalBlacklist::where('nik', $blacklist->nik)
                                       ->where('id', '!=', $blacklist->id)
                                       ->with('user')
                                       ->orderBy('created_at', 'desc')
                                       ->get();

        // Get guest reports for this NIK
        $guestReports = GuestReport::where('nik', $blacklist->nik)
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('admin.blacklist.show', compact('blacklist', 'relatedReports', 'guestReports'));
    }

    public function edit(RentalBlacklist $blacklist)
    {
        return view('admin.blacklist.edit', compact('blacklist'));
    }

    public function update(Request $request, RentalBlacklist $blacklist)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:rental_blacklists,nik,' . $blacklist->id,
            'no_hp' => 'required|string|max:20',
            'jenis_rental' => 'required|in:Rental Mobil,Rental Motor,Kamera,Lainnya',
            'alamat' => 'nullable|string|max:500',
            'deskripsi_masalah' => 'required|string',
            'bukti.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status_validitas' => 'required|in:Pending,Valid,Invalid',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $data = $request->except(['bukti', 'delete_bukti']);

        // Handle existing bukti deletion
        $currentBukti = $blacklist->bukti ?? [];
        if ($request->has('delete_bukti')) {
            foreach ($request->delete_bukti as $index) {
                if (isset($currentBukti[$index])) {
                    // Delete file from storage
                    \Storage::delete('public/bukti/' . $currentBukti[$index]);
                    unset($currentBukti[$index]);
                }
            }
            $currentBukti = array_values($currentBukti); // Reindex array
        }

        // Handle new file uploads
        if ($request->hasFile('bukti')) {
            foreach ($request->file('bukti') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/bukti', $filename);
                $currentBukti[] = $filename;
            }
        }

        $data['bukti'] = $currentBukti;

        $blacklist->update($data);

        return redirect()->route('admin.daftar-hitam.tampil', $blacklist->id)
            ->with('success', 'Data blacklist berhasil diperbarui.');
    }

    public function destroy(RentalBlacklist $blacklist)
    {
        $blacklist->delete();

        return redirect()->route('admin.daftar-hitam.indeks')
            ->with('success', 'Data blacklist berhasil dihapus.');
    }

    public function validateBlacklist(RentalBlacklist $blacklist)
    {
        $blacklist->update(['status_validitas' => 'Valid']);

        return redirect()->back()
            ->with('success', 'Data blacklist berhasil divalidasi.');
    }

    public function invalidateBlacklist(RentalBlacklist $blacklist)
    {
        $blacklist->update(['status_validitas' => 'Invalid']);

        return redirect()->back()
            ->with('success', 'Data blacklist berhasil ditandai sebagai invalid.');
    }
}
