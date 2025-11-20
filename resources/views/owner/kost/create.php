<?php 
$pageTitle = $pageTitle ?? 'Tambah Kost Baru';
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?= url('/owner/kost') ?>" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Kost
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kost Baru</h2>

        <form action="<?= url('/owner/kost/store') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Basic Info -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Dasar</h3>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Kost *</label>
                    <input type="text" name="name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Kost Putra Mandiri">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Lokasi/Kota *</label>
                        <input type="text" name="location" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: Medan">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Harga per Bulan (Rp) *</label>
                        <input type="number" name="price" required min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="500000">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap *</label>
                    <textarea name="address" required rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Alamat lengkap kost"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Tipe Gender *</label>
                    <select name="gender_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="campur">Campur</option>
                        <option value="putra">Khusus Putra</option>
                        <option value="putri">Khusus Putri</option>
                    </select>
                </div>
            </div>

            <!-- Facilities -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Fasilitas Umum</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="WiFi" class="rounded">
                        <span class="text-gray-700">WiFi</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Parkir Motor" class="rounded">
                        <span class="text-gray-700">Parkir Motor</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Parkir Mobil" class="rounded">
                        <span class="text-gray-700">Parkir Mobil</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Dapur Bersama" class="rounded">
                        <span class="text-gray-700">Dapur Bersama</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Ruang Tamu" class="rounded">
                        <span class="text-gray-700">Ruang Tamu</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Laundry" class="rounded">
                        <span class="text-gray-700">Laundry</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="CCTV" class="rounded">
                        <span class="text-gray-700">CCTV</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Keamanan 24 Jam" class="rounded">
                        <span class="text-gray-700">Keamanan 24 Jam</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="facilities[]" value="Dekat Kampus" class="rounded">
                        <span class="text-gray-700">Dekat Kampus</span>
                    </label>
                </div>
            </div>

            <!-- Description with CKEditor -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Deskripsi Detail</h3>
                <textarea name="description" id="description" rows="10"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Tulis deskripsi lengkap tentang kost Anda..."></textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex space-x-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i>Simpan Kost
                </button>
                <a href="<?= url('/owner/kost') ?>"
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
