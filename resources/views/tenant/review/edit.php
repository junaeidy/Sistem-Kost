<?php 
$pageTitle = $pageTitle ?? 'Edit Review';
$review = $review ?? [];
$kost = $kost ?? [];
$old = $_SESSION['old'] ?? [];
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);

// Use old values or existing review values
$rating = $old['rating'] ?? $review['rating'] ?? '';
$reviewText = $old['review_text'] ?? $review['review_text'] ?? '';
?>

<!-- Back Button -->
<div class="mb-4">
    <a href="<?= url('/tenant/search/' . $kost['id']) ?>" class="text-blue-600 hover:text-blue-700">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Detail Kost
    </a>
</div>

<!-- Page Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
    <p class="text-gray-600 mt-1">Perbarui penilaian Anda untuk <?= htmlspecialchars($kost['name']) ?></p>
</div>

<!-- Error Messages -->
<?php if (!empty($errors)): ?>
<div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <i class="fas fa-exclamation-circle text-red-600 mt-1 mr-3"></i>
        <div class="flex-1">
            <h3 class="font-semibold mb-2">Terjadi Kesalahan:</h3>
            <ul class="list-disc list-inside space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Review Form -->
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Kost Info -->
    <div class="mb-6 pb-6 border-b">
        <div class="flex items-start space-x-4">
            <?php if (!empty($kost['primary_photo'])): ?>
                <img src="<?= url('/uploads/kost/' . htmlspecialchars($kost['primary_photo'])) ?>" 
                     alt="<?= htmlspecialchars($kost['name']) ?>" 
                     class="w-24 h-24 object-cover rounded-lg">
            <?php else: ?>
                <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                    <i class="fas fa-home text-gray-400 text-3xl"></i>
                </div>
            <?php endif; ?>
            
            <div>
                <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($kost['name']) ?></h2>
                <p class="text-gray-600 text-sm mt-1">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    <?= htmlspecialchars($kost['address']) ?>
                </p>
                <p class="text-gray-500 text-xs mt-2">
                    Review dibuat: <?= date('d F Y', strtotime($review['created_at'])) ?>
                </p>
            </div>
        </div>
    </div>

    <form action="<?= url('/tenant/review/update/' . $review['id']) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

        <!-- Rating -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-3">
                Rating <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center space-x-2">
                <div class="rating-stars flex space-x-1" id="rating-input">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <label class="cursor-pointer">
                            <input type="radio" 
                                   name="rating" 
                                   value="<?= $i ?>" 
                                   class="hidden rating-radio"
                                   <?= ($rating == $i) ? 'checked' : '' ?>>
                            <i class="far fa-star text-4xl text-gray-300 hover:text-yellow-400 transition-colors rating-star" data-value="<?= $i ?>"></i>
                        </label>
                    <?php endfor; ?>
                </div>
                <span class="text-gray-600 ml-3" id="rating-text">Pilih rating</span>
            </div>
            <p class="text-gray-500 text-sm mt-2">Klik bintang untuk memberikan rating</p>
        </div>

        <!-- Review Text -->
        <div class="mb-6">
            <label for="review_text" class="block text-gray-700 font-semibold mb-2">
                Review (Opsional)
            </label>
            <textarea 
                id="review_text" 
                name="review_text" 
                rows="5" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                placeholder="Ceritakan pengalaman Anda tinggal di kost ini..."><?= htmlspecialchars($reviewText) ?></textarea>
            <p class="text-gray-500 text-sm mt-2">Minimal 10 karakter jika diisi</p>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="<?= url('/tenant/search/' . $kost['id']) ?>" 
               class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<!-- Rating Stars Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('rating-input');
    const ratingStars = ratingContainer.querySelectorAll('.rating-star');
    const ratingRadios = ratingContainer.querySelectorAll('.rating-radio');
    const ratingText = document.getElementById('rating-text');
    
    const ratingLabels = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };

    // Initialize from current value
    ratingRadios.forEach((radio, index) => {
        if (radio.checked) {
            updateStars(index + 1);
        }
    });

    // Hover effect
    ratingStars.forEach((star, index) => {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });
    });

    ratingContainer.addEventListener('mouseleave', function() {
        const checkedRadio = ratingContainer.querySelector('.rating-radio:checked');
        if (checkedRadio) {
            updateStars(parseInt(checkedRadio.value));
        } else {
            resetStars();
        }
    });

    // Click to select
    ratingStars.forEach((star, index) => {
        star.addEventListener('click', function() {
            ratingRadios[index].checked = true;
            updateStars(index + 1);
        });
    });

    function highlightStars(count) {
        ratingStars.forEach((star, index) => {
            if (index < count) {
                star.classList.remove('far', 'text-gray-300');
                star.classList.add('fas', 'text-yellow-400');
            } else {
                star.classList.remove('fas', 'text-yellow-400');
                star.classList.add('far', 'text-gray-300');
            }
        });
        ratingText.textContent = ratingLabels[count] || 'Pilih rating';
    }

    function updateStars(count) {
        highlightStars(count);
    }

    function resetStars() {
        ratingStars.forEach(star => {
            star.classList.remove('fas', 'text-yellow-400');
            star.classList.add('far', 'text-gray-300');
        });
        ratingText.textContent = 'Pilih rating';
    }
});
</script>

<style>
.rating-star {
    transition: all 0.2s ease;
}
.rating-star:hover {
    transform: scale(1.1);
}
</style>
