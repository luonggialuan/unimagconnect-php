<?php
include_once("../../connect.php");

// Kiểm tra nếu tồn tại dữ liệu được gửi từ yêu cầu AJAX
if (isset($_POST['userId']) && isset($_POST['status'])) {
    $userId = $_POST['userId'];
    $status = $_POST['status'];

    // Thực hiện truy vấn để cập nhật trạng thái của người dùng trong cơ sở dữ liệu
    $sql = "UPDATE users SET status = $status WHERE userId = $userId";
    if ($conn->query($sql) === TRUE) {
        echo "User status updated successfully";
    } else {
        echo "Error updating user status: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>
