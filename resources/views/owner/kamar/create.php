<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?= url('/owner/kost/' . $kost['id']) ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Detail Kost
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Tambah Kamar Baru</h2>
            <p class="text-gray-600 mt-1">Kost: <span class="font-semibold"><?= e($kost['name']) ?></span></p>
        </div>

        <form action="<?= url('/owner/kost/' . $kost['id'] . '/kamar/store') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Nomor Kamar -->
            <div class="mb-4">
                <label for="nomor_kamar" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Kamar <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nomor_kamar" 
                       name="nomor_kamar" 
                       required
                       placeholder="Contoh: 101, A1, dll"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Nomor kamar harus unik dalam satu kost</p>
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga per Bulan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                    <input type="number" 
                           id="harga" 
                           name="harga" 
                           required
                           min="0"
                           step="1000"
                           placeholder="0"
                           class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Luas -->
            <div class="mb-4">
                <label for="luas" class="block text-sm font-medium text-gray-700 mb-2">
                    Luas Kamar (m²)
                </label>
                <input type="number" 
                       id="luas" 
                       name="luas" 
                       min="0"
                       step="0.1"
                       placeholder="Contoh: 12"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select id="status" 
                        name="status" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available">Tersedia</option>
                    <option value="occupied">Terisi</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <!-- Fasilitas Kamar -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Fasilitas Kamar
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <?php 
                    $facilities = [
                        'Kasur', 'Lemari', 'Meja Belajar', 'Kursi', 
                        'AC', 'Kipas Angin', 'Kamar Mandi Dalam',
                        'Jendela', 'Ventilasi', 'Stop Kontak'
                    ];
                    foreach ($facilities as $facility): 
                    ?>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" 
                                   name="facilities[]" 
                                   value="<?= e($facility) ?>"
                                   class="rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm text-gray-700"><?= e($facility) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Tambahan
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          placeholder="Informasi tambahan tentang kamar ini..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="<?= url('/owner/kost/' . $kost['id']) ?>" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>
