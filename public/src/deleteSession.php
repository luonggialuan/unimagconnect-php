<?php
// Khởi động session
session_start();

// Xóa session
unset($_SESSION['log_access_done']);

// Đáp ứng cho yêu cầu HTTP
http_response_code(200);
?>
