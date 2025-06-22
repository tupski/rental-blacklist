<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SponsorPackage;
use App\Models\SponsorPurchase;
use App\Models\SponsorSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SponsorshipController extends Controller
{
    /**
     * Halaman pembelian paket sponsor
     */
    public function purchase(SponsorPackage $sponsorPackage)
    {
        if (!$sponsorPackage->is_active) {
            return redirect()->route('sponsor.kemitraan')
                           ->with('error', 'Paket sponsor tidak tersedia.');
        }

        return view('sponsorship.purchase', compact('sponsorPackage'));
    }

    /**
     * Proses pembelian paket sponsor
     */
    public function storePurchase(Request $request, SponsorPackage $sponsorPackage)
    {
        $request->validate([
            'agree_terms' => 'required|accepted'
        ], [
            'agree_terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.'
        ]);

        if (!$sponsorPackage->is_active) {
            return redirect()->route('sponsor.kemitraan')
                           ->with('error', 'Paket sponsor tidak tersedia.');
        }

        // Cek apakah user sudah memiliki pembelian yang pending atau aktif
        $existingPurchase = SponsorPurchase::where('user_id', auth()->id())
                                          ->whereIn('payment_status', ['pending', 'paid', 'confirmed'])
                                          ->first();

        if ($existingPurchase) {
            return redirect()->route('sponsorship.pembayaran')
                           ->with('error', 'Anda masih memiliki pembelian sponsor yang belum selesai.');
        }

        // Buat pembelian baru
        $purchase = SponsorPurchase::create([
            'user_id' => auth()->id(),
            'sponsor_package_id' => $sponsorPackage->id,
            'invoice_number' => SponsorPurchase::generateInvoiceNumber(),
            'amount' => $sponsorPackage->price,
            'payment_status' => 'pending',
            'payment_deadline' => now()->addHours(24), // 24 jam untuk pembayaran
        ]);

        return redirect()->route('sponsorship.pembayaran.detail', $purchase)
                        ->with('success', 'Pembelian berhasil dibuat. Silakan lakukan pembayaran dalam 24 jam.');
    }

    /**
     * Halaman daftar pembayaran sponsor
     */
    public function payment()
    {
        $purchases = SponsorPurchase::where('user_id', auth()->id())
                                   ->with('sponsorPackage')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('sponsorship.payment', compact('purchases'));
    }

    /**
     * Detail pembayaran sponsor
     */
    public function paymentDetail(SponsorPurchase $sponsorPurchase)
    {
        // Pastikan purchase milik user yang login
        if ($sponsorPurchase->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Update status jika sudah expired
        if ($sponsorPurchase->isExpired() && $sponsorPurchase->payment_status === 'pending') {
            $sponsorPurchase->update(['payment_status' => 'expired']);
        }

        return view('sponsorship.payment-detail', compact('sponsorPurchase'));
    }

    /**
     * Konfirmasi pembayaran dengan upload bukti
     */
    public function confirmPayment(Request $request, SponsorPurchase $sponsorPurchase)
    {
        // Pastikan purchase milik user yang login
        if ($sponsorPurchase->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Cek apakah masih bisa dikonfirmasi
        if ($sponsorPurchase->payment_status !== 'pending' || $sponsorPurchase->isExpired()) {
            return redirect()->route('sponsorship.pembayaran')
                           ->with('error', 'Pembayaran sudah tidak dapat dikonfirmasi.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'payment_notes' => 'nullable|string|max:1000'
        ], [
            'payment_proof.required' => 'Bukti pembayaran harus diupload.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'payment_proof.max' => 'Ukuran file maksimal 5MB.'
        ]);

        // Upload bukti pembayaran
        $proofPath = $request->file('payment_proof')->store('sponsor-payments', 'public');

        // Update status pembayaran
        $sponsorPurchase->update([
            'payment_status' => 'paid',
            'payment_proof' => $proofPath,
            'payment_notes' => $request->payment_notes,
            'paid_at' => now()
        ]);

        // Kirim email notifikasi ke user
        $this->sendPaymentConfirmationEmail($sponsorPurchase);

        // Kirim email notifikasi ke admin
        $this->sendAdminNotificationEmail($sponsorPurchase);

        return redirect()->route('sponsorship.pembayaran')
                        ->with('success', 'Konfirmasi pembayaran berhasil dikirim. Admin akan memverifikasi dalam 1x24 jam.');
    }

    /**
     * Halaman pengaturan sponsor setelah pembayaran dikonfirmasi
     */
    public function settings(SponsorPurchase $sponsorPurchase)
    {
        // Pastikan purchase milik user yang login
        if ($sponsorPurchase->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Pastikan pembayaran sudah dikonfirmasi
        if ($sponsorPurchase->payment_status !== 'confirmed') {
            return redirect()->route('sponsorship.pembayaran')
                           ->with('error', 'Pengaturan sponsor hanya tersedia setelah pembayaran dikonfirmasi admin.');
        }

        $sponsorSetting = $sponsorPurchase->sponsorSetting;

        return view('sponsorship.settings', compact('sponsorPurchase', 'sponsorSetting'));
    }

    /**
     * Update pengaturan sponsor
     */
    public function updateSettings(Request $request, SponsorPurchase $sponsorPurchase)
    {
        // Pastikan purchase milik user yang login
        if ($sponsorPurchase->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Pastikan pembayaran sudah dikonfirmasi
        if ($sponsorPurchase->payment_status !== 'confirmed') {
            return redirect()->route('sponsorship.pembayaran')
                           ->with('error', 'Pengaturan sponsor hanya tersedia setelah pembayaran dikonfirmasi admin.');
        }

        $maxLogoSize = $sponsorPurchase->sponsorPackage->max_logo_size_kb;

        $request->validate([
            'company_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:' . $maxLogoSize,
            'website_url' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'placement_positions' => 'required|array|min:1',
            'placement_positions.*' => 'string|in:home_top,home_bottom,footer,sidebar',
            'social_media.facebook' => 'nullable|url|max:255',
            'social_media.instagram' => 'nullable|url|max:255',
            'social_media.twitter' => 'nullable|url|max:255',
            'social_media.youtube' => 'nullable|url|max:255',
            'social_media.linkedin' => 'nullable|url|max:255',
            'social_media.tiktok' => 'nullable|url|max:255',
            'social_media.whatsapp' => 'nullable|string|max:20'
        ]);

        $data = $request->except(['logo']);

        // Filter social media yang kosong
        if (isset($data['social_media'])) {
            $data['social_media'] = array_filter($data['social_media']);
        }

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            $existingSetting = $sponsorPurchase->sponsorSetting;
            if ($existingSetting && $existingSetting->logo && Storage::disk('public')->exists($existingSetting->logo)) {
                Storage::disk('public')->delete($existingSetting->logo);
            }

            $data['logo'] = $request->file('logo')->store('sponsor-logos', 'public');
        }

        // Update atau buat pengaturan sponsor
        SponsorSetting::updateOrCreate(
            ['sponsor_purchase_id' => $sponsorPurchase->id],
            $data
        );

        return redirect()->route('sponsorship.pengaturan', $sponsorPurchase)
                        ->with('success', 'Pengaturan sponsor berhasil disimpan.');
    }

    /**
     * Halaman daftar sponsorship milik user
     */
    public function mySponsorship()
    {
        $purchases = SponsorPurchase::where('user_id', auth()->id())
                                   ->with(['sponsorPackage', 'sponsorSetting'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('sponsorship.my-sponsorship', compact('purchases'));
    }

    /**
     * Kirim email konfirmasi pembayaran ke user
     */
    private function sendPaymentConfirmationEmail(SponsorPurchase $purchase)
    {
        try {
            Mail::send('emails.sponsor-payment-confirmation', compact('purchase'), function ($message) use ($purchase) {
                $message->to($purchase->user->email, $purchase->user->name)
                        ->subject('Konfirmasi Pembayaran Sponsor - ' . $purchase->invoice_number);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
        }
    }

    /**
     * Kirim email notifikasi ke admin
     */
    private function sendAdminNotificationEmail(SponsorPurchase $purchase)
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@cekpenyewa.com');

            Mail::send('emails.admin-sponsor-notification', compact('purchase'), function ($message) use ($purchase, $adminEmail) {
                $message->to($adminEmail)
                        ->subject('Konfirmasi Pembayaran Sponsor Baru - ' . $purchase->invoice_number);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send admin notification email: ' . $e->getMessage());
        }
    }
}
