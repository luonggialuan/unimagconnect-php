<?php
// Include file kết nối đến cơ sở dữ liệu
include_once("../../connect.php");

// Kiểm tra quyền truy cập của người dùng
require_once '../../permissions.php';
checkAccess([ROLE_ADMIN], $conn);

// Kiểm tra xem request có phải là phương thức POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra xem biến $_POST['role_id'] có tồn tại không
    if (isset($_POST['role_id'])) {
        // Lấy role_id từ dữ liệu POST
        $role_id = $_POST['role_id'];

        // Truy vấn SQL để xóa role từ bảng roles
        $sql = "DELETE FROM roles WHERE roleId = ?";
        
        // Chuẩn bị câu lệnh SQL
        $stmt = $conn->prepare($sql);
        
        // Bind giá trị vào statement
        $stmt->bind_param("i", $role_id);
        
        // Thực thi statement
        if ($stmt->execute()) {
            // Trả về phản hồi cho client
            echo "Role has been deleted successfully.";
        } else {
            // Trả về thông báo lỗi nếu có lỗi xảy ra
            echo "Error: " . $conn->error;
        }

        // Đóng statement
        $stmt->close();
    } else {
        // Trả về thông báo lỗi nếu không tìm thấy role_id trong dữ liệu POST
        echo "Role ID is missing.";
    }
} else {
    // Trả về thông báo lỗi nếu request không phải là phương thức POST
    echo "Invalid request method.";
}
?>
