<?php
// Kết nối đến cơ sở dữ liệu
include_once ("../../connect.php");

// Kiểm tra xem có dữ liệu gửi từ phía client không
if (isset ($_POST['articleId']) && isset ($_POST['showStatus'])) {
    $articleId = $_POST['articleId'];
    $showStatus = $_POST['showStatus'];
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $publicDate = (isset ($_POST['showStatus']) == 1) ? date("Y-m-d") : null;
    // Chuẩn bị truy vấn SQL để cập nhật trạng thái
    $sql = "UPDATE articles SET showStatus = \"$showStatus\", publicDate = \"$publicDate\" WHERE articleId = $articleId";

    // Thực thi truy vấn
    if ($conn->query($sql) === TRUE) {
        // Trả về phản hồi thành công nếu cập nhật thành công
        // Tạo một mảng chứa dữ liệu để trả về
        $response = array("badgeClass" => "", "showStatus" => "");
        // Đặt giá trị của badgeClass và showStatus dựa trên giá trị mới của showStatus
        if ($showStatus == 0) {
            $response["badgeClass"] = "bg-danger";
            $response["showStatus"] = "None";
        } else {
            $response["badgeClass"] = "bg-primary";
            $response["showStatus"] = "Public";
        }
        // Chuyển đổi mảng thành JSON và trả về
        echo json_encode($response);
    } else {
        // Trả về phản hồi lỗi nếu có lỗi xảy ra trong quá trình cập nhật
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Trả về thông báo lỗi nếu không có dữ liệu gửi từ phía client
    echo "Không có dữ liệu gửi từ phía client";
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>