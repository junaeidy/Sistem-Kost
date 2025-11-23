<?php

namespace App\Controllers\Owner;

use Core\Controller;
use Core\Database;
use Core\Session;
use App\Models\OwnerModel;

/**
 * Owner Kost Management Controller
 * Handles CRUD operations for owner's kost
 */
class KostController extends Controller
{
    private $db;
    private $ownerModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ownerModel = new OwnerModel();
        
        // Load upload helper
        require_once __DIR__ . '/../../../helpers/upload.php';
    }

    /**
     * Get authenticated owner ID
     */
    private function getOwnerId()
    {
        $userId = Session::get('user_id');
        $owner = $this->ownerModel->findByUserId($userId);
        
        if (!$owner) {
            $this->flash('error', 'Owner profile tidak ditemukan.');
            $this->redirect(url('/login'));
            exit;
        }
        
        return $owner['id'];
    }

    /**
     * Show all owner's kost
     */
    public function index()
    {
        $ownerId = $this->getOwnerId();

        // Pagination setup
        $perPage = max(1, (int) (config('pagination.per_page') ?: 10));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $offset = ($page - 1) * $perPage;

        // Count total records
        $countQuery = "SELECT COUNT(*) as total FROM kost WHERE owner_id = :owner_id";
        $totalResult = $this->db->fetchOne($countQuery, ['owner_id' => $ownerId]);
        $total = (int) ($totalResult['total'] ?? 0);
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;

        $query = "SELECT k.*, 
                  (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_kamar,
                  (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_kamar,
                  (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'occupied') as occupied_kamar
                 FROM kost k
                 WHERE k.owner_id = :owner_id
                 ORDER BY k.created_at DESC
                 LIMIT :limit OFFSET :offset";
        
        $kostList = $this->db->fetchAll($query, [
            'owner_id' => $ownerId,
            'limit' => $perPage,
            'offset' => $offset
        ]);

        $this->view('owner/kost/index', [
            'title' => 'Kelola Kost',
            'pageTitle' => 'Kelola Kost',
            'kostList' => $kostList,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'per_page' => $perPage
            ]
        ], 'layouts/dashboard');
    }

    /**
     * Show create kost form
     */
    public function create()
    {
        $this->view('owner/kost/create', [
            'title' => 'Tambah Kost Baru',
            'pageTitle' => 'Tambah Kost Baru'
        ], 'layouts/dashboard');
    }

    /**
     * Store new kost
     */
    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect(url('/owner/kost'));
            return;
        }

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $ownerId = $this->getOwnerId();

        // Validate input
        $name = $this->post('name');
        $location = $this->post('location');
        $address = $this->post('address');
        $price = $this->post('price');
        $facilities = $this->post('facilities', []);
        $description = $this->post('description');
        $genderType = $this->post('gender_type', 'campur');

        if (empty($name) || empty($location) || empty($address) || empty($price)) {
            $this->flash('error', 'Semua field wajib diisi.');
            $this->back();
            return;
        }

        // Convert facilities array to JSON
        $facilitiesJson = !empty($facilities) ? json_encode($facilities) : null;

        // Insert kost
        $query = "INSERT INTO kost (owner_id, name, location, address, price, facilities, description, gender_type, status, created_at, updated_at)
                  VALUES (:owner_id, :name, :location, :address, :price, :facilities, :description, :gender_type, 'active', NOW(), NOW())";
        
        $params = [
            'owner_id' => $ownerId,
            'name' => $name,
            'location' => $location,
            'address' => $address,
            'price' => $price,
            'facilities' => $facilitiesJson,
            'description' => $description,
            'gender_type' => $genderType
        ];

        $stmt = $this->db->query($query, $params);
        $kostId = $stmt ? $this->db->getConnection()->lastInsertId() : false;

        if ($kostId) {
            // Handle photo upload
            $this->handlePhotoUpload($kostId);
            
            $this->flash('success', 'Kost berhasil ditambahkan.');
            $this->redirect(url('/owner/kost/' . $kostId));
        } else {
            $this->flash('error', 'Gagal menambahkan kost.');
            $this->back();
        }
    }

    /**
     * Handle photo upload for kost
     */
    private function handlePhotoUpload($kostId)
    {
        if (!isset($_FILES['photos']) || empty($_FILES['photos']['name'][0])) {
            return;
        }

        $uploadDir = dirname(dirname(dirname(__DIR__))) . '/public/uploads/kost/';
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $files = $_FILES['photos'];
        $isFirstPhoto = true;

        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileName = $files['name'][$i];
            $fileTmp = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileType = $files['type'][$i];

            // Validate file
            if ($fileSize > 5 * 1024 * 1024) { // 5MB
                continue;
            }

            if (!in_array($fileType, ['image/jpeg', 'image/jpg', 'image/png'])) {
                continue;
            }

            // Generate unique filename
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = 'kost_' . $kostId . '_' . time() . '_' . rand(1000, 9999) . '.' . $fileExt;
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $filePath)) {
                $photoUrl = 'uploads/kost/' . $newFileName;
                $isPrimary = $isFirstPhoto ? 1 : 0;

                $photoQuery = "INSERT INTO kost_photos (kost_id, photo_url, is_primary, display_order, created_at)
                              VALUES (:kost_id, :photo_url, :is_primary, :display_order, NOW())";
                
                $this->db->query($photoQuery, [
                    'kost_id' => $kostId,
                    'photo_url' => $photoUrl,
                    'is_primary' => $isPrimary,
                    'display_order' => $i
                ]);

                $isFirstPhoto = false;
            }
        }
    }

    /**
     * Show kost detail
     */
    public function show($id)
    {
        $ownerId = $this->getOwnerId();

        // Get kost with owner verification
        $query = "SELECT * FROM kost WHERE id = :id AND owner_id = :owner_id";
        $kost = $this->db->fetchOne($query, ['id' => $id, 'owner_id' => $ownerId]);

        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Get photos
        $photosQuery = "SELECT * FROM kost_photos WHERE kost_id = :kost_id ORDER BY is_primary DESC";
        $photos = $this->db->fetchAll($photosQuery, ['kost_id' => $id]);

        // Get kamar
        $kamarQuery = "SELECT * FROM kamar WHERE kost_id = :kost_id ORDER BY name";
        $kamars = $this->db->fetchAll($kamarQuery, ['kost_id' => $id]);

        $this->view('owner/kost/show', [
            'title' => 'Detail Kost',
            'pageTitle' => 'Detail Kost',
            'kost' => $kost,
            'photos' => $photos,
            'kamars' => $kamars
        ], 'layouts/dashboard');
    }

    /**
     * Show edit kost form
     */
    public function edit($id)
    {
        $ownerId = $this->getOwnerId();

        $query = "SELECT * FROM kost WHERE id = :id AND owner_id = :owner_id";
        $kost = $this->db->fetchOne($query, ['id' => $id, 'owner_id' => $ownerId]);

        if (!$kost) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Get photos
        $photosQuery = "SELECT * FROM kost_photos WHERE kost_id = :kost_id ORDER BY is_primary DESC";
        $photos = $this->db->fetchAll($photosQuery, ['kost_id' => $id]);

        $this->view('owner/kost/edit', [
            'title' => 'Edit Kost',
            'pageTitle' => 'Edit Kost',
            'kost' => $kost,
            'photos' => $photos
        ], 'layouts/dashboard');
    }

    /**
     * Update kost
     */
    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/owner/kost'));
            return;
        }

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $ownerId = $this->getOwnerId();

        // Verify ownership
        $verifyQuery = "SELECT id FROM kost WHERE id = :id AND owner_id = :owner_id";
        $exists = $this->db->fetchOne($verifyQuery, ['id' => $id, 'owner_id' => $ownerId]);

        if (!$exists) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Validate input
        $name = $this->post('name');
        $location = $this->post('location');
        $address = $this->post('address');
        $price = $this->post('price');
        $facilities = $this->post('facilities', []);
        $description = $this->post('description');
        $genderType = $this->post('gender_type', 'campur');

        if (empty($name) || empty($location) || empty($address) || empty($price)) {
            $this->flash('error', 'Semua field wajib diisi.');
            $this->back();
            return;
        }

        // Convert facilities array to JSON
        $facilitiesJson = !empty($facilities) ? json_encode($facilities) : null;

        // Update kost
        $query = "UPDATE kost SET 
                  name = :name,
                  location = :location,
                  address = :address,
                  price = :price,
                  facilities = :facilities,
                  description = :description,
                  gender_type = :gender_type,
                  updated_at = NOW()
                  WHERE id = :id";
        
        $params = [
            'id' => $id,
            'name' => $name,
            'location' => $location,
            'address' => $address,
            'price' => $price,
            'facilities' => $facilitiesJson,
            'description' => $description,
            'gender_type' => $genderType
        ];

        $stmt = $this->db->query($query, $params);

        if ($stmt) {
            // Handle photo upload
            $this->handlePhotoUpload($id);
            
            $this->flash('success', 'Kost berhasil diupdate.');
        } else {
            $this->flash('error', 'Gagal mengupdate kost.');
        }

        $this->redirect(url('/owner/kost/' . $id));
    }

    /**
     * Delete kost
     */
    public function delete($id)
    {
        if (!$this->isPost()) {
            $this->redirect(url('/owner/kost'));
            return;
        }

        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $ownerId = $this->getOwnerId();

        // Verify ownership
        $verifyQuery = "SELECT id FROM kost WHERE id = :id AND owner_id = :owner_id";
        $exists = $this->db->fetchOne($verifyQuery, ['id' => $id, 'owner_id' => $ownerId]);

        if (!$exists) {
            $this->flash('error', 'Kost tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Check if there are active bookings
        $bookingCheck = "SELECT COUNT(*) as total 
                        FROM bookings b
                        JOIN kamar ka ON b.kamar_id = ka.id
                        WHERE ka.kost_id = :kost_id AND b.status IN ('active_rent', 'paid')";
        $bookingResult = $this->db->fetchOne($bookingCheck, ['kost_id' => $id]);

        if ($bookingResult['total'] > 0) {
            $this->flash('error', 'Tidak bisa menghapus kost yang memiliki booking aktif.');
            $this->back();
            return;
        }

        // Get all photos for this kost before deletion
        $photosQuery = "SELECT photo_url FROM kost_photos WHERE kost_id = :kost_id";
        $photos = $this->db->fetchAll($photosQuery, ['kost_id' => $id]);

        // Delete physical photo files
        foreach ($photos as $photo) {
            if (!empty($photo['photo_url'])) {
                deleteFile($photo['photo_url']);
            }
        }

        // Delete kost (cascade will handle related records including kost_photos in DB)
        $deleted = $this->db->delete('kost', 'id = :id', ['id' => $id]);

        if ($deleted) {
            $this->flash('success', 'Kost dan semua foto berhasil dihapus.');
        } else {
            $this->flash('error', 'Gagal menghapus kost.');
        }

        $this->redirect(url('/owner/kost'));
    }
}
