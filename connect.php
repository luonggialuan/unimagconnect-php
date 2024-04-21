<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "web_comp1640";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Failed" . $conn->connect_error);
}
?>