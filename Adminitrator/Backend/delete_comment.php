<?php
// Kết nối đến cơ sở dữ liệu
include_once("../../connect.php");

// Kiểm tra xem dữ liệu đã được gửi từ form chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy ID của comment cần xóa từ dữ liệu gửi đi
    $commentId = $_POST["commentId"];

    // Query SQL để xóa comment từ cơ sở dữ liệu
    $deleteSql = "DELETE FROM comments WHERE commentId = $commentId";

    // Thực thi truy vấn
    if ($conn->query($deleteSql) === TRUE) {
        // Trả về thông báo thành công nếu xóa comment thành công
        echo "Comment deleted successfully.";
    } else {
        // Trả về thông báo lỗi nếu có lỗi xảy ra khi xóa comment
        echo "Error: " . $deleteSql . "<br>" . $conn->error;
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn->close();
} else {
    // Trả về thông báo lỗi nếu yêu cầu không hợp lệ
    echo "Invalid request.";
}
?>
