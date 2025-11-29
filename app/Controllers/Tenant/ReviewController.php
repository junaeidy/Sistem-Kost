<?php

namespace App\Controllers\Tenant;

use Core\Controller;
use Core\Session;
use App\Models\TenantModel;
use App\Models\ReviewModel;
use App\Models\KostModel;

/**
 * Tenant Review Controller
 * Handles review creation and management
 */
class ReviewController extends Controller
{
    private $tenantModel;
    private $reviewModel;
    private $kostModel;

    public function __construct()
    {
        $this->tenantModel = new TenantModel();
        $this->reviewModel = new ReviewModel();
        $this->kostModel = new KostModel();
    }

    /**
     * Show create review form
     * 
     * @param int $kostId
     */
    public function create($kostId)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get kost detail
        $kost = $this->kostModel->find($kostId);
        
        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/tenant/search'));
            return;
        }

        // Check if tenant can review (has completed booking)
        if (!$this->reviewModel->canTenantReview($tenantId, $kostId)) {
            $this->flash('error', 'Anda harus melakukan booking minimal 1 kali untuk memberikan review.');
            $this->redirect(url('/tenant/search/' . $kostId));
            return;
        }

        // Check if already reviewed
        if ($this->reviewModel->hasReviewed($tenantId, $kostId)) {
            $this->flash('error', 'Anda sudah memberikan review untuk kost ini.');
            $this->redirect(url('/tenant/search/' . $kostId));
            return;
        }

        $this->view('tenant/review/create', [
            'title' => 'Buat Review - ' . $kost['name'],
            'pageTitle' => 'Buat Review',
            'tenant' => $tenant,
            'kost' => $kost
        ], 'layouts/dashboard');
    }

    /**
     * Store new review
     * 
     * @param int $kostId
     */
    public function store($kostId)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Validate CSRF token
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            $this->flash('error', 'Token keamanan tidak valid.');
            $this->redirect(url('/tenant/review/create/' . $kostId));
            return;
        }

        // Validate input
        $errors = [];

        $rating = $_POST['rating'] ?? '';
        $reviewText = trim($_POST['review_text'] ?? '');

        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            $errors[] = 'Rating harus dipilih (1-5 bintang).';
        }

        if (!empty($reviewText) && strlen($reviewText) < 10) {
            $errors[] = 'Review minimal 10 karakter.';
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('old', $_POST);
            $this->redirect(url('/tenant/review/create/' . $kostId));
            return;
        }

        // Create review
        $reviewId = $this->reviewModel->createReview([
            'kost_id' => $kostId,
            'tenant_id' => $tenantId,
            'rating' => (int) $rating,
            'review_text' => !empty($reviewText) ? $reviewText : null
        ]);

        if ($reviewId) {
            $this->flash('success', 'Review berhasil ditambahkan!');
            $this->redirect(url('/tenant/search/' . $kostId));
        } else {
            $this->flash('error', 'Gagal menambahkan review. Pastikan Anda sudah pernah booking di kost ini.');
            $this->redirect(url('/tenant/review/create/' . $kostId));
        }
    }

    /**
     * Show edit review form
     * 
     * @param int $id Review ID
     */
    public function edit($id)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get review
        $review = $this->reviewModel->findById($id);
        
        if (!$review) {
            $this->flash('error', 'Review tidak ditemukan.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Check ownership
        if ($review['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Get kost detail
        $kost = $this->kostModel->find($review['kost_id']);

        $this->view('tenant/review/edit', [
            'title' => 'Edit Review - ' . $kost['name'],
            'pageTitle' => 'Edit Review',
            'tenant' => $tenant,
            'review' => $review,
            'kost' => $kost
        ], 'layouts/dashboard');
    }

    /**
     * Update review
     * 
     * @param int $id Review ID
     */
    public function update($id)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get review
        $review = $this->reviewModel->findById($id);
        
        if (!$review) {
            $this->flash('error', 'Review tidak ditemukan.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Check ownership
        if ($review['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Validate CSRF token
        if (!validate_csrf($_POST['csrf_token'] ?? '')) {
            $this->flash('error', 'Token keamanan tidak valid.');
            $this->redirect(url('/tenant/review/edit/' . $id));
            return;
        }

        // Validate input
        $errors = [];

        $rating = $_POST['rating'] ?? '';
        $reviewText = trim($_POST['review_text'] ?? '');

        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            $errors[] = 'Rating harus dipilih (1-5 bintang).';
        }

        if (!empty($reviewText) && strlen($reviewText) < 10) {
            $errors[] = 'Review minimal 10 karakter.';
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('old', $_POST);
            $this->redirect(url('/tenant/review/edit/' . $id));
            return;
        }

        // Update review
        $success = $this->reviewModel->updateReview($id, [
            'rating' => (int) $rating,
            'review_text' => !empty($reviewText) ? $reviewText : null
        ]);

        if ($success) {
            $this->flash('success', 'Review berhasil diperbarui!');
            $this->redirect(url('/tenant/search/' . $review['kost_id']));
        } else {
            $this->flash('error', 'Gagal memperbarui review.');
            $this->redirect(url('/tenant/review/edit/' . $id));
        }
    }

    /**
     * Delete review
     * 
     * @param int $id Review ID
     */
    public function delete($id)
    {
        $userId = Session::get('user_id');
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        $tenantId = $tenant['id'];

        // Get review
        $review = $this->reviewModel->findById($id);
        
        if (!$review) {
            $this->flash('error', 'Review tidak ditemukan.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Check ownership
        if ($review['tenant_id'] != $tenantId) {
            $this->flash('error', 'Anda tidak memiliki akses untuk menghapus review ini.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Delete review
        if ($this->reviewModel->deleteReview($id)) {
            $this->flash('success', 'Review berhasil dihapus.');
        } else {
            $this->flash('error', 'Gagal menghapus review.');
        }

        $this->redirect(url('/tenant/search/' . $review['kost_id']));
    }
}
