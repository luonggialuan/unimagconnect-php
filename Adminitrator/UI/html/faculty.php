<?php
include_once("../../../connect.php");

require_once '../../../permissions.php';

checkAccess([ROLE_ADMIN], $conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faculty Management</title>
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
                include_once("sidebar.php");
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
            include_once("header.php");
            ?>
            <!--  Header End -->


            <?php
            // Truy vấn SQL để lấy dữ liệu từ bảng facultys
            $sql = "SELECT * FROM faculties";
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
                echo '<h5 class="card-title fw-semibold mb-4">Faculty Management</h5>';
                echo '<br>';
                echo '<div class="table-responsive">';
                echo '<table id="dataTableExample" class="table text-nowrap mb-0 align-middle">';
                echo '<thead class="text-dark fs-4">';
                echo '<tr>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">ID</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Name</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0">Action</h6></th>';
                echo '<th class="border-bottom-0"><h6 class="fw-semibold mb-0"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#facultyModal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-dotted" viewBox="0 0 16 16">
                <path d="M8 0q-.264 0-.523.017l.064.998a7 7 0 0 1 .918 0l.064-.998A8 8 0 0 0 8 0M6.44.152q-.52.104-1.012.27l.321.948q.43-.147.884-.237L6.44.153zm4.132.271a8 8 0 0 0-1.011-.27l-.194.98q.453.09.884.237zm1.873.925a8 8 0 0 0-.906-.524l-.443.896q.413.205.793.459zM4.46.824q-.471.233-.905.524l.556.83a7 7 0 0 1 .793-.458zM2.725 1.985q-.394.346-.74.74l.752.66q.303-.345.648-.648zm11.29.74a8 8 0 0 0-.74-.74l-.66.752q.346.303.648.648zm1.161 1.735a8 8 0 0 0-.524-.905l-.83.556q.254.38.458.793l.896-.443zM1.348 3.555q-.292.433-.524.906l.896.443q.205-.413.459-.793zM.423 5.428a8 8 0 0 0-.27 1.011l.98.194q.09-.453.237-.884zM15.848 6.44a8 8 0 0 0-.27-1.012l-.948.321q.147.43.237.884zM.017 7.477a8 8 0 0 0 0 1.046l.998-.064a7 7 0 0 1 0-.918zM16 8a8 8 0 0 0-.017-.523l-.998.064a7 7 0 0 1 0 .918l.998.064A8 8 0 0 0 16 8M.152 9.56q.104.52.27 1.012l.948-.321a7 7 0 0 1-.237-.884l-.98.194zm15.425 1.012q.168-.493.27-1.011l-.98-.194q-.09.453-.237.884zM.824 11.54a8 8 0 0 0 .524.905l.83-.556a7 7 0 0 1-.458-.793zm13.828.905q.292-.434.524-.906l-.896-.443q-.205.413-.459.793zm-12.667.83q.346.394.74.74l.66-.752a7 7 0 0 1-.648-.648zm11.29.74q.394-.346.74-.74l-.752-.66q-.302.346-.648.648zm-1.735 1.161q.471-.233.905-.524l-.556-.83a7 7 0 0 1-.793.458zm-7.985-.524q.434.292.906.524l.443-.896a7 7 0 0 1-.793-.459zm1.873.925q.493.168 1.011.27l.194-.98a7 7 0 0 1-.884-.237zm4.132.271a8 8 0 0 0 1.012-.27l-.321-.948a7 7 0 0 1-.884.237l.194.98zm-2.083.135a8 8 0 0 0 1.046 0l-.064-.998a7 7 0 0 1-.918 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z"/>
              </svg></button></h6></th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Duyệt qua các dòng kết quả và hiển thị dữ liệu
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-0">' . $row["facultyId"] . '</h6></td>';
                    echo '<td class="border-bottom-0"><h6 class="fw-semibold mb-1">' . $row["facultyName"] . '</h6></td>';
                    echo '<td class="border-bottom-0">';
                    echo '<button type="button" class="btn btn-primary btn-sm btn-edit" 
                        data-bs-toggle="modal" 
                        data-bs-target="#facultyModal" 
                        data-faculty-id="' . $row["facultyId"] . '" 
                        data-faculty-name="' . $row["facultyName"] . '" >Edit</button>';
                    // echo ' <button type="button" class="btn btn-danger btn-sm btn-delete" data-faculty-id="' . $row["facultyId"] . '">Delete</button>';
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
    <div class="modal fade" id="facultyModal" tabindex="-1" aria-labelledby="facultyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="facultyModalLabel">Add New Faculty</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to add or edit faculty -->
                    <form id="facultyForm">
                        <div class="mb-3">
                            <label for="facultyName" class="form-label">Faculty Name</label>
                            <input type="text" class="form-control" id="facultyName" name="facultyName">
                        </div>
                        <input type="hidden" id="facultyId" name="facultyId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveFaculty">Save</button>
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
    <script src="../assets/js/data-table.js"></script>
    <script src="../assets/js/jquery.dataTables.js"></script>

    <script>
    // Sự kiện khi cửa sổ modal được ẩn đi
    $('#facultyModal').on('hide.bs.modal', function() {
        // Xóa nội dung của các ô input trong biểu mẫu
        $('#facultyForm').trigger('reset');
        // Đặt giá trị rỗng cho trường input facultyId
        $('#facultyId').val('');
        // Đặt lại tiêu đề modal
        $('#facultyModalLabel').text('Add New Faculty');
    });



    // Sự kiện khi nhấp vào nút "Edit"
    $('.btn-edit').click(function() {
        // Lấy thông tin của tạp chí từ các thuộc tính data
        var facultyId = $(this).data('faculty-id');
        var facultyName = $(this).data('faculty-name');

        // Đưa thông tin của tạp chí vào các trường input trong cửa sổ modal
        $('#facultyId').val(facultyId);
        $('#facultyName').val(facultyName);


        // Đổi tiêu đề của cửa sổ modal thành "Edit faculty"
        $('#facultyModalLabel').text('Edit Faculty');

        // Mở cửa sổ modal
        $('#facultyModal').modal('show');
    });


    // Sự kiện khi nhấp vào nút "Add"
    $('#addFaculty').click(function() {
        // Xóa nội dung của các ô input trong biểu mẫu
        $('#facultyForm').trigger('reset');
        //
        $('#facultyModalLabel').butt('Add New Faculty');
        // Đổi tiêu đề của cửa sổ modal thành "Add New faculty"
        $('#facultyModalLabel').text('Add New Faculty');

        // Mở cửa sổ modal
        $('#facultyModal').modal('show');
    });

    // Sự kiện khi người dùng nhấp vào nút "Save"
    $('#saveFaculty').click(function() {
        // Lấy dữ liệu từ biểu mẫu
        var formData = $('#facultyForm').serialize();

        // Gửi yêu cầu AJAX để thêm hoặc cập nhật tạp chí
        $.ajax({
            url: '../../Backend/save_faculty.php', // Thay thế đường dẫn bằng tên tệp xử lý
            type: 'POST',
            data: formData,
            success: function(response) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(response);

                // Sau khi thêm hoặc cập nhật thành công, đóng cửa sổ modal
                $('#facultyModal').modal('hide');

                // Sau đó, làm mới trang để cập nhật danh sách tạp chí
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    // Sự kiện khi nhấp vào nút "Delete"
    $('.btn-delete').click(function() {
        // Lấy ID của khoa từ thuộc tính dữ liệu data-faculty-id
        var facultyId = $(this).data('faculty-id');

        // Hiển thị cửa sổ xác nhận xóa bằng SweetAlert2
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi yêu cầu xóa tạp chí qua AJAX
                $.ajax({
                    url: '../../Backend/delete_faculty.php', // Thay thế đường dẫn bằng tên tệp xử lý
                    type: 'POST',
                    data: {
                        faculty_id: facultyId
                    }, // Gửi ID tạp chí cần xóa
                    success: function(response) {
                        // Xử lý phản hồi từ máy chủ nếu cần
                        console.log(response);

                        // Hiển thị thông báo xóa thành công bằng SweetAlert2
                        Swal.fire(
                            'Deleted!',
                            'Faculty has been deleted.',
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