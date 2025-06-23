<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih atas Donasi Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .donation-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #da3544;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #da3544;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #da3544;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Terima Kasih, {{ $donation->donor_name }}!</h1>
        <p>Donasi Anda sangat berarti bagi pengembangan CekPenyewa.com</p>
    </div>

    <div class="content">
        <p>Halo <strong>{{ $donation->donor_name }}</strong>,</p>

        <p>Terima kasih atas dukungan luar biasa Anda untuk pengembangan platform CekPenyewa.com! 
        Donasi Anda telah berhasil kami terima dan akan langsung digunakan untuk meningkatkan 
        kualitas layanan kami.</p>

        <div class="donation-details">
            <h3>📋 Detail Donasi Anda</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Nama Donatur:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $donation->donor_name }}</td>
                </tr>
                @if($donation->company_name)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Perusahaan:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $donation->company_name }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Jumlah Donasi:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;" class="amount">{{ $donation->formatted_amount }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Metode Pembayaran:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $donation->payment_method }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Referensi:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $donation->payment_reference }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Tanggal:</strong></td>
                    <td style="padding: 8px 0;">{{ $donation->paid_at->format('d F Y, H:i') }} WIB</td>
                </tr>
            </table>

            @if($donation->message)
            <div style="margin-top: 20px; padding: 15px; background: #e3f2fd; border-radius: 5px;">
                <strong>💬 Pesan Anda:</strong><br>
                <em>"{{ $donation->message }}"</em>
            </div>
            @endif
        </div>

        <h3>🚀 Dampak Donasi Anda</h3>
        <p>Dengan donasi sebesar <strong>{{ $donation->formatted_amount }}</strong>, Anda telah membantu:</p>
        <ul>
            <li>🖥️ Biaya server dan infrastruktur untuk {{ ceil($donation->amount / 50000) }} hari</li>
            <li>🛡️ Melindungi {{ ceil($donation->amount / 1000) }}+ bisnis rental dari pelanggan bermasalah</li>
            <li>⚡ Pengembangan fitur-fitur baru seperti AI moderation dan verifikasi otomatis</li>
            <li>👥 Dukungan operasional tim untuk moderasi dan customer service</li>
        </ul>

        <h3>📋 Langkah Selanjutnya</h3>
        <p>Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam. Setelah verifikasi selesai, 
        status donasi Anda akan diperbarui dan Anda akan menerima email konfirmasi.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('beranda') }}" class="btn">Kunjungi CekPenyewa.com</a>
        </div>

        <h3>🤝 Bagikan Dukungan Anda</h3>
        <p>Ajak teman dan keluarga untuk ikut mendukung pengembangan platform ini:</p>
        
        <div class="social-links" style="text-align: center;">
            <a href="https://wa.me/?text=Saya%20baru%20saja%20berdonasi%20untuk%20pengembangan%20platform%20CekPenyewa.com.%20Mari%20dukung%20bersama!%20{{ urlencode(route('donasi.indeks')) }}">
                📱 WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('donasi.indeks')) }}">
                📘 Facebook
            </a>
            <a href="https://twitter.com/intent/tweet?text=Saya%20baru%20saja%20berdonasi%20untuk%20pengembangan%20platform%20CekPenyewa.com&url={{ urlencode(route('donasi.indeks')) }}">
                🐦 Twitter
            </a>
        </div>

        <p style="margin-top: 30px;">Sekali lagi, terima kasih atas kepercayaan dan dukungan Anda. 
        Bersama-sama kita akan membangun ekosistem rental yang lebih aman dan terpercaya di Indonesia!</p>

        <p>Salam hangat,<br>
        <strong>Tim CekPenyewa.com</strong><br>
        PT. Indo Web Solution</p>
    </div>

    <div class="footer">
        <p>Email ini dikirim otomatis dari sistem CekPenyewa.com</p>
        <p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui website resmi kami.</p>
        <p>&copy; {{ date('Y') }} CekPenyewa.com - PT. Indo Web Solution. All rights reserved.</p>
    </div>
</body>
</html>
