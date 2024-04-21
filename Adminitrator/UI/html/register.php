<?php
include_once("../../../connect.php");
require_once '../../../permissions.php';

checkAccess([ROLE_ADMIN], $conn);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $name = trim($_POST['name']);
  $role = trim($_POST['role']);
  $facultyId = trim($_POST['faculty']);
  $confirmPassword = trim($_POST['confirm_password']);
  $email = trim($_POST['email']);
  // Array to store error messages
  $errors = array();

  // Check if all fields are filled
  if (empty($username) || empty($password) || empty($name) || empty($facultyId) || empty($confirmPassword) || empty($email)) {
    $errors[] = "Please fill in all fields.";
  } else {
    // Validate username
    if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
      $errors[] = "Username must be between 5 and 20 characters and contain only letters, numbers, and underscores.";
    }

    // Validate password
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
      $errors[] = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one number, and one special character.";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
      $errors[] = "Passwords do not match. Please try again.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Invalid email address. Please enter a valid email.";
    }

    // Check if username already exists
    $check_username_query = "SELECT * FROM users WHERE username = '$username'";
    $check_username_result = $conn->query($check_username_query);
    if ($check_username_result->num_rows > 0) {
      $errors[] = "Username already exists. Please choose another username.";
    }
  }

  // If there are errors, display an alert box
  if (!empty($errors)) {
    echo '<script>';
    echo 'alert("' . implode("\\n", $errors) . '");';
    echo 'window.history.go(-1);'; // Redirect to registration page
    echo '</script>';
  } else {
    // Add the account to the database if there are no errors
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (username, password, name, facultyId, email, roleId, status) VALUES ('$username', '$hashed_password','$name','$facultyId', '$email', '$role', 1)";

    if ($conn->query($insert_query) === TRUE) {
      echo "<script>alert('Register successful')</script>";
      echo "<script>window.history.go(-1);</script>"; // Redirect to login page
    } else {
      echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Modernize Free</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <?php
    include_once("sidebar.php");
    ?>
        <!--  Sidebar End -->

        <!--  Main wrapper -->
        <div class="body-wrapper">

            <!--  Header Start -->
            <?php
      include_once("header.php");
      ?>
            <!--  Header End -->

            <!--  Nội dung code từ đây xuống -->
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Create an account for system</h1>
                        <br />

                        <form class="forms-sample" action="#" method="post" enctype="multipart/form-data">
                            <div class="row mb-3">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input id="name" name="name" type="text" class="form-control" autocomplete="off"
                                        placeholder="Name..." />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input id="email" name="email" type="email" class="form-control" autocomplete="off"
                                        placeholder="Email..." />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input id="username" name="username" type="text" class="form-control"
                                        placeholder="Username" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input id="password" name="password" type="password" class="form-control"
                                        autocomplete="off" placeholder="Password" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Confirm Password</label>
                                <div class="col-sm-9">
                                    <input id="confirm_password" name="confirm_password" type="password"
                                        class="form-control" autocomplete="off" placeholder="Confirm Password" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleRole" class="col-sm-3 col-form-label">Role of user</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="exampleFormControlSelect1" name="role">
                                        <option selected disabled>Choose role of user</option>
                                        <?php
                    $re = mysqli_query($conn, "SELECT * FROM roles");
                    while ($row = mysqli_fetch_assoc($re)) {
                    ?>
                                        <option value="<?= $row['roleId'] ?>"><?= $row['roleName'] ?></option>
                                        <?php
                    }
                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="exampleFaculty" class="col-sm-3 col-form-label">Faculty of user</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="exampleFormControlSelect2" name="faculty">
                                        <option selected disabled>Choose faculty of user</option>
                                        <?php
                    $resultFaculties = mysqli_query($conn, "SELECT * FROM faculties");
                    while ($rowFaculties = mysqli_fetch_assoc($resultFaculties)) {
                    ?>
                                        <option value="<?= $rowFaculties['facultyId'] ?>">
                                            <?= $rowFaculties['facultyName'] ?></option>
                                        <?php
                    }
                    ?>
                                    </select>
                                </div>
                            </div>
                            <br />
                            <button type="submit" class="btn btn-primary me-2" id="btnSubmit" name="btnSubmit">
                                Submit
                            </button>
                        </form>
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
</body>

</html>