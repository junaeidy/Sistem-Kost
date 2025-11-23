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
     * @param int $limit Limit number of results
     * @param int $offset Offset for pagination
     * @return array
     */
    public function findByTenantId($tenantId, $status = null, $limit = null, $offset = 0)
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

        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params['limit'] = $limit;
            $params['offset'] = $offset;
        }

        return $this->fetchAll($query, $params);
    }

    /**
     * Count bookings by tenant ID
     * 
     * @param int $tenantId
     * @param string|null $status Filter by status
     * @return int
     */
    public function countByTenantId($tenantId, $status = null)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE tenant_id = :tenant_id";
        $params = ['tenant_id' => $tenantId];

        if ($status) {
            $query .= " AND status = :status";
            $params['status'] = $status;
        }

        $result = $this->fetchOne($query, $params);
        return (int) ($result['total'] ?? 0);
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
        // Validate required fields
        $required = ['tenant_id', 'kamar_id', 'start_date', 'duration_months', 'end_date', 'total_price'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                error_log("Booking creation failed: Missing field $field");
                return false;
            }
        }
        
        // Generate unique booking_id
        if (!isset($data['booking_id'])) {
            $data['booking_id'] = generateUniqueBookingId();
        }
        
        // Ensure status is set
        if (!isset($data['status'])) {
            $data['status'] = 'waiting_payment';
        }
        
        // Log the data being inserted
        error_log("Creating booking with data: " . json_encode($data));
        
        $result = $this->create($data);
        
        if ($result) {
            error_log("Booking created successfully with ID: $result, Booking Code: " . $data['booking_id']);
        } else {
            error_log("Failed to insert booking into database");
        }
        
        return $result;
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
                (start_date BETWEEN :start_date1 AND :end_date1)
                OR (end_date BETWEEN :start_date2 AND :end_date2)
                OR (:start_date3 BETWEEN start_date AND end_date)
            )
        ";

        $result = $this->fetchOne($query, [
            'kamar_id' => $kamarId,
            'start_date1' => $startDate,
            'end_date1' => $endDate,
            'start_date2' => $startDate,
            'end_date2' => $endDate,
            'start_date3' => $startDate
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
        // Cast to int for security
        $limit = (int) $limit;
        
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
            LIMIT {$limit}
        ";

        return $this->fetchAll($query, [
            'tenant_id' => $tenantId
        ]);
    }
    
    /**
     * Check if room has active booking
     * 
     * @param int $kamarId
     * @return bool
     */
    public function hasActiveBookingForRoom($kamarId)
    {
        $query = "SELECT COUNT(*) as count FROM {$this->table} 
                  WHERE kamar_id = :kamar_id 
                  AND status IN ('waiting_payment', 'paid', 'accepted', 'active_rent')
                  LIMIT 1";
        
        $result = $this->fetchOne($query, ['kamar_id' => $kamarId]);
        return ($result['count'] ?? 0) > 0;
    }
}
