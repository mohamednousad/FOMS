<?php
require_once 'db_connection.php';
session_start(); 

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['csrf_token']) && isset($_POST['login'])) {
        $admin_name = $conn->real_escape_string(trim($_POST['admin_name']));
        $password =  $conn->real_escape_string(trim($_POST['password']));

        // Prepare the query to fetch the user data
        $sql = "SELECT * FROM admin WHERE admin_name = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL preparation error: " . htmlspecialchars($conn->error)); 
        }
        // Execute the statement
        $stmt->bind_param("s",$admin_name);
        if (!$stmt->execute()) {
            die("Error executing statement: " . htmlspecialchars($stmt->error));
        }  

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();

            // Verify password for user
            if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];
            header('Location: ../dashboard.php');
                } 
            else {
            header('Location: ../index.php?alert_p');
            }
        } 
    
        else {
            header('Location: ../index.php?alert_u');
        }
    unset($_SESSION['csrf_token']);  // unset the session
    $stmt->close(); 
}

}

catch (Exception  $e){
    die("Error :" . $e->getMessage());
}

finally {
    $conn->close();
}

?>


