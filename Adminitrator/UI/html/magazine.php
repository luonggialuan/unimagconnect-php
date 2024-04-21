<?php
include_once ("../../../connect.php");

require_once '../../../permissions.php';

checkAccess([ROLE_ADMIN], $conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Magazine Management</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js
"></script>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css
" rel="stylesheet">
    <style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 24px;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
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
            // Truy vấn SQL để lấy dữ liệu từ bảng magazines
            $sql = "SELECT * FROM magazine";
            $result = $conn->query($sql);


            // Bắt đầu bảng HTML
            echo '<div class="container-fluid">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<div class="row">';
            echo '<div class="col-lg-12 d-flex align-items-stretch">';
            echo '<div class="card w-100">';
            echo '<div class="card-body p-4">';
            echo '<h5 class="card-title fw-semibold mb-4">Magazine Management</h5>';
            echo '<br>';
            echo '<div class="table-responsive">';
            echo '<table id="dataTableExample" class="table text-nowrap mb-0 align-middle">';
            echo '<thead class="text-dark fs-4">';
            echo '<tr>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">ID</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Name</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Description</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Closure Date Date</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Final Closure Date Date</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Year</h6></th>';
            echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Actions</h6></th>';
            echo '<th class="border-bottom-0">';
            echo '<h6 class="fw-semibold mb-0 text-center">';
            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#magazineModal">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">';
            echo '<path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>';
            echo '</svg></button></h6>';
            echo '</th>';

            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            if ($result->num_rows > 0) {
                // Duyệt qua các dòng kết quả và hiển thị dữ liệu
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0">' . $row["magazineId"] . '</h6></td>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-1">' . $row["magazineName"] . '</h6></td>';
                    echo '<td class="border-bottom-0"><h6 class="fw-normal">' . $row["magazineDescription"] . '</h6></td>';
                    echo '<td class="border-bottom-0"><p class="mb-0 fw-normal">' . $row["closureDate"] . '</p></td>';
                    echo '<td class="border-bottom-0"><p class="mb-0 fw-normal">' . $row["finalClosureDate"] . '</p></td>';
                    echo '<td class="border-bottom-0"><p class="mb-0 fw-normal">' . $row["magazineYear"] . '</p></td>';
                    echo '<td class="border-bottom-0">';
                    echo '<button type="button" class="btn btn-primary btn-sm btn-edit" 
                        data-bs-toggle="modal" 
                        data-bs-target="#magazineModal" 
                        data-magazine-id="' . $row["magazineId"] . '" 
                        data-magazine-name="' . $row["magazineName"] . '" 
                        data-magazine-description="' . $row["magazineDescription"] . '"
                        data-closure-date="' . $row["closureDate"] . '"
                        data-final-closure-date="' . $row["finalClosureDate"] . '"
                        data-magazine-year="' . $row["magazineYear"] . '">Edit</button>';
                    echo ' <button type="button" class="btn btn-danger btn-sm btn-delete" data-magazine-id="' . $row["magazineId"] . '">Delete</button>';
                    echo '</td>';


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
                echo "No data found.";
            }

            ?>

        </div>
    </div>
    <div class="modal fade" id="magazineModal" tabindex="-1" aria-labelledby="magazineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="magazineModalLabel">Add New Magazine</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add or edit magazine -->
                    <form id="magazineForm">
                        <div class="mb-3">
                            <label for="magazineName" class="form-label">Magazine Name</label>
                            <input type="text" class="form-control" id="magazineName" name="magazineName">
                        </div>
                        <div class="mb-3">
                            <label for="magazineDescription" class="form-label">Magazine Description</label>
                            <textarea class="form-control" id="magazineDescription"
                                name="magazineDescription"></textarea>
                        </div>
                        <div class="row mb-3">
                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Closure date</label>
                            <div class="col-sm-9">
                                <input id="datepicker1" name="magazineClosureDate" width="276" />
                                <script>
                                $('#datepicker1').datepicker({
                                    uiLibrary: 'bootstrap5',
                                    format: 'yyyy-mm-dd'
                                });
                                </script>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="finalClosureDate" class="col-sm-3 col-form-label">Final closure date</label>
                            <div class="col-sm-9">
                                <input id="datepicker2" name="magazineFinalClosureDate" width="276" />
                                <script>
                                $('#datepicker2').datepicker({
                                    uiLibrary: 'bootstrap5',
                                    format: 'yyyy-mm-dd'
                                });
                                </script>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="magazineYear" class="form-label">Magazine Year</label>
                            <select class="form-select" id="magazineYear" name="magazineYear">
                                <?php
                                // Lấy năm hiện tại
                                $currentYear = date("Y");

                                // Tạo một vòng lặp để tạo các tùy chọn cho dropdown từ năm hiện tại đến 10 năm sau
                                for ($year = $currentYear; $year <= $currentYear + 10; $year++) {
                                    echo '<option value="' . $year . '">' . $year . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" id="magazineId" name="magazineId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveMagazine">Save</button>
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

    <!-- Plugin js for this page -->
    <script src="../assets/assets_table/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="../assets/assets_table/vendors/datatables.net-bs5/dataTables.bootstrap5.js"></script>
    <!-- End plugin js for this page -->

    <!-- Custom js for this page -->
    <script src="../assets/assets_table/js/data-table.js"></script>
    <!-- End custom js for this page -->

    <script>
    // Sự kiện khi cửa sổ modal được ẩn đi
    $('#magazineModal').on('hide.bs.modal', function() {
        // Xóa nội dung của các ô input trong biểu mẫu
        $('#magazineForm').trigger('reset');
        $('#magazineModalLabel').text('Add New Magazine');
        $('#magazineId').val('');
    });

    // Sự kiện khi nhấp vào nút "Edit"
    $('.btn-edit').click(function() {
        // Lấy thông tin của tạp chí từ các thuộc tính data
        var magazineId = $(this).data('magazine-id');
        var magazineName = $(this).data('magazine-name');
        var magazineDescription = $(this).data('magazine-description');
        var magazineYear = $(this).data('magazine-year');
        var closureDate = $(this).data('closure-date');
        var finalClosureDate = $(this).data('final-closure-date');

        // Đưa thông tin của tạp chí vào các trường input trong cửa sổ modal
        $('#magazineId').val(magazineId);
        $('#magazineName').val(magazineName);
        $('#magazineDescription').val(magazineDescription);
        $('#magazineYear').val(magazineYear);
        $('#datepicker1').val(closureDate);
        $('#datepicker2').val(finalClosureDate);

        // Đổi tiêu đề của cửa sổ modal thành "Edit Magazine"
        $('#magazineModalLabel').text('Edit Magazine');

        // Mở cửa sổ modal
        $('#magazineModal').modal('show');
    });


    // Sự kiện khi nhấp vào nút "Add"
    $('#addMagazine').click(function() {
        // Xóa nội dung của các ô input trong biểu mẫu
        $('#magazineForm').trigger('reset');

        // Đổi tiêu đề của cửa sổ modal thành "Add New Magazine"
        $('#magazineModalLabel').text('Add New Magazine');

        // Mở cửa sổ modal
        $('#magazineModal').modal('show');
    });

    // Sự kiện khi người dùng nhấp vào nút "Save"
    $('#saveMagazine').click(function() {
        // Lấy dữ liệu từ biểu mẫu
        var formData = $('#magazineForm').serialize();

        // Gửi yêu cầu AJAX để thêm hoặc cập nhật tạp chí
        $.ajax({
            url: '../../Backend/save_magazine.php', // Thay thế đường dẫn bằng tên tệp xử lý
            type: 'POST',
            data: formData,
            success: function(response) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(response);

                // Sau khi thêm hoặc cập nhật thành công, đóng cửa sổ modal
                $('#magazineModal').modal('hide');

                // Sau đó, làm mới trang để cập nhật danh sách tạp chí
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('.btn-delete').click(function() {
        // Lấy ID của tạp chí cần xóa
        var magazineId = $(this).data('magazine-id');

        // Hiển thị cảnh báo xác nhận xóa
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu AJAX để xóa tạp chí
                $.ajax({
                    url: '../../Backend/delete_magazine.php',
                    type: 'POST',
                    data: {
                        magazineId: magazineId
                    },
                    success: function(response) {
                        // Xử lý phản hồi từ máy chủ nếu cần
                        console.log(response);

                        // Hiển thị thông báo xóa thành công
                        Swal.fire(
                            'Deleted!',
                            'Magazine has been deleted.',
                            'success'
                        ).then((result) => {
                            // Sau khi xóa thành công, làm mới trang để cập nhật danh sách tạp chí
                            window.location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    });
    </script>
</body>

</html>