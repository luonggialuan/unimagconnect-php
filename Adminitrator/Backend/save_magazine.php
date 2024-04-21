<?php
// Kết nối đến cơ sở dữ liệu
include_once("../../connect.php");

// Thực hiện các thao tác kiểm tra và lưu dữ liệu dựa trên phương thức POST và các tham số được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $magazineId = $_POST['magazineId'];
    $magazineName = $_POST['magazineName'];
    $magazineDescription = $_POST['magazineDescription'];
    $magazineClosureDate = date("Y-m-d", strtotime($_POST['magazineClosureDate']));
$magazineFinalClosureDate = date("Y-m-d", strtotime($_POST['magazineFinalClosureDate']));
    $magazineYear = $_POST['magazineYear'];

    // Kiểm tra xem là thêm mới hay chỉnh sửa dựa vào sự tồn tại của magazineId
    if (empty($magazineId)) {
        // Nếu magazineId rỗng, đây là yêu cầu để thêm mới tạp chí
        // Thực hiện thêm mới tạp chí vào cơ sở dữ liệu
        $query = "INSERT INTO magazine (magazineName, magazineDescription, closureDate, finalClosureDate, magazineYear) 
                  VALUES ('$magazineName', '$magazineDescription', '$magazineClosureDate', '$magazineFinalClosureDate', '$magazineYear')";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "New magazine created successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    } else {
        // Nếu magazineId không rỗng, đây là yêu cầu để chỉnh sửa tạp chí đã tồn tại
        // Thực hiện cập nhật thông tin tạp chí trong cơ sở dữ liệu dựa trên magazineId
        $query = "UPDATE magazine
                  SET magazineName='$magazineName', magazineDescription='$magazineDescription', closureDate='$magazineClosureDate', finalClosureDate='$magazineFinalClosureDate', magazineYear='$magazineYear' 
                  WHERE magazineId=$magazineId";
        
        if ($conn->query($query) === TRUE) {
            // Thành công
            echo "Magazine updated successfully";
        } else {
            // Lỗi
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn->close();
}
?>
