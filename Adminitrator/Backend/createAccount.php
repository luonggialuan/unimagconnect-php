<?php
include_once("../../connect.php");

if (isset($_POST['btnSubmit'])) {
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
