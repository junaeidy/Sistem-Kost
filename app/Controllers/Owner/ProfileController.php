<?php

namespace App\Controllers\Owner;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\OwnerModel;
use App\Models\UserModel;

/**
 * Owner Profile Controller
 * Manage owner profile and settings
 */
class ProfileController extends Controller
{
    private $db;
    private $ownerModel;
    private $userModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ownerModel = new OwnerModel();
        $this->userModel = new UserModel();
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
     * Show profile page
     */
    public function index()
    {
        $userId = Session::get('user_id');
        $ownerId = $this->getOwnerId();

        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Get owner data with user info
        $query = "SELECT o.*, u.email 
                  FROM owners o 
                  JOIN users u ON o.user_id = u.id 
                  WHERE o.id = :id";
        
        $owner = $this->db->fetchOne($query, ['id' => $ownerId]);

        $this->view('owner/profile/index', [
            'title' => 'Profil Saya',
            'pageTitle' => 'Profil Saya',
            'owner' => $owner
        ], 'layouts/dashboard');
    }

    /**
     * Update profile information
     */
    public function updateProfile()
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Validation
        $errors = [];

        if (empty($_POST['name'])) {
            $errors[] = 'Nama wajib diisi.';
        }

        if (empty($_POST['phone'])) {
            $errors[] = 'Nomor telepon wajib diisi.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
            return;
        }

        // Handle profile photo upload
        $profilePhoto = null;
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../../public/uploads/profile/';
            
            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                $this->flash('error', 'Format foto tidak valid. Gunakan JPG, PNG, atau GIF.');
                $this->back();
                return;
            }

            // Check file size (max 2MB)
            if ($_FILES['profile_photo']['size'] > 2097152) {
                $this->flash('error', 'Ukuran foto maksimal 2MB.');
                $this->back();
                return;
            }

            $fileName = 'profile_' . $ownerId . '_' . time() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $uploadPath)) {
                $profilePhoto = 'uploads/profile/' . $fileName;

                // Delete old photo
                $oldOwner = $this->db->fetchOne("SELECT profile_photo FROM owners WHERE id = :id", ['id' => $ownerId]);
                if (!empty($oldOwner['profile_photo']) && file_exists(__DIR__ . '/../../../public/' . $oldOwner['profile_photo'])) {
                    unlink(__DIR__ . '/../../../public/' . $oldOwner['profile_photo']);
                }
            } else {
                $this->flash('error', 'Gagal mengupload foto.');
                $this->back();
                return;
            }
        }

        // Update owner data
        $query = "UPDATE owners SET 
                    name = :name,
                    phone = :phone,
                    address = :address,
                    updated_at = NOW()";
        
        $params = [
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'] ?? null,
            'id' => $ownerId
        ];

        if ($profilePhoto) {
            $query .= ", profile_photo = :profile_photo";
            $params['profile_photo'] = $profilePhoto;
        }

        $query .= " WHERE id = :id";

        $stmt = $this->db->query($query, $params);

        if ($stmt) {
            $this->flash('success', 'Profil berhasil diperbarui.');
        } else {
            $this->flash('error', 'Gagal memperbarui profil.');
        }

        $this->redirect(url('/owner/profile'));
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $userId = Session::get('user_id');

        // Validation
        $errors = [];

        if (empty($_POST['current_password'])) {
            $errors[] = 'Password lama wajib diisi.';
        }

        if (empty($_POST['new_password'])) {
            $errors[] = 'Password baru wajib diisi.';
        }

        if (empty($_POST['confirm_password'])) {
            $errors[] = 'Konfirmasi password wajib diisi.';
        }

        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        if (strlen($_POST['new_password']) < 6) {
            $errors[] = 'Password baru minimal 6 karakter.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
            return;
        }

        // Get user
        $user = $this->db->fetchOne("SELECT * FROM users WHERE id = :id", ['id' => $userId]);

        if (!$user) {
            $this->flash('error', 'User tidak ditemukan.');
            $this->back();
            return;
        }

        // Verify current password
        if (!password_verify($_POST['current_password'], $user['password'])) {
            $this->flash('error', 'Password lama tidak sesuai.');
            $this->back();
            return;
        }

        // Update password
        $newPasswordHash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id";
        
        $stmt = $this->db->query($query, [
            'password' => $newPasswordHash,
            'id' => $userId
        ]);

        if ($stmt) {
            $this->flash('success', 'Password berhasil diperbarui.');
        } else {
            $this->flash('error', 'Gagal memperbarui password.');
        }

        $this->redirect(url('/owner/profile'));
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $ownerId = $this->getOwnerId();
        if (!$ownerId) {
            $this->flash('error', 'Owner tidak ditemukan.');
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        // Get owner data
        $owner = $this->db->fetchOne("SELECT profile_photo FROM owners WHERE id = :id", ['id' => $ownerId]);

        if (empty($owner['profile_photo'])) {
            $this->flash('error', 'Tidak ada foto untuk dihapus.');
            $this->back();
            return;
        }

        // Delete file
        $filePath = __DIR__ . '/../../../public/' . $owner['profile_photo'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Update database
        $query = "UPDATE owners SET profile_photo = NULL, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->query($query, ['id' => $ownerId]);

        if ($stmt) {
            $this->flash('success', 'Foto profil berhasil dihapus.');
        } else {
            $this->flash('error', 'Gagal menghapus foto profil.');
        }

        $this->redirect(url('/owner/profile'));
    }

    /**
     * Update profile (legacy method for backward compatibility)
     */
    public function update()
    {
        $this->updateProfile();
    }
}
