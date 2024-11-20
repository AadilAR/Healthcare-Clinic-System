<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost"; // your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "clinic"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    
    // Prepare and execute delete statement
    $sql = "DELETE FROM appointment WHERE a_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);
    
    if ($stmt->execute()) {
        echo "Appointment deleted successfully.";
    } else {
        echo "Error deleting appointment: " . $conn->error;
    }
    
    $stmt->close();
}

// Redirect back to appointment list
header("Location: patientstable.php");
exit();
?>
