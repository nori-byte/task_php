<?php
header('Content-Type: application/json');
$headers = getallheaders();
$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? 'NOT SET';
echo json_encode([
    'getallheaders' => $headers,
    'HTTP_AUTHORIZATION' => $auth,
    'all_server_keys' => array_keys($_SERVER)
], JSON_PRETTY_PRINT);
