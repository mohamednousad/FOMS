<?php
require_once('./scripts/register.php'); 

// if (!isset($_SESSION['admin_id'])) {
//     echo "<script>alert('Access denied!'); window.location.href='index.php'</script>";
//     exit();
// }

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Setting CSRF token for more secure registration
}


// Get the nic from the URL
$visitor_nic = isset($_GET['nic']) ? $_GET['nic'] : (isset($_GET['update']) ? $_GET['update'] : '');

// Fetch user data based on the user_id
$sql = "SELECT * FROM visitors WHERE nic_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $visitor_nic);
$stmt->execute();
$result = $stmt->get_result();

$sql2 = "SELECT * FROM visitors WHERE nic_number = ?";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("i", $visitor_nic);
$stmt2->execute();
$result2 = $stmt2->get_result();

// Update visitor's dtata
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $nic = $_POST['nic'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    if (!empty($nic) && !empty($phone_number) && !empty($address)) {
        $stmt = $conn->prepare("UPDATE visitors SET phone_number = ?, address = ? WHERE nic_number = ?");

        if ($stmt) {
            $stmt->bind_param("sss", $phone_number, $address, $nic);
            
            // Execute and check if successful
            if ($stmt->execute()) {
                header('Location: ?update=' .urldecode($nic));
            } else {
                die("An error occurred while executing the query. " . $stmt->error);
            }

            // Close statement
            $stmt->close();
        } else {
            die("An error occurred while preparing the query. " . $conn->error);
        }
    } 
    
    else {
        echo "<script>alert('All fields are required.');</script>";
    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
</head>
<?php include_once './include/page_load.php'; ?>

<body>

    <div class="sidebar">
        <h4 class="text-center mt-2 mb-3 fw-bold">District Secetariat</h4>
        <a href="dashboard.php">Dashboard</a>
        <a href="register.php">Registration</a>
        <a href="register.php" class="active">Visitor</a>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-primary rounded shadow-sm p-3 mb-3">
            <div class="container-fluid">
                <span class="navbar-brand text-white">Personal Dashboard</span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link bg-secondary" href="./scripts/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container">
            <div class="card shadow-none border-0">
                <div class="card-body position-relative">
                    <?php
            if (isset($_GET['update'])){
            echo '<div class="alert alert-dismissible alert-light fade show w-50 position-absolute z-1" role="alert">Details updated successfully
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }
            ?>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php $row = mysqli_fetch_assoc($result) ?>
                    <div class="container mt-4">
                        <div class="row align-items-center">
                            <div class="col-md-3 d-flex align-items-center">
                                <div style="width: 60px; height: 90px; border-radius: 50%; overflow: hidden;">
                                    <img src="./assets/images/user_logo2.jpg" alt="Profile Icon" class="img-fluid">
                                </div>
                                <div class="ms-3">
                                    <h5 class="fw-bold"><?php echo htmlspecialchars($row['name_with_initial'])?></h5>
                                    <p class="mb-0"><?php echo htmlspecialchars($row['nic_number'])?></p>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['gender'])?></small>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <form action="" method="POST">
                                    <input type="hidden" name="nic"
                                        value="<?php echo htmlspecialchars($row['nic_number']); ?>">

                                    <div class="mb-3">
                                        <input type="tel" name="phone_number"
                                            value="<?php echo htmlspecialchars($row['phone_number']); ?>"
                                            class="form-control border-0 p-3" required
                                            style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    </div>

                                    <div class="mb-3">
                                        <textarea name="address" class="form-control w-100 border-0 p-3" rows="2"
                                            required autocomplete="on"
                                            style="background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo htmlspecialchars($row['address']); ?></textarea>
                                    </div>
                                    <button class="btn btn-primary" type="submit" name="update">Update</button>
                                    <button class="btn btn-primary" onclick="exportToExcel()" type="button">Export to
                                        excel</button>


                                </form>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="container position-absolute w-50 mt-5 text-start">
                        <p class="mt-3 text-muted">No data</p>
                    </div>
                    <?php endif; ?>
                    <div class="row g-3 mt-4">
                        <!-- Date search bar -->
                        <div class="col-12 mb-3">
                            <input type="text" id="dateSearchInput" class="form-control w-50"
                                placeholder="Search by Date (YYYY-MM-DD)" onkeyup="searchDate()" />
                        </div>

                        <table id="myTable" class="table table-sm shadow-none">
                            <thead class="table-light">
                                <tr>
                                    <th>NIC</th>
                                    <th>Branch</th>
                                    <th>Purpose</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php if (mysqli_num_rows($result2) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                                <tr>
                                    <td> <?php echo htmlspecialchars($row['nic_number']); ?> </td>
                                    <td> <?php echo htmlspecialchars($row['visit_branch']); ?> </td>
                                    <td> <?php echo htmlspecialchars($row['purpose']); ?> </td>
                                    <td class="date-column"> <?php echo htmlspecialchars($row['visit_date']); ?> </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function searchDate() {
        let input = document.getElementById('dateSearchInput');
        let filter = input.value.toLowerCase(); // Case-insensitive search
        let rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            let dateCell = row.querySelector('.date-column');
            let dateText = dateCell ? dateCell.textContent.toLowerCase() : '';

            // Filter rows by matching date
            row.style.display = dateText.includes(filter) ? '' : 'none';
        });
    }

    function exportToExcel() {
        let table = document.getElementById("myTable");
        let workbook = XLSX.utils.table_to_book(table);
        XLSX.writeFile(workbook, 'table_data.xlsx');
    }
    </script>

    <?php require_once './include/footer.php'; ?>

    <!-- Custom js -->
    <script src="./assets/js/scripts.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>