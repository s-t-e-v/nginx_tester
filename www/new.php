<?php
ob_start(); // Prevents early output from breaking headers
ini_set('display_errors', '0'); // Make sure PHP errors aren't output

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Only POST method is allowed.";
    exit;
}

if (!isset($_GET['filename'])) {
    http_response_code(400);
    echo "Missing 'filename' parameter.";
    exit;
}

$filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $_GET['filename']);

if ($filename === '' || $filename === 'new') {
    http_response_code(400);
    echo "Invalid filename.";
    exit;
}

$body = file_get_contents('php://input');
$file_path = __DIR__ . '/' . $filename;

$result = @file_put_contents($file_path, $body);

if ($result !== false) {
    http_response_code(201);
    echo "File '$filename' created with POST body.\n";
} else {
    http_response_code(500);
    echo "Failed to create file.\n";
}
?>
