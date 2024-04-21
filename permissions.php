<?php
session_start();
// Khai báo các hằng số đại diện cho các vai trò
define('ROLE_ADMIN', 'Administrator');
define('ROLE_MARKETING_COORDINATOR', 'Marketing Coordinator');
define('ROLE_MARKETING_MANAGER', 'Marketing Manager');



// Hàm để lấy vai trò của người dùng
function getUserRole($conn)
{
    include_once ("connect.php");
    if (isset($_SESSION['userid'])) {
        $userId = $_SESSION['userid'];

        // Truy vấn để lấy roleId từ userId
        $sql = "SELECT roleId FROM users WHERE userId = $userId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $roleId = $row['roleId'];

            // Truy vấn để lấy roleName từ roleId
            $sql = "SELECT roleName FROM roles WHERE roleId = $roleId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row['roleName'];
            } else {
                return "Guest";
            }
        } else {
            return "Guest";
        }
    } else {
        return "Guest";
    }
}
function checkAccess($allowedRoles, $conn)
{
    $userRole = getUserRole($conn);
    if (!in_array($userRole, $allowedRoles)) {
        header('Location: /access-denied.php');
        exit();
    }
}

?>