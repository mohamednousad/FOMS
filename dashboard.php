<?php
require_once './scripts/db_connection.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Access denied!'); window.location.href='index.php'</script>";
    exit();
}

// Initialize filter variables
$filterDate = '';
$filterNIC = '';

// Check if the form is submitted and set the filter values
if (isset($_POST['filter'])) {
    $filterDate = isset($_POST['filterDate']) ? mysqli_real_escape_string($conn, $_POST['filterDate']) : '';
    $filterNIC = isset($_POST['filterNIC']) ? mysqli_real_escape_string($conn, $_POST['filterNIC']) : '';
}

// Base SQL query
$sql = "SELECT visitor_id, nic_number, name_with_initial, visit_branch, phone_number, address, purpose, MAX(visit_date) as visit_date 
        FROM visitors 
        WHERE 1";

// Apply filters
if (!empty($filterDate)) {
    $sql .= " AND visit_date = '$filterDate'";
}

if (!empty($filterNIC)) {
    $sql .= " AND nic_number = '$filterNIC'";
}

// Group by NIC number
$sql .= " GROUP BY nic_number";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
</head>

<?php include_once './include/page_load.php'; ?>

<body>

    <div class="sidebar">
        <h4 class="text-center mt-2 mb-3 fw-bold">District Secretariat</h4>
        <a href="#" class="active">Dashboard</a>
        <a href="register.php">Registration</a>
    </div>

    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-primary rounded shadow-sm p-3">
            <div class="container-fluid">
                <span class="navbar-brand text-white">Dashboard</span>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link bg-secondary" href="./scripts/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container mt-5">
            <div class="d-flex justify-content-between mt-4 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <input type="checkbox" id="selectAll">
                    <label for="selectAll">Select All</label>
                </div>

                <div class="d-flex gap-3">
                    <form action="" method="POST">
                        <div class="d-flex gap-2">
                            <input type="date" class="form-control" name="filterDate" id="filterDate"
                                value="<?php echo htmlspecialchars($filterDate); ?>" placeholder="Filter by Date">
                            <input type="text" class="form-control" name="filterNIC" id="filterNIC"
                                value="<?php echo htmlspecialchars($filterNIC); ?>" placeholder="Filter by NIC">
                            <button class="btn btn-primary" type="submit" name="filter">Filter</button>
                            <button id="printBtn" class="btn btn-primary">Export</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm table-bordered" id="myTable">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>*</th>
                            <th>NIC Number</th>
                            <th>Name</th>
                            <th>Branch</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><input type="checkbox" class="rowCheckbox"></td>
                            <td class="text-center"> <?php echo htmlspecialchars($row['nic_number']); ?> </td>
                            <td class="text-center"> <?php echo htmlspecialchars($row['name_with_initial']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['visit_branch']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['phone_number']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['address']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['purpose']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['visit_date']); ?> </td>
                            <td class="text-center">
                                <a href="./visitor_dashboard.php?nic=<?php echo $row['nic_number']; ?>"
                                    class="btn btn-secondary fs-6">
                                    <i class="fa-solid fa-pen-to-square"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No data</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php $conn->close(); ?>
    </div>

    <?php require_once './include/footer.php'; ?>

    <!-- Custom js -->
    <script src="./assets/js/scripts.js"></script>
    <script src="./assets/js/excelExport.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>