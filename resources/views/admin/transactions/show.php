<?php 
$pageTitle = $pageTitle ?? 'Detail Transaksi';
$transaction = $transaction ?? [];
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Transaction Info -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Main Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Transaksi</h2>
                    <p class="text-gray-600">Transaction ID: <span class="font-mono"><?= e($transaction['transaction_id']) ?></span></p>
                </div>
                <span class="px-4 py-2 text-sm font-medium rounded-full
                    <?= $transaction['payment_status'] === 'success' ? 'bg-green-100 text-green-800' : 
                        ($transaction['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                    <?= ucfirst($transaction['payment_status'] ?? 'pending') ?>
                </span>
            </div>

            <!-- Amount -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                <p class="text-3xl font-bold text-blue-600">
                    Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                </p>
            </div>

            <!-- Booking Details -->
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-800 border-b pb-2">Detail Booking</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Kost</p>
                        <p class="font-medium text-gray-800"><?= e($transaction['kost_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kamar</p>
                        <p class="font-medium text-gray-800"><?= e($transaction['kamar_name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Check-in</p>
                        <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($transaction['start_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Check-out</p>
                        <p class="font-medium text-gray-800"><?= date('d F Y', strtotime($transaction['end_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Durasi</p>
                        <p class="font-medium text-gray-800"><?= $transaction['duration_months'] ?> bulan</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Harga Booking</p>
                        <p class="font-medium text-gray-800">Rp <?= number_format($transaction['booking_price'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Alamat Kost</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['kost_address']) ?></p>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 border-b pb-2 mb-4">Detail Pembayaran</h3>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-medium text-gray-800"><?= formatPaymentMethod($transaction['payment_type']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Order ID Midtrans</span>
                    <span class="font-mono text-sm text-gray-800"><?= e($transaction['midtrans_order_id']) ?></span>
                </div>
                <?php if (!empty($transaction['midtrans_transaction_id'])): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-mono text-sm text-gray-800"><?= e($transaction['midtrans_transaction_id']) ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Transaksi</span>
                    <span class="font-medium text-gray-800"><?= date('d F Y H:i', strtotime($transaction['created_at'])) ?></span>
                </div>
                <?php if (!empty($transaction['paid_at'])): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pembayaran</span>
                    <span class="font-medium text-green-600"><i class="fas fa-check-circle mr-1"></i><?= date('d F Y H:i', strtotime($transaction['paid_at'])) ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($transaction['expires_at']) && $transaction['payment_status'] === 'pending'): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Berlaku Hingga</span>
                    <span class="font-medium text-orange-600"><i class="fas fa-clock mr-1"></i><?= date('d F Y H:i', strtotime($transaction['expires_at'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        
        <!-- Tenant Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 border-b pb-2 mb-4">
                <i class="fas fa-user mr-2 text-blue-600"></i>Informasi Tenant
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['tenant_name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['tenant_email']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['tenant_phone']) ?></p>
                </div>
            </div>
        </div>

        <!-- Owner Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 border-b pb-2 mb-4">
                <i class="fas fa-user-tie mr-2 text-green-600"></i>Informasi Owner
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['owner_name']) ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium text-gray-800"><?= e($transaction['owner_email']) ?></p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <a href="<?= url('/admin/transactions') ?>" 
               class="block w-full text-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

    </div>

</div>
