<?php
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo "Only DELETE method is allowed.";
    exit;
}

// Manually parse query string (because $_GET might be empty for DELETE)
parse_str($_SERVER['QUERY_STRING'], $query);

if (!isset($query['filename'])) {
    http_response_code(400); // Bad Request
    echo "Missing 'filename' parameter.";
    exit;
}

// Sanitize the filename
$filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $query['filename']);

if ($filename === '' || $filename === 'new') {
    http_response_code(400);
    echo "Invalid filename.";
    exit;
}

$file_path = __DIR__ . '/' . $filename;

if (file_exists($file_path)) {
    if (unlink($file_path)) {
        http_response_code(200); // OK
        echo "File '$filename' deleted successfully.\n";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Failed to delete file.";
    }
} else {
    http_response_code(404); // Not Found
    echo "File '$filename' not found.";
}
?>
