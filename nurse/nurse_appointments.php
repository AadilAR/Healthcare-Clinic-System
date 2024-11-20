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

// Handle form submission for Body Temp and Blood Pressure
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $body_temp = $_POST['body_temp'];
    $bp = $_POST['bp'];

    // Update patient table with the newly entered values
    $update_sql = "UPDATE patient SET body_temp=?, bp=? WHERE p_id=(SELECT patient_id FROM appointment WHERE a_id=?)";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('sdi', $body_temp, $bp, $appointment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Data updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating data.');</script>";
    }
    $stmt->close();
}

// Retrieve appointments along with patient and doctor details
$sql = "
SELECT a.a_id, p.p_name AS patient_name, p.p_email AS email, a.date, a.time, d.d_name AS doctor, p.p_contact AS phone, a.reason, p.insurance, p.body_temp, p.bp
FROM appointment a
JOIN patient p ON a.patient_id = p.p_id
JOIN doctor d ON a.doctor_id = d.d_id"; // Adjust based on your table structure
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment List</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tooplate-style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.css">
    <link rel="icon" type="x-icon" href="HCC.ico">

    <style>
        /* Custom button styles */
        .btn {
            background-color: #007bff; /* Blue background */
            color: white; /* White text */
            border: none; /* No border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px 20px; /* Padding for button */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s, transform 0.3s; /* Smooth transition */
        }

        .btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
            transform: scale(1.05); /* Slightly enlarge button */
        }

        /* Table styles */
        table {
            width: 100%; /* Full width */
            border-collapse: collapse; /* No space between borders */
            margin-top: 20px; /* Space above the table */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        th, td {
            padding: 12px; /* Padding in cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #ddd; /* Bottom border for rows */
        }

        th {
            background-color: #007bff; /* Header background color */
            color: white; /* Header text color */
            font-weight: bold; /* Bold text */
        }

        tr:hover {
            background-color: #f5f5f5; /* Light gray background on hover */
        }

        /* Input fields */
        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], input[type="time"], select {
            width: 100%; /* Full width */
            padding: 10px; /* Padding */
            border: 1px solid #ccc; /* Border color */
            border-radius: 5px; /* Rounded corners */
            box-sizing: border-box; /* Include padding in width */
            transition: border-color 0.3s; /* Smooth transition for border */
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, input[type="date"]:focus, input[type="time"]:focus, select:focus {
            border-color: #007bff; /* Blue border on focus */
            outline: none; /* Remove default outline */
        }

    </style>

</head>   

<body>

<div class="container">
    <h2>Appointment List</h2>
    <table id="example" class="table table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Time</th>
                <th>Doctor</th>
                <th>Phone</th>
                <th>Reason</th>
                <th>Insurance</th>
                <th>Body Temperature (°C)</th>
                <th>Blood Pressure (mm Hg)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    $isDone = !empty($row['insurance']) && !empty($row['body_temp']) && !empty($row['bp']) ? "Done" : "";
                    echo "<tr>
                            <td>{$row['a_id']}</td>
                            <td>{$row['patient_name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['doctor']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['reason']}</td>
                            <td>{$row['insurance']}</td> <!-- Insurance value from the database -->
                            <td>
                                <form method='post'>
                                    <input type='hidden' name='appointment_id' value='{$row['a_id']}'>
                                    <input type='text' name='body_temp' class='form-control' value='{$row['body_temp']}'>
                            </td>
                            <td>
                                    <input type='text' name='bp' class='form-control' value='{$row['bp']}'>
                                    <button type='submit' class='btn btn-primary'>Save</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='12'>No appointments found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>



</body>
</html>

<?php
$conn->close();
?>
