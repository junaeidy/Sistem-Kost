<!-- Kelola Foto Kost -->
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <a href="<?= url('/owner/kost') ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kost
            </a>

            <!-- Kost Info Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($kost['name'] ?? '') ?></h5>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($kost['address'] ?? '') ?>
                    </p>
                </div>
            </div>

            <!-- Upload Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-upload"></i> Upload Foto</h5>
                </div>
                <div class="card-body">
                    <form action="<?= url('/owner/kost/photos/' . $kost['id'] . '/upload') ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="photos" class="form-label">Pilih Foto (Maksimal 10 foto, maks 2MB per foto)</label>
                            <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required>
                            <small class="text-muted">Format: JPG, PNG, GIF, WEBP</small>
                        </div>
                        
                        <div id="preview" class="row mb-3"></div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Foto
                        </button>
                    </form>
                </div>
            </div>

            <!-- Existing Photos -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-images"></i> Foto Kost (<?= count($photos) ?>/10)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($photos)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada foto. Silakan upload foto kost Anda.
                        </div>
                    <?php else: ?>
                        <div class="row" id="photo-list">
                            <?php foreach ($photos as $photo): ?>
                                <div class="col-md-3 col-sm-6 mb-4" data-photo-id="<?= $photo['id'] ?>">
                                    <div class="card position-relative">
                                        <?php if ($photo['is_primary']): ?>
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-star"></i> Foto Utama
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <img src="<?= url($photo['photo_url']) ?>" class="card-img-top" alt="Foto Kost" style="height: 200px; object-fit: cover;">
                                        
                                        <div class="card-body p-2">
                                            <div class="d-flex justify-content-between">
                                                <?php if (!$photo['is_primary']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success btn-set-primary" data-photo-id="<?= $photo['id'] ?>">
                                                        <i class="fas fa-star"></i> Set Utama
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-success small">
                                                        <i class="fas fa-check"></i> Foto Utama
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-photo" data-photo-id="<?= $photo['id'] ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Tips:</strong> Drag and drop untuk mengubah urutan foto (coming soon). 
                            Foto utama akan ditampilkan sebagai thumbnail di listing.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Preview uploaded images
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    if (files.length > 10) {
        alert('Maksimal 10 foto');
        e.target.value = '';
        return;
    }
    
    files.forEach((file, index) => {
        if (file.size > 2097152) {
            alert(`File ${file.name} terlalu besar (maks 2MB)`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(event) {
            const col = document.createElement('div');
            col.className = 'col-md-2 col-sm-4 col-6 mb-2';
            col.innerHTML = `
                <img src="${event.target.result}" class="img-thumbnail" style="height: 100px; width: 100%; object-fit: cover;">
                <small class="d-block text-truncate">${file.name}</small>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});

// Set primary photo
document.querySelectorAll('.btn-set-primary').forEach(btn => {
    btn.addEventListener('click', function() {
        const photoId = this.dataset.photoId;
        
        if (!confirm('Set foto ini sebagai foto utama?')) return;
        
        fetch(`<?= url('/owner/kost/photos/set-primary/') ?>${photoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
});

// Delete photo
document.querySelectorAll('.btn-delete-photo').forEach(btn => {
    btn.addEventListener('click', function() {
        const photoId = this.dataset.photoId;
        
        if (!confirm('Hapus foto ini?')) return;
        
        fetch(`<?= url('/owner/kost/photos/delete/') ?>${photoId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
});
</script>
