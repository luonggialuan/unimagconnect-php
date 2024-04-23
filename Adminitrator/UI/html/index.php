<?php
include_once ("../../../connect.php");

require_once '../../../permissions.php';
// $userId = $_SESSION['userId'];
checkAccess([ROLE_ADMIN, ROLE_MARKETING_COORDINATOR, ROLE_MARKETING_MANAGER], $conn);
$userRole = getUserRole($conn);

?>
<!doctype html>
<html lang="en">

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

    <!-- <link rel="shortcut icon" href="../assets/assets_table/images/favicon.png" /> -->
    <style>
    .contribution-box {
        width: 200px;
        height: 150px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .contribution-box:hover {
        transform: scale(1.1);
        /* Tăng kích thước lên 10% */
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    }

    .contribution-box:nth-child(1) {
        background-color: #3498db;
        /* Màu cho khung thứ nhất */
    }

    .contribution-box:nth-child(2) {
        background-color: #e74c3c;
        /* Màu cho khung thứ hai */
    }

    .contribution-box:nth-child(3) {
        background-color: #2ecc71;
        /* Màu cho khung thứ ba */
    }

    .contribution-box:nth-child(4) {
        background-color: #f39c12;
        /* Màu cho khung thứ tư */
    }


    #contributors-count {
        font-size: 36px;
        font-weight: bold;
        color: #fff;
    }

    .label {
        font-size: 16px;
        margin-top: 5px;
        color: #fff;
    }
    </style>
