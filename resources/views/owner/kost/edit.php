<?php 
$pageTitle = $pageTitle ?? 'Edit Kost';
$kost = $kost ?? [];
$facilities = !empty($kost['facilities']) ? json_decode($kost['facilities'], true) : [];
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?= url('/owner/kost/' . $kost['id']) ?>" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail Kost
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Kost</h2>

        <form action="<?= url('/owner/kost/' . $kost['id'] . '/update') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Basic Info -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Dasar</h3>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Kost *</label>
                    <input type="text" name="name" required value="<?= e($kost['name']) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Lokasi/Kota *</label>
                        <input type="text" name="location" required value="<?= e($kost['location']) ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Harga per Bulan (Rp) *</label>
                        <input type="number" name="price" required min="0" value="<?= $kost['price'] ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap *</label>
                    <textarea name="address" required rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= e($kost['address']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tipe Gender *</label>
                    <select name="gender_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="campur" <?= $kost['gender_type'] === 'campur' ? 'selected' : '' ?>>Campur</option>
                        <option value="putra" <?= $kost['gender_type'] === 'putra' ? 'selected' : '' ?>>Khusus Putra</option>
                        <option value="putri" <?= $kost['gender_type'] === 'putri' ? 'selected' : '' ?>>Khusus Putri</option>
                    </select>
                </div>
            </div>

            <!-- Facilities -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Fasilitas Umum</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <?php 
                    $availableFacilities = ['WiFi', 'Parkir Motor', 'Parkir Mobil', 'Dapur Bersama', 'Ruang Tamu', 'Laundry', 'CCTV', 'Keamanan 24 Jam', 'Dekat Kampus'];
                    foreach ($availableFacilities as $facility): 
                    ?>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="facilities[]" value="<?= $facility ?>" 
                                   <?= in_array($facility, $facilities) ? 'checked' : '' ?> class="rounded">
                            <span class="text-gray-700"><?= $facility ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Description with CKEditor -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Deskripsi Detail</h3>
                <textarea name="description" id="description" rows="10"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= $kost['description'] ?? '' ?></textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Update Kost
                </button>
                <a href="<?= url('/owner/kost/' . $kost['id']) ?>"
                   class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });
</script>
