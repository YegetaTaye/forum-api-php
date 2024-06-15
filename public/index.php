<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

ini_set('display_errors', 0); // Suppress errors from being displayed in the output
error_reporting(E_ALL); // Report all errors (useful for debugging)

// Set a custom error handler to capture errors and send a JSON response
set_error_handler(function($severity, $message, $file, $line) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
    exit();
});

// Include the routing file
require_once __DIR__ . '/../src/routes/api.php';
?>
