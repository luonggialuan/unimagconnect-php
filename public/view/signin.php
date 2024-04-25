<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="../../css/login-style.css">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>

	</style>
</head>

<body>
	<?php
	include_once ("../../connect.php");
	session_start();
	if (isset($_SESSION['message'])) {
		echo '<script>';
		echo 'Swal.fire({';
		echo '    title: "Send mail reset password successful",';
		echo '    text: "Message sent, please check your email.",';
		echo '    icon: "success"';
		echo '})';
		echo '</script>';
		unset($_SESSION['message']);
	}

	if (isset($_POST['register'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$name = $_POST['name'];
		$facultyId = $_POST['faculty'];
		$confirmPassword = $_POST['confirm_password'];
		$email = $_POST['email'];

		// Check if the username already exists
		$check_username_query = "SELECT * FROM users WHERE username = '$username'";
		$check_username_result = $conn->query($check_username_query);

		// Check if the email already exists
		$check_email_query = "SELECT * FROM users WHERE email = '$email'";
		$check_email_result = $conn->query($check_email_query);

		// Regular expression to validate password
		$password_pattern = '/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~])(?=.*[a-z]).{8,}$/';

		// Array to store error messages
		$errors = array();

		// Check if all fields are filled
		if (empty($username) || empty($password) || empty($name) || empty($facultyId) || empty($confirmPassword) || empty($email)) {
			$errors[] = "Please fill in all fields.";
		} else if ($check_username_result->num_rows > 0) {
			$errors[] = "Username already exists. Please choose another username.";
		} else if ($check_email_result->num_rows > 0) {
			$errors[] = "Email already exists. Please choose another email.";
		} else if ($password !== $confirmPassword) {
			$errors[] = "Passwords do not match. Please try again.";
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Invalid email address. Please enter a valid email.";
		} else if (!preg_match($password_pattern, $password)) {
			$errors[] = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
		}

		// If there are errors, display an alert box and redirect back to the registration page
		if (!empty($errors)) {
			echo '<script>';
			echo 'Swal.fire({';
			echo '    title: "Errors",';
			echo '    text: "' . implode("\\n", $errors) . '",';
			echo '    icon: "error"';
			echo '}).then(function() {';
			echo '    window.history.go(-1);'; // Redirect back to the previous page after the user clicks OK
			echo '});';
			echo '</script>';

		} else {
			// Add the account to the database if there are no errors
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			$insert_query = "INSERT INTO users (username, password, name, facultyId, email, roleId, status) VALUES ('$username', '$hashed_password','$name','$facultyId', '$email', 2, 0)";

			if ($conn->query($insert_query) === TRUE) {
				echo '<script>';
				echo 'Swal.fire({';
				echo '    title: "Register successful",';
				echo '    text: "Your registration has been successful!",';
				echo '    icon: "success"';
				echo '}).then(function() {';
				echo '    window.history.go(-1);'; // Redirect back to the previous page after the user clicks OK
				echo '});';
				echo '</script>';

			} else {
				echo '<script>';
				echo 'Swal.fire({';
				echo '    title: "Error!",';
				echo '    text: "An error occurred: ' . $conn->error . '",';
				echo '    icon: "error"';
				echo '});';
				echo '</script>';
			}
		}
	}



	if (isset($_POST['signin'])) {
		// Check if username and password are provided
		if (empty($_POST['username']) || empty($_POST['password'])) {
			echo '<script>';
			echo 'Swal.fire({';
			echo '    title: "Empty fields",';
			echo '    text: "Username and password cannot be empty.",';
			echo '    icon: "error"';
			echo '}).then(function() {';
			echo '    window.history.back();'; // Redirect back to the previous page after the user clicks OK
			echo '});';
			echo '</script>';
			exit();
		}

		// Get input data and prevent SQL injection
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		// Search for the user by username
		$result = mysqli_query($conn, "SELECT userId, username, password, users.roleId, roleName, status, lastLogin FROM users
		INNER JOIN roles ON users.roleId = roles.roleId WHERE username='$username'");

		// Check if the user exists
		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);
			$hashed_password = $row['password'];
			$status = $row['status'];
			$roleName = $row['roleName'];

			// Check if the account is approved
			if ($status == 0) {
				echo '<script>';
				echo 'Swal.fire({';
				echo '    title: "Account pending approval",';
				echo '    text: "Please contact the administrator.",';
				echo '    icon: "warning"';
				echo '}).then(function() {';
				echo '    window.history.back();'; // Redirect back to the previous page after the user clicks OK
				echo '});';
				echo '</script>';

				exit();
			}

			// Compare the input password with the hashed password from the database
			if (password_verify($password, $hashed_password)) {
				// Successful login, set session and redirect user
				$sqlUpdate = "UPDATE users SET lastLogin = NOW() WHERE username = '$username' AND password = '$hashed_password'";
				$resultUpdate = mysqli_query($conn, $sqlUpdate);
				$userRole = $row["roleId"];
				$userId = $row["userId"];
				$_SESSION['userid'] = $userId;
				$_SESSION['username'] = $username;
				$lastLogin = $row['lastLogin'];
				if ($lastLogin === null) {
					$textEcho = "Welcome to UniMagConnect";
				} else {
					$textEcho = "Your last login was on $lastLogin";
				}
				if ($userRole == "2") {
					echo "<script>";
					echo "Swal.fire({";
					echo "    position: 'center',";
					echo "    icon: 'success',";
					echo "    title: 'Login successful!',";
					echo '    text: "Hello student! ' . $textEcho . '",';
					echo "    showConfirmButton: true,";
					// echo "    timer: 3000";
					echo "}).then(function() {"; // Thực hiện sau khi thông báo biến mất
					echo "        window.location.href = '../../index.php';"; // Chuyển hướng
					echo "});";
					echo "</script>";
				} elseif ($userRole == "5") {
					// $sqlUpdate = "UPDATE users SET lastLogin = NOW() WHERE username = '$username' AND password = '$password'";
					// $resultUpdate = mysqli_query($conn, $sqlUpdate);
					$_SESSION['guest'] = $userId;
					echo "<script>";
					echo "Swal.fire({";
					echo "    position: 'center',";
					echo "    icon: 'success',";
					echo "    title: 'Login successful!',";
					echo '    text: "Hello Guest! ' . $textEcho . '",';
					echo "    showConfirmButton: true,";
					// echo "    timer: 1500";
					echo "}).then(function() {"; // Thực hiện sau khi thông báo biến mất
					echo "        window.location.href = '../../index.php';"; // Chuyển hướng
					echo "});";
					echo "</script>";
				} else {
					echo '<script>';
					echo 'Swal.fire({';
					echo '    title: "Redirecting...",';
					echo '    text: "You will be redirected to the ' . $roleName . ' page shortly.",';
					echo '    icon: "info",';
					echo '    showConfirmButton: false';
					echo '});';
					echo 'setTimeout(function() {';
					echo '    window.location.href = "../../Adminitrator/UI/html/index.php";'; // Redirect to the admin page after a delay
					echo '}, 1500);'; // Delay in milliseconds, e.g., 2000ms = 2 seconds
					echo '</script>';
				}
			} else {
				// Incorrect password
				echo "<script>";
				echo "Swal.fire({";
				echo "    title: 'Incorrect password',";
				echo "    text: 'Please try again.',";
				echo "    icon: 'error'";
				echo "}).then(function() {";
				echo "    window.history.back();"; // Redirect back to the previous page after the user clicks OK
				echo "});";
				echo "</script>";

				exit();
			}
		} else {
			// User does not exist
			echo '<script>';
			echo 'Swal.fire({';
			echo '    title: "Username not found",';
			echo '    text: "Please check again.",';
			echo '    icon: "error"';
			echo '}).then(function() {';
			echo '    window.history.back();'; // Redirect back to the previous page after the user clicks OK
			echo '});';
			echo '</script>';

			exit();
		}
	}

	?>
	<div id="container" class="container">
		<!-- FORM SECTION -->
		<div class="row">
			<!-- SIGN UP -->
			<div class="col align-items-center flex-col sign-up">
				<div class="form-wrapper align-items-center">
					<div class="form sign-up">
						<form action="#" method="post">
							<input type="hidden" name="register" value="1">
							<div class="input-group">
								<i class='bx bxs-user'></i>
								<input type="name" name="name" placeholder="Name" />
							</div>
							<div class="input-group">
								<i class='bx bxs-user'></i>
								<input type="username" name="username" placeholder="User name" />
							</div>
							<div class="input-group">
								<i class='bx bx-mail-send'></i>
								<input type="email" name="email" placeholder="Email" />
							</div>
							<div class="input-group">
								<i class='bx bxs-lock-alt'></i>
								<input type="password" name="password" placeholder="Password" />
							</div>
							<div class="input-group">
								<i class='bx bxs-lock-alt'></i>
								<input type="password" name="confirm_password" placeholder="Confirm Password" />
							</div>
							<div class="input-group">
								<i class='bx bxs-lock-alt'></i>
								<select id="facultyInput" name="faculty">
									<option value="1" selected disabled>Select faculty</option>
									<?php
									// Query the database to retrieve the list of faculties/departments
									$queryFaculties = "SELECT facultyId, facultyName FROM faculties";
									$resultFaculties = mysqli_query($conn, $queryFaculties);

									// Check if any records are returned
									if (mysqli_num_rows($resultFaculties) > 0) {
										// Iterate through each record and create options for the select field
										while ($rowFaculties = mysqli_fetch_assoc($resultFaculties)) {
											echo "<option value='" . $rowFaculties['facultyId'] . "'>" . $rowFaculties['facultyName'] . "</option>";
										}
									} else {
										echo "<option value=''>No data available</option>";
									}

									// Close the database connection
									mysqli_close($conn);
									?>
								</select>
							</div>
							<button name="register">
								Sign up
							</button>
							<p>
								<span>
									Already have an account?
								</span>
								<b onclick="toggle()" class="pointer">
									Sign in here
								</b>
							</p>
						</form>
					</div>
				</div>

			</div>
			<!-- END SIGN UP -->
			<!-- SIGN IN -->
			<div class="col align-items-center flex-col sign-in">
				<div class="form-wrapper align-items-center">

					<div class="form sign-in">
						<form action="#" method="post">
							<input type="hidden" name="signin" value="1">
							<div class="input-group">
								<i class='bx bxs-user'></i>
								<input type="username" name="username" placeholder="User name" />
							</div>
							<div class="input-group">
								<i class='bx bxs-lock-alt'></i>
								<input type="password" name="password" placeholder="Password" />
							</div>
							<button type="submit">Sign In</button>
							<p>
								<a href='forgot-password.php'>
									Forgot password?
								</a>
							</p>
							<p>
								<span>
									Don't have an account?
								</span>
								<b onclick="toggle()" class="pointer">
									Sign up here
								</b>
							</p>
						</form>

					</div>

				</div>
				<div class="form-wrapper">

				</div>
			</div>
			<!-- END SIGN IN -->
		</div>
		<!-- END FORM SECTION -->
		<!-- CONTENT SECTION -->
		<div class="row content-row">
			<!-- SIGN IN CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="text sign-in">
					<h2>
						Welcome
					</h2>

				</div>
				<div class="img sign-in">

				</div>
			</div>
			<!-- END SIGN IN CONTENT -->
			<!-- SIGN UP CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="img sign-up">

				</div>
				<div class="text sign-up">
					<h2>
						Join with us
					</h2>

				</div>
			</div>
			<!-- END SIGN UP CONTENT -->
		</div>
		<!-- END CONTENT SECTION -->
	</div>

	<script src="../../js/login-animation.js"></script>
	<script>
		function showSuccessAlert() {
			Swal.fire({
				title: "Good job!",
				text: "Login successful!",
				icon: "success"
			}).then(function () {
				window.location.href = "../../index.php"; // Redirect after the user clicks the button in the alert
			});
		}
	</script>
</body>

</html>