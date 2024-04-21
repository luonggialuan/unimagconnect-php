<?php
session_start();
// Kết nối đến cơ sở dữ liệu
include_once ("../../connect.php");

// Kiểm tra xem dữ liệu đã được gửi từ form chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $content = $_POST["commentText"];
    $articleId = $_POST["articleId"];
    $commentId = $_POST["commentId"];// Kiểm tra xem có tồn tại commentId hay không
    // Lấy ngày hiện tại
    $commentDate = date("Y-m-d H:i:s");
    $authorId = $_SESSION['userid'];

    // Kiểm tra nếu có commentId, thực hiện cập nhật, ngược lại thêm mới
    if (empty ($commentId)) {
        // Thực hiện truy vấn để thêm comment vào cơ sở dữ liệu
        $sql = "INSERT INTO `comments` (`content`, `commentDate`, `articleId`, `authorId`) VALUES ('$content', '$commentDate', '$articleId', '$authorId')";
    } else {
        $sql = "UPDATE `comments` SET `content` = '$content', `commentDate` = '$commentDate' WHERE `commentId` = $commentId";
    }
    // Thực hiện câu lệnh SQL và kiểm tra kết quả
    if ($conn->query($sql) === TRUE) {
        if (empty ($commentId)) {
            echo "Comment added successfully.";
        } else {

            echo "Comment updated successfully.";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Đóng kết nối đến cơ sở dữ liệu
    $conn->close();
} else {
    echo "Invalid request.";
}
?>