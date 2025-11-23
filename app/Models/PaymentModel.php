<?php

namespace App\Models;

use Core\Model;

/**
 * Payment Model
 * Handles payment transaction data with Midtrans integration
 */
class PaymentModel extends Model
{
    protected $table = 'payments';

    /**
     * Create new payment record
     * 
     * @param array $data Payment data
     * @return int|false Payment ID or false on failure
     */
    public function createPayment($data)
    {
        $requiredFields = ['booking_id', 'amount', 'midtrans_order_id'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                return false;
            }
        }

        return $this->create($data);
    }

    /**
     * Find payment by order ID
     * 
     * @param string $orderId Midtrans order ID
     * @return array|false Payment data or false if not found
     */
    public function findByOrderId($orderId)
    {
        $query = "SELECT * FROM {$this->table} WHERE midtrans_order_id = :order_id LIMIT 1";
        return $this->fetchOne($query, ['order_id' => $orderId]);
    }

    /**
     * Find payment by booking ID
     * 
     * @param int $bookingId Booking ID
     * @return array|false Payment data or false if not found
     */
    public function findByBookingId($bookingId)
    {
        $query = "SELECT * FROM {$this->table} WHERE booking_id = :booking_id LIMIT 1";
        return $this->fetchOne($query, ['booking_id' => $bookingId]);
    }

    /**
     * Get payment detail with booking information
     * 
     * @param int $id Payment ID
     * @return array|false
     */
    public function getDetail($id)
    {
        $query = "
            SELECT 
                p.*,
                b.tenant_id,
                b.kamar_id,
                b.start_date,
                b.duration_months,
                b.end_date,
                b.total_price as booking_total,
                b.status as booking_status,
                k.name as kamar_name,
                ko.id as kost_id,
                ko.name as kost_name,
                ko.address as kost_address,
                t.name as tenant_name,
                t.phone as tenant_phone,
                u.email as tenant_email,
                o.name as owner_name,
                o.phone as owner_phone,
                o.address as owner_address
            FROM {$this->table} p
            INNER JOIN bookings b ON p.booking_id = b.id
            INNER JOIN tenants t ON b.tenant_id = t.id
            INNER JOIN users u ON t.user_id = u.id
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            INNER JOIN owners o ON ko.owner_id = o.id
            WHERE p.id = :id
            LIMIT 1
        ";

        return $this->fetchOne($query, ['id' => $id]);
    }

    /**
     * Update payment status
     * 
     * @param string $orderId Midtrans order ID
     * @param string $status Payment status
     * @param array $additionalData Additional data to update
     * @return bool
     */
    public function updateStatus($orderId, $status, $additionalData = [])
    {
        $data = array_merge([
            'payment_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ], $additionalData);

        $setClause = [];
        $params = ['order_id' => $orderId];

        foreach ($data as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE midtrans_order_id = :order_id";
        
        $stmt = $this->query($query, $params);
        return $stmt !== false;
    }

    /**
     * Update snap token
     * 
     * @param int $id Payment ID
     * @param string $snapToken Snap token from Midtrans
     * @return bool
     */
    public function updateSnapToken($id, $snapToken)
    {
        $query = "UPDATE {$this->table} SET snap_token = :snap_token WHERE id = :id";
        $stmt = $this->query($query, [
            'id' => $id,
            'snap_token' => $snapToken
        ]);
        return $stmt !== false;
    }

    /**
     * Update Midtrans response
     * 
     * @param string $orderId Midtrans order ID
     * @param array $response Response from Midtrans
     * @return bool
     */
    public function updateMidtransResponse($orderId, $response)
    {
        $query = "UPDATE {$this->table} SET midtrans_response = :response WHERE midtrans_order_id = :order_id";
        $stmt = $this->query($query, [
            'order_id' => $orderId,
            'response' => json_encode($response)
        ]);
        return $stmt !== false;
    }

    /**
     * Get all payments with filters
     * 
     * @param array $filters Filter criteria
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getAll($filters = [], $limit = 10, $offset = 0)
    {
        $query = "
            SELECT 
                p.*,
                b.tenant_id,
                t.name as tenant_name,
                ko.name as kost_name
            FROM {$this->table} p
            INNER JOIN bookings b ON p.booking_id = b.id
            INNER JOIN tenants t ON b.tenant_id = t.id
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            WHERE 1=1
        ";

        $params = [];

        // Filter by status
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query .= " AND p.payment_status = :status";
            $params['status'] = $filters['status'];
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $query .= " AND DATE(p.created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $query .= " AND DATE(p.created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        // Search by order ID or tenant name
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query .= " AND (p.midtrans_order_id LIKE :search OR t.name LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY p.created_at DESC";
        $query .= " LIMIT :limit OFFSET :offset";
        
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->fetchAll($query, $params);
    }

    /**
     * Count total payments with filters
     * 
     * @param array $filters Filter criteria
     * @return int
     */
    public function countAll($filters = [])
    {
        $query = "
            SELECT COUNT(*) as total
            FROM {$this->table} p
            INNER JOIN bookings b ON p.booking_id = b.id
            INNER JOIN tenants t ON b.tenant_id = t.id
            WHERE 1=1
        ";

        $params = [];

        // Apply same filters as getAll
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query .= " AND p.payment_status = :status";
            $params['status'] = $filters['status'];
        }

        if (isset($filters['date_from'])) {
            $query .= " AND DATE(p.created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }

        if (isset($filters['date_to'])) {
            $query .= " AND DATE(p.created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query .= " AND (p.midtrans_order_id LIKE :search OR t.name LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }

        $result = $this->fetchOne($query, $params);
        return $result ? (int)$result['total'] : 0;
    }

    /**
     * Get payments by tenant ID
     * 
     * @param int $tenantId Tenant ID
     * @param string|null $status Filter by status
     * @return array
     */
    public function getByTenantId($tenantId, $status = null)
    {
        $query = "
            SELECT 
                p.*,
                b.kamar_id,
                b.start_date,
                b.duration_months,
                k.name as kamar_name,
                ko.name as kost_name
            FROM {$this->table} p
            INNER JOIN bookings b ON p.booking_id = b.id
            INNER JOIN kamar k ON b.kamar_id = k.id
            INNER JOIN kost ko ON k.kost_id = ko.id
            WHERE b.tenant_id = :tenant_id
        ";

        $params = ['tenant_id' => $tenantId];

        if ($status) {
            $query .= " AND p.payment_status = :status";
            $params['status'] = $status;
        }

        $query .= " ORDER BY p.created_at DESC";

        return $this->fetchAll($query, $params);
    }

    /**
     * Generate unique order ID
     * 
     * @param int $bookingId Booking ID
     * @return string
     */
    public function generateOrderId($bookingId)
    {
        return 'KOST-' . date('YmdHis') . '-' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if payment exists for booking
     * 
     * @param int $bookingId Booking ID
     * @return bool
     */
    public function existsForBooking($bookingId)
    {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE booking_id = :booking_id";
        $result = $this->fetchOne($query, ['booking_id' => $bookingId]);
        return $result && $result['total'] > 0;
    }

    /**
     * Get payment statistics
     * 
     * @return array
     */
    public function getStatistics()
    {
        $query = "
            SELECT 
                COUNT(*) as total_payments,
                SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_count,
                SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN payment_status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as total_revenue,
                SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END) as pending_amount
            FROM {$this->table}
        ";

        return $this->fetchOne($query);
    }
}
