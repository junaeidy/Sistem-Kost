<?php 
$pageTitle = $pageTitle ?? 'Kelola Transaksi';
$transactions = $transactions ?? [];
$currentStatus = $currentStatus ?? 'all';
$stats = $stats ?? [];
?>

<!-- Statistics Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
    <div class="bg-white rounded-lg shadow-md p-3 md:p-4">
        <p class="text-gray-500 text-xs md:text-sm">Total Transaksi</p>
        <p class="text-xl md:text-2xl font-bold text-gray-800"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-3 md:p-4">
        <p class="text-gray-500 text-xs md:text-sm">Pending</p>
        <p class="text-xl md:text-2xl font-bold text-yellow-600"><?= $stats['pending'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-3 md:p-4">
        <p class="text-gray-500 text-xs md:text-sm">Success</p>
        <p class="text-xl md:text-2xl font-bold text-green-600"><?= $stats['success'] ?? 0 ?></p>
    </div>
    <div class="bg-white rounded-lg shadow-md p-3 md:p-4 col-span-2 lg:col-span-1">
        <p class="text-gray-500 text-xs md:text-sm">Total Revenue</p>
        <p class="text-lg md:text-2xl font-bold text-blue-600">Rp <?= number_format($stats['total_revenue'] ?? 0, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-4 md:mb-6">
    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-2 md:gap-3">
        <span class="text-gray-700 font-medium text-sm md:text-base">Filter Status:</span>
        <a href="<?= url('/admin/transactions?status=all') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Semua
        </a>
        <a href="<?= url('/admin/transactions?status=pending') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Pending
        </a>
        <a href="<?= url('/admin/transactions?status=success') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'success' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Success
        </a>
        <a href="<?= url('/admin/transactions?status=failed') ?>" 
           class="px-3 md:px-4 py-2 rounded-lg text-sm md:text-base text-center <?= $currentStatus === 'failed' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
            Failed
        </a>
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <?php if (empty($transactions)): ?>
        <div class="p-8 md:p-12 text-center">
            <i class="fas fa-receipt text-5xl md:text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-base md:text-lg">Tidak ada transaksi ditemukan</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Transaksi</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Tenant</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Kost / Kamar</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden xl:table-cell">Periode</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                        <th class="px-4 md:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($transactions as $transaction): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 md:px-6 py-4">
                                <p class="font-mono text-xs md:text-sm text-gray-800 truncate"><?= e($transaction['transaction_id']) ?></p>
                                <p class="text-xs text-gray-500">Order: #<?= $transaction['id'] ?></p>
                                <div class="lg:hidden mt-1">
                                    <p class="text-xs font-medium text-gray-800 truncate"><?= e($transaction['tenant_name'] ?? '-') ?></p>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden lg:table-cell">
                                <p class="font-medium text-gray-800 text-sm truncate"><?= e($transaction['tenant_name'] ?? '-') ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= e($transaction['tenant_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                <p class="font-medium text-gray-800 text-sm truncate"><?= e($transaction['kost_name'] ?? '-') ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= e($transaction['kamar_name'] ?? '-') ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden xl:table-cell">
                                <p class="text-sm text-gray-700 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($transaction['start_date'])) ?>
                                </p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">
                                    s/d <?= date('d M Y', strtotime($transaction['end_date'])) ?>
                                </p>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <p class="font-bold text-blue-600 text-sm md:text-base whitespace-nowrap">
                                    Rp <?= number_format($transaction['amount'], 0, ',', '.') ?>
                                </p>
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <span class="px-2 md:px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                    <?= $transaction['payment_status'] === 'success' ? 'bg-green-100 text-green-800' : 
                                        ($transaction['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= ucfirst($transaction['payment_status'] ?? 'pending') ?>
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                <p class="text-sm text-gray-700 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($transaction['created_at'])) ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    <?= date('H:i', strtotime($transaction['created_at'])) ?>
                                </p>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <a href="<?= url('/admin/transactions/' . $transaction['id']) ?>" 
                                   class="inline-flex items-center px-2 md:px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs md:text-sm whitespace-nowrap">
                                    <i class="fas fa-eye mr-0 md:mr-1"></i> <span class="hidden md:inline">Detail</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
    <?php 
    $baseUrl = url('/admin/transactions');
    include __DIR__ . '/../../components/pagination.php';
    ?>
<?php endif; ?>
