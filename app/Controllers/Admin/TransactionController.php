<?php

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Database;

/**
 * Admin Transaction Controller
 * Handles transaction/payment monitoring
 */
class TransactionController extends Controller
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Generate transaction ID
     */
    private function generateTransactionId($paymentId)
    {
        // Format: 2 huruf + 6 angka (contoh: TR123456)
        return 'TR' . str_pad($paymentId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Show all transactions
     */
    public function index()
    {
        $status = $this->get('status', 'all');
        $page = max(1, (int) $this->get('page', 1));
        $perPage = max(1, (int) (config('pagination.per_page') ?: 10)); // Fallback to 10 if not set
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT 
                    p.*,
                    b.start_date,
                    b.end_date,
                    k.name as kost_name,
                    ka.name as kamar_name,
                    t.name as tenant_name,
                    u.email as tenant_email
                  FROM payments p
                  LEFT JOIN bookings b ON p.booking_id = b.id
                  LEFT JOIN kamar ka ON b.kamar_id = ka.id
                  LEFT JOIN kost k ON ka.kost_id = k.id
                  LEFT JOIN tenants t ON b.tenant_id = t.id
                  LEFT JOIN users u ON t.user_id = u.id";
        
        $params = [];
        $whereClause = '';
        
        if ($status !== 'all') {
            $whereClause = " WHERE p.payment_status = :payment_status";
            $params['payment_status'] = $status;
        }
        
        // Count total
        $countQuery = "SELECT COUNT(*) as total FROM payments p" . $whereClause;
        $totalResult = $this->db->fetchOne($countQuery, $params);
        $total = (int) ($totalResult['total'] ?? 0);
        
        // Get paginated results
        $query .= $whereClause . " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;

        $transactions = $this->db->fetchAll($query, $params);
        
        // Add transaction_id to each transaction
        foreach ($transactions as &$transaction) {
            $transaction['transaction_id'] = $this->generateTransactionId($transaction['id']);
        }
        
        // Calculate pagination
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;

        // Calculate statistics
        $statsQuery = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as success,
                        SUM(CASE WHEN payment_status = 'failed' THEN 1 ELSE 0 END) as failed,
                        SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as total_revenue
                       FROM payments";
        $stats = $this->db->fetchOne($statsQuery);

        $this->view('admin/transactions/index', [
            'title' => 'Kelola Transaksi',
            'pageTitle' => 'Kelola Transaksi',
            'transactions' => $transactions,
            'currentStatus' => $status,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ], 'layouts/dashboard');
    }

    /**
     * Show transaction detail
     */
    public function show($id)
    {
        $query = "SELECT 
                    p.*,
                    b.start_date,
                    b.end_date,
                    b.duration_months,
                    b.total_price as booking_price,
                    k.name as kost_name,
                    k.address as kost_address,
                    ka.name as kamar_name,
                    t.name as tenant_name,
                    t.phone as tenant_phone,
                    u.email as tenant_email,
                    o.name as owner_name,
                    ou.email as owner_email
                  FROM payments p
                  LEFT JOIN bookings b ON p.booking_id = b.id
                  LEFT JOIN kamar ka ON b.kamar_id = ka.id
                  LEFT JOIN kost k ON ka.kost_id = k.id
                  LEFT JOIN tenants t ON b.tenant_id = t.id
                  LEFT JOIN users u ON t.user_id = u.id
                  LEFT JOIN owners o ON k.owner_id = o.id
                  LEFT JOIN users ou ON o.user_id = ou.id
                  WHERE p.id = :id";
        
        $transaction = $this->db->fetchOne($query, ['id' => $id]);

        if (!$transaction) {
            $this->flash('error', 'Transaksi tidak ditemukan.');
            $this->redirect(url('/admin/transactions'));
            return;
        }
        
        // Add transaction_id
        $transaction['transaction_id'] = $this->generateTransactionId($transaction['id']);

        $this->view('admin/transactions/show', [
            'title' => 'Detail Transaksi',
            'pageTitle' => 'Detail Transaksi',
            'transaction' => $transaction
        ], 'layouts/dashboard');
    }
}
