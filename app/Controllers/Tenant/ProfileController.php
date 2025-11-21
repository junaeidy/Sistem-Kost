<?php

namespace App\Controllers\Tenant;

use Core\Controller;
use Core\Session;
use Core\Database;
use App\Models\TenantModel;
use App\Models\UserModel;

/**
 * Tenant Profile Controller
 * Handles tenant profile management
 */
class ProfileController extends Controller
{
    private $db;
    private $tenantModel;
    private $userModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->tenantModel = new TenantModel();
        $this->userModel = new UserModel();
    }

    /**
     * Show profile page
     */
    public function index()
    {
        $userId = Session::get('user_id');
        
        // Get user data
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $this->flash('error', 'User tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Get tenant data
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/tenant/dashboard'));
            return;
        }

        // Ensure all required fields have default values
        $tenant = array_merge([
            'id' => 0,
            'name' => '',
            'phone' => '',
            'address' => '',
            'profile_photo' => '',
            'email' => $user['email'],
            'created_at' => date('Y-m-d H:i:s')
        ], $tenant);

        $this->view('tenant/profile/index', [
            'title' => 'Profil Saya',
            'pageTitle' => 'Profil Saya',
            'user' => $user,
            'tenant' => $tenant
        ], 'layouts/dashboard');
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        $userId = Session::get('user_id');
        
        // Get tenant
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Validate input
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Nama harus diisi.';
        }

        if (empty($phone)) {
            $errors[] = 'Nomor telepon harus diisi.';
        } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Nomor telepon tidak valid (10-15 digit).';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
            $this->redirect(url('/tenant/profile'));
            return;
        }

        // Handle profile photo upload
        $profilePhoto = $tenant['profile_photo'];
        
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_photo'];
            
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB

            if (!in_array($file['type'], $allowedTypes)) {
                $this->flash('error', 'Tipe file tidak diizinkan. Hanya JPG, PNG, dan GIF.');
                $this->redirect(url('/tenant/profile'));
                return;
            }

            if ($file['size'] > $maxSize) {
                $this->flash('error', 'Ukuran file maksimal 2MB.');
                $this->redirect(url('/tenant/profile'));
                return;
            }

            // Delete old photo if exists
            if ($profilePhoto && file_exists($profilePhoto)) {
                unlink($profilePhoto);
            }

            // Upload new photo
            $uploadDir = 'public/uploads/profile/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'tenant_' . $tenant['id'] . '_' . time() . '.' . $extension;
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $profilePhoto = $destination;
            } else {
                $this->flash('error', 'Gagal mengupload foto profil.');
            }
        }

        // Update tenant data
        $updateData = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'profile_photo' => $profilePhoto
        ];

        if ($this->tenantModel->update($tenant['id'], $updateData)) {
            $this->flash('success', 'Profil berhasil diperbarui.');
        } else {
            $this->flash('error', 'Gagal memperbarui profil.');
        }

        $this->redirect(url('/tenant/profile'));
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $userId = Session::get('user_id');
        
        // Validate input
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $errors[] = 'Semua field password harus diisi.';
        }

        if (strlen($newPassword) < 6) {
            $errors[] = 'Password baru minimal 6 karakter.';
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->flash('error', $error);
            }
            $this->redirect(url('/tenant/profile'));
            return;
        }

        // Get user
        $user = $this->userModel->find($userId);

        if (!$user) {
            $this->flash('error', 'User tidak ditemukan.');
            $this->redirect(url('/tenant/profile'));
            return;
        }

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            $this->flash('error', 'Password saat ini salah.');
            $this->redirect(url('/tenant/profile'));
            return;
        }

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        if ($this->userModel->update($userId, ['password' => $hashedPassword])) {
            $this->flash('success', 'Password berhasil diperbarui.');
        } else {
            $this->flash('error', 'Gagal memperbarui password.');
        }

        $this->redirect(url('/tenant/profile'));
    }

    /**
     * Delete profile photo
     */
    public function deletePhoto()
    {
        $userId = Session::get('user_id');
        
        // Get tenant
        $tenant = $this->tenantModel->findByUserId($userId);
        
        if (!$tenant) {
            $this->flash('error', 'Profil tenant tidak ditemukan.');
            $this->redirect(url('/login'));
            return;
        }

        // Delete photo file
        if ($tenant['profile_photo'] && file_exists($tenant['profile_photo'])) {
            unlink($tenant['profile_photo']);
        }

        // Update database
        if ($this->tenantModel->update($tenant['id'], ['profile_photo' => null])) {
            $this->flash('success', 'Foto profil berhasil dihapus.');
        } else {
            $this->flash('error', 'Gagal menghapus foto profil.');
        }

        $this->redirect(url('/tenant/profile'));
    }
}
