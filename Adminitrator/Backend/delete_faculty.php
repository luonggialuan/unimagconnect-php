<?php
// Kiểm tra xem có yêu cầu xóa tạp chí không
if (isset($_POST['faculty_id'])) {
    // Bao gồm tệp kết nối cơ sở dữ liệu
    include_once("../../connect.php");

    // Lấy ID của tạp chí từ yêu cầu POST
    $facultyId = $_POST['faculty_id'];

    // Xây dựng truy vấn SQL để xóa tạp chí
    $sql = "DELETE FROM faculties WHERE facultyId = $facultyId";

    // Thực thi truy vấn
    if ($conn->query($sql) === TRUE) {
        // Trả về thông báo thành công nếu xóa thành công
        echo json_encode(array("status" => "success", "message" => "Faculty deleted successfully."));
    } else {
        // Trả về thông báo lỗi nếu có lỗi xảy ra khi xóa
        echo json_encode(array("status" => "error", "message" => "Error deleting faculty: " . $conn->error));
    }

    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
} else {
    // Trả về thông báo lỗi nếu không có ID tạp chí được cung cấp trong yêu cầu POST
    echo json_encode(array("status" => "error", "message" => "No faculty ID provided."));
}
?>
