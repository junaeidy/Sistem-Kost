<?php
/**
 * Pagination Component
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param string $baseUrl Base URL for pagination links
 * @param int $total Total number of items
 */

$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$baseUrl = $baseUrl ?? '';
$total = $total ?? 0;

// Don't show pagination if only 1 page
if ($totalPages <= 1) {
    return;
}

// Calculate range of pages to show
$maxPagesToShow = 5;
$startPage = max(1, $currentPage - floor($maxPagesToShow / 2));
$endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

// Adjust start page if we're near the end
if ($endPage - $startPage < $maxPagesToShow - 1) {
    $startPage = max(1, $endPage - $maxPagesToShow + 1);
}

// Build query string
$queryParams = $_GET;
unset($queryParams['page']); // Remove existing page parameter
$queryString = http_build_query($queryParams);
$separator = !empty($queryString) ? '&' : '?';
?>

<div class="bg-white rounded-lg shadow-md p-4 md:p-6 mt-4 md:mt-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Info -->
        <div class="text-sm text-gray-600 text-center sm:text-left">
            Menampilkan halaman <span class="font-semibold"><?= $currentPage ?></span> dari 
            <span class="font-semibold"><?= $totalPages ?></span> 
            (Total: <span class="font-semibold"><?= $total ?></span> data)
        </div>
        
        <!-- Pagination Links -->
        <nav class="flex justify-center">
            <ul class="flex items-center gap-1 md:gap-2">
                <!-- Previous Button -->
                <?php if ($currentPage > 1): ?>
                    <li>
                        <a href="<?= $baseUrl . ($queryString ? '?' . $queryString . '&page=' : '?page=') . ($currentPage - 1) ?>" 
                           class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm md:text-base">
                            <i class="fas fa-chevron-left"></i>
                            <span class="hidden sm:inline ml-1">Prev</span>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm md:text-base">
                            <i class="fas fa-chevron-left"></i>
                            <span class="hidden sm:inline ml-1">Prev</span>
                        </span>
                    </li>
                <?php endif; ?>
                
                <!-- First Page -->
                <?php if ($startPage > 1): ?>
                    <li>
                        <a href="<?= $baseUrl . ($queryString ? '?' . $queryString . '&page=1' : '?page=1') ?>" 
                           class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm md:text-base">
                            1
                        </a>
                    </li>
                    <?php if ($startPage > 2): ?>
                        <li><span class="px-1 md:px-2 text-gray-500">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li>
                        <?php if ($i === $currentPage): ?>
                            <span class="px-2 md:px-3 py-1 md:py-2 bg-blue-600 text-white rounded-lg font-semibold text-sm md:text-base">
                                <?= $i ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= $baseUrl . ($queryString ? '?' . $queryString . '&page=' : '?page=') . $i ?>" 
                               class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm md:text-base">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endfor; ?>
                
                <!-- Last Page -->
                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <li><span class="px-1 md:px-2 text-gray-500">...</span></li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= $baseUrl . ($queryString ? '?' . $queryString . '&page=' : '?page=') . $totalPages ?>" 
                           class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm md:text-base">
                            <?= $totalPages ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <!-- Next Button -->
                <?php if ($currentPage < $totalPages): ?>
                    <li>
                        <a href="<?= $baseUrl . ($queryString ? '?' . $queryString . '&page=' : '?page=') . ($currentPage + 1) ?>" 
                           class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm md:text-base">
                            <span class="hidden sm:inline mr-1">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <span class="px-2 md:px-3 py-1 md:py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed text-sm md:text-base">
                            <span class="hidden sm:inline mr-1">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>
