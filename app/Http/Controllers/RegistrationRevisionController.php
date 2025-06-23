<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RentalRegistration;
use App\Helpers\FileNamingHelper;
use App\Notifications\RevisionSubmittedNotification;
use App\Models\User;

class RegistrationRevisionController extends Controller
{
    /**
     * Show the revision form
     */
    public function show()
    {
        $user = Auth::user();
        
        // Check if user is rental owner and needs revision
        if ($user->role !== 'pengusaha_rental' || !$user->needsRevision()) {
            return redirect()->route('dasbor')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $registration = $user->rentalRegistration;
        
        if (!$registration) {
            return redirect()->route('dasbor')->with('error', 'Data registrasi tidak ditemukan.');
        }

        return view('auth.revision', compact('registration', 'user'));
    }

    /**
     * Update the registration data
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is rental owner and needs revision
        if ($user->role !== 'pengusaha_rental' || !$user->needsRevision()) {
            return redirect()->route('dasbor')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $registration = $user->rentalRegistration;
        
        if (!$registration) {
            return redirect()->route('dasbor')->with('error', 'Data registrasi tidak ditemukan.');
        }

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
            'dokumen_legalitas.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'foto_tempat.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'nama_rental', 'jenis_rental', 'alamat', 'kota', 'provinsi',
            'no_hp', 'email', 'nama_pemilik', 'nik_pemilik', 'no_hp_pemilik',
            'deskripsi', 'website'
        ]);

        // Handle social media data
        $data['sosial_media'] = [
            'facebook' => $request->input('sosial_media.facebook'),
            'instagram' => $request->input('sosial_media.instagram'),
            'whatsapp' => $request->input('sosial_media.whatsapp'),
        ];

        // Handle file uploads for dokumen legalitas
        if ($request->hasFile('dokumen_legalitas')) {
            $dokumenFiles = $registration->dokumen_legalitas ?? [];
            foreach ($request->file('dokumen_legalitas') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = FileNamingHelper::generateRentalDocFilename($data['nama_rental'], $extension, 'dokumen-legalitas');
                    $path = $file->storeAs('rental-docs', $filename, 'public');
                    $dokumenFiles[] = $path;
                }
            }
            $data['dokumen_legalitas'] = $dokumenFiles;
        }

        // Handle file uploads for foto tempat
        if ($request->hasFile('foto_tempat')) {
            $fotoFiles = $registration->foto_tempat ?? [];
            foreach ($request->file('foto_tempat') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = FileNamingHelper::generateRentalPhotoFilename($data['nama_rental'], $extension);
                    $path = $file->storeAs('rental-photos', $filename, 'public');
                    $fotoFiles[] = $path;
                }
            }
            $data['foto_tempat'] = $fotoFiles;
        }

        // Update registration data
        $registration->update($data);

        // Update user account status back to pending for re-review
        $user->update([
            'account_status' => 'pending',
            'revision_notes' => null,
            'revision_requested_at' => null,
            'revision_requested_by' => null
        ]);

        // Send notification to admins about revision submission
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new RevisionSubmittedNotification($user, $registration));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send revision submission notification: ' . $e->getMessage());
        }

        return redirect()->route('dasbor')->with('success', 
            'Data revisi berhasil dikirim! Tim kami akan memverifikasi kembali dalam 1-3 hari kerja.');
    }

    /**
     * Remove uploaded file
     */
    public function removeFile(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'pengusaha_rental' || !$user->needsRevision()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'file_path' => 'required|string',
            'file_type' => 'required|in:dokumen_legalitas,foto_tempat'
        ]);

        $registration = $user->rentalRegistration;
        $filePath = $request->file_path;
        $fileType = $request->file_type;

        if ($registration && isset($registration->{$fileType})) {
            $files = $registration->{$fileType};
            $files = array_filter($files, function($file) use ($filePath) {
                return $file !== $filePath;
            });
            
            $registration->update([$fileType => array_values($files)]);
            
            // Delete physical file
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
            
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
