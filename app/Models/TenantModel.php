<?php

namespace App\Models;

use Core\Model;

/**
 * Tenant Model
 * Handles tenant/penyewa data
 */
class TenantModel extends Model
{
    protected $table = 'tenants';

    /**
     * Find tenant by user ID
     * 
     * @param int $userId
     * @return array|false
     */
    public function findByUserId($userId)
    {
        return $this->findOne(['user_id' => $userId]);
    }

    /**
     * Create tenant profile
     * 
     * @param array $data
     * @return int|false
     */
    public function createTenant($data)
    {
        return $this->create($data);
    }

    /**
     * Get tenant with user details
     * 
     * @param int $tenantId
     * @return array|false
     */
    public function getTenantWithUser($tenantId)
    {
        $query = "
            SELECT 
                t.*,
                u.email,
                u.role,
                u.status,
                u.created_at as user_created_at
            FROM tenants t
            JOIN users u ON t.user_id = u.id
            WHERE t.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $tenantId]);
    }

    /**
     * Get all tenants with user details
     * 
     * @return array
     */
    public function getAllWithUsers()
    {
        $query = "
            SELECT 
                t.*,
                u.email,
                u.status,
                u.created_at as registered_at
            FROM tenants t
            JOIN users u ON t.user_id = u.id
            ORDER BY u.created_at DESC
        ";

        return $this->fetchAll($query);
    }

    /**
     * Update tenant profile
     * 
     * @param int $tenantId
     * @param array $data
     * @return bool
     */
    public function updateTenant($tenantId, $data)
    {
        return $this->update($tenantId, $data);
    }

    /**
     * Get tenant bookings count
     * 
     * @param int $tenantId
     * @return int
     */
    public function getBookingsCount($tenantId)
    {
        $query = "SELECT COUNT(*) as total FROM bookings WHERE tenant_id = :tenant_id";
        $result = $this->fetchOne($query, ['tenant_id' => $tenantId]);
        return (int) $result['total'];
    }
}
