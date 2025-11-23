<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #10b981;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            line-height: 80px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .message {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
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
        .total-row {
            background: #eff6ff;
            margin: 0 -20px;
            padding: 15px 20px;
            margin-top: 15px;
        }
        .total-row .detail-value {
            color: #10b981;
            font-size: 20px;
        }
        .info-box {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 16px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
        }
        .info-box li {
            margin: 5px 0;
        }
        .contact-box {
            background: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .contact-box h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .contact-box p {
            margin: 5px 0;
            font-size: 18px;
        }
        .contact-box a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
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
            <div class="success-icon">✓</div>
            <h1>Pembayaran Berhasil!</h1>
            <p>Terima kasih atas pembayaran Anda</p>
        </div>

        <div class="content">
            <div class="message">
                <p style="margin: 0;"><strong>Halo, <?= htmlspecialchars($tenant_name ?? 'Customer') ?>!</strong></p>
                <p style="margin: 5px 0 0 0;">Pembayaran Anda telah berhasil diproses. Berikut adalah detail transaksi Anda:</p>
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
                    <span class="detail-label">Periode Sewa</span>
                    <span class="detail-value">
                        <?= date('d M Y', strtotime($start_date ?? 'now')) ?> - 
                        <?= date('d M Y', strtotime($end_date ?? 'now')) ?>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Durasi</span>
                    <span class="detail-value"><?= $duration_months ?? 0 ?> Bulan</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Metode Pembayaran</span>
                    <span class="detail-value"><?= strtoupper(str_replace('_', ' ', $payment_type ?? 'N/A')) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Tanggal Pembayaran</span>
                    <span class="detail-value"><?= date('d M Y H:i', strtotime($paid_at ?? 'now')) ?> WIB</span>
                </div>
                
                <div class="total-row">
                    <div class="detail-row" style="border: none;">
                        <span class="detail-label" style="font-size: 16px; color: #111827;">Total Dibayar</span>
                        <span class="detail-value">Rp <?= number_format($amount ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <h3>📋 Langkah Selanjutnya:</h3>
                <ul>
                    <li>Pembayaran Anda sedang diproses oleh pemilik kost</li>
                    <li>Anda akan menerima notifikasi setelah booking dikonfirmasi</li>
                    <li>Silakan hubungi pemilik kost untuk koordinasi check-in</li>
                </ul>
            </div>

            <?php if (!empty($owner_name) && !empty($owner_phone)): ?>
            <div class="contact-box">
                <h3>📞 Hubungi Pemilik Kost</h3>
                <p><strong><?= htmlspecialchars($owner_name) ?></strong></p>
                <p><a href="tel:<?= htmlspecialchars($owner_phone) ?>"><?= htmlspecialchars($owner_phone) ?></a></p>
            </div>
            <?php endif; ?>

            <center>
                <a href="<?= url('/tenant/bookings') ?>" class="button">Lihat Detail Booking</a>
            </center>
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
