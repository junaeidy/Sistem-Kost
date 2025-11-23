<?php

namespace App\Controllers\Owner;

use App\Models\KostModel;
use Core\Controller;

class KostPhotoController extends Controller
{
    private $kostModel;

    public function __construct()
    {
        parent::__construct();
        $this->kostModel = new KostModel();
        
        // Load upload helper
        require_once __DIR__ . '/../../../helpers/upload.php';
    }

    /**
     * Display photo management page for a kost
     */
    public function index($kostId)
    {
        // Verify ownership
        $kost = $this->kostModel->findByIdAndOwner($kostId, $_SESSION['owner_id']);
        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan');
            return redirect('/owner/kost');
        }

        // Get existing photos
        $photos = $this->kostModel->getPhotos($kostId);

        $data = [
            'title' => 'Kelola Foto Kost',
            'kost' => $kost,
            'photos' => $photos
        ];

        $this->view('owner/kost/photos', $data);
    }

    /**
     * Upload new photos
     */
    public function upload($kostId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect("/owner/kost/photos/{$kostId}");
        }

        // Verify ownership
        $kost = $this->kostModel->findByIdAndOwner($kostId, $_SESSION['owner_id']);
        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan');
            return redirect('/owner/kost');
        }

        // Check if files were uploaded
        if (!isset($_FILES['photos']) || empty($_FILES['photos']['name'][0])) {
            $this->flash('error', 'Tidak ada file yang diupload');
            return redirect("/owner/kost/photos/{$kostId}");
        }

        // Check current photo count
        $currentPhotos = $this->kostModel->getPhotos($kostId);
        $currentCount = count($currentPhotos);
        $maxPhotos = 10;

        if ($currentCount >= $maxPhotos) {
            $this->flash('error', "Maksimal {$maxPhotos} foto per kost");
            return redirect("/owner/kost/photos/{$kostId}");
        }

        // Upload photos
        $allowedTypes = getAllowedImageTypes();
        $maxSize = 2048; // 2MB
        
        $result = uploadMultiple($_FILES['photos'], 'uploads/kost', $allowedTypes, $maxSize);

        if (empty($result['uploaded'])) {
            $errorMsg = 'Gagal mengupload foto';
            if (!empty($result['failed'])) {
                $errorMsg .= ': ' . $result['failed'][0]['message'];
            }
            $this->flash('error', $errorMsg);
            return redirect("/owner/kost/photos/{$kostId}");
        }

        // Save to database
        $uploaded = 0;
        $nextOrder = $currentCount;
        
        foreach ($result['uploaded'] as $filePath) {
            // Check if we haven't exceeded the limit
            if ($currentCount + $uploaded >= $maxPhotos) {
                // Delete excess files
                deleteFile($filePath);
                continue;
            }

            // Set first photo as primary if no primary exists
            $isPrimary = ($currentCount == 0 && $uploaded == 0);

            $photoData = [
                'kost_id' => $kostId,
                'photo_url' => $filePath,
                'is_primary' => $isPrimary,
                'display_order' => $nextOrder++
            ];

            if ($this->kostModel->addPhoto($photoData)) {
                $uploaded++;
            } else {
                // Delete file if database insert failed
                deleteFile($filePath);
            }
        }

        if ($uploaded > 0) {
            $this->flash('success', "{$uploaded} foto berhasil diupload");
        }

        if (!empty($result['failed'])) {
            $failedCount = count($result['failed']);
            $this->flash('warning', "{$failedCount} foto gagal diupload");
        }

        return redirect("/owner/kost/photos/{$kostId}");
    }

    /**
     * Set a photo as primary
     */
    public function setPrimary($photoId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request']);
        }

        // Get photo
        $photo = $this->kostModel->getPhotoById($photoId);
        if (!$photo) {
            return $this->json(['success' => false, 'message' => 'Foto tidak ditemukan']);
        }

        // Verify ownership
        $kost = $this->kostModel->findByIdAndOwner($photo['kost_id'], $_SESSION['owner_id']);
        if (!$kost) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        // Set as primary
        if ($this->kostModel->setPrimaryPhoto($photoId, $photo['kost_id'])) {
            return $this->json(['success' => true, 'message' => 'Foto utama berhasil diubah']);
        }

        return $this->json(['success' => false, 'message' => 'Gagal mengubah foto utama']);
    }

    /**
     * Delete a photo
     */
    public function delete($photoId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request']);
        }

        // Get photo
        $photo = $this->kostModel->getPhotoById($photoId);
        if (!$photo) {
            return $this->json(['success' => false, 'message' => 'Foto tidak ditemukan']);
        }

        // Verify ownership
        $kost = $this->kostModel->findByIdAndOwner($photo['kost_id'], $_SESSION['owner_id']);
        if (!$kost) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        // Delete from database
        if ($this->kostModel->deletePhoto($photoId)) {
            // Delete physical file
            deleteFile($photo['photo_url']);

            // If deleted photo was primary, set another photo as primary
            if ($photo['is_primary']) {
                $remainingPhotos = $this->kostModel->getPhotos($photo['kost_id']);
                if (!empty($remainingPhotos)) {
                    $this->kostModel->setPrimaryPhoto($remainingPhotos[0]['id'], $photo['kost_id']);
                }
            }

            return $this->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
        }

        return $this->json(['success' => false, 'message' => 'Gagal menghapus foto']);
    }

    /**
     * Reorder photos
     */
    public function reorder($kostId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request']);
        }

        // Verify ownership
        $kost = $this->kostModel->findByIdAndOwner($kostId, $_SESSION['owner_id']);
        if (!$kost) {
            return $this->json(['success' => false, 'message' => 'Unauthorized']);
        }

        // Get order data from request
        $order = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($order['photos']) || !is_array($order['photos'])) {
            return $this->json(['success' => false, 'message' => 'Invalid data']);
        }

        // Update display order
        $success = true;
        foreach ($order['photos'] as $index => $photoId) {
            if (!$this->kostModel->updatePhotoOrder($photoId, $index)) {
                $success = false;
            }
        }

        if ($success) {
            return $this->json(['success' => true, 'message' => 'Urutan foto berhasil diubah']);
        }

        return $this->json(['success' => false, 'message' => 'Gagal mengubah urutan foto']);
    }
}
