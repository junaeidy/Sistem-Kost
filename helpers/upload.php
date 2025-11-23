<?php
/**
 * File Upload Helper
 * 
 * Provides functions for file upload, validation, deletion, and filename generation
 * 
 * @author Sistem Kost Development Team
 * @version 1.0
 */

/**
 * Upload a single file
 * 
 * @param array $file The file from $_FILES
 * @param string $destination Relative path from public/ (e.g., 'uploads/kost/')
 * @param array $allowedTypes Allowed MIME types (e.g., ['image/jpeg', 'image/png'])
 * @param int $maxSize Max file size in KB (default: 2048 = 2MB)
 * @return array ['success' => bool, 'message' => string, 'file_path' => string|null]
 */
function uploadFile($file, $destination, $allowedTypes = [], $maxSize = 2048)
{
    // Default allowed types if not specified
    if (empty($allowedTypes)) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    }

    // Validate file
    $validation = validateFile($file, $allowedTypes, $maxSize);
    if (!$validation['valid']) {
        return [
            'success' => false,
            'message' => $validation['message'],
            'file_path' => null
        ];
    }

    // Prepare destination directory
    $uploadDir = __DIR__ . '/../public/' . rtrim($destination, '/') . '/';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return [
                'success' => false,
                'message' => 'Gagal membuat direktori upload',
                'file_path' => null
            ];
        }
    }

    // Generate unique filename
    $fileName = generateFileName($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Return relative path (without 'public/')
        $relativePath = rtrim($destination, '/') . '/' . $fileName;
        
        return [
            'success' => true,
            'message' => 'File berhasil diupload',
            'file_path' => $relativePath
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Gagal mengupload file',
            'file_path' => null
        ];
    }
}

/**
 * Upload multiple files
 * 
 * @param array $files Array of files from $_FILES
 * @param string $destination Relative path from public/
 * @param array $allowedTypes Allowed MIME types
 * @param int $maxSize Max file size in KB
 * @return array ['success' => bool, 'uploaded' => array, 'failed' => array]
 */
