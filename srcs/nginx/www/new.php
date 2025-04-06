<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo "Only POST method is allowed.";
    exit;
}

if (!isset($_GET['filename'])) {
    http_response_code(400); // Bad Request
    echo "Missing 'filename' parameter.";
    exit;
}

// Sanitize the filename
$filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $_GET['filename']);

if ($filename === '' || $filename === 'new') {
    http_response_code(400);
    echo "Invalid filename.";
    exit;
}

$body = file_get_contents('php://input');

$file_path = __DIR__ . '/' . $filename;

if (file_put_contents($file_path, $body) !== false) {
    http_response_code(201); // Created
    echo "File '$filename' created with POST body.\n";
} else {
    http_response_code(500);
    echo "Failed to create file.";
}
?>

