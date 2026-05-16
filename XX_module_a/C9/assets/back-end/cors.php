<?php

/** EDIT AREA **/

// Cho phép tất cả domain (hoặc thay bằng frontend của bạn)
header("Access-Control-Allow-Origin: *");

// Cho phép các method
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Cho phép các header từ client
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Nếu cần gửi cookie/token (có thể giữ hoặc bỏ)
header("Access-Control-Allow-Credentials: true");

// Xử lý preflight request (QUAN TRỌNG)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}