</head>
<?php
if ($userRole == ROLE_ADMIN || $userRole == ROLE_MARKETING_MANAGER) {
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

            <?php
                // Khởi tạo câu truy vấn SQL cơ bản
                $sql = "SELECT 
            f.facultyId, 
            f.facultyName, 
            COUNT(a.articleId) AS numArticles,
            ROUND((COUNT(a.articleId) / totalArticles.totalArticlesInYear) * 100, 2) AS percentArticles
        FROM 
            faculties f
        LEFT JOIN 
            users u ON f.facultyId = u.facultyId
        LEFT JOIN 
            articles a ON u.userId = a.authorId
        LEFT JOIN 
            (SELECT 
                YEAR(submitDate) AS submitYear, 
                COUNT(articleId) AS totalArticlesInYear 
            FROM 
                articles 
            GROUP BY 
                YEAR(submitDate)) AS totalArticles ON YEAR(a.submitDate) = totalArticles.submitYear";

                // Kiểm tra xem người dùng đã chọn năm từ dropdown chưa
                if (isset($_GET['year'])) {
                    // Lấy giá trị năm từ yêu cầu GET
                    $selectedYear = $_GET['year'];

                    // Thêm điều kiện vào câu truy vấn để chỉ lấy dữ liệu cho năm được chọn
                    $sql .= " WHERE YEAR(a.submitDate) = $selectedYear";
                }

                // Tiếp tục câu truy vấn SQL (GROUP BY, ORDER BY, ...)
                $sql .= " GROUP BY f.facultyId, f.facultyName";

                // Thực hiện truy vấn SQL và xử lý kết quả
                $result = $conn->query($sql);

                // Khởi tạo mảng để lưu dữ liệu
                $data = array();

                // Lặp qua kết quả và đưa vào mảng
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }

                // Chuyển đổi thành định dạng JSON
                $data_json = json_encode($data);

                ?>

            <?php
                $sqlMagazine = "SELECT m.magazineId, m.magazineName, COUNT(a.articleId) AS numArticles
        FROM magazine m
        LEFT JOIN articles a ON m.magazineId = a.magazineId
        GROUP BY m.magazineId, m.magazineName";

                $resultMagazine = $conn->query($sqlMagazine);

                $magazineData = array();
                while ($rowMagazine = $resultMagazine->fetch_assoc()) {
                    $magazineData[] = $rowMagazine;
                }
                ?>
            <?php
                // Khởi tạo mảng để lưu số lượng bài báo theo từng năm
                $articlesByYear = array();

                // Lấy dữ liệu từ bảng articles và trích xuất năm từ cột submitDate
                $sqlByYear = "SELECT YEAR(submitDate) AS submitYear, COUNT(*) AS numArticles
              FROM articles
              GROUP BY YEAR(submitDate)
              ORDER BY submitYear";
                $resultByYear = $conn->query($sqlByYear);

                // Lặp qua kết quả và đếm số lượng bài báo theo từng năm
                while ($rowByYear = $resultByYear->fetch_assoc()) {
                    $submitYear = $rowByYear['submitYear']; // Lấy năm từ cột submitYear
                    $articlesByYear[$submitYear] = $rowByYear['numArticles']; // Lưu số lượng bài báo cho từng năm
                }

                // Chuyển mảng kết quả thành JSON để sử dụng trong mã JavaScript
                $articlesByYearJSON = json_encode($articlesByYear);
                ?>

            <?php
                // Truy vấn CSDL
                $sqlfaculties = "SELECT f.facultyName,
               SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS numActivatedArticles,
               SUM(CASE WHEN a.status = 2 THEN 1 ELSE 0 END) AS numInactivatedArticles,
               SUM(CASE WHEN a.status = 0 THEN 1 ELSE 0 END) AS numPendingArticles
        FROM faculties f
        LEFT JOIN users u ON f.facultyId = u.facultyId
        LEFT JOIN articles a ON u.userId = a.authorId
        GROUP BY f.facultyName";

                $resultfaculties = $conn->query($sqlfaculties);

                // Xử lý kết quả
                $datafaculties = array();
                while ($rowfaculties = $resultfaculties->fetch_assoc()) {
                    $datafaculties[] = $rowfaculties;
                }

                // Chuyển đổi dữ liệu thành định dạng dùng cho biểu đồ
                $facultyNames = [];
                $numActivatedArticles = [];
                $numInactivatedArticles = [];
                $numPendingArticles = [];

                foreach ($datafaculties as $rowfaculties) {
                    $facultyNames[] = $rowfaculties['facultyName'];
                    $numActivatedArticles[] = $rowfaculties['numActivatedArticles'];
                    $numInactivatedArticles[] = $rowfaculties['numInactivatedArticles'];
                    $numPendingArticles[] = $rowfaculties['numPendingArticles'];
                }
                ?>


            <?php
                // Truy vấn CSDL để lấy số lượng bài báo được duyệt và từ chối cho từng tạp chí
                $sqlMagazineApprovalRatio = "SELECT m.magazineName,
                    SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS approvedArticles,
                    SUM(CASE WHEN a.status = 2 THEN 1 ELSE 0 END) AS rejectedArticles,
                    COUNT(a.articleId) AS totalArticles
                    FROM magazine m
                    LEFT JOIN articles a ON m.magazineId = a.magazineId
                    WHERE a.status <> 0
                    GROUP BY m.magazineId, m.magazineName;";

                $resultMagazineApprovalRatio = $conn->query($sqlMagazineApprovalRatio);

                // Xử lý kết quả
                $approvalRatioData = array();
                while ($rowMagazineApprovalRatio = $resultMagazineApprovalRatio->fetch_assoc()) {
                    $magazineName = $rowMagazineApprovalRatio['magazineName'];
                    $approvedArticles = $rowMagazineApprovalRatio['approvedArticles'];
                    $rejectedArticles = $rowMagazineApprovalRatio['rejectedArticles'];
                    $totalArticles = $rowMagazineApprovalRatio['totalArticles'];

                    // Tính tỷ lệ duyệt và từ chối
                    $approvalRatio = $totalArticles > 0 ? round(($approvedArticles / $totalArticles) * 100, 2) : 0;
                    $rejectionRatio = $totalArticles > 0 ? round(($rejectedArticles / $totalArticles) * 100, 2) : 0;

                    // Lưu dữ liệu vào mảng
                    $approvalRatioData[] = array(
                        'magazineName' => $magazineName,
                        'approvalRatio' => $approvalRatio,
                        'rejectionRatio' => $rejectionRatio
                    );
                }
                ?>
            <?php
                $sqlPeople = "SELECT COUNT(DISTINCT authorId) AS total_contributors FROM articles";
                $resultPeople = mysqli_query($conn, $sqlPeople);

                // Kiểm tra xem truy vấn có thành công không
                if ($resultPeople) {
                    // Lấy dòng dữ liệu từ kết quả truy vấn
                    $row = mysqli_fetch_assoc($resultPeople);
                    // Gán giá trị số lượng người đóng góp vào mảng
                    $PeopleData['total_contributors'] = $row['total_contributors'];

                    // Chuyển đổi dữ liệu sang JSON
                    $PeopleDataJSON = json_encode($PeopleData);
                } else {
                    // Xử lý trường hợp truy vấn không thành công
                    echo "Lỗi: " . mysqli_error($conn);
                }
                ?>
            <?php
                $sqlArticles = "SELECT COUNT(*) AS total_articles FROM articles";
                $resultArticles = mysqli_query($conn, $sqlArticles);

                // Kiểm tra xem truy vấn có thành công không
                if ($resultArticles) {
                    // Lấy dòng dữ liệu từ kết quả truy vấn
                    $row = mysqli_fetch_assoc($resultArticles);
                    // Gán giá trị số lượng bài báo vào mảng
                    $ArticlesData['total_articles'] = $row['total_articles'];

                    // Chuyển đổi dữ liệu sang JSON
                    $ArticlesDataJSON = json_encode($ArticlesData);
                } else {
                    // Xử lý trường hợp truy vấn không thành công
                    echo "Lỗi: " . mysqli_error($conn);
                }

                ?>
            <?php
                // Truy vấn SQL để đếm tổng số tài khoản được tạo
                $sqlAccounts = "SELECT COUNT(*) AS total_accounts FROM users";
                $resultAccounts = mysqli_query($conn, $sqlAccounts);

                // Kiểm tra xem truy vấn có thành công không
                if ($resultAccounts) {
                    // Lấy dòng dữ liệu từ kết quả truy vấn
                    $rowAccounts = mysqli_fetch_assoc($resultAccounts);
                    // Lấy giá trị tổng số tài khoản được tạo
                    $totalAccounts = $rowAccounts['total_accounts'];

                    // Định dạng số tài khoản
                    $formattedTotalAccounts = number_format($totalAccounts);

                    // Chuyển đổi dữ liệu sang JSON
                    $accountsDataJSON = json_encode(['total_accounts' => $formattedTotalAccounts]);
                } else {
                    // Xử lý trường hợp truy vấn không thành công
                    echo "Lỗi: " . mysqli_error($conn);
                }
                ?>
            <?php
                $sql = "SELECT SUBSTRING_INDEX(accessedPage, '&id', 1) AS basePage, COUNT(*) AS pageViews FROM accesslog GROUP BY basePage";
                $result = mysqli_query($conn, $sql);

                // Ánh xạ giữa accessedPage và nhãn tương ứng
                $pageLabels = array(
                    "" => "Home Page",
                    "statistics" => "Statistics",
                    "magazineStudent" => "Magazine Student",
                    "addArticleStudent" => "Magazine Add Article Student",
                    "updateArticleStudent" => "Update Article Student",
                    "your-articles" => "Articles history",
                    "signin" => "Signin"
                );

                // Xử lý dữ liệu để chuẩn bị cho biểu đồ
                $labels = array();
                $pageViews = array();

                while ($row = mysqli_fetch_assoc($result)) {
                    $accessedPage = $row['basePage'];

                    // Trích xuất phần query string từ đường dẫn URL
                    $query = parse_url($accessedPage, PHP_URL_QUERY);

                    // Kiểm tra xem query string có tồn tại không
                    if ($query !== null) {
                        // Nếu có, phân tích các tham số từ query string
                        parse_str($query, $params);

                        // Lấy giá trị của tham số 'page' từ query string
                        $page = isset($params['page']) ? $params['page'] : '';
                    } else {
                        // Nếu không có query string, gán giá trị mặc định cho 'page'
                        $page = '';
                    }

                    // Lấy nhãn tương ứng từ pageLabels hoặc sử dụng page nếu không có nhãn
                    $accessedPageLabel = isset($pageLabels[$page]) ? $pageLabels[$page] : $page;

                    $labels[] = $accessedPageLabel;
                    $pageViews[] = $row['pageViews'];
                }


                // Truy vấn SQL để lấy thông tin về số lần hoạt động của từng người dùng
                $sqlUserActivity = "SELECT u.Name, COUNT(a.userId) AS activityCount 
                    FROM users u 
                    INNER JOIN accesslog a ON u.userId = a.userId 
                    GROUP BY a.userId";
                $resultUserActivity = mysqli_query($conn, $sqlUserActivity);

                // Xử lý dữ liệu về hoạt động của người dùng
                $userActivityLabels = array();
                $userActivityData = array();
                while ($row = mysqli_fetch_assoc($resultUserActivity)) {
                    $userActivityLabels[] = $row['Name'];
                    $userActivityData[] = $row['activityCount'];
                }

                // Truy vấn SQL để lấy thông tin về trình duyệt đang được sử dụng
                function getBrowserName($browserInfo)
                {
                    if (strpos($browserInfo, 'Edg') !== false) {
                        return 'Edge';
                    } elseif (strpos($browserInfo, 'Chrome') !== false) {
                        return 'Chrome';
                    } elseif (strpos($browserInfo, 'Firefox') !== false) {
                        return 'Firefox';
                    } elseif (strpos($browserInfo, 'Safari') !== false) {
                        return 'Safari';
                    } elseif (strpos($browserInfo, 'Opera') !== false) {
                        return 'Opera';
                    } elseif (strpos($browserInfo, 'Coc Coc') !== false) {
                        return 'Coc Coc';
                    } else {
                        // Nếu không phát hiện ra trình duyệt nào, trả về chuỗi browserInfo ban đầu
                        return $browserInfo;
                    }
                }

                // Tiếp tục xử lý dữ liệu từ cơ sở dữ liệu
                $sqlBrowsers = "SELECT browserInfo, COUNT(*) AS browserCount FROM accesslog GROUP BY browserInfo";
                $resultBrowsers = mysqli_query($conn, $sqlBrowsers);

                // Xử lý dữ liệu về trình duyệt
                $browserLabels = array();
                $browserData = array();

                while ($row = mysqli_fetch_assoc($resultBrowsers)) {
                    $browserLabels[] = getBrowserName($row['browserInfo']);
                    $browserData[] = $row['browserCount'];
                }

                ?>
            <?php
                // Tên biểu đồ
                $chartName1 = "Faculty contribution rates have data by year";
                $chartDescription1 = "This chart shows each department's contribution rate for each academic year.";

                $selectedYear = date("Y");
                if (isset($_GET['year'])) {
                    $selectedYear = $_GET['year'];
                }
                // Câu truy vấn SQL với điều kiện cho năm đã chọn
                $sql1 = "SELECT 
    faculties.facultyId, 
    faculties.facultyName, 
    COUNT(articles.articleId) AS num_articles,
    ROUND(COUNT(articles.articleId) / total.total_articles * 100, 2) AS contribution_percentage
