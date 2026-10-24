<?php
session_start();
header('Content-Type: application/json');
echo json_encode([
    'error' => isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : null,
    'success' => isset($_SESSION['success']) ? $_SESSION['success'] : null
]);
