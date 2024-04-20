<style>
    /* Tùy chỉnh kích thước bảng */
    .tableEdit {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    /* Tùy chỉnh đường viền cho table, header và body */
    .tableEdit,
    .tableEdit th,
    .tableEdit td {
        border: 1px solid #dee2e6;
    }

    /* Tùy chỉnh màu nền cho header */
    .tableEdit thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
    }

    /* Tùy chỉnh kích thước của các header */
    .tableEdit th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 0;
        border-bottom-width: 2px;
        text-align: center;
    }

    /* Tùy chỉnh kích thước của các ô */
    .tableEdit td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 0;
        text-align: center;
    }

    /* Tùy chỉnh màu chẵn lẻ cho các dòng */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Tùy chỉnh màu hover cho các dòng */
    .tableEdit tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }

    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border-radius: 1rem !important;
        transition: transform 0.3s ease;
    }

    /* Bo tròn các góc của thẻ card */
    .card {
        border-radius: 1rem !important;
    }

    /* Hiệu ứng bo tròn cho nút */
    .btn-primary {
        border-radius: 1.5rem !important;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    /* Tùy chỉnh responsive */
    @media (max-width: 575.98px) {
        .table-responsive-sm {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive-sm>.table-bordered {
            border: 0;
        }
    }

    .table-container {
        padding: 20px;
        /* hoặc bạn có thể sử dụng margin: 20px; */
    }

    .disabled {
        opacity: 0.65;
        /* Điều chỉnh độ mờ */
        pointer-events: none;
        /* Ngăn chặn các sự kiện click và hover */
        cursor: not-allowed;
        /* Đổi con trỏ khi hover */
    }
</style>


<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-semibold mb-4 text-center">Your Article Uploaded</h5>
                            <br>
                            <div class="table-responsive">
                                <table class="table tableEdit table-striped table-bordered">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Submit Date</th>
                                            <th>Action</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $userId = $_SESSION['userid'];
                                        // Select articles of user
                                        $sql_user_articles = "SELECT articles.*, magazine.*
                                        FROM articles
                                        INNER JOIN magazine ON articles.magazineId = magazine.magazineId
                                        WHERE articles.authorId = (SELECT userId FROM users WHERE userId = $userId)
                                        ";
                                        $result = $conn->query($sql_user_articles);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $nowDate = date('Y-m-d');
                                                $finalClosureDate = $row["finalClosureDate"];
                                                $articleId = $row["articleId"];
                                                ?>
                                                <tr>
                                                    <td style="width: 20%">
                                                        <?= $row['title'] ?>
                                                    </td>
                                                    <td style="width: 30%">
                                                        <?= substr($row['content'], 0, 150) . "..." ?>
                                                    </td>
                                                    <td>
                                                        <?= $row['submitDate'] ?>
                                                    </td>
                                                    <td style="width: 22%">
                                                        <form action="download.php" method="post" enctype="multipart/form-data"
                                                            class="d-inline-block">
                                                            <input type="hidden" name="articleUserId" id="articleUserId"
                                                                value="<?= $row['articleId'] ?>">
                                                            <button type="submit" class="btn btn-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor" class="bi bi-download"
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                                                                    <path
                                                                        d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                                                                </svg> Download
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-info view-article-btn"
                                                            data-article-id="<?= $row['articleId'] ?>" data-bs-toggle="modal"
                                                            data-bs-target="#viewArticleModal">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8 2a5 5 0 0 0-5 5c0 2.51 2.91 4.71 5 4.71s5-2.21 5-4.71a5 5 0 0 0-5-5zm0 8a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                                                            </svg> View
                                                        </button>
                                                        <?php
                                                        if ($nowDate <= $finalClosureDate) {
                                                            ?>
                                                            <a href="?page=updateArticleStudent&id=<?= $row['articleId'] ?>"
                                                                class="btn btn-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                                </svg> Update
                                                            </a>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <a href="" class="btn btn-primary disabled">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                                </svg> Update
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>

                                                    </td>
                                                    <td>
                                                        <?php
                                                        // Hàm để kiểm tra xem một bài viết có comment hay không
                                                        if (!function_exists('hasComment')) {
                                                            function hasComment($articleId, $conn)
                                                            {
                                                                // Chuẩn bị câu truy vấn SQL để đếm số lượng comment cho bài viết
                                                                $sqlcomment = "SELECT COUNT(*) AS commentCount FROM comments WHERE articleId = $articleId";

                                                                // Thực thi câu truy vấn
                                                                $resultcomment = $conn->query($sqlcomment);

                                                                // Kiểm tra nếu câu truy vấn thành công
                                                                if ($resultcomment) {
                                                                    // Lấy dòng dữ liệu từ kết quả  
                                                                    $rowcomment = $resultcomment->fetch_assoc();

                                                                    // Lấy số lượng comment từ dòng dữ liệu
                                                                    $commentCount = $rowcomment['commentCount'];

                                                                    // Trả về true nếu có ít nhất một comment, ngược lại trả về false
                                                                    return ($commentCount > 0);
                                                                } else {
                                                                    // Nếu có lỗi xảy ra trong quá trình thực thi câu truy vấn, trả về false
                                                                    return false;
                                                                }
                                                            }
                                                        }
                                                        $hasComment = hasComment($articleId, $conn);
                                                        if ($hasComment) {
                                                            if ($nowDate > $finalClosureDate) {
                                                                echo '<p class="text-danger">Submission deadline expired or editing closed</p>';
                                                            }
                                                            echo '<p class="text-success">There are comments.</p>';

                                                        } else {
                                                            if ($nowDate > $finalClosureDate) {
                                                                echo '<p class="text-danger">Submission deadline expired or editing closed</p>';
                                                            }
                                                            echo "No comment.";
                                                        }
                                                        ?>
                                                    </td>

                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="4">No data found!</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewArticleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">View Article Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Article details will be displayed here -->
                <div id="articleDetails"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- search popup area -->
<div class="search-popup">
    <!-- close button -->
    <button type="button" class="btn-close" aria-label="Close"></button>
    <!-- content -->
    <div class="search-content">
        <div class="text-center">
            <h3 class="mb-4 mt-0">Press ESC to close</h3>
        </div>
        <!-- form -->
        <form class="d-flex search-form">
            <input class="form-control me-2" type="search" placeholder="Search and press enter ..." aria-label="Search">
            <button class="btn btn-default btn-lg" type="submit"><i class="icon-magnifier"></i></button>
        </form>
    </div>
</div>


</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Lắng nghe sự kiện khi người dùng nhấn nút "View"
    $('.view-article-btn').click(function () {
        var articleId = $(this).data('article-id'); // Lấy ID của bài báo từ thuộc tính data
        // Gửi yêu cầu AJAX để lấy thông tin chi tiết về bài báo
        $.ajax({
            url: 'public/src/view_articles.php', // Thay đổi thành URL thích hợp của bạn để lấy thông tin bài báo
            type: 'GET',
            data: {
                articleId: articleId
            },
            success: function (response) {
                // Cập nhật nội dung của modal với thông tin chi tiết của bài báo
                $('#articleDetails').html(response);
            }
        });
    });
</script>