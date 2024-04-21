<?php
// Bắt đầu phiên làm việc
session_start();
include_once("../../connect.php");

// Kiểm tra xem ID tạp chí đã được gửi hay chưa
if (isset($_POST["magazineId"])) {
    // Lấy ID tạp chí từ yêu cầu
    $magazineId = $_POST["magazineId"];

    // Thực hiện truy vấn SQL để xóa tạp chí từ cơ sở dữ liệu
    $sql = "DELETE FROM magazine WHERE magazineId = $magazineId";

    if ($conn->query($sql) === TRUE) {
        // Trả về phản hồi thành công nếu truy vấn xóa thành công
        echo json_encode(["success" => true]);
    } else {
        // Trả về phản hồi không thành công nếu có lỗi xảy ra trong quá trình xóa
        echo json_encode(["success" => false, "message" => "Error deleting record: " . $conn->error]);
    }
} else {
    // Trường hợp không có ID tạp chí được gửi
    echo json_encode(["success" => false, "message" => "Magazine ID is missing"]);
}

?>