FROM 
    faculties
LEFT JOIN 
    users ON faculties.facultyId = users.facultyId
LEFT JOIN 
    articles ON users.userId = articles.authorId
LEFT JOIN 
    (SELECT 
        COUNT(articleId) AS total_articles 
    FROM 
        articles 
    WHERE 
        YEAR(submitDate) = $selectedYear
    ) AS total ON 1=1
WHERE 
    YEAR(articles.submitDate) = $selectedYear
GROUP BY 
    faculties.facultyId, faculties.facultyName;
    ";

                $result1 = $conn->query($sql1);

                $data1 = array();
                while ($row = $result1->fetch_assoc()) {
                    $data1[] = $row;
                }

                $data_json1 = json_encode($data1);



                ?>
            <?php
                // Truy vấn để thống kê số lượng bài báo theo khoa
                $selectedYear = date("Y");
                if (isset($_GET['year'])) {
                    $selectedYear = $_GET['year'];
                }
                $sql = "SELECT f.facultyName, 
                COUNT(DISTINCT u.userId) AS authorCount, 
                COUNT(*) AS articleCount 
            FROM articles a 
            JOIN users u ON a.authorId = u.userId 
            JOIN faculties f ON u.facultyId = f.facultyId 
            WHERE YEAR(a.submitDate) = $selectedYear
            GROUP BY f.facultyName";
                $result = mysqli_query($conn, $sql);

                // Xử lý dữ liệu để chuẩn bị cho biểu đồ
                $facultyNames = array();
                $articleCounts = array();

                while ($row = mysqli_fetch_assoc($result)) {
                    $facultyNames[] = $row['facultyName'];
                    $articleCounts[] = $row['authorCount'];
                }
                ?>

            <?php
                // Kiểm tra năm được chọn từ tham số GET
                $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

                // Truy vấn SQL để lấy số lượng bài viết không có bình luận theo năm được chọn
                $sql = "SELECT YEAR(a.submitDate) AS submitYear, COUNT(a.articleId) AS articleCount
        FROM articles a
        LEFT JOIN comments c ON a.articleId = c.articleId
        WHERE c.commentId IS NULL AND YEAR(a.submitDate) = $selectedYear
        GROUP BY submitYear";

                $result = $conn->query($sql);

                $data = array();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                ?>
            <?php
                $sql = "SELECT COUNT(*) AS num_articles
