<?php
/**
 * Review Section Component
 * Menampilkan review & rating untuk kost
 * 
 * Required variables:
 * - $kost - Data kost
 * - $reviewStats - Statistik review
 * - $reviews - List review
 * - $reviewPagination - Data pagination
 * 
 * Optional variables (untuk authenticated tenant):
 * - $canReview - Apakah bisa buat review
 * - $hasReviewed - Apakah sudah pernah review
 * - $userReview - Review dari user
 */

$reviewStats = $reviewStats ?? ['total_reviews' => 0, 'average_rating' => 0];
$reviews = $reviews ?? [];
$reviewPagination = $reviewPagination ?? ['current_page' => 1, 'total_pages' => 1];
$canReview = $canReview ?? false;
$hasReviewed = $hasReviewed ?? false;
$userReview = $userReview ?? null;
?>

<!-- Reviews & Rating Section -->
<div class="bg-white rounded-lg shadow-md p-6" id="reviews-section">
    <h3 class="text-2xl font-bold text-gray-800 mb-6">
        <i class="fas fa-star text-yellow-400 mr-2"></i>
        Review & Rating
    </h3>

    <?php if ($reviewStats['total_reviews'] > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Average Rating -->
            <div class="text-center md:col-span-1">
                <div class="text-5xl font-bold text-gray-800 mb-2">
                    <?= number_format($reviewStats['average_rating'], 1) ?>
                </div>
                <div class="flex justify-center mb-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= floor($reviewStats['average_rating'])): ?>
                            <i class="fas fa-star text-yellow-400 text-2xl"></i>
                        <?php elseif ($i - 0.5 <= $reviewStats['average_rating']): ?>
                            <i class="fas fa-star-half-alt text-yellow-400 text-2xl"></i>
                        <?php else: ?>
                            <i class="far fa-star text-yellow-400 text-2xl"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-600">
                    Dari <?= $reviewStats['total_reviews'] ?> review
                </p>
            </div>

            <!-- Rating Distribution -->
            <div class="md:col-span-2">
                <?php 
                $ratingData = [
                    5 => $reviewStats['five_star'] ?? 0,
                    4 => $reviewStats['four_star'] ?? 0,
                    3 => $reviewStats['three_star'] ?? 0,
                    2 => $reviewStats['two_star'] ?? 0,
                    1 => $reviewStats['one_star'] ?? 0
                ];
                ?>
                <?php foreach ($ratingData as $star => $count): ?>
                    <div class="flex items-center mb-2">
                        <div class="w-16 text-sm text-gray-600">
                            <?= $star ?> <i class="fas fa-star text-yellow-400 text-xs"></i>
                        </div>
                        <div class="flex-1 mx-3">
                            <div class="bg-gray-200 rounded-full h-3">
                                <?php 
                                $percentage = $reviewStats['total_reviews'] > 0 
                                    ? ($count / $reviewStats['total_reviews']) * 100 
                                    : 0;
                                ?>
                                <div class="bg-yellow-400 h-3 rounded-full" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>
                        <div class="w-12 text-sm text-gray-600 text-right">
                            <?= $count ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-8 mb-6">
            <i class="fas fa-star text-gray-300 text-5xl mb-3"></i>
            <p class="text-gray-500">Belum ada review untuk kost ini</p>
        </div>
    <?php endif; ?>

    <!-- Write Review Button (for authenticated tenant only) -->
    <?php if (isset($canReview) && isset($hasReviewed)): ?>
        <?php if ($canReview && !$hasReviewed): ?>
            <div class="mb-6">
                <a href="<?= url('/tenant/review/create/' . $kost['id']) ?>" 
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-pen mr-2"></i>
                    Tulis Review
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- User's Own Review (if exists) -->
    <?php if (!empty($userReview)): ?>
        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-5 mb-6">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h4 class="font-semibold text-gray-800 mb-1">Review Anda</h4>
                    <div class="flex items-center">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="<?= $i <= $userReview['rating'] ? 'fas' : 'far' ?> fa-star text-yellow-400"></i>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-600">
                            <?= date('d M Y', strtotime($userReview['created_at'])) ?>
                        </span>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="<?= url('/tenant/review/edit/' . $userReview['id']) ?>" 
                       class="text-blue-600 hover:text-blue-700 text-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="<?= url('/tenant/review/delete/' . $userReview['id']) ?>" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus review ini?');">
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
            <?php if (!empty($userReview['review_text'])): ?>
                <p class="text-gray-700"><?= nl2br(e($userReview['review_text'])) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Reviews List -->
    <?php if (!empty($reviews)): ?>
        <div class="space-y-4">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">
                Review dari Penyewa Lain
            </h4>
            
            <?php foreach ($reviews as $review): ?>
                <?php 
                // Skip user's own review in the list (already shown above)
                if (!empty($userReview) && $review['id'] == $userReview['id']) continue;
                ?>
                <div class="border-b border-gray-200 pb-4 last:border-0">
                    <div class="flex items-start space-x-4">
                        <!-- Reviewer Avatar -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($review['profile_photo'])): ?>
                                <img src="<?= url('/uploads/profile/' . $review['profile_photo']) ?>" 
                                     alt="<?= e($review['tenant_name']) ?>" 
                                     class="w-12 h-12 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Review Content -->
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h5 class="font-semibold text-gray-800">
                                        <?= e($review['tenant_name']) ?>
                                    </h5>
                                    <div class="flex items-center">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star text-yellow-400 text-sm"></i>
                                        <?php endfor; ?>
                                        <span class="ml-2 text-sm text-gray-500">
                                            <?= date('d M Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($review['review_text'])): ?>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    <?= nl2br(e($review['review_text'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($reviewPagination['total_pages'] > 1): ?>
            <div class="flex justify-center items-center space-x-2 mt-6 pt-6 border-t">
                <?php 
                $currentPage = $reviewPagination['current_page'];
                $totalPages = $reviewPagination['total_pages'];
                $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
                
                // Preserve other query parameters
                parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
                unset($params['review_page']);
                $queryString = !empty($params) ? '&' . http_build_query($params) : '';
                ?>
                
                <!-- Previous Button -->
                <?php if ($currentPage > 1): ?>
                    <a href="<?= $baseUrl ?>?review_page=<?= $currentPage - 1 ?><?= $queryString ?>#reviews-section" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= $baseUrl ?>?review_page=<?= $i ?><?= $queryString ?>#reviews-section" 
                       class="px-4 py-2 rounded-lg transition <?= $i == $currentPage ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Button -->
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= $baseUrl ?>?review_page=<?= $currentPage + 1 ?><?= $queryString ?>#reviews-section" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php elseif ($reviewStats['total_reviews'] == 0): ?>
        <p class="text-center text-gray-500 py-8">
            Jadilah yang pertama memberikan review!
        </p>
    <?php endif; ?>
</div>
