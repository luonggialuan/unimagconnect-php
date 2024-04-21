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
    <title>Modernize Free</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
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

    <link rel="shortcut icon" href="../assets/assets_table/images/favicon.png" />
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
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);
            ?>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4">
                                        <h5 class="card-title fw-semibold mb-4">Approve accounts request</h5>
                                        <br>
                                        <div class="table-responsive">
                                            <table id="dataTableExample" class="table text-nowrap mb-0 align-middle">
                                                <thead class="text-dark fs-4">
                                                    <tr>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">UserName</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Name</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Email</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Address</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Faculty</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Role</h6>
                                                        </th>
                                                        <th class="border-bottom-0">
                                                            <h6 class="fw-semibold mb-0">Status</h6>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo    "<td class='border-bottom-0'>";
                                                            echo     "   <h6 class='fw-semibold mb-0'>" . $row["username"] . "</h6>";
                                                            echo    "</td>";
                                                            echo    "<td class='border-bottom-0'>";
                                                            echo     "   <h6 class='fw-semibold mb-1'>" . $row["name"] . "</h6>";
                                                            echo      "  <span class='fw-normal'></span>";
                                                            echo    "</td>";
                                                            echo   "<td class='border-bottom-0'>";
                                                            echo    "<p class='mb-0 fw-normal'>" . $row["email"] . "</p>";
                                                            echo "</td>";
                                                            echo "<td class='border-bottom-0'>";
                                                            echo "   <h6 class='fw-semibold mb-0'>" . $row["address"] . "</h6>";
                                                            echo "</td>";
                                                            
                                                            ?>
                                                    <td class='border-bottom-0'>
                                                        <?php
                                                            // Lấy tên của vai trò từ bảng roles
                                                            $facultyId = $row["facultyId"];

                                                            if ($facultyId !== null) {
                                                                $facultySql = "SELECT * FROM faculties WHERE facultyId = $facultyId";
                                                                $facultyResult = $conn->query($facultySql);
                                                            
                                                                if ($facultyResult->num_rows > 0) {
                                                                    $facultyRow = $facultyResult->fetch_assoc();
                                                                    echo "<h6 class='fw-semibold mb-0'>" . $facultyRow["facultyName"] . "</h6>";
                                                                } else {
                                                                    echo "Unknown Faculty";
                                                                }
                                                            } else {
                                                                echo "<h6 class='fw-semibold mb-0'>No Faculty</h6>";
                                                            }                                                            
                                                            ?>
                                                    </td>


                                                    <td class='border-bottom-0'>
                                                        <?php
                                                            // Lấy tên của vai trò từ bảng roles
                                                            $roleId = $row["roleId"];
                                                            $roleSql = "SELECT * FROM roles WHERE roleId = $roleId";
                                                            $roleResult = $conn->query($roleSql);
                                                            if ($roleResult->num_rows > 0) {
                                                                $roleRow = $roleResult->fetch_assoc();
                                                                echo "<h6 class='fw-semibold mb-0'>" . $roleRow["roleName"] . "</h6>";
                                                            } else {
                                                                echo "Unknown role";
                                                            }
                                                            ?>
                                                    </td>
                                                    <td class="border-bottom-0">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <label class="switch"
                                                                onclick="toggleStatus(this, <?php echo $row['userId']; ?>)">
                                                                <input type="checkbox"
                                                                    <?php echo ($row["status"] == 1) ? 'checked' : ''; ?>>
                                                                <span class="slider"></span>
                                                            </label>
                                                        </div>
                                                    </td>

                                                    <?php
                                                            echo "</td>";
                                                            echo "</tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='4'>No users found.</td></tr>";
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
    function toggleStatus(label, userId) {
        var checkbox = label.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;

        // Gửi yêu cầu AJAX để cập nhật trạng thái trong cơ sở dữ liệu
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Backend/update_status_user.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Xử lý phản hồi từ máy chủ nếu cần
                console.log(xhr.responseText);
            }
        };
        xhr.send("userId=" + userId + "&status=" + (checkbox.checked ? 1 : 0));
    }
    </script>

</body>

</html>