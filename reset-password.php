<?php
include_once ('connect.php');
$token = $_GET["token"];

$token_hash = hash("sha256", $token);


$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .form-container form {
        max-width: 400px;
        width: 100%;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .error-message {
        color: red;
        margin-top: 5px;
    }
    </style>
</head>

<body>

    <div class="form-container">
        <form method="post" action="process-reset-password.php">
            <h1 class="text-center mb-4">Reset Password</h1>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Comfirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>

            <?php if (isset($error_message)): ?>
            <div class="error-message text-center"><?= $error_message ?></div>
            <?php endif; ?>
        </form>
    </div>

</body>

</html>