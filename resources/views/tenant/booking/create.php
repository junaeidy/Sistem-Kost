<?php 
$pageTitle = $pageTitle ?? 'Booking Kamar';
$kamar = $kamar ?? [];
?>

<!-- Back Button -->
<div class="mb-4">
    <a href="<?= url('/tenant/search/' . ($kamar['kost_id'] ?? '')) ?>" class="text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail Kost
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Booking Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                Form Booking
            </h2>

            <form method="POST" action="<?= url('/tenant/bookings') ?>" id="bookingForm">
                <input type="hidden" name="kamar_id" value="<?= $kamar['id'] ?? '' ?>">

                <!-- Start Date -->
                <div class="mb-6">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i>
                        Tanggal Mulai Sewa <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           required
                           min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Pilih tanggal mulai Anda ingin menyewa</p>
                </div>

                <!-- Duration -->
                <div class="mb-6">
                    <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-1"></i>
                        Durasi Sewa (Bulan) <span class="text-red-500">*</span>
                    </label>
                    <select id="duration_months" 
                            name="duration_months" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Durasi --</option>
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6" selected>6 Bulan</option>
                        <option value="12">12 Bulan</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Minimal 1 bulan, maksimal 12 bulan</p>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Catatan (Opsional)
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              placeholder="Tambahkan catatan atau permintaan khusus..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>

                <!-- Calculated End Date (Display Only) -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">Tanggal Selesai (Estimasi)</p>
                    <p id="end_date_display" class="text-lg font-semibold text-gray-800">-</p>
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" 
                               name="agree_terms" 
                               id="agree_terms"
                               required
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Saya setuju dengan <a href="#" class="text-blue-600 hover:text-blue-700">syarat dan ketentuan</a> 
                            yang berlaku dan informasi yang saya berikan adalah benar.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-check mr-2"></i>
                        Buat Booking
                    </button>
                    <a href="<?= url('/tenant/search/' . ($kamar['kost_id'] ?? '')) ?>" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Room & Price Summary -->
    <div class="space-y-6">
        
        <!-- Room Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kamar</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Nama Kost</p>
                    <p class="font-semibold text-gray-800"><?= e($kamar['kost_name'] ?? '') ?></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nama Kamar</p>
                    <p class="font-semibold text-gray-800"><?= e($kamar['name'] ?? '') ?></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Lokasi</p>
                    <p class="text-sm text-gray-700"><?= e($kamar['kost_address'] ?? '') ?></p>
                </div>

                <?php if (!empty($kamar['kost_location'])): ?>
                <div>
                    <p class="text-sm text-gray-500">Area</p>
                    <p class="text-sm text-gray-700"><?= e($kamar['kost_location']) ?></p>
                </div>
                <?php endif; ?>

                <div class="pt-3 border-t">
                    <p class="text-sm text-gray-500">Harga per Bulan</p>
                    <p class="text-2xl font-bold text-blue-600">
                        Rp <?= number_format($kamar['price'] ?? 0, 0, ',', '.') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Price Calculation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Biaya</h3>
            
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Harga per Bulan</span>
                    <span class="font-semibold">Rp <span id="price_per_month"><?= number_format($kamar['price'] ?? 0, 0, ',', '.') ?></span></span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Durasi</span>
                    <span class="font-semibold"><span id="duration_display">-</span></span>
                </div>

                <div class="border-t pt-2 mt-2">
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-800">Total Biaya</span>
                        <span class="text-xl font-bold text-blue-600">
                            Rp <span id="total_price">0</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-xs text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Pembayaran dilakukan setelah booking dikonfirmasi
                </p>
            </div>
        </div>

        <!-- Contact Owner -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Kontak Pemilik</h3>
            
            <div class="space-y-2">
                <div>
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="font-semibold text-gray-800"><?= e($kamar['owner_name'] ?? '') ?></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">No. Telepon</p>
                    <p class="font-semibold text-gray-800"><?= e($kamar['owner_phone'] ?? '') ?></p>
                </div>
            </div>

            <a href="https://wa.me/62<?= ltrim($kamar['owner_phone'] ?? '', '0') ?>" 
               target="_blank"
               class="block w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-center transition">
                <i class="fab fa-whatsapp mr-2"></i>
                Chat WhatsApp
            </a>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const durationInput = document.getElementById('duration_months');
    const endDateDisplay = document.getElementById('end_date_display');
    const durationDisplay = document.getElementById('duration_display');
    const totalPriceDisplay = document.getElementById('total_price');
    const pricePerMonth = <?= $kamar['price'] ?? 0 ?>;

    function updateCalculations() {
        const startDate = startDateInput.value;
        const duration = parseInt(durationInput.value) || 0;

        // Calculate end date
        if (startDate && duration > 0) {
            const start = new Date(startDate);
            const end = new Date(start);
            end.setMonth(end.getMonth() + duration);
            
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            endDateDisplay.textContent = end.toLocaleDateString('id-ID', options);
        } else {
            endDateDisplay.textContent = '-';
        }

        // Update duration display
        if (duration > 0) {
            durationDisplay.textContent = duration + ' Bulan';
        } else {
            durationDisplay.textContent = '-';
        }

        // Calculate total price
        const totalPrice = pricePerMonth * duration;
        totalPriceDisplay.textContent = totalPrice.toLocaleString('id-ID');
    }

    startDateInput.addEventListener('change', updateCalculations);
    durationInput.addEventListener('change', updateCalculations);

    // Initial calculation
    updateCalculations();
});
</script>
