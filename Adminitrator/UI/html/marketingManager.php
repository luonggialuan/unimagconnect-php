<?php
include_once ("../../../connect.php");
require_once '../../../permissions.php';
checkAccess([ROLE_MARKETING_MANAGER], $conn);
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

    <link rel="shortcut icon" href="../../assets/assets_table/images/favicon.png" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js
"></script>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css
" rel="stylesheet">


    <style>
    #checkDefault.form-check-input {
        font-weight: bold;
        border: 1px solid black;
    }

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
    </style>

</head>
<?php 


if (isset ($_SESSION['returnError']) && $_SESSION['returnError'] !== null) {
    $title = $_SESSION['returnError']; // Tạo tiêu đề có chứa $_SESSION['return']
    echo "<script>";
    echo "Swal.fire({";
    echo "    position: 'center',";
    echo "    icon: 'error',"; // 'Errors' corrected to 'error'
    echo "    title: '" . $title . "',"; // concatenate $title variable
    echo "    showConfirmButton: true,";
    echo "    timer: 3000";
    echo "});";
    echo "</script>";

    unset($_SESSION['returnError']);
}

if (isset ($_SESSION['return']) && $_SESSION['return'] !== null) {
    $title = $_SESSION['return']; // Tạo tiêu đề có chứa $_SESSION['return']
    echo "<script>";
    echo "Swal.fire({";
    echo "    position: 'center',";
    echo "    icon: 'success',";
    echo "    title: '$title',";
    echo "    showConfirmButton: true,";
    echo "    timer: 3000";
    echo "});";
    echo "</script>";

    unset($_SESSION['return']);
}

if (isset ($_SESSION['returnInfo']) && $_SESSION['returnInfo'] !== null) {
    $title = $_SESSION['returnInfo']; // Tạo tiêu đề có chứa $_SESSION['return']
    echo "<script>";
    echo "Swal.fire({";
    echo "    position: 'center',";
    echo "    icon: 'info',";
    echo "    title: '$title',";
    echo "    showConfirmButton: true,";
    echo "    timer: 3000";
    echo "});";
    echo "</script>";

    unset($_SESSION['returnInfo']);
}
?>

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


            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-10">
                                        <h5 class="card-title fw-semibold mb-4">Articles is approved</h5>
                                        <form action="../../../download.php" method="post"
                                            enctype="multipart/form-data">
                                            <div class="d-flex justify-content-end">
                                                <br>
                                                <div class="ms-auto">
                                                    <div class="btn-group me-2">
                                                        <select class="form-select" name="optionMagazine">
                                                            <option value="optionAllMagazine">All Magazines</option>
                                                            <?php
                                                                $sql_magazines = "SELECT * FROM magazine";
                                                                $result_magazines = $conn->query($sql_magazines);
                                                                while ($row = $result_magazines->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?= $row['magazineId'] ?>">
                                                                <?= $row['magazineName'] ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                    <div class="btn-group me-2">
                                                        <select class="form-select" name="optionFaculty">
                                                            <option value="optionAllFaculty">All Faculties</option>
                                                            <?php
                                                                $sql_faculties = "SELECT * FROM faculties";
                                                                $result_faculties = $conn->query($sql_faculties);
                                                                while ($row = $result_faculties->fetch_assoc()) {
                                                            ?>
                                                            <option value="<?= $row['facultyId'] ?>">
                                                                <?= $row['facultyName'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="btn-group">
                                                        <button type="submit" class="btn btn-primary me-2"
                                                            id="downloadAll" name="btnDownloadAll">
                                                            Download All
                                                        </button>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="submit" class="btn btn-primary me-2" id="download"
                                                            name="btnDownload">
                                                            Download
                                                        </button>
                                                    </div>

                                                </div>
                                            </div>

                                            <div><br></div>


                                            <div class="table-responsive">
                                                <table id="dataTableExample"
                                                    class="table text-nowrap mb-0 align-middle">
                                                    <thead class="text-dark fs-4">
                                                        <tr>
                                                            <th
                                                                class="border-bottom-0 d-flex justify-content-center align-items-center">
                                                                <h6 class="fw-semibold mb-0">Download</h6>
                                                            </th>
                                                            <th class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">Title of article</h6>
                                                            </th>
                                                            <th class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">Submit date</h6>
                                                            </th>
                                                            <th class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">Magazine</h6>
                                                            </th>

                                                            <th class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">Status public</h6>
                                                            </th>
                                                            <th class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">Action</h6>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                    $re = mysqli_query($conn, 
                                                    "SELECT * FROM articles 
                                                    INNER JOIN magazine ON articles.magazineId = magazine.magazineId 
                                                    WHERE status = 1
                                                    ");

                                                    while ($row = mysqli_fetch_assoc($re)) {
                                                        
                                                        $showStatus = $row['showStatus'] == 0 ? 'None' : 'Public';
                                                        $badgeClass = $row['showStatus'] == 0 ? 'bg-danger' : 'bg-primary';
                                                        ?>
                                                        <tr>
                                                            <td
                                                                class="border-bottom-0 d-flex justify-content-center align-items-center">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <input type="checkbox" class="form-check-input"
                                                                        id="checkDefault" name="articleId[]"
                                                                        value="<?= $row['articleId'] ?>">
                                                                </div>
                                                            </td>
                                                            <td class="border-bottom-0" style="width: 30%">
                                                                <!-- <h6 class="fw-semibold mb-1"> -->
                                                                <?= $row['title'] ?>
                                                                <!-- </h6> -->
                                                            </td>
                                                            <td class="border-bottom-0">
                                                                <p class="mb-0 fw-normal">
                                                                    <?= $row['submitDate'] ?>
                                                                </p>
                                                            </td>
                                                            <td class="border-bottom-0">
                                                                <h6 class="fw-semibold mb-0">
                                                                    <?= $row['magazineName'] ?>
                                                                </h6>
                                                            </td>
                                                            <td class="border-bottom-0">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span
                                                                        class="badge <?= $badgeClass ?> rounded-3 fw-semibold">
                                                                        <?= $showStatus ?>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td class="border-bottom-0">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <label class="switch"
                                                                        onclick="toggleStatus(this, <?php echo $row['articleId']; ?>)">
                                                                        <input type="checkbox"
                                                                            <?php echo ($row["showStatus"] == 1) ? 'checked' : ''; ?>>
                                                                        <span class="slider"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
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
        var checkbox = label.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;

        // Gửi yêu cầu AJAX để cập nhật trạng thái trong cơ sở dữ liệu
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../Backend/update_showStatus.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Xử lý phản hồi từ máy chủ
                var response = JSON.parse(xhr.responseText);
                // Cập nhật badgeClass và showStatus
                var badgeClass = response.badgeClass;
                var showStatus = response.showStatus;
                // Cập nhật badge trên giao diện
                var badgeElement = label.closest('tr').querySelector('.badge');
                badgeElement.className = "badge " + badgeClass + " rounded-3 fw-semibold";
                badgeElement.innerText = showStatus;
            }
        };
        xhr.send("articleId=" + articleId + "&showStatus=" + (checkbox.checked ? 1 : 0));
    }
    </script>
</body>

</html>