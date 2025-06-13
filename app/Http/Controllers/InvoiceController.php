<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TopupRequest;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $topup = TopupRequest::with('user')
                            ->where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        $data = [
            'invoice_number' => $topup->invoice_number,
            'created_at' => $topup->created_at->format('d/m/Y H:i'),
            'status_color' => $topup->status_color,
            'status_text' => $topup->status_text,
            'user_name' => $topup->user->name,
            'user_email' => $topup->user->email,
            'payment_method' => ucfirst($topup->payment_method),
            'payment_channel' => $topup->payment_channel ? ucfirst($topup->payment_channel) : null,
            'expires_at' => $topup->expires_at ? $topup->expires_at->format('d/m/Y H:i') : null,
            'formatted_amount' => $topup->formatted_amount,
            'notes' => $topup->notes,
            'admin_notes' => $topup->admin_notes,
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function download($id)
    {
        $topup = TopupRequest::with('user')
                            ->where('id', $id)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        $data = [
            'topup' => $topup,
            'user' => $topup->user,
            'generated_at' => now()->format('d/m/Y H:i')
        ];

        $pdf = Pdf::loadView('invoice.pdf', $data);
        
        return $pdf->download("invoice-{$topup->invoice_number}.pdf");
    }
}
