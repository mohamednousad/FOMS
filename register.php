<?php
require_once './scripts/register.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access denied!'); window.location.href='index.php'</script>";
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Setting CSRF token for more secure registration
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
</head>
<?php include_once './include/page_load.php'; ?>

<body>
    </head>

    <body>

        <div class="sidebar">
            <h4 class="text-center mt-2 mb-3 fw-bold">District Secetariat</h4>
            <a href="dashboard.php">Dashboard</a>
            <a href="#" class="active">Registration</a>
        </div>

        <div class="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-primary rounded shadow-sm p-3 mb-5">
                <div class="container-fluid">
                    <span class="navbar-brand text-white">Registration</span>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link bg-secondary" href="./scripts/logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Registration Form Section -->
            <div class="container form-section">
                <div class="position-relative">
                    <?php
            if (isset($_GET['msg'])){
            echo '<div class="alert alert-dismissible alert-light fade show w-50 position-absolute z-1" role="alert">Registration successful
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            ?>
                </div>
                <form action="" method="POST">
                    <?php if(!is_null($result) && mysqli_num_rows($result) > 0) {
                        $visitor = mysqli_fetch_assoc($result); }  ?>
                    <div class="row g-3">
                        <div class="col-md-6 position-relative">
                            <label for="nicField" class="form-label">NIC Number</label>
                            <input type="text" name="nic_number" class="form-control"
                                value="<?= !is_null($visitor) ? htmlspecialchars($visitor['nic_number']) : ''?>"
                                id="nicField" autocomplete="on" required placeholder="Enter the nic number">
                            <button class="btn btn-light position-absolute" type="button" style="right: 8px; top: 32px;"
                                onclick="sendNIC()">fill</button>
                            <!-- Alert message -->
                            <small id="alert-message" class="text-danger d-none" role="alert"></small>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name with Initial</label>
                            <input type="text" name="name_with_initial"
                                value="<?= !is_null($visitor) ? htmlspecialchars($visitor['name_with_initial']) : ''?>"
                                class="form-control" id="name" autocomplete="on" required
                                placeholder="Enter the name, including initial">
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" name="address"
                                value="<?= !is_null($visitor) ? htmlspecialchars($visitor['address']) : ''?>"
                                class="form-control" id="address" autocomplete="on" required
                                placeholder="Enter the address">
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" class="form-select" id="gender" required>
                                <option value="male"
                                    <?= (!is_null($visitor) && $visitor['gender'] == 'male') ? 'selected' : ''; ?>>Male
                                </option>
                                <option value="female"
                                    <?= (!is_null($visitor) && $visitor['gender'] == 'female') ? 'selected' : ''; ?>>
                                    Female</option>
                                <option value="other"
                                    <?= (!is_null($visitor) && $visitor['gender'] == 'other') ? 'selected' : ''; ?>>
                                    Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" name="phone_number"
                                value="<?= !is_null($visitor) ? htmlspecialchars($visitor['phone_number']) : ''?>"
                                class="form-control" id="phone" autocomplete="on" required
                                placeholder="Enter the phone number">
                        </div>

                        <div class="col-md-6">
                            <label for="branch" class="form-label">Visit Branch</label>
                            <select name="visit_branch" class="form-select" id="branch" required>
                                <option value="">Select the branch</option>
                                <option value="administration">Administration</option>
                                <option value="planning">Planning</option>
                                <option value="accounts">Accounts</option>
                                <option value="statistics">Statistics</option>
                                <option value="land use">Land use</option>
                                <option value="election">Election</option>
                                <option value="samurdhi">Samurdhi</option>
                                <option value="agriculture">Agriculture</option>
                                <option value="feild branch">Feild Branch</option>
                                <option value="GA">GA</option>
                                <option value="additional GA">Additional GA</option>
                                <option value="engineering">Engineering</option>
                                <option value="interneal audit">Interneal audit</option>
                                <option value="it branch">IT Branch</option>
                                <option value="motor traffic">Motor traffic</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="purpose" class="form-label">Purpose</label>
                            <input type="text" name="purpose" class="form-control" id="purpose" autocomplete="on"
                                required placeholder="Enter the purpose">
                        </div>
                        <div class="col-12">
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                            <button type="submit" name="register" id="registerBtn" class="btn btn-primary"
                                onclick="return validateNIC()">Register</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php require_once './include/footer.php'; ?>

        <!-- Custom js -->
        <script src="./assets/js/scripts.js"></script>
        <script src="./assets/js/register.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>

    </body>

</html>