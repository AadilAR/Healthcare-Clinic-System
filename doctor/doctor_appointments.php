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

// Get the logged-in doctor's name and ID from the session
$doctor_name = $_SESSION['name'];

// Fetch doctor's ID from the doctor table using the session name
$sql = "SELECT d_id FROM doctor WHERE d_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doctor_name);
$stmt->execute();
$stmt->bind_result($doctor_id);
$stmt->fetch();
$stmt->close();

// Retrieve appointments and patient details for the logged-in doctor
$sql = "SELECT appointment.a_id, patient.p_name, patient.p_email, patient.p_contact, patient.insurance, patient.bp, patient.body_temp, patient.treat_plan, appointment.date, appointment.time, appointment.reason 
        FROM appointment 
        JOIN patient ON appointment.patient_id = patient.p_id 
        WHERE appointment.doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="x-icon" href="HCC.ico">
    
    <title>My Appointments | Doctor</title>
    <link rel="stylesheet" href="../User/css/bootstrap.min.css">
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
        <h2>Appointments for Dr. <?php echo htmlspecialchars($doctor_name); ?></h2>
        <form action="update_treatment.php" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Contact</th>
                        <th>Insurance</th>
                        <th>BP</th>
                        <th>Body Temp</th>
                        <th>Treatment Plan</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['a_id'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['p_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['p_email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['p_contact']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['insurance']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['bp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['body_temp']) . "</td>";
                            echo "<td><input type='text' name='treat_plan[" . $row['a_id'] . "]' value='" . htmlspecialchars($row['treat_plan']) . "'></td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                            echo "<td><input type='submit' value='Update' class='btn btn-primary'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>No appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- SCRIPTS -->
    <script src="../User/js/jquery.js"></script>
    <script src="../User/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
