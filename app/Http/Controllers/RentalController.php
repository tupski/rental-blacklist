<?php

namespace App\Http\Controllers;

use App\Models\RentalRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'pengusaha_rental')
                    ->where('is_verified', true);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $location = $request->get('location');
            $query->where('company_address', 'like', "%{$location}%");
        }

        if ($request->filled('entity_type')) {
            $query->where('entity_type', $request->get('entity_type'));
        }

        $rentals = $query->orderBy('company_name')
                        ->paginate(9)
                        ->appends($request->query());

        return view('rentals.index', compact('rentals'));
    }

    public function create()
    {
        return view('rental.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rental' => 'required|string|max:255',
            'jenis_rental' => 'required|array|min:1',
            'jenis_rental.*' => 'string|in:Motor,Mobil,Alat Berat,Elektronik,Peralatan,Lainnya',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'nik_pemilik' => 'required|string|size:16',
            'no_hp_pemilik' => 'required|string|max:20',
            'deskripsi' => 'nullable|string',
            'website' => 'nullable|url',
            'sosial_media.facebook' => 'nullable|url',
            'sosial_media.instagram' => 'nullable|url',
            'sosial_media.whatsapp' => 'nullable|string',
            'dokumen_legalitas.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'foto_tempat.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        $data['status'] = RentalRegistration::STATUS_PENDING;

        // Handle sosial media
        $data['sosial_media'] = [
            'facebook' => $request->input('sosial_media.facebook'),
            'instagram' => $request->input('sosial_media.instagram'),
            'whatsapp' => $request->input('sosial_media.whatsapp'),
        ];

        // Handle file uploads for dokumen legalitas
        if ($request->hasFile('dokumen_legalitas')) {
            $dokumenFiles = [];
            foreach ($request->file('dokumen_legalitas') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('rental-docs', 'public');
                    $dokumenFiles[] = $path;
                }
            }
            $data['dokumen_legalitas'] = $dokumenFiles;
        }

        // Handle file uploads for foto tempat
        if ($request->hasFile('foto_tempat')) {
            $fotoFiles = [];
            foreach ($request->file('foto_tempat') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('rental-photos', 'public');
                    $fotoFiles[] = $path;
                }
            }
            $data['foto_tempat'] = $fotoFiles;
        }

        RentalRegistration::create($data);

        return redirect()->route('rental.daftar')
            ->with('success', 'Pendaftaran berhasil dikirim! Tim kami akan memverifikasi dalam 1-3 hari kerja.');
    }
}
