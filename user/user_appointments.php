<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
    // Redirect to login if not logged in
    header("Location: ../login/login.html");
    exit();
}

// Get the user's name from the session
$user_name = $_SESSION['name'];

// Retrieve the patient ID from the session or fetch based on the logged-in user's email
$sql = "SELECT p_id FROM patient WHERE p_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patient_id = $row['p_id'];

    // Fetch the patient's appointments
    $appointment_query = "SELECT a.date, a.time, a.reason, d.d_name 
                          FROM appointment a 
                          JOIN doctor d ON a.doctor_id = d.d_id 
                          WHERE a.patient_id = ?";
    $appointment_stmt = $conn->prepare($appointment_query);
    $appointment_stmt->bind_param("i", $patient_id);
    $appointment_stmt->execute();
    $appointments = $appointment_stmt->get_result();
} else {
    echo "No patient found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="../User/css/bootstrap.min.css">
    <link rel="icon" type="x-icon" href="HCC.ico">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Appointments</h2>
        <?php if ($appointments->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Doctor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $appointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['time']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['d_name']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>

    <!-- SCRIPTS -->
    <script src="../User/js/jquery.js"></script>
    <script src="../User/js/bootstrap.min.js"></script>
</body>
</html>
