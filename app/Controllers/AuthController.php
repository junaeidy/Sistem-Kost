<?php

namespace App\Controllers;

use Core\Controller;
use Core\Session;
use App\Models\UserModel;
use App\Models\OwnerModel;
use App\Models\TenantModel;

/**
 * Authentication Controller
 * Handles login, register, and logout
 */
class AuthController extends Controller
{
    private $userModel;
    private $ownerModel;
    private $tenantModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->ownerModel = new OwnerModel();
        $this->tenantModel = new TenantModel();
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        $this->view('auth/login', [
            'title' => 'Login'
        ], false);
    }

    /**
     * Process login
     */
    public function login()
    {
        if (!$this->isPost()) {
            $this->redirect(url('/login'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $email = $this->post('email');
        $password = $this->post('password');

        // Validate input
        if (empty($email) || empty($password)) {
            $this->flash('error', 'Email dan password harus diisi.');
            $this->back();
            return;
        }

        // Verify user
        $user = $this->userModel->verifyUser($email, $password);

        if (!$user) {
            $this->flash('error', 'Email atau password salah.');
            $this->back();
            return;
        }

        // Check rejected or suspended status (block login completely)
        if ($user['role'] === 'owner' && $user['status'] === 'rejected') {
            $this->flash('error', 'Akun Anda telah ditolak oleh admin. Silakan hubungi administrator.');
            $this->back();
            return;
        }

        if ($user['status'] === 'suspended') {
            $this->flash('error', 'Akun Anda telah disuspend. Silakan hubungi administrator.');
            $this->back();
            return;
        }

        // Get additional user details
        $userDetails = $this->userModel->getUserWithDetails($user['id']);

        // Set session (for all users including pending)
        Session::set('user_id', $user['id']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role']);
        Session::set('user_status', $user['status']);

        // Set name based on role
        if ($user['role'] === 'owner' && isset($userDetails['owner_name'])) {
            Session::set('user_name', $userDetails['owner_name']);
            Session::set('owner_id', $userDetails['id']);
        } elseif ($user['role'] === 'tenant' && isset($userDetails['tenant_name'])) {
            Session::set('user_name', $userDetails['tenant_name']);
            Session::set('tenant_id', $userDetails['id']);
        } else {
            Session::set('user_name', explode('@', $user['email'])[0]);
        }

        // Redirect based on role and status
        switch ($user['role']) {
            case 'admin':
                $this->flash('success', 'Selamat datang, Admin!');
                $this->redirect(url('/admin/dashboard'));
                break;
            case 'owner':
                // Check if owner is pending verification
                if ($user['status'] === 'pending') {
                    $this->flash('info', 'Akun Anda sedang dalam proses verifikasi. Mohon tunggu konfirmasi dari admin.');
                    $this->redirect(url('/owner/pending'));
                } else {
                    $this->flash('success', 'Selamat datang, ' . Session::get('user_name') . '!');
                    $this->redirect(url('/owner/dashboard'));
                }
                break;
            case 'tenant':
                $this->flash('success', 'Selamat datang, ' . Session::get('user_name') . '!');
                $this->redirect(url('/tenant/dashboard'));
                break;
            default:
                $this->redirect(url('/'));
        }
    }

    /**
     * Show tenant register form
     */
    public function showRegister()
    {
        $this->view('auth/register', [
            'title' => 'Daftar Sebagai Penyewa'
        ], false);
    }

    /**
     * Process tenant registration
     */
    public function register()
    {
        if (!$this->isPost()) {
            $this->redirect(url('/register'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $name = sanitize($this->post('name'));
        $email = sanitize($this->post('email'));
        $phone = sanitize($this->post('phone'));
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');

        // Validation
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Nama harus diisi.';
        }

        if (empty($email) || !is_valid_email($email)) {
            $errors[] = 'Email tidak valid.';
        }

        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Email sudah terdaftar.';
        }

        if (empty($phone)) {
            $errors[] = 'Nomor HP harus diisi.';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            flash_input();
            $this->back();
            return;
        }

        // Begin transaction
        $this->userModel->beginTransaction();

        try {
            // Create user
            $userId = $this->userModel->createUser([
                'email' => $email,
                'password' => $password,
                'role' => 'tenant',
                'status' => 'active'
            ]);

            if (!$userId) {
                throw new \Exception('Gagal membuat user.');
            }

            // Create tenant profile
            $tenantId = $this->tenantModel->createTenant([
                'user_id' => $userId,
                'name' => $name,
                'phone' => $phone
            ]);

            if (!$tenantId) {
                throw new \Exception('Gagal membuat profil penyewa.');
            }

            $this->userModel->commit();

            clear_old_input();
            $this->flash('success', 'Registrasi berhasil! Silakan login.');
            $this->redirect(url('/login'));

        } catch (\Exception $e) {
            $this->userModel->rollback();
            $this->flash('error', 'Registrasi gagal: ' . $e->getMessage());
            flash_input();
            $this->back();
        }
    }

    /**
     * Show owner register form
     */
    public function showRegisterOwner()
    {
        $this->view('auth/register-owner', [
            'title' => 'Daftar Sebagai Pemilik Kost'
        ], false);
    }

    /**
     * Process owner registration
     */
    public function registerOwner()
    {
        if (!$this->isPost()) {
            $this->redirect(url('/register-owner'));
            return;
        }

        // Validate CSRF
        if (!$this->validateCsrf()) {
            $this->flash('error', 'Invalid CSRF token.');
            $this->back();
            return;
        }

        $name = sanitize($this->post('name'));
        $email = sanitize($this->post('email'));
        $phone = sanitize($this->post('phone'));
        $address = sanitize($this->post('address'));
        $password = $this->post('password');
        $passwordConfirm = $this->post('password_confirm');

        // Validation
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Nama harus diisi.';
        }

        if (empty($email) || !is_valid_email($email)) {
            $errors[] = 'Email tidak valid.';
        }

        if ($this->userModel->emailExists($email)) {
            $errors[] = 'Email sudah terdaftar.';
        }

        if (empty($phone)) {
            $errors[] = 'Nomor HP harus diisi.';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }

        if ($password !== $passwordConfirm) {
            $errors[] = 'Konfirmasi password tidak cocok.';
        }

        if (!empty($errors)) {
            $this->flash('error', implode('<br>', $errors));
            flash_input();
            $this->back();
            return;
        }

        // Handle KTP upload (optional)
        $ktpPhoto = null;
        if (isset($_FILES['ktp_photo']) && $_FILES['ktp_photo']['error'] === UPLOAD_ERR_OK) {
            $ktpPhoto = $this->uploadKtp($_FILES['ktp_photo']);
            if (!$ktpPhoto) {
                $this->flash('error', 'Gagal mengupload foto KTP.');
                flash_input();
                $this->back();
                return;
            }
        }

        // Begin transaction
        $this->userModel->beginTransaction();

        try {
            // Create user with pending status
            $userId = $this->userModel->createUser([
                'email' => $email,
                'password' => $password,
                'role' => 'owner',
                'status' => 'pending'
            ]);

            if (!$userId) {
                throw new \Exception('Gagal membuat user.');
            }

            // Create owner profile
            $ownerId = $this->ownerModel->createOwner([
                'user_id' => $userId,
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'ktp_photo' => $ktpPhoto
            ]);

            if (!$ownerId) {
                throw new \Exception('Gagal membuat profil pemilik.');
            }

            $this->userModel->commit();

            clear_old_input();
            $this->flash('success', 'Registrasi berhasil! Akun Anda menunggu verifikasi admin.');
            $this->redirect(url('/owner/pending'));

        } catch (\Exception $e) {
            $this->userModel->rollback();
            
            // Delete uploaded file if exists
            if ($ktpPhoto && file_exists(dirname(__DIR__, 2) . '/public/uploads/ktp/' . $ktpPhoto)) {
                unlink(dirname(__DIR__, 2) . '/public/uploads/ktp/' . $ktpPhoto);
            }

            $this->flash('error', 'Registrasi gagal: ' . $e->getMessage());
            flash_input();
            $this->back();
        }
    }

    /**
     * Show pending page for owners awaiting verification
     */
    public function showPending()
    {
        // Check if user is actually an owner with pending status
        if (!Session::isLoggedIn() || !Session::hasRole('owner')) {
            $this->redirect(url('/login'));
            return;
        }

        $user = $this->userModel->find(Session::userId());
        
        if (!$user || $user['status'] !== 'pending') {
            // If not pending, redirect to appropriate page
            $this->redirect(url('/owner/dashboard'));
            return;
        }

        $this->view('auth/pending', [
            'title' => 'Menunggu Verifikasi'
        ], false);
    }

    /**
     * Logout
     */
    public function logout()
    {
        Session::destroy();
        $this->flash('success', 'Anda telah logout.');
        $this->redirect(url('/login'));
    }

    /**
     * Upload KTP photo
     * 
     * @param array $file
     * @return string|false
     */
    private function uploadKtp($file)
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'ktp_' . time() . '_' . random_string(8) . '.' . $extension;
        $uploadPath = dirname(__DIR__, 2) . '/public/uploads/ktp/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $filename;
        }

        return false;
    }
}
