<?php
// Kết nối đến cơ sở dữ liệu
include_once("../../connect.php");

// Thực hiện các thao tác kiểm tra và lưu dữ liệu dựa trên phương thức POST và các tham số được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $roleId = $_POST['roleId'];
    $roleName = $_POST['roleName'];

    // Kiểm tra xem là thêm mới hay chỉnh sửa dựa vào sự tồn tại của roleId
    if (empty($roleId)) {
        // Nếu roleId rỗng, đây là yêu cầu để thêm mới tạp chí
        // Thực hiện thêm mới tạp chí vào cơ sở dữ liệu
        $query = "INSERT INTO roles (roleName)
                  VALUES ('$roleName')";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "New role created successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        // Nếu roleId không rỗng, đây là yêu cầu để chỉnh sửa tạp chí đã tồn tại
        // Thực hiện cập nhật thông tin tạp chí trong cơ sở dữ liệu dựa trên roleId
        $query = "UPDATE roles
                  SET roleName='$roleName'
                  WHERE roleId=$roleId";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "Role updated successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn->close();
}
?>

