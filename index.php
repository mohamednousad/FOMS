<?php
session_start();

if (isset($_SESSION['admin_id'])){
    header("location: dashboard.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Setting CSRF token for more secure login
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <form class="p-4 w-100 shadow-lg rounded" style="max-width: 400px; background-color: #fff;"
            action="./scripts/login.php" method="POST">
            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="./assets/images/logo.png" alt="Logo" style="width: 80px; height: auto;">
            </div>

            <h2 class="mb-4 fw-bold text-center">District Secretariat Ampara</h2>

            <!-- Username Field -->
            <div class="mb-3">
                <label for="user-name" class="form-label">User Name</label>
                <input type="text" id="user-name" name="admin_name" class="form-control">
                <?php if (isset($_GET['alert_u'])): ?>
                <span class='text-danger mt-2'>Incorrect username! Try again</span>
                <?php endif; ?>
                <div id="emailHelp" class="form-text">We'll never share your details with anyone else.</div>
            </div>

            <!-- Password Field -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <?php if (isset($_GET['alert_p'])): ?>
                <span class='text-danger mt-2'>Incorrect password! Try again</span>
                <?php endif; ?>
            </div>

            <!-- Forgot Password Link -->
            <div class="d-flex justify-content-between">
                <a href="#" class="text-muted">Forgot password?</a>
            </div>

            <!-- Login Button -->
            <button type="submit" name="login" class="btn btn-primary w-100 mt-4">Login</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>