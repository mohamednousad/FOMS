<?php
require_once 'db_connection.php';
session_start(); 

$visitor = null;
$result = null;

if (isset($_GET['nic'])) {
    $nic = mysqli_real_escape_string($conn, $_GET['nic']);
    $stmt = $conn->prepare("SELECT * FROM visitors WHERE nic_number = ?");

    if (!$stmt) {
        die("Could not prepare the query: " . htmlspecialchars($conn->error));
    } 
    $stmt->bind_param("s", $nic);
    if (!$stmt ->execute()) {
        die("Could not execute the query: " . htmlspecialchars($stmt->error));
    } 
    $result = $stmt->get_result();
    if(mysqli_num_rows($result) == 0)
    {
        echo "<script>alert('The visitor is not registered');</script>";
    }
    $stmt->close();
}


// register visitor securely
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['csrf_token']) && isset($_POST['register'])) {
    $nic_number = $conn->real_escape_string(trim($_POST['nic_number']));
    $name_with_initial = $conn->real_escape_string(trim($_POST['name_with_initial']));
    $address = $conn->real_escape_string($_POST['address']);
    $gender = $_POST['gender'];
    $phone_number = $conn->real_escape_string(trim($_POST['phone_number']));
    $visit_branch = $_POST['visit_branch'];
    $purpose = $conn->real_escape_string($_POST['purpose']);

    // Inserting room details using prepare statement
    $stmt = $conn->prepare("INSERT INTO visitors (nic_number, name_with_initial, address, gender, phone_number , visit_branch , purpose , visit_date) VALUES (?,?,?,?,?, ?,?,CURRENT_DATE)");

    if (!$stmt) {
        die("Could not prepare the query: " . htmlspecialchars($conn->error));
    } 

    $stmt->bind_param("ssssiss", $nic_number, $name_with_initial, $address , $gender , $phone_number , $visit_branch , $purpose);

    if ($stmt->execute()) {
        header('Location: ?msg');
    } else {
        die("Could not execute the query: " . htmlspecialchars($stmt->error));
    }

    unset($_SESSION['csrf_token']);  // unset the session
    $stmt->close();
    $conn->close();
}