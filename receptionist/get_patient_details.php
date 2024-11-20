<?php
// Database connection
$servername = "localhost"; // Your database server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "clinic"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get patient ID from the request
if (isset($_GET['p_id'])) {
    $patient_id = $_GET['p_id'];

    // Prepare statement to fetch patient details
    $sql = "SELECT p_email, p_contact, insurance FROM patient WHERE p_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); // Return JSON response
    } else {
        echo json_encode(['p_email' => '', 'p_contact' => '',  'insurance' => '']);
    }
    $stmt->close();
}
$conn->close();
?>