function uploadMultiple($files, $destination, $allowedTypes = [], $maxSize = 2048)
{
    $uploaded = [];
    $failed = [];

    // Handle both standard and multiple file upload formats
    if (isset($files['name'])) {
        if (is_array($files['name'])) {
            // Multiple files uploaded with same field name
            $fileCount = count($files['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];

                $result = uploadFile($file, $destination, $allowedTypes, $maxSize);
                
                if ($result['success']) {
                    $uploaded[] = $result['file_path'];
                } else {
                    $failed[] = [
                        'file' => $file['name'],
                        'message' => $result['message']
                    ];
                }
            }
        } else {
            // Single file
            $result = uploadFile($files, $destination, $allowedTypes, $maxSize);
            if ($result['success']) {
                $uploaded[] = $result['file_path'];
            } else {
                $failed[] = [
                    'file' => $files['name'],
                    'message' => $result['message']
                ];
            }
        }
    }

    return [
        'success' => count($uploaded) > 0,
        'uploaded' => $uploaded,
        'failed' => $failed
    ];
}

/**
 * Delete a file
 * 
 * @param string $filePath Relative path from public/ (e.g., 'uploads/kost/photo.jpg')
 * @return bool True if deleted or doesn't exist, false on error
 */
function deleteFile($filePath)
{
    if (empty($filePath)) {
        return true; // No file to delete
    }

    $fullPath = __DIR__ . '/../public/' . ltrim($filePath, '/');
    
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    
    return true; // File doesn't exist, consider it deleted
}

/**
 * Generate unique filename
 * 
 * @param string $originalName Original filename
 * @return string Unique filename with original extension
 */
function generateFileName($originalName)
{
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $timestamp = time();
    $random = bin2hex(random_bytes(8));
    
    return $timestamp . '_' . $random . '.' . strtolower($extension);
}

/**
 * Validate uploaded file
 * 
 * @param array $file The file from $_FILES
 * @param array $allowedTypes Allowed MIME types
 * @param int $maxSize Max file size in KB
 * @return array ['valid' => bool, 'message' => string]
 */
function validateFile($file, $allowedTypes, $maxSize)
{
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['valid' => false, 'message' => 'File tidak ditemukan'];
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
        ];
        
        $message = $errorMessages[$file['error']] ?? 'Error tidak diketahui';
        return ['valid' => false, 'message' => $message];
    }

    // Check file size
    $fileSizeKB = $file['size'] / 1024;
    if ($fileSizeKB > $maxSize) {
        return [
            'valid' => false, 
            'message' => "File terlalu besar. Maksimal {$maxSize}KB (" . round($maxSize/1024, 2) . "MB)"
        ];
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        return [
            'valid' => false,
            'message' => 'Tipe file tidak diizinkan. Yang diizinkan: ' . implode(', ', $allowedTypes)
        ];
    }

    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = [];
    foreach ($allowedTypes as $type) {
        switch ($type) {
            case 'image/jpeg':
                $allowedExtensions[] = 'jpg';
                $allowedExtensions[] = 'jpeg';
                break;
            case 'image/png':
                $allowedExtensions[] = 'png';
                break;
            case 'image/gif':
                $allowedExtensions[] = 'gif';
                break;
            case 'image/webp':
                $allowedExtensions[] = 'webp';
                break;
            case 'application/pdf':
                $allowedExtensions[] = 'pdf';
                break;
        }
    }

    if (!in_array($extension, $allowedExtensions)) {
        return [
            'valid' => false,
            'message' => 'Ekstensi file tidak valid. Yang diizinkan: ' . implode(', ', $allowedExtensions)
        ];
    }

    // Sanitize filename to prevent path traversal
    $filename = basename($file['name']);
    if ($filename !== $file['name']) {
        return [
            'valid' => false,
            'message' => 'Nama file tidak valid'
        ];
    }

    return ['valid' => true, 'message' => 'Valid'];
}

/**
 * Resize image to specified dimensions
 * 
 * @param string $sourcePath Full path to source image
 * @param string $destPath Full path to destination
 * @param int $maxWidth Maximum width
 * @param int $maxHeight Maximum height
 * @param bool $crop Whether to crop or resize proportionally
 * @return bool Success status
 */
function resizeImage($sourcePath, $destPath, $maxWidth = 800, $maxHeight = 800, $crop = false)
{
    if (!file_exists($sourcePath)) {
        return false;
    }

    // Get image info
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) {
        return false;
    }

    list($width, $height, $type) = $imageInfo;

    // Create image resource from source
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    if (!$source) {
        return false;
    }

    // Calculate new dimensions
    if ($crop) {
        $newWidth = $maxWidth;
        $newHeight = $maxHeight;
    } else {
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
    }

    // Create new image
    $dest = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG and GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        $transparent = imagecolorallocatealpha($dest, 255, 255, 255, 127);
        imagefilledrectangle($dest, 0, 0, $newWidth, $newHeight, $transparent);
    }

    // Resize
    imagecopyresampled($dest, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save image
    $result = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $result = imagejpeg($dest, $destPath, 90);
            break;
        case IMAGETYPE_PNG:
            $result = imagepng($dest, $destPath, 9);
            break;
        case IMAGETYPE_GIF:
            $result = imagegif($dest, $destPath);
            break;
        case IMAGETYPE_WEBP:
            $result = imagewebp($dest, $destPath, 90);
            break;
    }

    // Free memory
    imagedestroy($source);
    imagedestroy($dest);

    return $result;
}

/**
 * Get allowed image MIME types
 * 
 * @return array
 */
function getAllowedImageTypes()
{
    return ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
}

/**
 * Get allowed document MIME types
 * 
 * @return array
 */
function getAllowedDocumentTypes()
{
    return ['application/pdf'];
}
