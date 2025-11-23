<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran <?= $status == 'expired' ? 'Kadaluarsa' : 'Dibatalkan' ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .icon {
            width: 80px;
            height: 80px;
            background: #fee2e2;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            line-height: 80px;
            text-align: center;
            color: #dc2626;
        }
        .content {
            padding: 40px 30px;
        }
        .message {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .details-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: table;
            width: 100%;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            display: table-cell;
            color: #6b7280;
            font-weight: 500;
            width: 50%;
            vertical-align: top;
        }
        .detail-value {
            display: table-cell;
            color: #111827;
            font-weight: 600;
            text-align: right;
            width: 50%;
            vertical-align: top;
        }
        .info-box {
            background: #dbeafe;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }
        .info-box p {
            margin: 5px 0;
            color: #1e3a8a;
        }
        .reason-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-box h3 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 16px;
        }
        .reason-box ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
        }
        .reason-box li {
            margin: 5px 0;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            line-height: 1.4;
            vertical-align: middle;
        }
        .button-secondary {
            background: #6b7280;
            color: white !important;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">❌</div>
            <h1>Pembayaran <?= $status == 'expired' ? 'Kadaluarsa' : 'Dibatalkan' ?></h1>
            <p><?= $status == 'expired' ? 'Waktu pembayaran telah habis' : 'Pembayaran telah dibatalkan' ?></p>
        </div>

        <div class="content">
            <div class="message">
                <p style="margin: 0;"><strong>Halo, <?= htmlspecialchars($tenant_name ?? 'Customer') ?></strong></p>
                <p style="margin: 5px 0 0 0;">
                    <?php if ($status == 'expired'): ?>
                        Maaf, pembayaran Anda telah melewati batas waktu yang ditentukan.
                    <?php else: ?>
                        Pembayaran untuk booking Anda telah dibatalkan.
                    <?php endif; ?>
                </p>
            </div>

            <div class="details-box">
                <h2 style="margin: 0 0 15px 0; color: #111827; font-size: 18px;">Detail Booking</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID</span>
                    <span class="detail-value"><?= htmlspecialchars($booking_id ?? '-') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value"><?= htmlspecialchars($order_id ?? '-') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Nama Kost</span>
                    <span class="detail-value"><?= htmlspecialchars($kost_name ?? '-') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Kamar</span>
                    <span class="detail-value"><?= htmlspecialchars($kamar_name ?? '-') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Total Amount</span>
                    <span class="detail-value">Rp <?= number_format($amount ?? 0, 0, ',', '.') ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #ef4444;">
                        <?= $status == 'expired' ? 'KADALUARSA' : 'DIBATALKAN' ?>
                    </span>
                </div>
            </div>

            <?php if ($status == 'expired'): ?>
            <div class="reason-box">
                <h3>⏰ Mengapa Pembayaran Kadaluarsa?</h3>
                <ul>
                    <li>Pembayaran tidak diselesaikan dalam waktu yang ditentukan (24 jam)</li>
                    <li>Untuk menjaga ketersediaan kamar bagi customer lain</li>
                    <li>Booking otomatis dibatalkan dan kamar kembali tersedia</li>
                </ul>
            </div>
            <?php endif; ?>

            <div class="info-box">
                <h3>💡 Apa yang harus dilakukan?</h3>
                <p><strong>Jangan khawatir!</strong> Anda masih bisa melakukan booking kembali jika kamar masih tersedia.</p>
                <p>Silakan lakukan booking baru untuk melanjutkan proses penyewaan.</p>
            </div>

            <center>
                <a href="<?= url('/') ?>" class="button">Cari Kost Lagi</a>
                <br>
                <a href="<?= url('/tenant/bookings') ?>" class="button button-secondary">Lihat Riwayat Booking</a>
            </center>

            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px;">
                <strong>Butuh bantuan?</strong><br>
                Jika Anda memiliki pertanyaan, silakan hubungi customer support kami.
            </p>
        </div>

        <div class="footer">
            <p><strong><?= config('app.name') ?? 'Sistem Kost' ?></strong></p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                &copy; <?= date('Y') ?> <?= config('app.name') ?? 'Sistem Kost' ?>. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
