<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationThankYou;

class DonationController extends Controller
{
    /**
     * Show donation page
     */
    public function index()
    {
        return view('donations.index');
    }

    /**
     * Store donation
     */
    public function store(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'required|string|max:20',
            'donor_province' => 'required|string|max:255',
            'donor_city' => 'required|string|max:255',
            'donor_type' => 'required|in:personal,company',
            'company_name' => 'required_if:donor_type,company|nullable|string|max:255',
            'amount' => 'required|numeric|min:10000',
            'message' => 'nullable|string|max:1000',
        ]);

        $donation = Donation::create($request->all());

        return redirect()->route('donasi.pembayaran', $donation)
                        ->with('success', 'Terima kasih! Silakan lanjutkan ke pembayaran.');
    }

    /**
     * Show payment page
     */
    public function payment(Donation $donation)
    {
        if ($donation->status !== 'pending') {
            return redirect()->route('donasi.indeks')
                           ->with('error', 'Donasi ini sudah diproses.');
        }

        return view('donations.payment', compact('donation'));
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Request $request, Donation $donation)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_reference' => 'required|string|max:255',
        ]);

        $donation->update([
            'payment_method' => $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Send thank you email
        try {
            Mail::to($donation->donor_email)->send(new DonationThankYou($donation));
        } catch (\Exception $e) {
            // Log error but don't fail the process
            \Log::error('Failed to send donation thank you email: ' . $e->getMessage());
        }

        return redirect()->route('donasi.terima-kasih', $donation)
                        ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Show thank you page
     */
    public function thankYou(Donation $donation)
    {
        return view('donations.thank-you', compact('donation'));
    }

    /**
     * Get provinces (for AJAX)
     */
    public function getProvinces()
    {
        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau', 'Jambi',
            'Sumatera Selatan', 'Bangka Belitung', 'Bengkulu', 'Lampung', 'DKI Jakarta',
            'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten',
            'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur', 'Kalimantan Barat',
            'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Gorontalo', 'Sulawesi Barat', 'Maluku', 'Maluku Utara', 'Papua', 'Papua Barat'
        ];

        return response()->json($provinces);
    }

    /**
     * Get cities by province (for AJAX)
     */
    public function getCities(Request $request)
    {
        $province = $request->get('province');

        // Simplified city data - in real app, you'd use proper API
        $cities = [
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cimahi', 'Sukabumi', 'Tasikmalaya'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Yogyakarta', 'Magelang', 'Salatiga'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Kediri', 'Blitar', 'Mojokerto', 'Madiun'],
            // Add more as needed
        ];

        return response()->json($cities[$province] ?? []);
    }
}
