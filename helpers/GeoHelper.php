<?php

/**
 * GeoHelper
 * Helper class untuk Geographic Information System (GIS)
 * Menyediakan fungsi utilities untuk map dan koordinat
 */

class GeoHelper
{
    /**
     * Validasi koordinat latitude dan longitude
     * 
     * @param float $latitude
     * @param float $longitude
     * @return bool
     */
    public static function validateCoordinates($latitude, $longitude)
    {
        return (
            is_numeric($latitude) && 
            is_numeric($longitude) &&
            $latitude >= -90 && $latitude <= 90 &&
            $longitude >= -180 && $longitude <= 180
        );
    }

    /**
     * Hitung jarak antara dua koordinat menggunakan Haversine formula
     * 
     * @param float $lat1 Latitude point 1
     * @param float $lon1 Longitude point 1
     * @param float $lat2 Latitude point 2
     * @param float $lon2 Longitude point 2
     * @return float Jarak dalam kilometer
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius bumi dalam kilometer
        $earthRadius = 6371;

        // Konversi derajat ke radian
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        // Haversine formula
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Format jarak untuk tampilan
     * 
     * @param float $distanceKm Jarak dalam kilometer
     * @return string Jarak terformat (e.g., "1.5 km" atau "500 m")
     */
    public static function formatDistance($distanceKm)
    {
        if ($distanceKm < 1) {
            return round($distanceKm * 1000) . ' m';
        }
        
        return round($distanceKm, 1) . ' km';
    }

    /**
     * Get default center coordinates untuk Medan Sunggal
     * 
     * @return array ['lat' => float, 'lon' => float]
     */
    public static function getDefaultCenter()
    {
        return [
            'lat' => 3.5952,
            'lon' => 98.6722,
            'zoom' => 13
        ];
    }

    /**
     * Generate Google Maps URL untuk directions
     * 
     * @param float $latitude
     * @param float $longitude
     * @param string $label Label untuk marker
     * @return string
     */
    public static function getGoogleMapsUrl($latitude, $longitude, $label = '')
    {
        $label = urlencode($label);
        return "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";
    }

    /**
     * Generate OpenStreetMap URL
     * 
     * @param float $latitude
     * @param float $longitude
     * @param int $zoom Zoom level (1-19)
     * @return string
     */
    public static function getOSMUrl($latitude, $longitude, $zoom = 16)
    {
        return "https://www.openstreetmap.org/?mlat={$latitude}&mlon={$longitude}#map={$zoom}/{$latitude}/{$longitude}";
    }

    /**
     * Format koordinat untuk display
     * 
     * @param float $latitude
     * @param float $longitude
     * @param int $precision
     * @return string
     */
    public static function formatCoordinates($latitude, $longitude, $precision = 6)
    {
        return number_format($latitude, $precision) . ', ' . number_format($longitude, $precision);
    }
}
