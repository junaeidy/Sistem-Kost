-- ================================================================
-- SISTEM INFORMASI MANAJEMEN KOST - DATABASE SCHEMA
-- ================================================================
-- Author: Sistem Kost Development Team
-- Version: 1.0
-- Database: MySQL 5.7+
-- ================================================================

-- Create database
CREATE DATABASE IF NOT EXISTS kost_db;
USE kost_db;

-- ================================================================
-- TABLE: users
-- Description: Master table untuk semua user (admin, owner, tenant)
-- ================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'owner', 'tenant') NOT NULL,
    status ENUM('pending', 'active', 'rejected', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: owners
-- Description: Data detail untuk user dengan role owner
-- ================================================================
CREATE TABLE owners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    ktp_photo VARCHAR(255) DEFAULT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: tenants
-- Description: Data detail untuk user dengan role tenant/penyewa
-- ================================================================
CREATE TABLE tenants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: kost
-- Description: Data kost yang dikelola oleh owner
-- ================================================================
CREATE TABLE kost (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    address TEXT NOT NULL,
    location VARCHAR(255) DEFAULT NULL COMMENT 'Area/kelurahan (e.g., Medan Sunggal)',
    facilities TEXT DEFAULT NULL COMMENT 'JSON array of facilities',
    description TEXT DEFAULT NULL COMMENT 'HTML content from CKEditor',
    gender_type ENUM('putra', 'putri', 'campur') DEFAULT 'campur',
    status ENUM('active', 'inactive', 'pending') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES owners(id) ON DELETE CASCADE,
    INDEX idx_owner_id (owner_id),
    INDEX idx_location (location),
    INDEX idx_status (status),
    INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: kost_photos
-- Description: Multiple photos untuk setiap kost
-- ================================================================
CREATE TABLE kost_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kost_id INT NOT NULL,
    photo_url VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Foto utama/thumbnail',
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kost_id) REFERENCES kost(id) ON DELETE CASCADE,
    INDEX idx_kost_id (kost_id),
    INDEX idx_is_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: kamar
-- Description: Kamar-kamar dalam setiap kost
-- ================================================================
CREATE TABLE kamar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kost_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size DECIMAL(6, 2) DEFAULT NULL COMMENT 'Room size in square meters',
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    facilities TEXT DEFAULT NULL COMMENT 'JSON array of room-specific facilities',
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kost_id) REFERENCES kost(id) ON DELETE CASCADE,
    INDEX idx_kost_id (kost_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: bookings
-- Description: Booking/pemesanan kamar oleh tenant
-- ================================================================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id VARCHAR(20) NOT NULL UNIQUE COMMENT 'Human-readable booking code',
    tenant_id INT NOT NULL,
    kamar_id INT NOT NULL,
    start_date DATE NOT NULL,
    duration_months INT NOT NULL DEFAULT 1,
    end_date DATE NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('waiting_payment', 'paid', 'accepted', 'rejected', 'active_rent', 'completed', 'cancelled') DEFAULT 'waiting_payment',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    FOREIGN KEY (kamar_id) REFERENCES kamar(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_kamar_id (kamar_id),
    INDEX idx_status (status),
    INDEX idx_start_date (start_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: payments
-- Description: Data pembayaran via Midtrans
-- ================================================================
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    midtrans_order_id VARCHAR(255) NOT NULL UNIQUE,
    midtrans_transaction_id VARCHAR(255) DEFAULT NULL,
    payment_type VARCHAR(50) DEFAULT NULL COMMENT 'e.g., qris, bank_transfer, credit_card',
    payment_status ENUM('pending', 'paid', 'failed', 'expired', 'cancelled') DEFAULT 'pending',
    payment_url TEXT DEFAULT NULL,
    snap_token VARCHAR(255) DEFAULT NULL,
    paid_at TIMESTAMP NULL DEFAULT NULL,
    expires_at DATETIME NULL DEFAULT NULL,
    midtrans_response TEXT DEFAULT NULL COMMENT 'JSON response from Midtrans',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    INDEX idx_booking_id (booking_id),
    INDEX idx_midtrans_order_id (midtrans_order_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: notifications (Optional - untuk fitur notifikasi)
-- ================================================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: reviews (Optional - untuk review kost)
-- ================================================================
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kost_id INT NOT NULL,
    tenant_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kost_id) REFERENCES kost(id) ON DELETE CASCADE,
    FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE,
    INDEX idx_kost_id (kost_id),
    INDEX idx_tenant_id (tenant_id),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- TABLE: activity_logs (Optional - untuk audit trail)
-- ================================================================
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL COMMENT 'e.g., kost, booking, payment',
    entity_id INT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ================================================================
-- END OF SCHEMA
-- ================================================================
