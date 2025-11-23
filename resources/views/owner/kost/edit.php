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

        <form action="<?= url('/owner/kost/' . $kost['id'] . '/update') ?>" method="POST" enctype="multipart/form-data">
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

            <!-- Photos Upload -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Foto Kost</h3>
                
                <!-- Existing Photos -->
                <?php 
                $photos = isset($photos) ? $photos : [];
                if (!empty($photos)): 
                ?>
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Foto yang sudah diupload:</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3" id="existingPhotos">
                            <?php foreach ($photos as $photo): ?>
                                <div class="relative group">
                                    <img src="<?= asset($photo['photo_url']) ?>" 
                                         alt="Kost Photo" 
                                         class="w-full h-32 object-cover rounded-lg border border-gray-300">
                                    <?php if ($photo['is_primary']): ?>
                                        <span class="absolute top-1 right-1 px-2 py-1 bg-yellow-500 text-white text-xs rounded">
                                            Primary
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Untuk mengelola foto yang ada, silakan gunakan halaman manajemen foto khusus atau hubungi admin.</p>
                    </div>
                <?php endif; ?>

                <!-- New Photos Upload -->
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Upload Foto Kost Tambahan</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 transition" 
                         id="dropZone">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-700 mb-1">Drag & drop foto di sini atau klik untuk browse</p>
                        <p class="text-sm text-gray-500">Format: JPG, JPEG, PNG (Max 5MB per file, max 10 foto)</p>
                        <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png" multiple
                               class="hidden" id="photoInput">
                    </div>

                    <!-- Photo Preview -->
                    <div id="photoPreview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    </div>
                </div>
            </div>

            <!-- Map Picker Component -->
            <?php include __DIR__ . '/../../components/map-picker.php'; ?>

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
    let selectedPhotos = [];

    // CKEditor initialization
    ClassicEditor
        .create(document.querySelector('#description'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });

    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const photoInput = document.getElementById('photoInput');

    if (dropZone && photoInput) {
        dropZone.addEventListener('click', () => photoInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            handleFiles(e.dataTransfer.files);
        });

        photoInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });
    }

    function handleFiles(files) {
        const maxFiles = 10;
        const maxSize = 5 * 1024 * 1024; // 5MB

        for (let file of files) {
            if (selectedPhotos.length >= maxFiles) {
                alert(`Maksimum ${maxFiles} foto yang diizinkan`);
                break;
            }

            if (file.size > maxSize) {
                alert(`File ${file.name} terlalu besar. Maksimum 5MB`);
                continue;
            }

            if (!file.type.startsWith('image/')) {
                alert(`File ${file.name} bukan gambar`);
                continue;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                selectedPhotos.push({
                    file: file,
                    preview: e.target.result,
                    id: Date.now() + Math.random()
                });
                renderPhotos();
            };
            reader.readAsDataURL(file);
        }

        // Reset input
        photoInput.value = '';
    }

    function renderPhotos() {
        const preview = document.getElementById('photoPreview');
        preview.innerHTML = '';

        if (selectedPhotos.length === 0) {
            return;
        }

        selectedPhotos.forEach((photo, index) => {
            const div = document.createElement('div');
            div.className = 'relative group';
            
            const img = document.createElement('img');
            img.src = photo.preview;
            img.alt = 'Preview';
            img.className = 'w-full h-40 object-cover rounded-lg border border-gray-300';
            
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'absolute top-2 right-2 bg-red-600 text-white rounded-full w-10 h-10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition hover:bg-red-700';
            btn.innerHTML = '<i class="fas fa-trash text-sm"></i>';
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                deletePhoto(photo.id);
            });
            
            div.appendChild(img);
            div.appendChild(btn);
            preview.appendChild(div);
        });

        // Update hidden input with file data
        updateFileInput();
    }

    function deletePhoto(id) {
        selectedPhotos = selectedPhotos.filter(p => p.id !== id);
        renderPhotos();
    }

    function updateFileInput() {
        // Create a new DataTransfer object to hold the files
        const dataTransfer = new DataTransfer();
        selectedPhotos.forEach(photo => {
            dataTransfer.items.add(photo.file);
        });
        photoInput.files = dataTransfer.files;
    }

    // Form submit handler to ensure files are included
    document.querySelector('form').addEventListener('submit', function(e) {
        if (selectedPhotos.length === 0) {
            // Still allow form submission without photos
            photoInput.files = new DataTransfer().files;
        }
    });
</script>
