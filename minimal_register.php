<?php
header('Content-Type: application/json');

// Just echo back what was sent
echo json_encode([
    'success' => true,
    'message' => 'Test successful',
    'received' => $_POST
]);
?>