<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * Handles user authentication and management
 */
class UserModel extends Model
{
    protected $table = 'users';

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email)
    {
        return $this->findOne(['email' => $email]);
    }

    /**
     * Create new user
     * 
     * @param array $data
     * @return int|false
     */
    public function createUser($data)
    {
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->create($data);
    }

    /**
     * Verify user password
     * 
     * @param string $email
     * @param string $password
     * @return array|false
     */
    public function verifyUser($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Update user status
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Get user with role details (owner or tenant)
     * 
     * @param int $userId
     * @return array|false
     */
    public function getUserWithDetails($userId)
    {
        $query = "
            SELECT 
                u.*,
                o.name as owner_name,
                o.phone as owner_phone,
                o.ktp_photo as owner_ktp,
                o.address as owner_address,
                t.name as tenant_name,
                t.phone as tenant_phone,
                t.profile_photo as tenant_photo,
                t.address as tenant_address
            FROM users u
            LEFT JOIN owners o ON u.id = o.user_id
            LEFT JOIN tenants t ON u.id = t.user_id
            WHERE u.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $userId]);
    }

    /**
     * Check if email exists
     * 
     * @param string $email
     * @param int|null $excludeId
     * @return bool
     */
    public function emailExists($email, $excludeId = null)
    {
        $query = "SELECT COUNT(*) as total FROM users WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId) {
            $query .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $result = $this->fetchOne($query, $params);
        return $result['total'] > 0;
    }
}
