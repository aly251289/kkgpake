<?php
/**
 * TinyMCE Image Upload Handler
 * Handles image uploads from TinyMCE editor
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set header for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Set header for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Allowed file extensions
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$max_file_size = 5 * 1024 * 1024; // 5MB

// Upload directory (relative to admin folder)
$upload_dir = '../assets/img/content/';

// Create directory if not exists
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check for file in different possible keys (TinyMCE uses 'file', but could be others)
$file_key = null;
$possible_keys = ['file', 'image', 'upload'];

foreach ($possible_keys as $key) {
    if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
        $file_key = $key;
        break;
    }
}

// If still no file found, check all FILES
if ($file_key === null && !empty($_FILES)) {
    foreach ($_FILES as $key => $file_data) {
        if (isset($file_data['error']) && $file_data['error'] === UPLOAD_ERR_OK) {
            $file_key = $key;
            break;
        }
    }
}



// Check if file was uploaded
if ($file_key === null) {
    $error_message = 'No file uploaded or upload error.';

    // Try to get more specific error if available
    if (!empty($_FILES)) {
        $first_file = reset($_FILES);
        if (isset($first_file['error'])) {
            switch ($first_file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = 'File size exceeds the allowed limit.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = 'File was only partially uploaded.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message = 'No file was uploaded.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = 'Missing temporary folder.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = 'Failed to write file to disk.';
                    break;
            }
        }
    }



    http_response_code(400);
    echo json_encode(['error' => $error_message]);
    exit;
}

$file = $_FILES[$file_key];
$filename = $file['name'];
$filesize = $file['size'];
$tmp_name = $file['tmp_name'];



// Get file extension
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

// Validate file extension
if (!in_array($ext, $allowed_extensions)) {


    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_extensions)]);
    exit;
}

// Validate file size
if ($filesize > $max_file_size) {


    http_response_code(400);
    echo json_encode(['error' => 'File size exceeds 5MB limit.']);
    exit;
}

// Validate MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $tmp_name);
finfo_close($finfo);

$allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($mime_type, $allowed_mimes)) {


    http_response_code(400);
    echo json_encode(['error' => 'Invalid file MIME type.']);
    exit;
}

// Generate unique filename
$new_filename = 'content_' . uniqid() . '_' . time() . '.' . $ext;
$target_path = $upload_dir . $new_filename;



// Move uploaded file
if (move_uploaded_file($tmp_name, $target_path)) {
    // Auto resize if image is too large (optional)
    $image_info = getimagesize($target_path);
    if ($image_info) {
        $width = $image_info[0];
        $max_width = 1200;

        if ($width > $max_width) {
            $height = $image_info[1];
            $ratio = $max_width / $width;
            $new_width = $max_width;
            $new_height = intval($height * $ratio);

            $src = imagecreatefromstring(file_get_contents($target_path));
            if ($src) {
                $dst = imagecreatetruecolor($new_width, $new_height);

                // Preserve transparency for PNG and GIF
                if ($ext === 'png' || $ext === 'gif') {
                    imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                // Save resized image
                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($dst, $target_path, 85);
                        break;
                    case 'png':
                        imagepng($dst, $target_path, 8);
                        break;
                    case 'gif':
                        imagegif($dst, $target_path);
                        break;
                    case 'webp':
                        imagewebp($dst, $target_path, 85);
                        break;
                }

                imagedestroy($src);
                imagedestroy($dst);
            }
        }
    }

    // Return the URL for TinyMCE
    // Dynamic Base URL detection
    // If script is at /kkgpaket/admin/upload_image.php -> base is /kkgpaket
    // If script is at /admin/upload_image.php -> base is empty string
    $script_dir = dirname($_SERVER['SCRIPT_NAME']); // e.g., /kkgpaket/admin or /admin
    $base_url = dirname($script_dir); // e.g., /kkgpaket or / (or empty/backslash on windows)

    // Normalize slashes and remove trailing slash
    $base_url = str_replace('\\', '/', $base_url);
    if ($base_url === '/' || $base_url === '.') {
        $base_url = '';
    }

    $image_url = $base_url . '/assets/img/content/' . $new_filename;



    // TinyMCE expects 'location' key in response
    echo json_encode(['location' => $image_url]);
} else {


    http_response_code(500);
    echo json_encode(['error' => 'Failed to move uploaded file.']);
}
