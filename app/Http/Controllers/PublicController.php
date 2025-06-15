<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalBlacklist;
use App\Models\Setting;
use App\Models\Sponsor;
use App\Models\DocumentVerification;
use App\Helpers\PhoneHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PublicController extends Controller
{
    public function index()
    {
        // Statistik untuk halaman utama
        $stats = [
            'total_laporan' => RentalBlacklist::where('status_validitas', 'Valid')->count(),
            'total_pelanggan_bermasalah' => RentalBlacklist::where('status_validitas', 'Valid')->distinct('nik')->count(),
            'rental_terdaftar' => RentalBlacklist::distinct('user_id')->count(),
            'laporan_bulan_ini' => RentalBlacklist::where('status_validitas', 'Valid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Get settings untuk tampilan
        $settings = [
            'site_name' => Setting::get('site_name', 'RentalGuard'),
            'site_tagline' => Setting::get('site_tagline', 'Sistem Blacklist Rental Indonesia'),
            'hero_title' => Setting::get('hero_title', 'Lindungi Bisnis Rental Anda'),
            'hero_subtitle' => Setting::get('hero_subtitle', 'Cek data blacklist pelanggan sebelum menyewakan barang. 100% Gratis untuk pengusaha rental!'),
            'meta_title' => Setting::get('meta_title', 'RentalGuard - Sistem Blacklist Rental Indonesia'),
            'meta_description' => Setting::get('meta_description', 'Sistem blacklist rental terpercaya di Indonesia. Cek data pelanggan bermasalah sebelum menyewakan barang Anda. Gratis untuk pengusaha rental.'),
            'meta_keywords' => Setting::get('meta_keywords', 'blacklist rental, rental indonesia, cek pelanggan rental, sistem blacklist, rental bermasalah'),
            'contact_email' => Setting::get('contact_email', 'support@rentalguard.id'),
            'facebook_url' => Setting::get('facebook_url', ''),
            'twitter_url' => Setting::get('twitter_url', ''),
            'instagram_url' => Setting::get('instagram_url', ''),
            'whatsapp_number' => Setting::get('whatsapp_number', ''),
        ];

        // Get sponsors
        $homeTopSponsors = Sponsor::active()->position('home_top')->orderBy('sort_order')->get();
        $homeBottomSponsors = Sponsor::active()->position('home_bottom')->orderBy('sort_order')->get();

        return view('home', compact('stats', 'settings', 'homeTopSponsors', 'homeBottomSponsors'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'cari' => 'required|string|min:3'
        ]);

        // Check if user can search
        if (auth()->check() && !auth()->user()->canSearchData()) {
            $message = 'Akses pencarian ditolak.';
            if (!auth()->user()->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan fitur pencarian.';
            } elseif (auth()->user()->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk menggunakan fitur pencarian.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'account_status' => auth()->user()->account_status,
                'email_verified' => auth()->user()->hasVerifiedEmail()
            ], 403);
        }

        $search = $request->input('cari');

        $results = RentalBlacklist::search($search)
            ->where('status_validitas', 'Valid')
            ->with('user')
            ->get()
            ->map(function ($item) {
                // Cek apakah user sudah login (rental terverifikasi)
                $isAuthenticated = auth()->check();
                $user = auth()->user();

                // Cek apakah user sudah unlock data ini atau NIK ini
                $isUnlocked = $isAuthenticated && ($user->hasUnlockedData($item->id) || $user->hasUnlockedNik($item->nik));

                // Check if user can access uncensored data
                $canAccessData = $isAuthenticated && $user->canAccessData();

                // Tampilkan data sesuai dengan status akun
                return [
                    'id' => $item->id,
                    'nama_lengkap' => $canAccessData ? $item->nama_lengkap : $item->sensored_nama,
                    'nik' => $canAccessData ? $item->nik : $item->sensored_nik,
                    'no_hp' => $canAccessData ? $item->no_hp : $item->sensored_no_hp,
                    'alamat' => $canAccessData ? $item->alamat : $item->sensored_alamat,
                    'jenis_rental' => $item->jenis_rental,
                    'jenis_laporan' => $item->jenis_laporan,
                    'tanggal_kejadian' => $item->tanggal_kejadian->format('d/m/Y'),
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($item->nik),
                    'pelapor' => $item->user->name,
                    'is_verified' => $item->user && $item->user->role === 'pengusaha_rental',
                    'is_unlocked' => $isUnlocked,
                    'can_access_data' => $canAccessData
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => $results->count()
        ]);
    }

    public function detail($id)
    {
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Cek apakah user sudah login
        $isAuthenticated = auth()->check();
        $user = auth()->user();

        // Cek apakah user dapat mengakses data
        $canAccessData = $isAuthenticated && $user->canAccessData();
        $shouldShowFullData = $canAccessData;

        // Cek apakah user sudah unlock data ini atau NIK ini (untuk user biasa)
        $isUnlocked = $isAuthenticated && ($user->hasUnlockedData($id) || $user->hasUnlockedNik($blacklist->nik));

        // Jika admin, pemilik rental, atau sudah unlock, tampilkan data lengkap
        if ($shouldShowFullData || $isUnlocked) {
            return response()->json([
                'success' => true,
                'message' => $shouldShowFullData ? 'Data lengkap tersedia untuk admin/pemilik rental.' : 'Data lengkap karena sudah di-unlock.',
                'data' => [
                    'nama_lengkap' => $blacklist->nama_lengkap,
                    'nik' => $blacklist->nik,
                    'no_hp' => $blacklist->no_hp,
                    'alamat' => $blacklist->alamat,
                    'jenis_kelamin' => $blacklist->jenis_kelamin,
                    'jenis_rental' => $blacklist->jenis_rental,
                    'jenis_laporan' => $blacklist->jenis_laporan,
                    'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                    'kronologi' => $blacklist->kronologi,
                    'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                    'pelapor' => $blacklist->user->name,
                    'pelapor_id' => $blacklist->user->id,
                    'pelapor_role' => $blacklist->user->role,
                    'is_verified' => true,
                    'is_full_access' => true
                ]
            ]);
        }

        // Untuk user tidak login atau user biasa yang belum unlock, tampilkan data sensor
        return response()->json([
            'success' => true,
            'message' => 'Untuk melihat data lengkap, silakan login sebagai pengusaha rental (GRATIS) atau beli kredit untuk akses sekali lihat.',
            'data' => [
                'nama_lengkap' => $blacklist->sensored_nama,
                'nik' => $blacklist->sensored_nik,
                'no_hp' => $blacklist->sensored_no_hp,
                'alamat' => $blacklist->sensored_alamat,
                'jenis_kelamin' => $blacklist->jenis_kelamin,
                'jenis_rental' => $blacklist->jenis_rental,
                'jenis_laporan' => $blacklist->jenis_laporan,
                'tanggal_kejadian' => $blacklist->tanggal_kejadian->format('d/m/Y'),
                'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
                'pelapor' => $blacklist->user->name,
                'pelapor_id' => $blacklist->user->id,
                'pelapor_role' => $blacklist->user->role,
                'is_verified' => false,
                'is_full_access' => false
            ]
        ]);
    }



    /**
     * Unlock blacklist data for authenticated user
     */
    public function unlockData($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::findOrFail($id);

        // Check if user can access full features
        if (!$user->canAccessFullFeatures()) {
            $message = 'Akses ditolak.';
            if (!$user->isActive()) {
                $message = 'Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat menggunakan fitur ini.';
            } elseif ($user->requiresEmailVerification()) {
                $message = 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk menggunakan fitur ini.';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
                'account_status' => $user->account_status,
                'email_verified' => $user->hasVerifiedEmail()
            ], 403);
        }

        // Check if user is pengusaha_rental (they get free access)
        if ($user->role === 'pengusaha_rental') {
            return response()->json([
                'success' => false,
                'message' => 'Pengusaha rental mendapat akses gratis ke semua data'
            ]);
        }

        // Check if already unlocked
        if ($user->hasUnlockedData($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Data sudah dibuka sebelumnya'
            ]);
        }

        // Determine price based on rental type
        $detailPrices = [
            'Rental Mobil' => 1500,
            'Rental Motor' => 1500,
            'Rental Kamera' => 1000,
            'Rental Lainnya' => 800,
        ];

        $price = $detailPrices[$blacklist->jenis_rental] ?? 800;

        try {
            $user->unlockData($id, $price, "Unlock data blacklist: {$blacklist->nama_lengkap}");

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuka!',
                'data' => [
                    'amount_paid' => $price,
                    'remaining_balance' => $user->getCurrentBalance()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function fullDetail($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access (active account and unlocked data)
        $hasAccess = $user && (
            $user->canAccessData() ||
            ($user->isActive() && ($user->hasUnlockedData($id) || $user->hasUnlockedNik($blacklist->nik)))
        );

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data lengkap ini'
            ], 403);
        }

        // Format data lengkap sesuai dengan semua field dari form lapor
        $data = [
            'id' => $blacklist->id,
            // Informasi Penyewa
            'nama_lengkap' => $blacklist->nama_lengkap,
            'nik' => $blacklist->nik,
            'jenis_kelamin' => $blacklist->jenis_kelamin,
            'jenis_kelamin_formatted' => $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'no_hp' => $blacklist->no_hp,
            'alamat' => $blacklist->alamat,

            // Foto Penyewa dan KTP/SIM
            'foto_penyewa' => $blacklist->foto_penyewa ?: [],
            'foto_ktp_sim' => $blacklist->foto_ktp_sim ?: [],

            // Informasi Pelapor
            'nama_perusahaan_rental' => $blacklist->nama_perusahaan_rental,
            'nama_penanggung_jawab' => $blacklist->nama_penanggung_jawab,
            'no_wa_pelapor' => $blacklist->no_wa_pelapor,
            'email_pelapor' => $blacklist->email_pelapor,
            'alamat_usaha' => $blacklist->alamat_usaha,
            'website_usaha' => $blacklist->website_usaha,

            // Detail Masalah
            'jenis_rental' => $blacklist->jenis_rental,
            'tanggal_sewa' => $blacklist->tanggal_sewa,
            'tanggal_sewa_formatted' => $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : null,
            'tanggal_kejadian' => $blacklist->tanggal_kejadian,
            'tanggal_kejadian_formatted' => $blacklist->tanggal_kejadian->format('d/m/Y'),
            'jenis_kendaraan' => $blacklist->jenis_kendaraan,
            'nomor_polisi' => $blacklist->nomor_polisi,
            'nilai_kerugian' => $blacklist->nilai_kerugian,
            'nilai_kerugian_formatted' => $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : null,
            'jenis_laporan' => $blacklist->jenis_laporan ?: [],
            'kronologi' => $blacklist->kronologi,

            // Status Penanganan
            'status_penanganan' => $blacklist->status_penanganan ?: [],
            'status_lainnya' => $blacklist->status_lainnya,

            // Bukti Pendukung
            'bukti' => $blacklist->bukti ?: [],

            // Persetujuan
            'persetujuan' => $blacklist->persetujuan,
            'nama_pelapor_ttd' => $blacklist->nama_pelapor_ttd,
            'tanggal_pelaporan' => $blacklist->tanggal_pelaporan,
            'tanggal_pelaporan_formatted' => $blacklist->tanggal_pelaporan ? $blacklist->tanggal_pelaporan->format('d/m/Y') : null,
            'tipe_pelapor' => $blacklist->tipe_pelapor,

            // Informasi Sistem
            'status_validitas' => $blacklist->status_validitas,
            'jumlah_laporan' => RentalBlacklist::countReportsByNik($blacklist->nik),
            'pelapor' => $blacklist->user->name,
            'created_at' => $blacklist->created_at->format('d/m/Y H:i'),
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function printDetail($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access (active account and unlocked data OR rental owner)
        $hasAccess = $user && (
            $user->canAccessData() ||
            ($user->isActive() && ($user->hasUnlockedData($id) || $user->hasUnlockedNik($blacklist->nik))) ||
            $user->role === 'pengusaha_rental'
        );

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data lengkap ini');
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
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));

        return view('public.print-detail', compact('blacklist', 'verificationCode', 'qrCode', 'verificationUrl'));
    }

    public function downloadPDF($id)
    {
        $user = auth()->user();
        $blacklist = RentalBlacklist::with('user')->findOrFail($id);

        // Check if user has access (active account and unlocked data OR rental owner)
        $hasAccess = $user && (
            $user->canAccessData() ||
            ($user->isActive() && ($user->hasUnlockedData($id) || $user->hasUnlockedNik($blacklist->nik))) ||
            $user->role === 'pengusaha_rental'
        );

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke data lengkap ini');
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
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));

        // Generate filename: laporan-penyewa-nama-penyewa-tanggalcetak-jamcetak.pdf
        $namaPenyewa = str_replace(' ', '-', strtolower($blacklist->nama_lengkap));
        $namaPenyewa = preg_replace('/[^a-z0-9\-]/', '', $namaPenyewa);
        $tanggalCetak = now()->format('dmY');
        $jamCetak = now()->format('His');
        $filename = "laporan-penyewa-{$namaPenyewa}-{$tanggalCetak}-{$jamCetak}.pdf";

        $pdf = Pdf::loadView('public.pdf-detail', compact('blacklist', 'verificationCode', 'qrCode', 'verificationUrl'));
        return $pdf->download($filename);
    }
}
