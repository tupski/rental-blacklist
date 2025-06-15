<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email - CekPenyewa.com</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #da3544;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #da3544;
        }
        .content {
            margin-bottom: 30px;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        h1, h2, h3 {
            color: #da3544;
        }
        a {
            color: #da3544;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #da3544;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #b82d3c;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">CekPenyewa.com</div>
            <p>Sistem Blacklist Rental Indonesia</p>
        </div>
        
        <div class="content">
            {!! $content !!}
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis dari sistem CekPenyewa.com</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi support kami:</p>
            <p>Email: support@cekpenyewa.com | Website: <a href="https://cekpenyewa.com">cekpenyewa.com</a></p>
            <p>&copy; {{ date('Y') }} CekPenyewa.com - PT. Indo Web Solution</p>
        </div>
    </div>
</body>
</html>
