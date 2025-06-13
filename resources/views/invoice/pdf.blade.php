<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $topup->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        .company-tagline {
            font-size: 14px;
            color: #666;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px 0;
            vertical-align: top;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .billing-info {
            margin-bottom: 30px;
        }
        .billing-info table {
            width: 100%;
        }
        .billing-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .items-table .text-right {
            text-align: right;
        }
        .total-row {
            background-color: #e3f2fd;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-paid { background-color: #d1ecf1; color: #0c5460; }
        .status-confirmed { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-expired { background-color: #e2e3e5; color: #383d41; }
        .notes {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Rental Blacklist Indonesia</div>
        <div class="company-tagline">Sistem Blacklist Rental Terpercaya</div>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td width="50%">
                    <div class="invoice-title">INVOICE</div>
                    <strong>No. Invoice:</strong> {{ $topup->invoice_number }}<br>
                    <strong>Tanggal:</strong> {{ $topup->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ $topup->status }}">{{ $topup->status_text }}</span>
                </td>
                <td width="50%" style="text-align: right;">
                    <strong>Rental Blacklist Indonesia</strong><br>
                    Sistem Blacklist Rental<br>
                    Indonesia<br>
                    <br>
                    <strong>Dibuat:</strong> {{ $generated_at }}
                </td>
            </tr>
        </table>
    </div>

    <div class="billing-info">
        <table>
            <tr>
                <td width="50%">
                    <div class="section-title">Tagihan Kepada:</div>
                    <strong>{{ $user->name }}</strong><br>
                    {{ $user->email }}<br>
                    Role: {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </td>
                <td width="50%">
                    <div class="section-title">Detail Pembayaran:</div>
                    <strong>Metode:</strong> {{ ucfirst($topup->payment_method) }}<br>
                    @if($topup->payment_channel)
                        <strong>Channel:</strong> {{ ucfirst($topup->payment_channel) }}<br>
                    @endif
                    @if($topup->expires_at)
                        <strong>Batas Bayar:</strong> {{ $topup->expires_at->format('d/m/Y H:i') }}<br>
                    @endif
                    @if($topup->paid_at)
                        <strong>Dibayar:</strong> {{ $topup->paid_at->format('d/m/Y H:i') }}<br>
                    @endif
                    @if($topup->confirmed_at)
                        <strong>Dikonfirmasi:</strong> {{ $topup->confirmed_at->format('d/m/Y H:i') }}<br>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Topup Saldo - {{ ucfirst($topup->payment_method) }}
                    @if($topup->payment_channel)
                        ({{ ucfirst($topup->payment_channel) }})
                    @endif
                </td>
                <td class="text-right">{{ $topup->formatted_amount }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ $topup->formatted_amount }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($topup->notes)
    <div class="notes">
        <div class="section-title">Catatan:</div>
        {{ $topup->notes }}
    </div>
    @endif

    @if($topup->admin_notes)
    <div class="notes">
        <div class="section-title">Catatan Admin:</div>
        {{ $topup->admin_notes }}
    </div>
    @endif

    <div class="footer">
        Invoice ini dibuat secara otomatis oleh sistem Rental Blacklist Indonesia.<br>
        Untuk pertanyaan, silakan hubungi customer service kami.<br>
        <br>
        <strong>Terima kasih atas kepercayaan Anda!</strong>
    </div>
</body>
</html>
