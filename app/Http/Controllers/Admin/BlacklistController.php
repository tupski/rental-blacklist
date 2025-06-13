<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalBlacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    public function index()
    {
        $blacklists = RentalBlacklist::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.blacklist.index', compact('blacklists'));
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

        return redirect()->route('admin.blacklist.index')
            ->with('success', 'Data blacklist berhasil ditambahkan.');
    }

    public function show(RentalBlacklist $blacklist)
    {
        return view('admin.blacklist.show', compact('blacklist'));
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

        return redirect()->route('admin.blacklist.show', $blacklist->id)
            ->with('success', 'Data blacklist berhasil diperbarui.');
    }

    public function destroy(RentalBlacklist $blacklist)
    {
        $blacklist->delete();

        return redirect()->route('admin.blacklist.index')
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
