<?php

namespace App\Models;

use Core\Model;

/**
 * Kost Model
 * Handles kost (boarding house) operations including search and filtering
 */
class KostModel extends Model
{
    protected $table = 'kost';

    /**
     * Find kost by owner ID
     * 
     * @param int $ownerId
     * @return array
     */
    public function findByOwnerId($ownerId)
    {
        $query = "
            SELECT 
                k.*,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_kamar,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_kamar,
                (SELECT photo_url FROM kost_photos WHERE kost_id = k.id AND is_primary = 1 LIMIT 1) as primary_photo
            FROM {$this->table} k
            WHERE k.owner_id = :owner_id
            ORDER BY k.created_at DESC
        ";

        return $this->fetchAll($query, ['owner_id' => $ownerId]);
    }

    /**
     * Search and filter kost
     * 
     * @param array $filters
     * @return array
     */
    public function search($filters = [])
    {
        $query = "
            SELECT 
                k.*,
                o.name as owner_name,
                o.phone as owner_phone,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_kamar,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_kamar,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_rooms,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_rooms,
                (SELECT MIN(price) FROM kamar WHERE kost_id = k.id) as min_price,
                (SELECT photo_url FROM kost_photos WHERE kost_id = k.id AND is_primary = 1 LIMIT 1) as primary_photo
            FROM {$this->table} k
            INNER JOIN owners o ON k.owner_id = o.id
            WHERE k.status = 'active'
        ";

        $params = [];

        // Keyword search (q parameter from homepage)
        if (!empty($filters['q'])) {
            $query .= " AND (k.name LIKE :keyword OR k.location LIKE :keyword2 OR k.address LIKE :keyword3 OR k.description LIKE :keyword4)";
            $params['keyword'] = '%' . $filters['q'] . '%';
            $params['keyword2'] = '%' . $filters['q'] . '%';
            $params['keyword3'] = '%' . $filters['q'] . '%';
            $params['keyword4'] = '%' . $filters['q'] . '%';
        }

        // Location filter
        if (!empty($filters['location'])) {
            $query .= " AND (k.location LIKE :location OR k.address LIKE :location2 OR k.name LIKE :location3)";
            $params['location'] = '%' . $filters['location'] . '%';
            $params['location2'] = '%' . $filters['location'] . '%';
            $params['location3'] = '%' . $filters['location'] . '%';
        }

        // Gender type filter (support both 'gender' and 'gender_type' parameters)
        $genderFilter = $filters['gender'] ?? $filters['gender_type'] ?? '';
        if (!empty($genderFilter)) {
            $query .= " AND k.gender_type = :gender_type";
            $params['gender_type'] = $genderFilter;
        }

        // Facilities filter (check if facilities column contains the facility)
        if (!empty($filters['facilities'])) {
            $query .= " AND k.facilities LIKE :facilities";
            $params['facilities'] = '%' . $filters['facilities'] . '%';
        }

        // Having clause for price filter (after aggregation)
        $havingClauses = [];
        
        if (!empty($filters['min_price'])) {
            $havingClauses[] = "min_price >= :min_price";
            $params['min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $havingClauses[] = "min_price <= :max_price";
            $params['max_price'] = $filters['max_price'];
        }

        // Only available rooms
        if (!empty($filters['available_only'])) {
            $havingClauses[] = "available_kamar > 0";
        }

        if (!empty($havingClauses)) {
            $query .= " HAVING " . implode(' AND ', $havingClauses);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'DESC';

        $allowedSorts = ['created_at', 'min_price', 'name'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) {
            $sortOrder = 'DESC';
        }

        $query .= " ORDER BY k.{$sortBy} {$sortOrder}";

        return $this->fetchAll($query, $params);
    }

    /**
     * Get kost detail with all related information
     * 
     * @param int $id
     * @return array|false
     */
    public function getDetailWithRooms($id)
    {
        $query = "
            SELECT 
                k.*,
                o.name as owner_name,
                o.phone as owner_phone,
                o.address as owner_address,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id) as total_kamar,
                (SELECT COUNT(*) FROM kamar WHERE kost_id = k.id AND status = 'available') as available_kamar
            FROM {$this->table} k
            INNER JOIN owners o ON k.owner_id = o.id
            WHERE k.id = :id
            LIMIT 1
        ";

        $kost = $this->fetchOne($query, ['id' => $id]);

        if ($kost) {
            // Get photos
            $photosQuery = "SELECT * FROM kost_photos WHERE kost_id = :kost_id ORDER BY is_primary DESC, display_order ASC";
            $kost['photos'] = $this->fetchAll($photosQuery, ['kost_id' => $id]);

            // Get rooms
            $roomsQuery = "SELECT * FROM kamar WHERE kost_id = :kost_id ORDER BY name ASC";
            $kost['kamar'] = $this->fetchAll($roomsQuery, ['kost_id' => $id]);
        }

        return $kost;
    }

    /**
     * Count kost by owner
     * 
     * @param int $ownerId
     * @return int
     */
    public function countByOwner($ownerId)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE owner_id = :owner_id";
        $result = $this->fetchOne($query, ['owner_id' => $ownerId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get kost statistics
     * 
     * @return array
     */
    public function getStatistics()
    {
        $query = "
            SELECT 
                COUNT(*) as total_kost,
                COUNT(CASE WHEN status = 'active' THEN 1 END) as active_kost,
                COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_kost,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_kost
            FROM {$this->table}
        ";

        return $this->fetchOne($query) ?: [];
    }

    /**
     * Get all photos for a kost
     * 
     * @param int $kostId
     * @return array
     */
    public function getPhotos($kostId)
    {
        $query = "SELECT * FROM kost_photos WHERE kost_id = :kost_id ORDER BY is_primary DESC, display_order ASC";
        return $this->fetchAll($query, ['kost_id' => $kostId]);
    }

    /**
     * Get a photo by ID
     * 
     * @param int $photoId
     * @return array|false
     */
    public function getPhotoById($photoId)
    {
        $query = "SELECT * FROM kost_photos WHERE id = :id LIMIT 1";
        return $this->fetchOne($query, ['id' => $photoId]);
    }

    /**
     * Add a new photo
     * 
     * @param array $data
     * @return bool|int
     */
    public function addPhoto($data)
    {
        $query = "
            INSERT INTO kost_photos (kost_id, photo_url, is_primary, display_order)
            VALUES (:kost_id, :photo_url, :is_primary, :display_order)
        ";

        $stmt = $this->query($query, $data);
        return $stmt ? $this->db->getConnection()->lastInsertId() : false;
    }

    /**
     * Set a photo as primary (and unset others)
     * 
     * @param int $photoId
     * @param int $kostId
     * @return bool
     */
    public function setPrimaryPhoto($photoId, $kostId)
    {
        // First, unset all primary flags for this kost
        $query1 = "UPDATE kost_photos SET is_primary = 0 WHERE kost_id = :kost_id";
        $this->query($query1, ['kost_id' => $kostId]);

        // Then set the selected photo as primary
        $query2 = "UPDATE kost_photos SET is_primary = 1 WHERE id = :id";
        $stmt = $this->query($query2, ['id' => $photoId]);
        return $stmt !== false;
    }

    /**
     * Delete a photo
     * 
     * @param int $photoId
     * @return bool
     */
    public function deletePhoto($photoId)
    {
        $query = "DELETE FROM kost_photos WHERE id = :id";
        $stmt = $this->query($query, ['id' => $photoId]);
        return $stmt !== false;
    }

    /**
     * Update photo display order
     * 
     * @param int $photoId
     * @param int $order
     * @return bool
     */
    public function updatePhotoOrder($photoId, $order)
    {
        $query = "UPDATE kost_photos SET display_order = :order WHERE id = :id";
        $stmt = $this->query($query, ['id' => $photoId, 'order' => $order]);
        return $stmt !== false;
    }

    /**
     * Find kost by ID and owner (for ownership verification)
     * 
     * @param int $id
     * @param int $ownerId
     * @return array|false
     */
    public function findByIdAndOwner($id, $ownerId)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id AND owner_id = :owner_id LIMIT 1";
        return $this->fetchOne($query, ['id' => $id, 'owner_id' => $ownerId]);
    }
}
