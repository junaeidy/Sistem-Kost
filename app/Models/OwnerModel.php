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
     * @param array|string|null $filter Status filter (can be string or array with 'status' key)
     * @param int|null $limit Limit results
     * @return array
     */
    public function getAllWithUsers($filter = null, $limit = null)
    {
        $query = "
            SELECT 
                o.*,
                u.email,
                u.status,
                u.created_at
            FROM owners o
            JOIN users u ON o.user_id = u.id
        ";

        $params = [];

        // Handle filter (can be string or array)
        if ($filter) {
            if (is_array($filter) && isset($filter['status'])) {
                $query .= " WHERE u.status = :status";
                $params['status'] = $filter['status'];
            } elseif (is_string($filter)) {
                $query .= " WHERE u.status = :status";
                $params['status'] = $filter;
            }
        }

        $query .= " ORDER BY u.created_at DESC";

        if ($limit) {
            $query .= " LIMIT " . intval($limit);
        }

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

    /**
     * Get all owners with verification status
     * 
     * @param string|null $status Filter by verification status
     * @return array
     */
    public function getAllWithVerificationStatus($status = null)
    {
        $query = "
            SELECT 
                o.*,
                u.email,
                u.status as user_status,
                u.created_at as registered_at,
                verified_by_user.email as verified_by_email
            FROM owners o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN users verified_by_user ON o.verified_by = verified_by_user.id
        ";

        $params = [];

        if ($status) {
            $query .= " WHERE o.verification_status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY 
            CASE o.verification_status 
                WHEN 'pending' THEN 1 
                WHEN 'approved' THEN 2 
                WHEN 'rejected' THEN 3 
            END,
            o.created_at DESC
        ";

        return $this->fetchAll($query, $params);
    }

    /**
     * Get owner with user details (alias for compatibility)
     * 
     * @param int $ownerId
     * @return array|false
     */
    public function getWithUser($ownerId)
    {
        return $this->getOwnerWithUser($ownerId);
    }

    /**
     * Count owners by verification status
     * 
     * @param string $status
     * @return int
     */
    public function countByVerificationStatus($status)
    {
        $query = "SELECT COUNT(*) as total FROM owners WHERE verification_status = :status";
        $result = $this->fetchOne($query, ['status' => $status]);
        return (int) $result['total'];
    }
}
