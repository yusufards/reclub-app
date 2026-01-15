<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kamu Diterima!</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #374151;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Header Hijau (Emerald) */
        .header {
            background-color: #10b981;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .content {
            padding: 32px;
            text-align: center;
        }

        .success-icon {
            font-size: 48px;
            line-height: 1;
            margin-bottom: 15px;
            display: block;
        }

        /* Card Detail */
        .card {
            background-color: #f0fdf4;
            border: 1px dashed #10b981;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
        }

        .card-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            color: #047857;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .card-row {
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5;
            display: flex;
            justify-content: space-between;
        }

        .label {
            font-weight: bold;
            color: #065f46;
            width: 30%;
        }

        .value {
            color: #1f2937;
            font-weight: 600;
            width: 70%;
            text-align: right;
        }

        .btn {
            display: inline-block;
            background-color: #111827;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            transition: opacity 0.3s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>YESS! DITERIMA 🎉</h1>
        </div>
        <div class="content">
            <span class="success-icon">🙌</span>
            <h2 style="margin: 0 0 10px 0; color: #111827; font-size: 20px;">Halo {{ $participant->user->name }}!</h2>
            <p style="margin-bottom: 20px; line-height: 1.6;">
                Kabar gembira! Permintaan join kamu untuk mabar di bawah ini telah <strong>DIKONFIRMASI</strong> oleh
                Host.
            </p>

            <div class="card">
                <div class="card-title">DETAIL TIKET MASUK</div>
                <div class="card-row">
                    <span class="label">Aktivitas:</span>
                    <span class="value">{{ $participant->room->title }}</span>
                </div>
                <div class="card-row">
                    <span class="label">Waktu:</span>
                    <span class="value">{{ $participant->room->start_datetime->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="card-row">
                    <span class="label">Lokasi:</span>
                    <span class="value">{{ $participant->room->venue->name }}</span>
                </div>
                <div class="card-row" style="margin-bottom: 0;">
                    <span class="label">Biaya:</span>
                    <span class="value">
                        @if($participant->room->cost_per_person > 0)
                            Rp {{ number_format($participant->room->cost_per_person, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </span>
                </div>
            </div>

            <p style="font-size: 14px; color: #6b7280; margin-bottom: 25px;">
                Siapkan peralatanmu dan jangan sampai terlambat ya!
            </p>

            <a href="{{ route('rooms.show', $participant->room->id) }}" class="btn">
                Lihat Detail Room
            </a>
        </div>
        <div class="footer">
            Sampai jumpa di lapangan!<br>
            &copy; {{ date('Y') }} {{ config('app.name') }} Team.
        </div>
    </div>
</body>

</html>