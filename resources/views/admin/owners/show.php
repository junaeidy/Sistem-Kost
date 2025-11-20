<?php 
$pageTitle = $pageTitle ?? 'Detail Owner';
$owner = $owner ?? [];
$kosts = $kosts ?? [];
?>

<!-- Owner Info Card -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-start justify-between mb-6">
        <div class="flex items-start">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mr-6">
                <i class="fas fa-user text-4xl text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2"><?= e($owner['name']) ?></h2>
                <div class="space-y-1">
                    <p class="text-gray-600">
                        <i class="fas fa-envelope mr-2"></i><?= e($owner['email']) ?>
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-phone mr-2"></i><?= e($owner['phone']) ?>
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-map-marker-alt mr-2"></i><?= e($owner['address'] ?? '-') ?>
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>Bergabung: <?= date('d F Y', strtotime($owner['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div>
            <span class="px-4 py-2 text-sm font-medium rounded-full
                <?= $owner['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                    ($owner['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                    ($owner['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) ?>">
                <?= ucfirst($owner['status']) ?>
            </span>
        </div>
    </div>

    <!-- KTP Photo -->
    <?php if (!empty($owner['ktp_photo'])): ?>
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-id-card mr-2"></i>Foto KTP
            </h3>
            <div class="max-w-md">
                <img src="<?= asset($owner['ktp_photo']) ?>" 
                     alt="KTP" 
                     class="w-full rounded-lg border border-gray-300 shadow-sm"
                     onclick="window.open(this.src, '_blank')">
                <p class="text-xs text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
        <div class="flex flex-wrap gap-3">
            
            <?php if ($owner['status'] === 'pending'): ?>
                <!-- Approve Button -->
                <form action="<?= url('/admin/owners/' . $owner['id'] . '/approve') ?>" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menyetujui owner ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Setujui
                    </button>
                </form>
                
                <!-- Reject Button -->
                <button onclick="showRejectModal()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
                
            <?php elseif ($owner['status'] === 'active'): ?>
                <!-- Suspend Button -->
                <button onclick="showSuspendModal()" class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    <i class="fas fa-ban mr-2"></i>Suspend
                </button>
                
            <?php elseif ($owner['status'] === 'suspended'): ?>
                <!-- Activate Button -->
                <form action="<?= url('/admin/owners/' . $owner['id'] . '/activate') ?>" method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin mengaktifkan kembali owner ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>Aktifkan Kembali
                    </button>
                </form>
            <?php endif; ?>
            
            <a href="<?= url('/admin/owners') ?>" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>
</div>

<!-- Owner's Kost -->
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-building mr-2"></i>Daftar Kost (<?= count($kosts) ?>)
    </h3>
    
    <?php if (empty($kosts)): ?>
        <p class="text-gray-500 text-center py-8">Owner belum memiliki kost</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($kosts as $kost): ?>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <h4 class="font-semibold text-gray-800 mb-2"><?= e($kost['name']) ?></h4>
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt mr-1"></i><?= e($kost['location']) ?>
                    </p>
                    <p class="text-lg font-bold text-blue-600 mb-2">
                        Rp <?= number_format($kost['price'], 0, ',', '.') ?>/bulan
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="px-2 py-1 text-xs rounded-full
                            <?= $kost['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                            <?= ucfirst($kost['status']) ?>
                        </span>
                        <a href="<?= url('/admin/kost/' . $kost['id']) ?>" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            Detail <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tolak Owner</h3>
        <form action="<?= url('/admin/owners/' . $owner['id'] . '/reject') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Alasan Penolakan</label>
                <textarea name="reason" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Tolak Owner
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Suspend Modal -->
<div id="suspendModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Suspend Owner</h3>
        <form action="<?= url('/admin/owners/' . $owner['id'] . '/suspend') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Alasan Suspend</label>
                <textarea name="reason" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          placeholder="Masukkan alasan suspend..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeSuspendModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                    Suspend Owner
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function showSuspendModal() {
    document.getElementById('suspendModal').classList.remove('hidden');
}

function closeSuspendModal() {
    document.getElementById('suspendModal').classList.add('hidden');
}
</script>
