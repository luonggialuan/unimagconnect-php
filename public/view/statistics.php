<body>
    <!--  Body Wrapper -->
    <div class="container">
        <div class="body-wrapper">

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
            LEFT JOIN users u ON f.facultyId = u.facultyId
            LEFT JOIN articles a ON u.userId = a.authorId
            LEFT JOIN magazine m ON a.magazineId = m.magazineId
            WHERE f.facultyId = (SELECT facultyId FROM users WHERE userId = $userId)
            GROUP BY m.magazineId, m.magazineName, f.facultyId, f.facultyName;            
            ";

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
     AND u.facultyId = (SELECT facultyId FROM users WHERE userId = 15)
     GROUP BY m.magazineId, m.magazineName, u.facultyId     
     ";

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
                                            <?= $facultyNameOnly ?>
                                            by Magazine
                                        </h5>
                                        <div class="chart-container" style="position: relative; height: 400px;">
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
                                        <div class="chart-container" style="position: relative; height: 400px;">
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
                                            <?= $facultyNameOnly ?>
                                            by Year
                                        </h5>
                                        <div class="chart-container" style="position: relative; height: 400px;">
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
                                            <?= $facultyNameOnly ?> Articles Chart
                                        </h5>
                                        <div class="chart-container" style="position: relative; height: 400px;">
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
                                        <div class="chart-container" style="position: relative; height: 400px;">
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

    // Vẽ biểu đồ tròn
    var ctx = document.getElementById('articleChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie', // Loại biểu đồ tròn
        data: {
            labels: facultyNames, // Tên của các khoa
            datasets: [{
                label: 'Number of Articles', // Chú thích cho dữ liệu biểu đồ
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
        type: 'pie',
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

</body>