FROM articles
WHERE NOW() > DATE_ADD(submitDate, INTERVAL 14 DAY)
AND articleId NOT IN (
    SELECT articleId
    FROM comments
    WHERE commentDate >= DATE_ADD(submitDate, INTERVAL 14 DAY)
)";

                $result = $conn->query($sql);

                $num_articles = 0;
                if ($result->num_rows > 0) {
                    // Lấy dữ liệu từ kết quả
                    $row = $result->fetch_assoc();
                    $num_articles = $row["num_articles"];
                }
                ?>

            <div class="container-fluid">
                <form class="row g-3" method="GET" action="">
                    <div class="col-auto">
                        <label for="year" class="col-form-label">Choose year:</label>
                    </div>
                    <div class="col-auto">
                        <select class="form-select" name="year" id="year">
                            <option value="">All year</option>
                            <?php
                                $sql_year = "SELECT magazineYear FROM magazine GROUP BY magazineYear";
                                $result_year = mysqli_query($conn, $sql_year);
                                while ($row_year = mysqli_fetch_assoc($result_year)) {
                                    echo "<option value='" . $row_year['magazineYear'] . "'>" . $row_year['magazineYear'] . "</option>";
                                }
                                ?>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>

                <div class="card">
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="contribution-box" style="background-color: #3498db;">
                                    <span id="contributors-count"><?php echo $ArticlesData['total_articles']; ?></span>
                                    <span class="label">Article</span>
                                </div>
                            </div>
                            <?php
                                if ($userRole == ROLE_ADMIN) {
                                    ?>
                            <div class="col-md-4">
                                <div class="contribution-box" style="background-color: #2ecc71;">
                                    <span id="contributors-count"><?php echo number_format($totalAccounts); ?></span>
                                    <span class="label">Accounts</span>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="col-md-4">
                                <div class="contribution-box" style="background-color: #f39c12;">
                                    <span
                                        id="contributors-count"><?php echo $PeopleData['total_contributors']; ?></span>
                                    <span class="label">Contributors</span>
                                </div>
                            </div>
                        </div>
                        <?php
                            if ($userRole == ROLE_ADMIN) {
                                ?>
                        <div class="row mt-4">
                            <!-- Biểu đồ cho trang truy cập -->
                            <div class="col-lg-4">
                                <h2 class="text-center">Page Access</h2>
                                <div class="chart-container" style="position: relative; height: 400px;">
                                    <canvas id="pageAccessChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                            <!-- Biểu đồ cho hoạt động người dùng -->
                            <div class="col-lg-4">
                                <h2 class="text-center">User Activity</h2>
                                <div class="chart-container" style="position: relative; height: 400px;">
                                    <canvas id="userActivityChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                            <!-- Biểu đồ cho việc sử dụng trình duyệt -->
                            <div class="col-lg-4">
                                <h2 class="text-center">Browser Usage</h2>
                                <div class="chart-container" style="position: relative; height: 400px;">
                                    <canvas id="browserUsageChart" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of Contributor</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="facultyContribuildtorChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <!-- First column for Magazine -->
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of Articles by Magazine</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="height: 400px;">
                                            <canvas id="magazineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of Articles by Faculties</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="facultyChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- New row for Year -->
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Contribution rate of each Faculty
                                            during the year
                                            learn in <?php
                                                if (isset($_GET['year'])) {
                                                    // Lấy giá trị năm từ yêu cầu GET
                                                    $selectedYear = $_GET['year'];

                                                    // Thêm điều kiện vào câu truy vấn để chỉ lấy dữ liệu cho năm được chọn
                                                    echo "in $selectedYear";
                                                } ?></h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="chart1"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Magazine Approval Ratio</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="approvalRatioChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of articles without comments</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="noCommentChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of articles without comment for
                                            14 days</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="myChart"></canvas>
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


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>
    <script>
    // Lấy dữ liệu từ PHP và chuyển thành biến JavaScript
    var num_articles = <?php echo $num_articles; ?>;

    // Lấy canvas để vẽ biểu đồ
    var ctx = document.getElementById('myChart').getContext('2d');

    // Tạo biểu đồ
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['This post has no comments for 14 days'],
            datasets: [{
                label: 'Quantity',
                data: [num_articles],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    </script>
    <script>
    // Dữ liệu từ PHP được chuyển sang biến JavaScript
    var data = <?php echo json_encode($data); ?>;

    // Tạo mảng chứa năm và số lượng bài viết không có bình luận
    var years = [];
    var articleCounts = [];

    // Đổ dữ liệu từ PHP vào mảng JavaScript
    data.forEach(function(item) {
        years.push(item.submitYear); // Lấy năm và thêm vào mảng
        articleCounts.push(item.articleCount); // Lấy số lượng bài viết không có bình luận và thêm vào mảng
    });

    // Vẽ biểu đồ cột
    var ctx = document.getElementById('noCommentChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: years, // Năm
            datasets: [{
                label: 'Number of posts without comments',
                data: articleCounts, // Số lượng bài viết không có bình luận
                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Màu của cột
                borderColor: 'rgba(255, 99, 132, 1)', // Viền của cột
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>


    <script>
    // Lấy dữ liệu từ PHP và chuyển thành JavaScript object
    var data = <?php echo json_encode($data); ?>;

    // Tạo mảng chứa tên khoa và tỷ lệ phần trăm bài báo cáo
    var facultyNames = [];
    var percentArticles = [];

    // Đổ dữ liệu từ PHP vào mảng JavaScript và tính tỷ lệ phần trăm
    data.forEach(function(item) {
        facultyNames.push(item.facultyName); // Lấy tên khoa và thêm vào mảng
        percentArticles.push(item.percentArticles); // Lấy tỷ lệ phần trăm bài báo cáo và thêm vào mảng
    });

    // Mảng màu cho các cột
    var colors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'
    ];

    // Vẽ biểu đồ tròn
    var ctx = document.getElementById('articleChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie', // Loại biểu đồ tròn
        data: {
            labels: facultyNames, // Tên của các khoa
            datasets: [{
                label: 'Number of Articles', // Chú thích cho dữ liệu biểu đồ
                data: percentArticles, // Tỷ lệ phần trăm bài báo cáo cho từng khoa
                backgroundColor: colors, // Mảng màu cho các cột
                borderColor: colors.map(color => color.replace('0.5', '1')), // Viền của các cột
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + '%'; // Thêm dấu % vào sau giá trị
                            return label;
                        }
                    }
                }
            }
        }
    });
    </script>


    <script>
    var magazineData = <?php echo json_encode($magazineData); ?>;

    var magazineNames = [];
    var numArticlesByMagazine = [];

    magazineData.forEach(function(item) {
        magazineNames.push(item.magazineName);
        numArticlesByMagazine.push(item.numArticles);
    });

    var magazineColors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'
    ];

    var magazineCtx = document.getElementById('magazineChart').getContext('2d');
    var magazineChart = new Chart(magazineCtx, {
        type: 'bar',
        data: {
            labels: magazineNames,
            datasets: [{
                label: 'Number of Articles',
                data: numArticlesByMagazine,
                backgroundColor: magazineColors,
                borderColor: magazineColors.map(color => color.replace('0.5', '1')),
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed;
                            return label;
                        }
                    }
                }
            }
        }
    });
    </script>
    <script>
    // Assume you have fetched data from the database and stored it in a variable named 'articlesByYear'
    var articlesByYear = <?php echo json_encode($articlesByYear); ?>;

    var years = Object.keys(articlesByYear);
    var numArticles = Object.values(articlesByYear);

    var ctx = document.getElementById('yearChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Sửa loại biểu đồ thành 'bar' để hiển thị cột
        data: {
            labels: years,
            datasets: [{
                label: 'Number of Articles',
                data: numArticles,
                backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền cho các cột
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script>
    var ctx = document.getElementById('facultyChart').getContext('2d');
    var facultyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($facultyNames); ?>,
            datasets: [{
                label: 'Activated Articles',
                data: <?php echo json_encode($numActivatedArticles); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }, {
                label: 'Inactivated Articles',
                data: <?php echo json_encode($numInactivatedArticles); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }, {
                label: 'Pending Articles',
                data: <?php echo json_encode($numPendingArticles); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.5)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script>
    // Lấy dữ liệu PHP và chuyển thành JavaScript object
    var approvalRatioData = <?php echo json_encode($approvalRatioData); ?>;

    // Tạo mảng chứa tên tạp chí và tỷ lệ duyệt, từ chối
    var magazineNames = [];
    var approvalRatios = [];
    var rejectionRatios = [];

    // Đổ dữ liệu từ PHP vào mảng JavaScript
    approvalRatioData.forEach(function(item) {
        magazineNames.push(item.magazineName); // Lấy tên tạp chí và thêm vào mảng
        approvalRatios.push(item.approvalRatio); // Lấy tỷ lệ duyệt và thêm vào mảng
        rejectionRatios.push(item.rejectionRatio); // Lấy tỷ lệ từ chối và thêm vào mảng
    });

    // Vẽ biểu đồ cột
    var ctx = document.getElementById('approvalRatioChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Loại biểu đồ cột
        data: {
            labels: magazineNames, // Tên của các tạp chí
            datasets: [{
                label: 'Approval Ratio (%)', // Chú thích cho dữ liệu tỷ lệ duyệt
                data: approvalRatios, // Tỷ lệ duyệt
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Màu nền cho cột tỷ lệ duyệt
                borderColor: 'rgba(54, 162, 235, 1)', // Màu viền cho cột tỷ lệ duyệt
                borderWidth: 1
            }, {
                label: 'Rejection Ratio (%)', // Chú thích cho dữ liệu tỷ lệ từ chối
                data: rejectionRatios, // Tỷ lệ từ chối
                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Màu nền cho cột tỷ lệ từ chối
                borderColor: 'rgba(255, 99, 132, 1)', // Màu viền cho cột tỷ lệ từ chối
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Bắt đầu trục y từ giá trị 0
                }
            }
        }
    });
    </script>
    <script>
    var pageAccessCtx = document.getElementById('pageAccessChart').getContext('2d');
    var pageAccessChart = new Chart(pageAccessCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Page Views',
                data: <?php echo json_encode($pageViews); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    var userActivityChart = new Chart(userActivityCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($userActivityLabels); ?>,
            datasets: [{
                label: 'Activity Count',
                data: <?php echo json_encode($userActivityData); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    var browserUsageCtx = document.getElementById('browserUsageChart').getContext('2d');
    var browserUsageChart = new Chart(browserUsageCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($browserLabels); ?>,
            datasets: [{
                label: 'Browser Count',
                data: <?php echo json_encode($browserData); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        }
    });
    </script>
    <script>
    // Lấy dữ liệu từ PHP và chuyển đổi thành JavaScript
    var facultyNames = <?php echo json_encode($facultyNames); ?>;
    var articleCounts = <?php echo json_encode($articleCounts); ?>;

    // Lấy thẻ canvas và vẽ biểu đồ
    var ctx = document.getElementById('facultyContribuildtorChart').getContext('2d');
    var facultyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: facultyNames,
            datasets: [{
                label: 'Number of Contribuildtor',
                data: articleCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script>
    // Dữ liệu JSON từ máy chủ
    var jsonData = <?php echo $data_json1; ?>;

    // Tạo mảng chứa tên khoa và phần trăm đóng góp của mỗi khoa
    var facultyNames = [];
    var contributionPercentages = [];
    var barColors = []; // Mảng chứa màu sắc riêng cho từng cột

    // Lặp qua dữ liệu JSON để lấy tên khoa, phần trăm đóng góp và màu sắc cho từng cột
    for (var i = 0; i < jsonData.length; i++) {
        facultyNames.push(jsonData[i].facultyName);
        contributionPercentages.push(jsonData[i].contribution_percentage);
        // Thêm màu sắc riêng cho từng cột (có thể tùy chỉnh)
        barColors.push(getRandomColor()); // Hàm này sẽ tạo màu sắc ngẫu nhiên, bạn có thể thay thế bằng logic của mình
    }

    var ctx = document.getElementById('chart1').getContext('2d');

    var fixedColors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)'
    ];

    var facultyChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: facultyNames,
            datasets: [{
                label: 'Contribution Percentage (%)',
                data: contributionPercentages,
                backgroundColor: fixedColors,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true
                }
            },
            onClick: function(evt, element) {
                var activePoints = facultyChart.getElementsAtEventForMode(evt, 'nearest', {
                    intersect: true
                }, true);

                if (activePoints.length > 0) {
                    var chartData = activePoints[0]._chart.config.data;
                    var idx = activePoints[0].index;
                    var label = chartData.labels[idx];
                    var value = chartData.datasets[0].data[idx];
                    alert(label + ': ' + value + '%');
                }
            }
        }
    });


    // Hàm tạo màu sắc ngẫu nhiên
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lắng nghe sự kiện gửi form
        document.querySelector('form').addEventListener('submit', function(event) {
            // Kiểm tra giá trị của dropdown 'year'
            var selectedYear = document.getElementById('year').value;
            if (selectedYear === '') {
                // Nếu giá trị là rỗng, xóa tham số 'year' khỏi URL và gửi form đi
                var currentUrl = window.location.href;
                var urlParts = currentUrl.split('?');
                if (urlParts.length > 1) {
                    var queryParams = urlParts[1].split('&');
                    var newQueryParams = queryParams.filter(function(param) {
                        return !param.startsWith('year='); // Lọc bỏ tham số 'year'
                    });
                    var newUrl = urlParts[0] + (newQueryParams.length > 0 ? '?' + newQueryParams.join(
                        '&') : '');
                    window.location.href = newUrl; // Chuyển hướng đến URL mới
                    event.preventDefault(); // Ngăn chặn việc gửi form một cách bình thường
                }
            }
        });
    });
    </script>
    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var selectedYear = document.getElementById('year').value;
        if (selectedYear === '') {
            event.preventDefault(); // Ngăn chặn việc gửi form đi
            var url = new URL(window.location.href);
            url.searchParams.delete('year'); // Xóa tham số 'year' khỏi URL
            window.location.href = url; // Chuyển hướng đến URL mới
        }
    });
    </script>

</body>
<?php
} else if ($userRole == ROLE_MARKETING_COORDINATOR || $userRole == ROLE_MARKETING_MANAGER) {
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

            <?php
                    $userId = $_SESSION['userid'];
                    $sql_faculty_name = "SELECT facultyName FROM faculties WHERE facultyId = (SELECT facultyId FROM users WHERE userId = $userId)";
                    $resultFacultyNameOnly = $conn->query($sql_faculty_name);
                    $rowFacultyNameOnly = $resultFacultyNameOnly->fetch_assoc();
                    $facultyNameOnly = $rowFacultyNameOnly["facultyName"];


                    $sql = "SELECT f.facultyId, f.facultyName, COUNT(a.articleId) AS numArticles
                FROM faculties f
                LEFT JOIN users u ON f.facultyId = u.facultyId
                LEFT JOIN articles a ON u.userId = a.authorId
                WHERE f.facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
                GROUP BY f.facultyId, f.facultyName";


                    $result = $conn->query($sql);

                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }

                    $data_json = json_encode($data);


                    ?>

            <?php
                    $sqlMagazine = "SELECT m.magazineId, m.magazineName, f.facultyId, f.facultyName, COUNT(a.articleId) AS numArticles
                FROM faculties f
                INNER JOIN users u ON f.facultyId = u.facultyId
                INNER JOIN articles a ON u.userId = a.authorId
                INNER JOIN magazine m ON a.magazineId = m.magazineId
                WHERE f.facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
                GROUP BY m.magazineId, m.magazineName, f.facultyId, f.facultyName";

                    $resultMagazine = $conn->query($sqlMagazine);

                    $magazineData = array();
                    while ($rowMagazine = $resultMagazine->fetch_assoc()) {
                        $magazineData[] = $rowMagazine;
                    }
                    ?>
            <?php
                    // Khởi tạo mảng để lưu số lượng bài báo theo từng năm
                    $articlesByYear = array();

                    // Lấy dữ liệu từ bảng articles và trích xuất năm từ cột submitDate
                    $sqlByYear = "SELECT YEAR(submitDate) AS submitYear, COUNT(*) AS numArticles
                FROM articles
                INNER JOIN users ON articles.authorId = users.userId
                WHERE facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
                GROUP BY YEAR(submitDate)
                ORDER BY submitYear";
                    $resultByYear = $conn->query($sqlByYear);

                    // Lặp qua kết quả và đếm số lượng bài báo theo từng năm
                    while ($rowByYear = $resultByYear->fetch_assoc()) {
                        $submitYear = $rowByYear['submitYear']; // Lấy năm từ cột submitYear
                        $articlesByYear[$submitYear] = $rowByYear['numArticles']; // Lưu số lượng bài báo cho từng năm
                    }

                    // Chuyển mảng kết quả thành JSON để sử dụng trong mã JavaScript
                    $articlesByYearJSON = json_encode($articlesByYear);
                    ?>

            <?php
                    // Truy vấn CSDL
                    $sqlfaculties = "SELECT f.facultyName, f.facultyId,
                SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS numActivatedArticles,
                SUM(CASE WHEN a.status = 2 THEN 1 ELSE 0 END) AS numInactivatedArticles,
                SUM(CASE WHEN a.status = 0 THEN 1 ELSE 0 END) AS numPendingArticles
         FROM faculties f
         LEFT JOIN users u ON f.facultyId = u.facultyId
         LEFT JOIN articles a ON u.userId = a.authorId
         WHERE f.facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
         GROUP BY f.facultyName";

                    $resultfaculties = $conn->query($sqlfaculties);

                    // Xử lý kết quả
                    $datafaculties = array();
                    while ($rowfaculties = $resultfaculties->fetch_assoc()) {
                        $datafaculties[] = $rowfaculties;
                    }

                    // Chuyển đổi dữ liệu thành định dạng dùng cho biểu đồ
                    $facultyNames = [];
                    $numActivatedArticles = [];
                    $numInactivatedArticles = [];
                    $numPendingArticles = [];

                    foreach ($datafaculties as $rowfaculties) {
                        $facultyNames[] = $rowfaculties['facultyName'];
                        $numActivatedArticles[] = $rowfaculties['numActivatedArticles'];
                        $numInactivatedArticles[] = $rowfaculties['numInactivatedArticles'];
                        $numPendingArticles[] = $rowfaculties['numPendingArticles'];
                    }
                    ?>


            <?php
                    // Truy vấn CSDL để lấy số lượng bài báo được duyệt và từ chối cho từng tạp chí
                    $sqlMagazineApprovalRatio = "SELECT m.magazineName, u.facultyId,
            SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS approvedArticles,
            SUM(CASE WHEN a.status = 2 THEN 1 ELSE 0 END) AS rejectedArticles,
            COUNT(a.articleId) AS totalArticles
     FROM magazine m
     LEFT JOIN articles a ON m.magazineId = a.magazineId
     LEFT JOIN users u ON a.authorId = u.userId
     WHERE a.status <> 0
     AND u.facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
     GROUP BY m.magazineId, m.magazineName, u.facultyId";

                    $resultMagazineApprovalRatio = $conn->query($sqlMagazineApprovalRatio);

                    // Xử lý kết quả
                    $approvalRatioData = array();
                    while ($rowMagazineApprovalRatio = $resultMagazineApprovalRatio->fetch_assoc()) {
                        $magazineName = $rowMagazineApprovalRatio['magazineName'];
                        $approvedArticles = $rowMagazineApprovalRatio['approvedArticles'];
                        $rejectedArticles = $rowMagazineApprovalRatio['rejectedArticles'];
                        $totalArticles = $rowMagazineApprovalRatio['totalArticles'];

                        // Tính tỷ lệ duyệt và từ chối
                        $approvalRatio = $totalArticles > 0 ? round(($approvedArticles / $totalArticles) * 100, 2) : 0;
                        $rejectionRatio = $totalArticles > 0 ? round(($rejectedArticles / $totalArticles) * 100, 2) : 0;

                        // Lưu dữ liệu vào mảng
                        $approvalRatioData[] = array(
                            'magazineName' => $magazineName,
                            'approvalRatio' => $approvalRatio,
                            'rejectionRatio' => $rejectionRatio
                        );
                    }
                    ?>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- First column for Magazine -->
                            <div class="col-lg-6 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of Articles
                                            <?= $facultyNameOnly ?> by Magazine
                                        </h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="magazineChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Second column for Faculty -->
                            <div class="col-lg-6 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4">
                                        <h5 class="card-title fw-semibold mb-4 text-center">Number of Articles by
                                            <?= $facultyNameOnly ?>
                                        </h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="articleChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New row for Year -->
                        <div class="row">
                            <div class="col-lg-12 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Number of Articles
                                            <?= $facultyNameOnly ?> by Year
                                        </h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="yearChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">
                                            Number of Articles by <?= $facultyNameOnly ?>
                                        </h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="facultyChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex align-items-stretch">
                                <div class="card w-100">
                                    <div class="card-body p-4 text-center">
                                        <h5 class="card-title fw-semibold mb-4">Magazine Approval Ratio</h5>
                                        <div class="chart-container d-flex justify-content-center align-items-center"
                                            style="position: relative; height: 400px;">
                                            <canvas id="approvalRatioChart"></canvas>
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


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2"></script>

    <script>
    // Lấy dữ liệu từ PHP và chuyển thành JavaScript object
    var data = <?php echo json_encode($data); ?>;

    // Tạo mảng chứa tên khoa và số lượng bài báo cáo
    var facultyNames = [];
    var numArticles = [];

    // Đổ dữ liệu từ PHP vào mảng JavaScript
    data.forEach(function(item) {
        facultyNames.push(item.facultyName); // Lấy tên khoa và thêm vào mảng
        numArticles.push(item.numArticles); // Lấy số lượng bài báo cáo và thêm vào mảng
    });

    // Mảng màu cho các cột
    var colors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'
    ];

    // Vẽ biểu đồ bar
    var ctx = document.getElementById('articleChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Loại biểu đồ bar
        data: {
            labels: facultyNames, // Tên của các khoa
            datasets: [{
                label: facultyNames, // Chú thích cho dữ liệu biểu đồ
                data: numArticles, // Số lượng bài báo cáo cho từng khoa
                backgroundColor: colors, // Mảng màu cho các cột
                borderColor: colors.map(color => color.replace('0.5', '1')), // Viền của các cột
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            return label;
                        }
                    }
                }
            }
        }
    });
    </script>



    <script>
    var magazineData = <?php echo json_encode($magazineData); ?>;

    var magazineNames = [];
    var numArticlesByMagazine = [];
    var magazineColors = ['rgba(255, 99, 132, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)', 'rgba(153, 102, 255, 0.5)', 'rgba(255, 159, 64, 0.5)'
    ];

    magazineData.forEach(function(item) {
        magazineNames.push(item.magazineName);
        numArticlesByMagazine.push(item.numArticles);
    });

    var magazineCtx = document.getElementById('magazineChart').getContext('2d');
    var magazineChart = new Chart(magazineCtx, {
        type: 'bar',
        data: {
            labels: magazineNames,
            datasets: [{
                label: 'Number of Articles',
                data: numArticlesByMagazine,
                backgroundColor: magazineColors,
                borderColor: magazineColors.map(color => color.replace('0.5', '1')),
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            return label;
                        }
                    }
                }
            }
        }
    });
    </script>

    <script>
    // Assume you have fetched data from the database and stored it in a variable named 'articlesByYear'
    var articlesByYear = <?php echo json_encode($articlesByYear); ?>;

    var years = Object.keys(articlesByYear);
    var numArticles = Object.values(articlesByYear);

    var ctx = document.getElementById('yearChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Sửa loại biểu đồ thành 'bar' để hiển thị cột
        data: {
            labels: years,
            datasets: [{
                label: 'Number of Articles',
                data: numArticles,
                backgroundColor: 'rgba(75, 192, 192, 0.5)', // Màu nền cho các cột
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <script>
    var ctx = document.getElementById('facultyChart').getContext('2d');
    var facultyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($facultyNames); ?>,
            datasets: [{
                label: 'Approved Articles',
                data: <?php echo json_encode($numActivatedArticles); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }, {
                label: 'Rejected Articles',
                data: <?php echo json_encode($numInactivatedArticles); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.5)'
            }, {
                label: 'Pending Articles',
                data: <?php echo json_encode($numPendingArticles); ?>,
                backgroundColor: 'rgba(255, 206, 86, 0.5)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    <script>
    // Lấy dữ liệu PHP và chuyển thành JavaScript object
    var approvalRatioData = <?php echo json_encode($approvalRatioData); ?>;

    // Tạo mảng chứa tên tạp chí và tỷ lệ duyệt, từ chối
    var magazineNames = [];
    var approvalRatios = [];
    var rejectionRatios = [];

    // Đổ dữ liệu từ PHP vào mảng JavaScript
    approvalRatioData.forEach(function(item) {
        magazineNames.push(item.magazineName); // Lấy tên tạp chí và thêm vào mảng
        approvalRatios.push(item.approvalRatio); // Lấy tỷ lệ duyệt và thêm vào mảng
        rejectionRatios.push(item.rejectionRatio); // Lấy tỷ lệ từ chối và thêm vào mảng
    });

    // Vẽ biểu đồ cột
    var ctx = document.getElementById('approvalRatioChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar', // Loại biểu đồ cột
        data: {
            labels: magazineNames, // Tên của các tạp chí
            datasets: [{
                label: 'Approval Ratio (%)', // Chú thích cho dữ liệu tỷ lệ duyệt
                data: approvalRatios, // Tỷ lệ duyệt
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Màu nền cho cột tỷ lệ duyệt
                borderColor: 'rgba(54, 162, 235, 1)', // Màu viền cho cột tỷ lệ duyệt
                borderWidth: 1
            }, {
                label: 'Rejection Ratio (%)', // Chú thích cho dữ liệu tỷ lệ từ chối
                data: rejectionRatios, // Tỷ lệ từ chối
                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Màu nền cho cột tỷ lệ từ chối
                borderColor: 'rgba(255, 99, 132, 1)', // Màu viền cho cột tỷ lệ từ chối
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true // Bắt đầu trục y từ giá trị 0
                }
            }
        }
    });
    </script>

</body>
<?php
}
?>

</html>