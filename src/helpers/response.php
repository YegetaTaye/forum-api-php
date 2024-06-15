<?php
function sendResponse($status, $body) {
    header("Content-Type: application/json");
    http_response_code($status);
    echo json_encode($body);
}
?>
