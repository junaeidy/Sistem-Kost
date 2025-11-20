<?php

namespace App\Models;

use Core\Model;

/**
 * Owner Model
 * Handles owner-specific data
 */
class OwnerModel extends Model
{
    protected $table = 'owners';

    /**
     * Find owner by user ID
     * 
     * @param int $userId
     * @return array|false
     */
    public function findByUserId($userId)
    {
        return $this->findOne(['user_id' => $userId]);
    }

    /**
     * Create owner profile
     * 
     * @param array $data
     * @return int|false
     */
    public function createOwner($data)
    {
        return $this->create($data);
    }

    /**
     * Get owner with user details
     * 
     * @param int $ownerId
     * @return array|false
     */
    public function getOwnerWithUser($ownerId)
    {
        $query = "
            SELECT 
                o.*,
                u.email,
                u.role,
                u.status,
                u.created_at as user_created_at
            FROM owners o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $ownerId]);
    }

    /**
     * Get all owners with user details
     * 
     * @param string|null $status
     * @return array
     */
    public function getAllWithUsers($status = null)
    {
        $query = "
            SELECT 
                o.*,
                u.email,
                u.status as user_status,
                u.created_at as registered_at
            FROM owners o
            JOIN users u ON o.user_id = u.id
        ";

        $params = [];

        if ($status) {
            $query .= " WHERE u.status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY u.created_at DESC";

        return $this->fetchAll($query, $params);
    }

    /**
     * Update owner profile
     * 
     * @param int $ownerId
     * @param array $data
     * @return bool
     */
    public function updateOwner($ownerId, $data)
    {
        return $this->update($ownerId, $data);
    }

    /**
     * Count owners by status
     * 
     * @param string $status
     * @return int
     */
    public function countByStatus($status)
    {
        $query = "
            SELECT COUNT(*) as total 
            FROM owners o
            JOIN users u ON o.user_id = u.id
            WHERE u.status = :status
        ";

        $result = $this->fetchOne($query, ['status' => $status]);
        return (int) $result['total'];
    }
}
