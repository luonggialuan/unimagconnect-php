<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    include_once ("../../connect.php");
    $articleId = $_GET['articleId'];
    $fileSql = "SELECT * FROM files WHERE articleId = $articleId";
    $fileResult = $conn->query($fileSql);
    // Query to retrieve detailed information of the article from the database
    $sql = "SELECT articles.*, users.Name as authorName, magazineName FROM articles
        INNER JOIN users ON articles.authorId = users.userId
        INNER JOIN magazine ON magazine.magazineId = articles.magazineId
        WHERE articleId = $articleId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output the data
        $row = $result->fetch_assoc();
        echo '<div class="table-responsive table-responsive-x5">';
        echo '<table class="table table-bordered">';
        echo '<tr>';
        echo '<th>Article Title</th>';
        echo '<td>' . $row["title"] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Article Content</th>';
        echo '<td>' . $row["content"] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Submission Date</th>';
        echo '<td>' . $row["submitDate"] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Author</th>';
        echo '<td>' . $row["authorName"] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Magazine</th>';
        echo '<td>' . $row["magazineName"] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Status</th>';
        echo '<td style="color:' . ($row["status"] == 1 ? 'green' : ($row["status"] == 2 ? 'red' : 'blue')) . '">';
        switch ($row["status"]) {
            case 0:
                echo 'Pending';
                break;
            case 1:
                echo 'Approved' . ' - ' . ($row['showStatus'] == 1 ? 'Public' : 'Non-public');
                break;
            case 2:
                echo 'Rejected';
                break;
            default:
                echo 'Unknown';
                break;
        }
        echo '</td>';
        echo '</tr>';
        if ($fileResult->num_rows > 0) {
            echo '<tr>';
            echo '<th>Files</th>';
            echo '<td>';
            $firstFile = true; // Variable to check the first time in the loop
            while ($fileRow = $fileResult->fetch_assoc()) {
                if (!$firstFile) {
                    echo '<br>'; // Add a new line (line break) before the second file onwards
                } else {
                    $firstFile = false; // Mark the first time passed
                }
                echo '<a href="../../../' . $fileRow['filePath'] . '" download>' . $fileRow['fileName'] . '</a>';
            }
            echo '</td>';
            echo '</tr>';
        } else {
            echo '<p>No files found for this article.</p>';
        }
        echo '<tr>';
        echo '<th>Article Image</th>';
        echo '<td><img src="../../../images_article/' . basename($row["image"]) . '" alt="Article Image" width="200" height="200"></td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';

        // Query to retrieve comments for the article
        $commentSql = "SELECT comments.*, users.Name as authorName FROM comments
        INNER JOIN users ON comments.authorId = users.userId
        WHERE articleId = $articleId";

        $commentResult = $conn->query($commentSql);

        if ($commentResult->num_rows > 0) {
            echo '<div class="container mt-4">';
            echo '<h6 class="modal-title">Comments</h6>';
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            while ($commentRow = $commentResult->fetch_assoc()) {
                echo '<div class="my-2">';
                echo '<small class="text-muted  ">' . $commentRow['commentDate'] . '</small>';
                echo '</div>';
                echo '<div class="d-flex" style="align-items: baseline;">';
                echo '<h6 class="card-subtitle mb-2 me-2 text-primary">' . $commentRow['authorName'] . ': </h6>';
                echo '<p class="card-text">' . $commentRow['content'] . '</p>';
                echo '</div>';
                echo '<div class="d-flex justify-content-end mt-2">';
                echo '<button type="button" class="btn btn-primary btn-sm btn-edit-comment me-2" 
                data-bs-toggle="modal"
                data-action="edit"
                data-bs-target="#commentModal" 
                data-comment-id="' . $commentRow["commentId"] . '" 
                data-comment-content="' . $commentRow["content"] . '">Edit Comment</button>';
                echo '<button type="button" class="btn btn-danger btn-sm" onclick="deleteComment(' . $commentRow['commentId'] . ')">Delete</button>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="container mt-4">';
            echo '<p>No comments found for this article.</p>';
            echo '</div>';
        }

    } else {
        echo 'Article information not found.';
    }
    ?>
    <script>
        $(document).ready(function () {
            $('.btn-edit-comment').click(function () {
                var commentId = $(this).data('comment-id');
                var content = $(this).data('comment-content');
                openCommentModal(commentId, content);
            });
        });

        function openCommentModal(commentId, content) {
            $('#commentModal .modal-title').text('Edit Comment');
            $('#commentModal #commentId').val(commentId);
            $('#commentModal #commentText').val(content);
            $('#commentModal').modal('show');
        }


        function deleteComment(commentId) {
            // Hiển thị cửa sổ xác nhận bằng SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this comment!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Nếu người dùng xác nhận xóa, thực hiện gửi yêu cầu xóa comment
                    $.ajax({
                        url: '../../Backend/delete_comment.php',
                        type: 'POST',
                        data: {
                            commentId: commentId
                        },
                        success: function (response) {
                            // Xử lý phản hồi từ server nếu cần
                            console.log(response);
                            // Tải lại trang để cập nhật danh sách comment
                            location.reload();
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            // Hiển thị thông báo lỗi nếu có lỗi xảy ra
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'An error occurred while deleting the comment. Please try again later.'
                            });
                        }
                    });
                }
            });
        }
    </script>
</body>