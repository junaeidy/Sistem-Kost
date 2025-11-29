<?php

namespace App\Models;

use Core\Model;

/**
 * Review Model
 * Handles review/rating management for kost
 */
class ReviewModel extends Model
{
    protected $table = 'reviews';

    /**
     * Check if tenant has completed booking at this kost
     * A tenant can only review if they have at least one completed, active, or accepted booking
     * 
     * @param int $tenantId
     * @param int $kostId
     * @return bool
     */
    public function canTenantReview($tenantId, $kostId)
    {
        $query = "
            SELECT COUNT(*) as count
            FROM bookings b
            INNER JOIN kamar k ON b.kamar_id = k.id
            WHERE b.tenant_id = :tenant_id 
            AND k.kost_id = :kost_id
            AND b.status IN ('paid', 'accepted', 'active_rent', 'completed')
        ";

        $result = $this->fetchOne($query, [
            'tenant_id' => $tenantId,
            'kost_id' => $kostId
        ]);

        return $result && $result['count'] > 0;
    }

    /**
     * Check if tenant already reviewed this kost
     * 
     * @param int $tenantId
     * @param int $kostId
     * @return bool
     */
    public function hasReviewed($tenantId, $kostId)
    {
        $query = "
            SELECT COUNT(*) as count
            FROM {$this->table}
            WHERE tenant_id = :tenant_id AND kost_id = :kost_id
        ";

        $result = $this->fetchOne($query, [
            'tenant_id' => $tenantId,
            'kost_id' => $kostId
        ]);

        return $result && $result['count'] > 0;
    }

    /**
     * Get existing review by tenant and kost
     * 
     * @param int $tenantId
     * @param int $kostId
     * @return array|null
     */
    public function getByTenantAndKost($tenantId, $kostId)
    {
        $query = "
            SELECT r.*, t.name as tenant_name, t.profile_photo
            FROM {$this->table} r
            INNER JOIN tenants t ON r.tenant_id = t.id
            WHERE r.tenant_id = :tenant_id AND r.kost_id = :kost_id
            LIMIT 1
        ";

        return $this->fetchOne($query, [
            'tenant_id' => $tenantId,
            'kost_id' => $kostId
        ]);
    }

    /**
     * Find reviews by kost ID with tenant info
     * 
     * @param int $kostId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByKostId($kostId, $limit = null, $offset = 0)
    {
        $query = "
            SELECT 
                r.*,
                t.name as tenant_name,
                t.profile_photo
            FROM {$this->table} r
            INNER JOIN tenants t ON r.tenant_id = t.id
            WHERE r.kost_id = :kost_id
            ORDER BY r.created_at DESC
        ";

        if ($limit) {
            $query .= " LIMIT :limit OFFSET :offset";
            return $this->fetchAll($query, [
                'kost_id' => $kostId,
                'limit' => $limit,
                'offset' => $offset
            ]);
        }

        return $this->fetchAll($query, ['kost_id' => $kostId]);
    }

    /**
     * Count reviews by kost ID
     * 
     * @param int $kostId
     * @return int
     */
    public function countByKostId($kostId)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE kost_id = :kost_id";
        $result = $this->fetchOne($query, ['kost_id' => $kostId]);
        return $result ? (int) $result['count'] : 0;
    }

    /**
     * Get review statistics for a kost
     * 
     * @param int $kostId
     * @return array
     */
    public function getKostStats($kostId)
    {
        $query = "
            SELECT 
                COUNT(*) as total_reviews,
                COALESCE(AVG(rating), 0) as average_rating,
                COALESCE(SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END), 0) as five_star,
                COALESCE(SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END), 0) as four_star,
                COALESCE(SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END), 0) as three_star,
                COALESCE(SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END), 0) as two_star,
                COALESCE(SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END), 0) as one_star
            FROM {$this->table}
            WHERE kost_id = :kost_id
        ";

        $result = $this->fetchOne($query, ['kost_id' => $kostId]);
        
        if ($result) {
            $result['average_rating'] = round((float) $result['average_rating'], 1);
            $result['total_reviews'] = (int) $result['total_reviews'];
            $result['five_star'] = (int) $result['five_star'];
            $result['four_star'] = (int) $result['four_star'];
            $result['three_star'] = (int) $result['three_star'];
            $result['two_star'] = (int) $result['two_star'];
            $result['one_star'] = (int) $result['one_star'];
        }

        return $result ?: [
            'total_reviews' => 0,
            'average_rating' => 0,
            'five_star' => 0,
            'four_star' => 0,
            'three_star' => 0,
            'two_star' => 0,
            'one_star' => 0
        ];
    }

    /**
     * Create new review
     * 
     * @param array $data
     * @return int|false
     */
    public function createReview($data)
    {
        // Validate required fields
        $required = ['kost_id', 'tenant_id', 'rating'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Review creation failed: Missing field $field");
                return false;
            }
        }

        // Validate rating range
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            error_log("Review creation failed: Invalid rating value");
            return false;
        }

        // Check if tenant can review
        if (!$this->canTenantReview($data['tenant_id'], $data['kost_id'])) {
            error_log("Review creation failed: Tenant has no completed booking");
            return false;
        }

        // Check if already reviewed
        if ($this->hasReviewed($data['tenant_id'], $data['kost_id'])) {
            error_log("Review creation failed: Tenant already reviewed this kost");
            return false;
        }

        $fields = ['kost_id', 'tenant_id', 'rating', 'review_text'];
        $insertData = [];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $insertData[$field] = $data[$field];
            }
        }

        return $this->create($insertData);
    }

    /**
     * Update existing review
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateReview($id, $data)
    {
        // Validate rating if provided
        if (isset($data['rating']) && ($data['rating'] < 1 || $data['rating'] > 5)) {
            error_log("Review update failed: Invalid rating value");
            return false;
        }

        $fields = ['rating', 'review_text'];
        $updateData = [];
        
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            return false;
        }

        return $this->update($id, $updateData);
    }

    /**
     * Delete review
     * 
     * @param int $id
     * @return bool
     */
    public function deleteReview($id)
    {
        return $this->delete($id);
    }

    /**
     * Find review by ID with details
     * 
     * @param int $id
     * @return array|null
     */
    public function findById($id)
    {
        $query = "
            SELECT 
                r.*,
                t.name as tenant_name,
                t.profile_photo,
                k.name as kost_name
            FROM {$this->table} r
            INNER JOIN tenants t ON r.tenant_id = t.id
            INNER JOIN kost k ON r.kost_id = k.id
            WHERE r.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $id]);
    }
}
