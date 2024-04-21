<?php
// Kiểm tra xem đã ghi log trước đó hay chưa
if (!isset($_SESSION['log_access_done'])) {
    // Kết nối đến cơ sở dữ liệu
    include_once("connect.php");

    // Lấy thông tin về người dùng và trang đã truy cập
    $userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $accessTime = date('Y-m-d H:i:s');
    $browserInfo = $_SERVER['HTTP_USER_AGENT'];
    $accessedPage = $_SERVER['REQUEST_URI'];

    // Chuẩn bị truy vấn SQL
    $sql = "INSERT INTO accessLog (userId, ipAddress, accessTime, browserInfo, accessedPage) 
            VALUES (?, ?, ?, ?, ?)";

    // Sử dụng prepared statement để tránh SQL injection
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        // Gán giá trị vào các tham số của truy vấn
        mysqli_stmt_bind_param($stmt, "issss", $userId, $ipAddress, $accessTime, $browserInfo, $accessedPage);

        // Thực thi truy vấn
        mysqli_stmt_execute($stmt);

        // Đóng statement
        mysqli_stmt_close($stmt);

        // Đánh dấu rằng đã ghi log để tránh ghi lại trong cùng một request hoặc trang
        $_SESSION['log_access_done'] = true;
    } else {
        // Xử lý khi truy vấn thất bại
        echo "Error: " . mysqli_error($conn);
    }
}
?>
