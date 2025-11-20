<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Session;
use Core\Database;

/**
 * Admin Profile Controller
 * Manage admin profile and settings
 */
class ProfileController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Show profile page
     */
    public function index()
    {
        $userId = Session::get('user_id');

        // Get user data
        $user = $this->db->fetchOne("SELECT * FROM users WHERE id = :id", ['id' => $userId]);

        if (!$user) {
            $this->flash('error', 'User tidak ditemukan.');
            $this->redirect(url('/admin/dashboard'));
            return;
        }

        $this->view('admin/profile/index', [
            'title' => 'Profil Admin',
            'pageTitle' => 'Profil Admin',
            'user' => $user
        ], 'layouts/dashboard');
    }

    /**
     * Update profile information
     */
    public function updateProfile()
    {
        $userId = Session::get('user_id');

        // Validation
        $errors = [];

        if (empty($_POST['email'])) {
            $errors[] = 'Email wajib diisi.';
        }

        // Check email uniqueness if changed
        $currentUser = $this->db->fetchOne("SELECT email FROM users WHERE id = :id", ['id' => $userId]);
        
        if ($_POST['email'] !== $currentUser['email']) {
            $existingEmail = $this->db->fetchOne(
                "SELECT id FROM users WHERE email = :email AND id != :id",
                ['email' => $_POST['email'], 'id' => $userId]
            );

            if ($existingEmail) {
                $errors[] = 'Email sudah digunakan.';
            }
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            $this->back();
            return;
        }

        // Update user data
        $query = "UPDATE users SET 
                    email = :email,
                    updated_at = NOW()
                  WHERE id = :id";
        
        $stmt = $this->db->query($query, [
            'email' => $_POST['email'],
            'id' => $userId
        ]);

        if ($stmt) {
            // Update session email
            Session::set('email', $_POST['email']);
            $this->flash('success', 'Profil berhasil diperbarui.');
        } else {
            $this->flash('error', 'Gagal memperbarui profil.');
        }

        $this->redirect(url('/admin/profile'));
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

        $this->redirect(url('/admin/profile'));
    }
}
