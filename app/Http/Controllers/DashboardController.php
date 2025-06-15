<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use App\Models\DocumentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_laporan' => RentalBlacklist::count(),
            'laporan_saya' => RentalBlacklist::where('user_id', Auth::id())->count(),
            'laporan_valid' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'laporan_pending' => RentalBlacklist::where('status_validitas', 'Pending')->count(),
        ];

        $recentReports = RentalBlacklist::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Handle search from URL parameter
        $searchQuery = $request->get('cari');

        return view('dashboard', compact('stats', 'recentReports', 'searchQuery'));
    }

    public function printDetail($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);
        $user = Auth::user();

        // Only rental owners can access this
        if ($user->role !== 'pengusaha_rental') {
            abort(403, 'Akses ditolak');
        }

        // Generate verification code and barcode
        $verificationCode = DocumentVerification::generateVerificationCode();

        // Create verification record
        DocumentVerification::create([
            'verification_code' => $verificationCode,
            'blacklist_id' => $blacklist->id,
            'user_id' => $user->id,
            'document_type' => 'print',
            'generated_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Generate barcode
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($verificationCode, $generator::TYPE_CODE_128));

        return view('rental.print-detail', compact('blacklist', 'verificationCode', 'barcode'));
    }

    public function downloadPDF($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);
        $user = Auth::user();

        // Only rental owners can access this
        if ($user->role !== 'pengusaha_rental') {
            abort(403, 'Akses ditolak');
        }

        // Generate verification code and barcode
        $verificationCode = DocumentVerification::generateVerificationCode();

        // Create verification record
        DocumentVerification::create([
            'verification_code' => $verificationCode,
            'blacklist_id' => $blacklist->id,
            'user_id' => $user->id,
            'document_type' => 'pdf',
            'generated_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Generate barcode
        $generator = new BarcodeGeneratorPNG();
        $barcode = base64_encode($generator->getBarcode($verificationCode, $generator::TYPE_CODE_128));

        // Generate filename: laporan-penyewa-nama-penyewa-tanggalcetak-jamcetak.pdf
        $namaPenyewa = str_replace(' ', '-', strtolower($blacklist->nama_lengkap));
        $namaPenyewa = preg_replace('/[^a-z0-9\-]/', '', $namaPenyewa);
        $tanggalCetak = now()->format('dmY');
        $jamCetak = now()->format('His');
        $filename = "laporan-penyewa-{$namaPenyewa}-{$tanggalCetak}-{$jamCetak}.pdf";

        $pdf = Pdf::loadView('rental.pdf-detail', compact('blacklist', 'verificationCode', 'barcode'));
        return $pdf->download($filename);
    }
}
