-- ================================================================
-- MIGRATION: Add Latitude & Longitude columns to kost table
-- ================================================================
-- Description: Menambahkan kolom koordinat untuk fitur GIS/Map
-- Date: 2025-11-23
-- ================================================================

USE kost_db;

-- Tambahkan kolom latitude dan longitude
ALTER TABLE kost 
ADD COLUMN latitude DECIMAL(10, 8) DEFAULT NULL COMMENT 'Koordinat latitude untuk GIS map' AFTER location,
ADD COLUMN longitude DECIMAL(11, 8) DEFAULT NULL COMMENT 'Koordinat longitude untuk GIS map' AFTER latitude,
ADD INDEX idx_coordinates (latitude, longitude);

-- ================================================================
-- Notes:
-- - DECIMAL(10, 8) untuk latitude: range -90.00000000 to 90.00000000
-- - DECIMAL(11, 8) untuk longitude: range -180.00000000 to 180.00000000
-- - Index ditambahkan untuk optimasi query berbasis lokasi
-- - Owner akan set koordinat via map picker di form create/edit kost
-- ================================================================
