<?php

namespace App\Http\Controllers;

use App\Models\RentalBlacklist;
use App\Models\DocumentVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

    public function search(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $perPage = 5;

        if (strlen($search) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Pencarian minimal 3 karakter'
            ]);
        }

        $query = RentalBlacklist::with('user')
            ->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('no_hp', 'LIKE', "%{$search}%")
                  ->orWhere('alamat', 'LIKE', "%{$search}%");
            })
            ->where('status_validitas', 'Valid')
            ->latest();

        $total = $query->count();
        $results = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'nama_lengkap' => $item->nama_lengkap,
                                'nik' => $item->nik,
                                'no_hp' => $item->no_hp,
                                'alamat' => $item->alamat,
                                'jenis_rental' => $item->jenis_rental,
                                'status_validitas' => $item->status_validitas,
                                'jumlah_laporan' => 1, // For rental owners, each record is one report
                                'pelapor' => $item->user ? $item->user->name : 'Tidak diketahui',
                                'can_edit' => false // Rental owners can't edit reports
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

        // Generate QR Code with verification URL
        $verificationUrl = route('verifikasi.index', ['kode' => $verificationCode]);
        $qrCode = new QrCode($verificationUrl);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        return view('rental.print-detail', compact('blacklist', 'verificationCode', 'qrCodeBase64', 'verificationUrl'));
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

        // Generate QR Code with verification URL
        $verificationUrl = route('verifikasi.index', ['kode' => $verificationCode]);
        $qrCode = new QrCode($verificationUrl);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        // Generate filename: laporan-penyewa-nama-penyewa-tanggalcetak-jamcetak.pdf
        $namaPenyewa = str_replace(' ', '-', strtolower($blacklist->nama_lengkap));
        $namaPenyewa = preg_replace('/[^a-z0-9\-]/', '', $namaPenyewa);
        $tanggalCetak = now()->format('dmY');
        $jamCetak = now()->format('His');
        $filename = "laporan-penyewa-{$namaPenyewa}-{$tanggalCetak}-{$jamCetak}.pdf";

        $pdf = Pdf::loadView('rental.pdf-detail', compact('blacklist', 'verificationCode', 'qrCodeBase64', 'verificationUrl'));
        return $pdf->download($filename);
    }
}
