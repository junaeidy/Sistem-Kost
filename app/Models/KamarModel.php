<?php

namespace App\Models;

use Core\Model;

/**
 * Kamar Model
 * Handles room (kamar) operations
 */
class KamarModel extends Model
{
    protected $table = 'kamar';

    /**
     * Find rooms by kost ID
     * 
     * @param int $kostId
     * @return array
     */
    public function findByKostId($kostId)
    {
        return $this->findAll(['kost_id' => $kostId]);
    }

    /**
     * Get room detail with kost information
     * 
     * @param int $id
     * @return array|false
     */
    public function getDetailWithKost($id)
    {
        $query = "
            SELECT 
                k.*,
                ko.id as kost_id,
                ko.name as kost_name,
                ko.address as kost_address,
                ko.location as kost_location,
                ko.facilities as kost_facilities,
                ko.gender_type,
                ko.description as kost_description,
                o.id as owner_id,
                o.name as owner_name,
                o.phone as owner_phone,
                (SELECT photo_url FROM kost_photos WHERE kost_id = ko.id AND is_primary = 1 LIMIT 1) as kost_photo
            FROM {$this->table} k
            INNER JOIN kost ko ON k.kost_id = ko.id
            INNER JOIN owners o ON ko.owner_id = o.id
            WHERE k.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $id]);
    }

    /**
     * Get available rooms by kost ID
     * 
     * @param int $kostId
     * @return array
     */
    public function getAvailableByKostId($kostId)
    {
        return $this->findAll([
            'kost_id' => $kostId,
            'status' => 'available'
        ]);
    }

    /**
     * Update room status
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
     * Count rooms by kost ID and status
     * 
     * @param int $kostId
     * @param string|null $status
     * @return int
     */
    public function countByKostId($kostId, $status = null)
    {
        if ($status) {
            $query = "SELECT COUNT(*) as count FROM {$this->table} 
                      WHERE kost_id = :kost_id AND status = :status";
            $params = ['kost_id' => $kostId, 'status' => $status];
        } else {
            $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE kost_id = :kost_id";
            $params = ['kost_id' => $kostId];
        }
        
        $result = $this->fetchOne($query, $params);
        return $result['count'] ?? 0;
    }
}
