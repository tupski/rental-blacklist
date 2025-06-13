<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopupRequest;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TopupRequestNotification;

class TopupController extends Controller
{
    // Harga detail per kategori
    private $detailPrices = [
        'Rental Mobil' => 1500,
        'Rental Motor' => 1500,
        'Rental Kamera' => 1000,
        'Rental Lainnya' => 800,
    ];

    // Paket topup yang tersedia
    private $topupPackages = [
        [
            'name' => 'Paket Starter',
            'amount' => 10000,
            'bonus' => 0,
            'total' => 10000,
            'description' => 'Cocok untuk 6-12 detail',
            'popular' => false
        ],
        [
            'name' => 'Paket Basic',
            'amount' => 25000,
            'bonus' => 2500,
            'total' => 27500,
            'description' => 'Cocok untuk 18-34 detail',
            'popular' => false
        ],
        [
            'name' => 'Paket Popular',
            'amount' => 50000,
            'bonus' => 7500,
            'total' => 57500,
            'description' => 'Cocok untuk 38-71 detail',
            'popular' => true
        ],
        [
            'name' => 'Paket Premium',
            'amount' => 100000,
            'bonus' => 20000,
            'total' => 120000,
            'description' => 'Cocok untuk 80-150 detail',
            'popular' => false
        ],
        [
            'name' => 'Paket Business',
            'amount' => 250000,
            'bonus' => 62500,
            'total' => 312500,
            'description' => 'Cocok untuk 208-390 detail',
            'popular' => false
        ]
    ];

    public function index()
    {
        $user = Auth::user();
        $currentBalance = $user->getCurrentBalance();
        $packages = $this->topupPackages;
        $detailPrices = $this->detailPrices;

        // Get recent topup requests
        $recentTopups = $user->topupRequests()
                            ->latest()
                            ->take(5)
                            ->get();

        return view('topup.index', compact('currentBalance', 'packages', 'detailPrices', 'recentTopups'));
    }

    public function create()
    {
        $user = Auth::user();
        $currentBalance = $user->getCurrentBalance();
        $packages = $this->topupPackages;
        $detailPrices = $this->detailPrices;

        // Payment methods
        $paymentMethods = [
            'manual' => [
                'name' => 'Transfer Manual',
                'description' => 'Transfer ke rekening bank atau e-wallet',
                'channels' => [
                    'BCA' => ['name' => 'BCA', 'account' => '6050381330', 'holder' => 'ANGGA DWY SAPUTRA'],
                    'BJB' => ['name' => 'BJB', 'account' => '12345869594939', 'holder' => 'ANGGA DWY SAPUTRA'],
                    'BRI' => ['name' => 'BRI', 'account' => '208319382834', 'holder' => 'ANGGA DWY SAPUTRA'],
                    'GoPay' => ['name' => 'GoPay', 'account' => '0819-1191-9993', 'holder' => 'ANGGA DWY SAPUTRA'],
                    'OVO' => ['name' => 'OVO', 'account' => '0822-1121-9993', 'holder' => 'ANGGA DWY SAPUTRA'],
                    'Dana' => ['name' => 'Dana', 'account' => '0819-1191-9993', 'holder' => 'ANGGA DWY SAPUTRA'],
                ]
            ],
            'midtrans' => [
                'name' => 'Midtrans',
                'description' => 'Pembayaran otomatis via Midtrans',
                'channels' => []
            ],
            'xendit' => [
                'name' => 'Xendit',
                'description' => 'Pembayaran otomatis via Xendit',
                'channels' => []
            ]
        ];

        return view('topup.create', compact('currentBalance', 'packages', 'detailPrices', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        // Determine amount from package or custom
        $amount = 0;
        if ($request->package === 'custom') {
            $amount = $request->custom_amount;
        } else {
            $amount = $request->package;
        }

        $request->merge(['amount' => $amount]);

        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|in:manual,midtrans,xendit',
            'payment_channel' => 'required_if:payment_method,manual',
            'notes' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        // Ensure user has balance record
        if (!$user->balance) {
            $user->balance()->create(['balance' => 0]);
        }

        // Create topup request
        $topupRequest = TopupRequest::create([
            'user_id' => $user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_channel' => $request->payment_channel,
            'payment_details' => [
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'created_via' => 'web'
            ],
            'notes' => $request->notes,
            'expires_at' => now()->addHours(24), // 24 jam untuk pembayaran
        ]);

        // Send notification
        try {
            $topupRequest->user->notify(new TopupRequestNotification($topupRequest, 'created'));
        } catch (\Exception $e) {
            \Log::warning('Failed to send topup notification: ' . $e->getMessage());
        }

        // Always redirect to confirm page regardless of payment method
        return redirect()->route('topup.confirm', $topupRequest->invoice_number);
    }

    public function confirm($invoice)
    {
        $topupRequest = TopupRequest::where('invoice_number', $invoice)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        if (!$topupRequest->canBePaid()) {
            return redirect()->route('topup.index')->with('error', 'Request topup sudah tidak valid');
        }

        return view('topup.confirm', compact('topupRequest'));
    }

    public function uploadProof(Request $request, $invoice)
    {
        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string|max:500'
        ]);

        $topupRequest = TopupRequest::where('invoice_number', $invoice)
                                  ->where('user_id', Auth::id())
                                  ->firstOrFail();

        if (!$topupRequest->canBePaid()) {
            return redirect()->route('topup.index')->with('error', 'Request topup sudah tidak valid');
        }

        // Store the uploaded file
        $file = $request->file('proof_of_payment');
        $filename = 'proof_' . $invoice . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('topup-proofs', $filename, 'public');

        // Update topup request
        $topupRequest->update([
            'proof_of_payment' => $path,
            'notes' => $request->notes,
            'status' => 'pending_confirmation'
        ]);

        return redirect()->route('topup.index')->with('success', 'Bukti pembayaran berhasil diupload. Pembayaran akan dikonfirmasi dalam 1x24 jam.');
    }
}
