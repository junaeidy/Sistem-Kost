<?php

namespace App\Controllers\Owner;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\OwnerModel;

/**
 * Owner Kamar Controller
 * Manage rooms for owner's kost
 */
class KamarController extends Controller
{
    private $db;
    private $ownerModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ownerModel = new OwnerModel();
    }

    /**
     * Get owner ID from session
     */
    private function getOwnerId()
    {
        $userId = Session::get('user_id');
        $owner = $this->ownerModel->findByUserId($userId);
        return $owner ? $owner['id'] : null;
    }

    /**
     * Verify kost ownership
     */
    private function verifyKostOwnership($kostId)
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            return false;
        }

        $query = "SELECT id FROM kost WHERE id = :id AND owner_id = :owner_id";
        $kost = $this->db->fetchOne($query, [
            'id' => $kostId,
            'owner_id' => $ownerId
        ]);

        return $kost !== false;
    }

    /**
     * Show create kamar form
     */
    public function create($kostId)
    {
        if (!$this->verifyKostOwnership($kostId)) {
            $this->flash('error', 'Kost tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Get kost data
        $query = "SELECT * FROM kost WHERE id = :id";
        $kost = $this->db->fetchOne($query, ['id' => $kostId]);

        $this->view('owner/kamar/create', [
            'title' => 'Tambah Kamar',
            'pageTitle' => 'Tambah Kamar Baru',
            'kost' => $kost
        ], 'layouts/dashboard');
    }

    /**
     * Store new kamar
     */
    public function store($kostId)
    {
        if (!$this->verifyKostOwnership($kostId)) {
            $this->flash('error', 'Kost tidak ditemukan atau Anda tidak memiliki akses.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Validation
        $errors = [];
        
        if (empty($_POST['nomor_kamar'])) {
            $errors[] = 'Nomor kamar wajib diisi.';
        }
        
        if (empty($_POST['harga']) || !is_numeric($_POST['harga'])) {
            $errors[] = 'Harga kamar wajib diisi dan harus berupa angka.';
        }

        // Check duplicate nomor kamar in same kost
        $checkQuery = "SELECT id FROM kamar WHERE kost_id = :kost_id AND name = :name";
        $existing = $this->db->fetchOne($checkQuery, [
            'kost_id' => $kostId,
            'name' => $_POST['nomor_kamar']
        ]);

        if ($existing) {
            $errors[] = 'Nomor kamar sudah digunakan di kost ini.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
            return;
        }

        // Prepare facilities JSON
        $facilities = [];
        if (isset($_POST['facilities']) && is_array($_POST['facilities'])) {
            $facilities = $_POST['facilities'];
        }

        // Insert kamar
        $query = "INSERT INTO kamar (kost_id, name, price, size, facilities, description, status) 
                  VALUES (:kost_id, :name, :price, :size, :facilities, :description, :status)";
        
        $params = [
            'kost_id' => $kostId,
            'name' => $_POST['nomor_kamar'],
            'price' => $_POST['harga'],
            'size' => !empty($_POST['luas']) ? $_POST['luas'] : null,
            'facilities' => json_encode($facilities),
            'description' => $_POST['description'] ?? null,
            'status' => $_POST['status'] ?? 'available'
        ];

        $stmt = $this->db->query($query, $params);

        if ($stmt) {
            $this->flash('success', 'Kamar berhasil ditambahkan.');
            $this->redirect(url('/owner/kost/' . $kostId));
        } else {
            $this->flash('error', 'Gagal menambahkan kamar.');
            $this->back();
        }
    }

    /**
     * Show edit kamar form
     */
    public function edit($id)
    {
        // Get kamar with kost info
        $query = "SELECT k.*, kost.name as kost_name, kost.owner_id 
                  FROM kamar k 
                  JOIN kost ON k.kost_id = kost.id 
                  WHERE k.id = :id";
        $kamar = $this->db->fetchOne($query, ['id' => $id]);

        if (!$kamar) {
            $this->flash('error', 'Kamar tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Verify ownership
        $ownerId = $this->getOwnerId();
        if ($kamar['owner_id'] != $ownerId) {
            $this->flash('error', 'Anda tidak memiliki akses ke kamar ini.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        $this->view('owner/kamar/edit', [
            'title' => 'Edit Kamar',
            'pageTitle' => 'Edit Kamar',
            'kamar' => $kamar
        ], 'layouts/dashboard');
    }

    /**
     * Update kamar
     */
    public function update($id)
    {
        // Get kamar with kost info
        $query = "SELECT k.*, kost.owner_id 
                  FROM kamar k 
                  JOIN kost ON k.kost_id = kost.id 
                  WHERE k.id = :id";
        $kamar = $this->db->fetchOne($query, ['id' => $id]);

        if (!$kamar) {
            $this->flash('error', 'Kamar tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Verify ownership
        $ownerId = $this->getOwnerId();
        if ($kamar['owner_id'] != $ownerId) {
            $this->flash('error', 'Anda tidak memiliki akses ke kamar ini.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Validation
        $errors = [];
        
        if (empty($_POST['nomor_kamar'])) {
            $errors[] = 'Nomor kamar wajib diisi.';
        }
        
        if (empty($_POST['harga']) || !is_numeric($_POST['harga'])) {
            $errors[] = 'Harga kamar wajib diisi dan harus berupa angka.';
        }

        // Check duplicate nomor kamar (exclude current)
        $checkQuery = "SELECT id FROM kamar 
                       WHERE kost_id = :kost_id 
                       AND name = :name 
                       AND id != :id";
        $existing = $this->db->fetchOne($checkQuery, [
            'kost_id' => $kamar['kost_id'],
            'name' => $_POST['nomor_kamar'],
            'id' => $id
        ]);

        if ($existing) {
            $errors[] = 'Nomor kamar sudah digunakan di kost ini.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
            return;
        }

        // Prepare facilities JSON
        $facilities = [];
        if (isset($_POST['facilities']) && is_array($_POST['facilities'])) {
            $facilities = $_POST['facilities'];
        }

        // Update kamar
        $updateQuery = "UPDATE kamar SET 
                        name = :name,
                        price = :price,
                        size = :size,
                        facilities = :facilities,
                        description = :description,
                        status = :status,
                        updated_at = NOW()
                        WHERE id = :id";
        
        $params = [
            'name' => $_POST['nomor_kamar'],
            'price' => $_POST['harga'],
            'size' => !empty($_POST['luas']) ? $_POST['luas'] : null,
            'facilities' => json_encode($facilities),
            'description' => $_POST['description'] ?? null,
            'status' => $_POST['status'] ?? 'available',
            'id' => $id
        ];

        $stmt = $this->db->query($updateQuery, $params);

        if ($stmt) {
            $this->flash('success', 'Kamar berhasil diupdate.');
            $this->redirect(url('/owner/kost/' . $kamar['kost_id']));
        } else {
            $this->flash('error', 'Gagal mengupdate kamar.');
            $this->back();
        }
    }

    /**
     * Delete kamar
     */
    public function delete($id)
    {
        // Get kamar with kost info
        $query = "SELECT k.*, kost.owner_id 
                  FROM kamar k 
                  JOIN kost ON k.kost_id = kost.id 
                  WHERE k.id = :id";
        $kamar = $this->db->fetchOne($query, ['id' => $id]);

        if (!$kamar) {
            $this->flash('error', 'Kamar tidak ditemukan.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Verify ownership
        $ownerId = $this->getOwnerId();
        if ($kamar['owner_id'] != $ownerId) {
            $this->flash('error', 'Anda tidak memiliki akses ke kamar ini.');
            $this->redirect(url('/owner/kost'));
            return;
        }

        // Check if kamar has active bookings
        $checkBooking = "SELECT COUNT(*) as total FROM bookings 
                         WHERE kamar_id = :kamar_id 
                         AND status IN ('pending', 'confirmed', 'active')";
        $bookingCount = $this->db->fetchOne($checkBooking, ['kamar_id' => $id]);

        if ($bookingCount && $bookingCount['total'] > 0) {
            $this->flash('error', 'Tidak dapat menghapus kamar yang memiliki booking aktif.');
            $this->back();
            return;
        }

        // Delete kamar
        $deleted = $this->db->delete('kamar', 'id = :id', ['id' => $id]);

        if ($deleted) {
            $this->flash('success', 'Kamar berhasil dihapus.');
        } else {
            $this->flash('error', 'Gagal menghapus kamar.');
        }

        $this->redirect(url('/owner/kost/' . $kamar['kost_id']));
    }
}
