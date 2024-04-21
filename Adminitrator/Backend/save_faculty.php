<?php
// Kết nối đến cơ sở dữ liệu
include_once("../../connect.php");

// Thực hiện các thao tác kiểm tra và lưu dữ liệu dựa trên phương thức POST và các tham số được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $facultyId = $_POST['facultyId'];
    $facultyName = $_POST['facultyName'];

    // Kiểm tra xem là thêm mới hay chỉnh sửa dựa vào sự tồn tại của magazineId
    if (empty($facultyId)) {
        // Nếu magazineId rỗng, đây là yêu cầu để thêm mới tạp chí
        // Thực hiện thêm mới tạp chí vào cơ sở dữ liệu
        $query = "INSERT INTO faculties (facultyName) 
                  VALUES ('$facultyName')";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "New faculty created successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
    else {
        // Nếu magazineId không rỗng, đây là yêu cầu để chỉnh sửa tạp chí đã tồn tại
        // Thực hiện cập nhật thông tin tạp chí trong cơ sở dữ liệu dựa trên magazineId
        $query = "UPDATE faculties
                  SET facultyName='$facultyName' 
                  WHERE facultyId=$facultyId";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "Faculty updated successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn->close();
}

?>
