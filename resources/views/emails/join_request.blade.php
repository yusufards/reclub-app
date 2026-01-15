<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Join Room</title>
    <style>
        /* CSS Inline Sederhana untuk Email Client Compatibility */
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

        /* Header Biru Gelap/Emerald */
        .header {
            background-color: #10b981;
            /* Emerald 500 */
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .content {
            padding: 32px;
        }

        .notification-badge {
            background-color: #fef3c7;
            /* Yellow 100 */
            color: #d97706;
            /* Yellow 600 */
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 15px;
        }

        .info-box {
            background-color: #f9fafb;
            /* Gray 50 */
            border: 1px solid #e5e7eb;
            /* Gray 200 */
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .info-row {
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 1.5;
        }

        .label {
            font-weight: bold;
            color: #6b7280;
            /* Gray 500 */
            width: 100px;
            display: inline-block;
        }

        .value {
            color: #111827;
            /* Gray 900 */
            font-weight: 600;
        }

        /* Group Button agar rapi */
        .btn-group {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            margin: 0 5px;
            transition: opacity 0.3s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-accept {
            background-color: #10b981;
            /* Emerald 500 */
            color: #ffffff !important;
        }

        .btn-reject {
            background-color: #ef4444;
            /* Red 500 */
            color: #ffffff !important;
        }

        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }

        .link-secondary {
            color: #6b7280;
            text-decoration: none;
            font-size: 13px;
            border-bottom: 1px dotted #9ca3af;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Permintaan Join Baru 🔔</h1>
        </div>

        <div class="content">
            <span class="notification-badge">Pending Approval</span>

            <h2 style="margin-top: 0; margin-bottom: 10px; color: #111827; font-size: 22px;">Halo Host! 👋</h2>
            <p style="line-height: 1.6; margin-bottom: 20px;">
                Ada teman baru yang ingin bergabung ke dalam room mabar Anda. Mohon tinjau detail permintaan di bawah
                ini:
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="label">Peserta:</span>
                    <span class="value">{{ $participant->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Room:</span>
                    <span class="value">{{ $participant->room->title }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Waktu:</span>
                    <span class="value">{{ $participant->room->start_datetime->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="info-row" style="margin-bottom: 0;">
                    <span class="label">Status:</span>
                    <span class="value" style="color: #d97706;">Menunggu Konfirmasi</span>
                </div>
            </div>

            <p style="text-align: center; margin-bottom: 10px; font-size: 14px; color: #6b7280;">
                Tentukan tindakan Anda sekarang (Klik salah satu):
            </p>

            {{-- TOMBOL AKSI MAGIC LINK --}}
            <div class="btn-group">
                {{-- Tombol TERIMA --}}
                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('participants.confirm_email', ['participant' => $participant->id]) }}"
                    class="btn btn-accept">
                    ✅ Terima
                </a>

                {{-- Tombol TOLAK --}}
                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('participants.reject_email', ['participant' => $participant->id]) }}"
                    class="btn btn-reject">
                    🚫 Tolak
                </a>
            </div>

            <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('rooms.show', $participant->room->id) }}" class="link-secondary">
                    Lihat Detail Room di Website &rarr;
                </a>
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            Anda menerima email ini karena Anda adalah Host dari room tersebut.
        </div>
    </div>

</body>

</html>