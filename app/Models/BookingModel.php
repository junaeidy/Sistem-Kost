<?php

namespace App\Models;

use Core\Model;

/**
 * Booking Model
 * Handles booking/reservation management
 */
class BookingModel extends Model
{
    protected $table = 'bookings';

    /**
     * Find bookings by tenant ID
     * 
     * @param int $tenantId
     * @param string|null $status Filter by status
     * @return array
     */
    public function findByTenantId($tenantId, $status = null)
    {
        $query = "
            SELECT 
                b.*,
                k.name as kamar_name,
                k.price as kamar_price,
                ko.name as kost_name,
                ko.address as kost_address,
                ko.location as kost_location,
                o.name as owner_name,
                o.phone as owner_phone
            FROM {$this->table} b
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            INNER JOIN owners o ON ko.owner_id = o.id
            WHERE b.tenant_id = :tenant_id
        ";

        $params = ['tenant_id' => $tenantId];

        if ($status) {
            $query .= " AND b.status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY b.created_at DESC";

        return $this->fetchAll($query, $params);
    }

    /**
     * Get booking detail with full information
     * 
     * @param int $id
     * @return array|false
     */
    public function getDetail($id)
    {
        $query = "
            SELECT 
                b.*,
                k.name as kamar_name,
                k.price as kamar_price,
                k.facilities as kamar_facilities,
                k.description as kamar_description,
                ko.id as kost_id,
                ko.name as kost_name,
                ko.address as kost_address,
                ko.location as kost_location,
                ko.facilities as kost_facilities,
                ko.gender_type,
                o.name as owner_name,
                o.phone as owner_phone,
                o.address as owner_address,
                t.name as tenant_name,
                t.phone as tenant_phone,
                (SELECT photo_url FROM kost_photos WHERE kost_id = ko.id AND is_primary = 1 LIMIT 1) as kost_photo
            FROM {$this->table} b
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            INNER JOIN owners o ON ko.owner_id = o.id
            INNER JOIN tenants t ON b.tenant_id = t.id
            WHERE b.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $id]);
    }

    /**
     * Create new booking
     * 
     * @param array $data
     * @return int|false
     */
    public function createBooking($data)
    {
        return $this->create($data);
    }

    /**
     * Update booking status
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
     * Cancel booking
     * 
     * @param int $id
     * @return bool
     */
    public function cancelBooking($id)
    {
        return $this->update($id, ['status' => 'cancelled']);
    }

    /**
     * Check if kamar is available for booking
     * 
     * @param int $kamarId
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function isKamarAvailable($kamarId, $startDate, $endDate)
    {
        $query = "
            SELECT COUNT(*) as count
            FROM {$this->table}
            WHERE kamar_id = :kamar_id
            AND status IN ('waiting_payment', 'paid', 'accepted', 'active_rent')
            AND (
                (start_date BETWEEN :start_date AND :end_date)
                OR (end_date BETWEEN :start_date AND :end_date)
                OR (:start_date BETWEEN start_date AND end_date)
            )
        ";

        $result = $this->fetchOne($query, [
            'kamar_id' => $kamarId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        return ($result['count'] ?? 0) == 0;
    }

    /**
     * Get active booking for tenant
     * 
     * @param int $tenantId
     * @return array|false
     */
    public function getActiveBooking($tenantId)
    {
        return $this->findOne([
            'tenant_id' => $tenantId,
            'status' => 'active_rent'
        ]);
    }

    /**
     * Count bookings by status for tenant
     * 
     * @param int $tenantId
     * @param string $status
     * @return int
     */
    public function countByStatus($tenantId, $status)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE tenant_id = :tenant_id AND status = :status";
        
        $result = $this->fetchOne($query, [
            'tenant_id' => $tenantId,
            'status' => $status
        ]);

        return $result['count'] ?? 0;
    }

    /**
     * Get recent bookings for tenant
     * 
     * @param int $tenantId
     * @param int $limit
     * @return array
     */
    public function getRecentBookings($tenantId, $limit = 5)
    {
        $query = "
            SELECT 
                b.*,
                k.name as kamar_name,
                ko.name as kost_name,
                ko.location as kost_location
            FROM {$this->table} b
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            WHERE b.tenant_id = :tenant_id
            ORDER BY b.created_at DESC
            LIMIT :limit
        ";

        return $this->fetchAll($query, [
            'tenant_id' => $tenantId,
            'limit' => $limit
        ]);
    }
}
