<?php
include_once ('../../../connect.php');

require_once '../../../permissions.php';

checkAccess([ROLE_MARKETING_COORDINATOR], $conn);
?>

<!doctype html>
<html lang="en" disabled>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UniMagConnect</title>
    <!-- <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" /> -->
    <link rel="stylesheet" href="../assets/css/styles.min.css" />


    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/assets_table/vendors/datatables.net-bs5/dataTables.bootstrap5.css">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="../assets/assets_table/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="../assets/assets_table/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <!-- End layout styles -->

    <link rel="shortcut icon" href="../../assets/assets_table/images/favicon.png" />
    <!-- <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" /> -->
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .no-click {
        pointer-events: none;
        opacity: 0.5;
    }

    /* CSS cho nút */
    .switch {
        display: inline-flex;
        /* Sử dụng flexbox để căn giữa chữ bên trong */
        align-items: center;
        /* Căn chữ theo chiều dọc */
        justify-content: center;
        /* Căn chữ theo chiều ngang */
        cursor: pointer;
        width: 70px;
        /* Điều chỉnh kích thước của nút */
        height: 30px;
        /* Điều chỉnh kích thước của nút */
        border-radius: 30px;
        /* Đảm bảo góc tròn cho nút */
        overflow: hidden;
        position: relative;
        background-color: #ccc;
        transition: background-color 0.4s;
    }

    /* CSS cho slider (nút trượt) */
    .slider {
        /* Các thuộc tính CSS cho slider */
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 30px;
        /* Đảm bảo góc tròn cho nút */
        transition: .4s;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        /* Màu chữ mặc định */
        font-size: 14px;
        /* Kích thước chữ */
    }

    /* Các trạng thái của slider */
    .slider.active {
        background-color: green;
        /* Màu nền cho trạng thái active */
    }

    .slider.pending {
        background-color: blue;
        /* Màu nền cho trạng thái pending */
    }

    .slider.inactive {
        background-color: red;
        /* Màu nền cho trạng thái inactive */
    }


    .green {
        background-color: green;
        color: white;
    }

    .yellow {
        background-color: yellow;
        color: black;
    }

    .red {
        background-color: red;
        color: white;
    }
    </style>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.html" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/dark-logo.svg" width="180" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>

                <!-- Sidebar navigation-->
                <?php
                include_once ("sidebar.php");
                ?>
                <!-- End Sidebar navigation -->

            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <?php
            include_once ("header.php");
            ?>
            <!--  Header End -->


            <?php
            // Truy vấn SQL để lấy dữ liệu từ bảng articles
            $userId = $_SESSION['userid'];
            $sql = "SELECT a.*, u.facultyId AS authorFacultyId, f.facultyName AS authorFacultyName
        FROM articles a
        INNER JOIN users u ON a.authorId = u.userId
        INNER JOIN faculties f ON u.facultyId = f.facultyId
        WHERE u.facultyId = (SELECT facultyId FROM users WHERE userId = '$userId')";
            $result = $conn->query($sql);


            if ($result->num_rows > 0) {
                // Bắt đầu bảng HTML
                echo '<div class="container-fluid">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<div class="row">';
                echo '<div class="col-lg-12 d-flex align-items-stretch">';
                echo '<div class="card w-100">';
                echo '<div class="card-body p-4">';
                echo '<h5 class="card-title fw-semibold mb-4">Accounts of System</h5>';
                echo '<br>';
                echo '<div class="table-responsive">';
                echo '<table id="dataTableExample" class="table text-nowrap mb-0 align-middle">';
                echo '<thead class="text-dark fs-4">';
                echo '<tr>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">ID</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Title</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Content</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Submit Date</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Deadline feedback</h6></th>';
                // echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Ahthor</h6></th>';
                // echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Magazine</h6></th>';
                // echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Image</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Status</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Detail</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Comment</h6></th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Duyệt qua các dòng kết quả và hiển thị dữ liệu
                while ($row = $result->fetch_assoc()) {
                    $submitDate = strtotime($row["submitDate"]); // Chuyển đổi ngày nộp thành timestamp
                    $deadline = strtotime('tomorrow', strtotime('+14 days', $submitDate)) - 1; // Lấy thời gian 23:59 của ngày cuối cùng
                    $currentDate = time(); // Lấy timestamp của ngày hiện tại
                    $remainingTime = $deadline - $currentDate; // Tính thời gian còn lại đến hạn feedback
            
                    // Tính số ngày, giờ và phút còn lại
                    $remainingDays = floor($remainingTime / (60 * 60 * 24));
                    $remainingHours = floor(($remainingTime % (60 * 60 * 24)) / (60 * 60));
                    $remainingMinutes = floor(($remainingTime % (60 * 60)) / 60);

                    $remainingResult = "$remainingDays" . ' days ' . "$remainingHours" . ' hours ' . "$remainingMinutes" . ' minutes left';
                    // Xác định màu sắc dựa trên số ngày còn lại
                    if ($remainingDays > 5) {
                        $colorClass = 'bg-primary';
                        $colorButton = 'btn-primary';
                        $lockButton = '';
                    } elseif ($remainingDays > 0) {
                        $colorClass = 'bg-warning';
                        $colorButton = 'btn-warning';
                        $lockButton = '';
                    } else {
                        $colorClass = 'bg-danger';
                        $remainingResult = "Expired";
                        $colorButton = 'btn-danger';
                        $lockButton = 'disabled';
                    }


                    echo '<tr>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0">' . $row["articleId"] . '</h6></td>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-1">' . substr($row["title"], 0, 20) . "..." . '</h6></td>';
                    echo '<td class="border-bottom-0"><h6 class="fw-normal">' . substr($row["content"], 0, 15) . "..." . '</h6></td>';
                    echo '<td class="border-bottom-0"><p class="mb-0 fw-normal">' . $row["submitDate"] . '</p></td>';
                    echo '<td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge ' . $colorClass . ' rounded-3 fw-semibold style="background-color:red;"">' . $remainingResult . ' </span></div></td>';
                    // echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0 fs-4">' . $row["authorId"] . '</h6></td>';
                    // echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0 fs-4">' . $row["magazineId"] . '</h6></td>';
                    // echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0 fs-4">' . $row["Image"] . '</h6></td>';
            
                    // echo '<td class="border-bottom-0"><div class="d-flex align-items-center gap-2"><span class="badge bg-primary rounded-3 fw-semibold">' . $row["status"] . '</span></div></td>';
                    ?>
            <?php if ($row['showStatus'] != 1) { ?>
            <td class="border-bottom-0">
                <div class="d-flex align-items-center gap-2">
                    <label class="switch" onclick="toggleStatus(this, <?php echo $row['articleId']; ?>)">
                        <input type="hidden" name="status<?php echo $row['articleId']; ?>"
                            value="<?php echo $row['status']; ?>">
                        <span
                            class="slider <?php echo ($row["status"] == 1) ? 'active' : (($row["status"] == 0) ? 'pending' : 'inactive'); ?>">
                            <?php echo ($row["status"] == 1) ? 'Approved' : (($row["status"] == 0) ? 'Pending' : 'Rejected'); ?>
                        </span>
                    </label>
                </div>
            </td>
            <?php
                    } else {
                        ?>
            <td class="border-bottom-0 no-click">
                <div class="d-flex align-items-center gap-2">
                    <label class="switch">
                        <span
                            class="slider <?php echo ($row["status"] == 1) ? 'active' : (($row["status"] == 0) ? 'pending' : 'inactive'); ?>">
                            <?php echo ($row["status"] == 1) ? 'Approved' : (($row["status"] == 0) ? 'Pending' : 'Rejected'); ?>
                        </span>
                    </label>
                </div>
            </td>
            <?php
                    }
                    ?>

            <?php
                    echo '<td class="border-bottom-0">
                    <button type="button" class="btn btn-primary btn-sm btn-view-details" data-article-id="' . $row["articleId"] . '">View Details</button>

                              </td>';
                    if ($lockButton !== 'disabled') {
                        echo '<td class="border-bottom-0">
                              <button type="button" class="btn ' . $colorButton . ' btn-sm btn-comment" data-article-id="' . $row["articleId"] . '" ' . $lockButton . '>Comment</button>
                                        </td>';
                    } else {
                        echo '<td class="border-bottom-0">
                                <button type="button" class="btn ' . $colorButton . ' btn-sm " ' . $lockButton . '>Comment</button>
                                          </td>';
                    }
                    echo '</tr>';
                }
                // Kết thúc bảng HTML
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } else {
                echo "Không có dữ liệu.";
            }

            ?>

        </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Article Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsModalBody">
                    <!-- Nội dung của thông tin chi tiết sẽ được điền vào đây -->
                    <h6 class="modal-title">Comments</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Content</th>
                                <th scope="col">Comment Date</th>
                                <th scope="col">Author</th>
                            </tr>
                        </thead>
                        <tbody id="commentTableBody">
                            <!-- Dữ liệu bình luận sẽ được thêm vào đây -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">Add Comment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="commentForm">
                        <div class="mb-3">
                            <label for="commentText" class="form-label">Comment:</label>
                            <textarea class="form-control" id="commentText" name="commentText"></textarea>
                        </div>
                        <input type="hidden" id="articleId" name="articleId" value="">
                        <input type="hidden" id="commentId" name="commentId" value="">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitComment">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- core:js -->
    <script src="../assets/assets_table/vendors/core/core.js"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="../assets/assets_table/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="../assets/assets_table/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="../assets/assets_table/vendors/feather-icons/feather.min.js"></script>
    <script src="../assets/assets_table/js/template.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="../assets/assets_table/js/data-table.js"></script>
    <!-- End custom js for this page -->

    <script>
    function toggleStatus(label, articleId) {
        var hiddenInput = label.querySelector('input[type="hidden"]');
        var slider = label.querySelector('.slider');

        var status = parseInt(hiddenInput.value);

        if (status == 0) {
            status = 1;
            slider.innerText = "Approved"; // Thay đổi văn bản cho trạng thái active
        } else if (status === 1) {
            status = 2;
            slider.innerText = "Rejected"; // Thay đổi văn bản cho trạng thái inactive
        } else {
            status = 0;
            slider.innerText = "Pending"; // Thay đổi văn bản cho trạng thái pending
        }

        hiddenInput.value = status;

        slider.classList.remove('active', 'pending', 'inactive');
        if (status === 0) {
            slider.classList.add('pending');
        } else if (status === 1) {
            slider.classList.add('active');
        } else {
            slider.classList.add('inactive');
        }

        // Gửi yêu cầu AJAX để cập nhật trạng thái trong cơ sở dữ liệu
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Backend/update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(xhr.responseText);

            }
        };
        xhr.send("articleId=" + articleId + "&status=" + status);
    }



    $(document).ready(function() {
        function viewDetails(articleId) {
            // Gửi yêu cầu Ajax để lấy thông tin chi tiết của bài viết
            $.ajax({
                url: '../../Backend/get_articles_detail.php',
                type: 'GET',
                data: {
                    articleId: articleId
                },
                success: function(response) {
                    $('#detailsModalBody').html(response);
                    $('#detailsModal').modal('show');
                    $('#commentTableBody').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        // Sử dụng sự kiện 'click' cho nút xem chi tiết
        $('.btn-view-details').click(function() {
            var articleId = $(this).data('article-id');
            viewDetails(articleId);
        });
    });


    document.addEventListener("DOMContentLoaded", function() {
        // Lắng nghe sự kiện khi nhấn nút comment hoặc nút edit comment
        $('.btn-comment, .btn-edit-comment').click(function() {
            var articleId = $(this).data('article-id');
            var action = $(this).data('action');
            console.log('Action:', action);
            if (action === 'edit') {
                // Nếu là nút edit comment, hiển thị modal chỉ để sửa comment
                var commentId = $(this).data('comment-id'); // Lấy ID của comment cần chỉnh sửa
                var commentContent = $(this).data(
                    'comment-content'); // Lấy nội dung của comment cần chỉnh sửa

                // Đặt giá trị cho form comment modal để sửa comment
                $('#articleId').val(articleId);
                $('#commentText').val(commentContent);
                $('#commentModal .modal-title').text('Edit Comment');
                $('#submitComment').text('Save'); // Đổi nút "Submit" thành "Save"
                $('#commentModal').modal('show'); // Hiển thị modal form comment để chỉnh sửa comment
            } else {
                // Nếu không phải là nút edit comment, hiển thị modal comment để thêm mới
                $('#articleId').val(
                    articleId); // Đặt giá trị articleId cho trường ẩn trong form comment
                $('#commentText').val(''); // Đặt giá trị mặc định cho trường commentText là rỗng
                $('#commentModal .modal-title').text('Add Comment');
                $('#submitComment').text('Submit'); // Đổi nút "Save" thành "Submit"
                $('#commentModal').modal('show'); // Hiển thị modal form comment để thêm mới comment
            }
        });

        // Lắng nghe sự kiện khi gửi comment
        $('#submitComment').click(function() {
            var formData = $('#commentForm').serialize(); // Lấy dữ liệu từ form comment
            $.ajax({
                url: '../../Backend/process_comment.php', // Script PHP để xử lý comment
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Hiển thị thông báo SweetAlert2 với nội dung là response
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response,
                    }).then((result) => {
                        // Nếu người dùng nhấn OK, ẩn modal và làm sạch form
                        if (result.isConfirmed) {
                            $('#commentModal').modal('hide');
                            $('#commentForm')[0].reset();
                        }
                    });
                },

            });
        });

        // Lắng nghe sự kiện khi đóng modal
        $('#commentModal').on('hidden.bs.modal', function() {
            $('#commentForm')[0].reset(); // Xóa các giá trị trong form comment khi đóng modal
            $('#commentId').val('');
            $('#articleId').val('');
        });
    });

    function toggleNoClick(status) {
        var elements = document.querySelectorAll('.switch');
        elements.forEach(function(element) {
            if (status === true) {
                element.classList.add('no-click');
            } else {
                element.classList.remove('no-click');
            }
        });
    }
    </script>


    </script>

</body>

</html>