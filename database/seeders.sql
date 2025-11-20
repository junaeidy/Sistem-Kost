-- ================================================================
-- SISTEM INFORMASI MANAJEMEN KOST - SAMPLE DATA SEEDERS
-- ================================================================
-- Author: Sistem Kost Development Team
-- Version: 1.0
-- Description: Sample data untuk testing dan development
-- ================================================================

USE kost_db;

-- ================================================================
-- SEEDER: Default Admin Account
-- ================================================================
-- Password: admin123 (hashed dengan bcrypt)
INSERT INTO users (email, password, role, status) VALUES
('admin@kost.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- ================================================================
-- SEEDER: Sample Owner Accounts
-- ================================================================
-- Password untuk semua: password123
INSERT INTO users (email, password, role, status) VALUES
('owner1@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 'active'),
('owner2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 'pending'),
('owner3@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'owner', 'active');

-- Owner details
INSERT INTO owners (user_id, name, phone, address) VALUES
(2, 'Budi Santoso', '081234567890', 'Jl. Gatot Subroto No. 123, Medan'),
(3, 'Siti Nurhaliza', '081234567891', 'Jl. Sisingamangaraja No. 45, Medan'),
(4, 'Ahmad Dahlan', '081234567892', 'Jl. Asia No. 67, Medan');

-- ================================================================
-- SEEDER: Sample Tenant Accounts
-- ================================================================
-- Password untuk semua: password123
INSERT INTO users (email, password, role, status) VALUES
('tenant1@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tenant', 'active'),
('tenant2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tenant', 'active');

-- Tenant details
INSERT INTO tenants (user_id, name, phone, address) VALUES
(5, 'Rina Wijaya', '082345678901', 'Medan'),
(6, 'Doni Prasetyo', '082345678902', 'Medan');

-- ================================================================
-- SEEDER: Sample Kost Data
-- ================================================================
INSERT INTO kost (owner_id, name, price, address, location, facilities, description, gender_type, status) VALUES
(1, 'Kost Putri Melati', 800000.00, 'Jl. Setia Budi No. 10, Medan Sunggal', 'Medan Sunggal', 
'["WiFi", "AC", "Kamar Mandi Dalam", "Kasur", "Lemari", "Dapur Bersama"]',
'<h3>Kost Putri Melati</h3><p>Kost nyaman khusus putri dengan fasilitas lengkap di lokasi strategis Medan Sunggal.</p><ul><li>Dekat kampus</li><li>Dekat supermarket</li><li>Aman dan nyaman</li></ul>',
'putri', 'active'),

(1, 'Kost Putra Mandiri', 750000.00, 'Jl. Karya No. 25, Medan Sunggal', 'Medan Sunggal',
'["WiFi", "Kipas Angin", "Kamar Mandi Luar", "Kasur", "Lemari"]',
'<h3>Kost Putra Mandiri</h3><p>Kost khusus putra dengan harga terjangkau dan lokasi strategis.</p>',
'putra', 'active'),

(3, 'Kost Campur Sejahtera', 900000.00, 'Jl. Flamboyan No. 15, Medan Sunggal', 'Medan Sunggal',
'["WiFi", "AC", "Kamar Mandi Dalam", "Kasur", "Lemari", "Parkir Motor", "Laundry"]',
'<h3>Kost Campur Sejahtera</h3><p>Kost campur dengan fasilitas premium dan keamanan 24 jam.</p>',
'campur', 'active');

-- ================================================================
-- SEEDER: Sample Photos untuk Kost
-- ================================================================
INSERT INTO kost_photos (kost_id, photo_url, is_primary, display_order) VALUES
(1, 'kost/kost1_1.jpg', TRUE, 1),
(1, 'kost/kost1_2.jpg', FALSE, 2),
(1, 'kost/kost1_3.jpg', FALSE, 3),
(2, 'kost/kost2_1.jpg', TRUE, 1),
(2, 'kost/kost2_2.jpg', FALSE, 2),
(3, 'kost/kost3_1.jpg', TRUE, 1),
(3, 'kost/kost3_2.jpg', FALSE, 2);

-- ================================================================
-- SEEDER: Sample Kamar Data
-- ================================================================
INSERT INTO kamar (kost_id, name, price, status, description) VALUES
-- Kamar untuk Kost 1 (Putri Melati)
(1, 'Kamar 101', 800000.00, 'available', 'Kamar lantai 1 dengan AC dan kamar mandi dalam'),
(1, 'Kamar 102', 800000.00, 'occupied', 'Kamar lantai 1 dengan AC dan kamar mandi dalam'),
(1, 'Kamar 201', 850000.00, 'available', 'Kamar lantai 2 dengan balkon'),
(1, 'Kamar 202', 850000.00, 'available', 'Kamar lantai 2 dengan balkon'),

-- Kamar untuk Kost 2 (Putra Mandiri)
(2, 'Kamar A1', 750000.00, 'available', 'Kamar standar dengan kipas angin'),
(2, 'Kamar A2', 750000.00, 'available', 'Kamar standar dengan kipas angin'),
(2, 'Kamar B1', 700000.00, 'occupied', 'Kamar ekonomis'),

-- Kamar untuk Kost 3 (Campur Sejahtera)
(3, 'Kamar Premium 1', 900000.00, 'available', 'Kamar premium dengan AC dan kamar mandi dalam'),
(3, 'Kamar Premium 2', 900000.00, 'available', 'Kamar premium dengan AC dan kamar mandi dalam'),
(3, 'Kamar Standard 1', 850000.00, 'available', 'Kamar standard dengan fasilitas lengkap');

-- ================================================================
-- SEEDER: Sample Bookings
-- ================================================================
INSERT INTO bookings (tenant_id, kamar_id, start_date, duration_months, end_date, total_price, status) VALUES
(1, 2, '2025-01-01', 6, '2025-06-30', 4800000.00, 'active_rent'),
(2, 7, '2025-02-01', 3, '2025-04-30', 2100000.00, 'active_rent');

-- ================================================================
-- SEEDER: Sample Payments
-- ================================================================
INSERT INTO payments (booking_id, amount, midtrans_order_id, payment_type, payment_status, paid_at) VALUES
(1, 4800000.00, 'ORDER-1735561200-001', 'bank_transfer', 'paid', '2024-12-30 10:30:00'),
(2, 2100000.00, 'ORDER-1735561200-002', 'qris', 'paid', '2025-01-15 14:20:00');

-- ================================================================
-- SEEDER: Sample Reviews (Optional)
-- ================================================================
INSERT INTO reviews (kost_id, tenant_id, rating, review_text) VALUES
(1, 1, 5, 'Kost sangat nyaman dan bersih. Pemilik ramah dan responsif. Highly recommended!'),
(2, 2, 4, 'Kost bagus dengan harga terjangkau. Lokasi strategis dekat kampus.');

-- ================================================================
-- SEEDER: Sample Notifications
-- ================================================================
INSERT INTO notifications (user_id, title, message, type, is_read) VALUES
(2, 'Booking Baru', 'Ada booking baru untuk kamar di Kost Putri Melati', 'info', TRUE),
(4, 'Booking Baru', 'Ada booking baru untuk kamar di Kost Campur Sejahtera', 'info', FALSE),
(5, 'Pembayaran Berhasil', 'Pembayaran Anda telah berhasil diproses', 'success', TRUE),
(6, 'Booking Diterima', 'Booking Anda telah diterima oleh pemilik kost', 'success', TRUE);

-- ================================================================
-- DISPLAY SUMMARY
-- ================================================================
SELECT 'SEEDING COMPLETED!' as Status;
SELECT COUNT(*) as Total_Users FROM users;
SELECT COUNT(*) as Total_Owners FROM owners;
SELECT COUNT(*) as Total_Tenants FROM tenants;
SELECT COUNT(*) as Total_Kost FROM kost;
SELECT COUNT(*) as Total_Kamar FROM kamar;
SELECT COUNT(*) as Total_Bookings FROM bookings;
SELECT COUNT(*) as Total_Payments FROM payments;

-- ================================================================
-- END OF SEEDERS
-- ================================================================
