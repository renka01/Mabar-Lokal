<?php
require_once __DIR__ . '/../config/env.php';

http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

echo json_encode([
    'ok'     => true,
    'app'    => (string) env('APP_NAME', 'MabarLokal'),
    'status' => 'up',
    'time'   => date('c'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
