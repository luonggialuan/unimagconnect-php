<?php
// Khởi tạo biến kết nối đến cơ sở dữ liệu
include_once ("../../../connect.php");
include_once ("../../../permissions.php");



$userRole = getUserRole($conn);

function checkUserRole($role, $allowedRoles)
{
    return in_array($role, $allowedRoles);
}

$menuItems = array(
    array("name" => "Statistics", "link" => "index.php", "allowedRoles" => [ROLE_MARKETING_COORDINATOR, ROLE_MARKETING_MANAGER, ROLE_ADMIN]),
    array("name" => "Create accounts system", "link" => "register.php", "allowedRoles" => [ROLE_ADMIN]),
    array("name" => "List accounts", "link" => "approveAccount.php", "allowedRoles" => [ROLE_ADMIN]),
    array("name" => "Roles", "link" => "rolemanager.php", "allowedRoles" => [ROLE_ADMIN]),
    array("name" => "Magazines", "link" => "magazine.php", "allowedRoles" => [ROLE_ADMIN]),
    array("name" => "Faculties", "link" => "faculty.php", "allowedRoles" => [ROLE_ADMIN]),
    array("name" => "Articles Management", "link" => "articles.php", "allowedRoles" => [ROLE_MARKETING_COORDINATOR]),
    array("name" => "Approved Articles", "link" => "marketingManager.php", "allowedRoles" => [ROLE_MARKETING_MANAGER]),
);

$userId = $_SESSION["userid"];
$faculty_user = '';

$sql_user = "SELECT * FROM users";
$result_user = $conn->query($sql_user);
$row_user = $result_user->fetch_assoc();

if ($row_user["facultyId"]) {
    $sql_faculty_user = "SELECT facultyName FROM users INNER JOIN faculties ON users.facultyId = faculties.facultyId WHERE userId = $userId";
    $result_faculty_user = $conn->query($sql_faculty_user);
    $row_faculty_user = $result_faculty_user->fetch_assoc();
    $faculty_user = $row_faculty_user["facultyName"];
}
?>

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <!-- <a href="./index.html" class="text-nowrap logo-img">
                <img src="../../assets/images/logos/dark-logo.svg" width="180" alt="" />
            </a> -->
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <h5>ROLE:
                        <?= $userRole ?>
                    </h5>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <?php foreach ($menuItems as $menuItem): ?>
                    <?php if (checkUserRole($userRole, $menuItem["allowedRoles"])): ?>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?php echo $menuItem["link"]; ?>" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">
                                    <?php echo $menuItem["name"]; ?>
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>