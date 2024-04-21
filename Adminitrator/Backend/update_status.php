<?php
include_once ("../../connect.php");
require '../../public/src/mail.php';

if (isset($_POST['articleId']) && isset($_POST['status'])) {
    $articleId = $_POST['articleId'];
    $status = $_POST['status'];

    $sql = "UPDATE articles SET status = $status WHERE articleId = $articleId";

    if ($conn->query($sql)) {
        echo "Cập nhật trạng thái thành công";

        $sql_updated_article = "SELECT * FROM articles
                                INNER JOIN users ON articles.authorId = users.userId
                                INNER JOIN faculties ON users.facultyId = faculties.facultyId
                                WHERE articleId = $articleId";
        $result_updated_article = $conn->query($sql_updated_article);
        $row = $result_updated_article->fetch_assoc();

        if ($status !== '0') {
            $student_name = $row['name'];
            $student_email = $row['email'];
            $facultyName = $row['facultyName'];
            $articleTitle = $row['title'];
            $articleStatus = $status === '1' ? 'Approved' : 'Rejected';

            $message = file_get_contents("mailFormStatusArt.html");
            $message = str_replace('%studentName%', $student_name, $message);
            $message = str_replace('%facultyName%', $facultyName, $message);
            $message = str_replace('%title%', $articleTitle, $message);
            $message = str_replace('%approvalStatus%', $articleStatus, $message);

            sendEmail("Approval Article", $student_email, $student_name, $message, false, null);
        }


    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Không có dữ liệu gửi từ phía client";
}

$conn->close();
?>