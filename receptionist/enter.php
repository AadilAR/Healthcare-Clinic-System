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

// Assuming the following POST data is coming from a form submission
$patient_name = $_POST['patient']; // Ensure this is being passed correctly
$patient_email = $_POST['email'];
$patient_phone = $_POST['phone'];
$date = $_POST['date'];
$time = $_POST['time'];
$doctor_id = $_POST['doctor_id']; // assuming you're also submitting doctor ID
$reason = $_POST['reason'];
$insurance = $_POST['insurance']; // New insurance field

// Check if patient already exists
$sql = "SELECT p_id FROM patient WHERE p_email = ? OR p_contact = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $patient_email, $patient_phone);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Patient exists, get their ID
    $row = $result->fetch_assoc();
    $patient_id = $row['p_id'];
} else {
    // Patient does not exist, insert them
    $insert_patient_sql = "INSERT INTO patient (p_name, p_email, p_contact, insurance) VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_patient_sql);
    $insert_stmt->bind_param("ssss", $patient_name, $patient_email, $patient_phone, $insurance); // Include insurance here
    $insert_stmt->execute();
    $patient_id = $conn->insert_id; // Get the ID of the newly created patient
}

// Now insert the appointment with the correct patient ID
$insert_appointment_sql = "INSERT INTO appointment (patient_id, doctor_id, date, time, reason) VALUES (?, ?, ?, ?, ?)";
$appointment_stmt = $conn->prepare($insert_appointment_sql);
$appointment_stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $reason);
$appointment_stmt->execute();

// Update patient insurance status
$updateInsurance = "UPDATE patient SET insurance = ? WHERE p_id = ?";
$stmtInsurance = $conn->prepare($updateInsurance);
$stmtInsurance->bind_param("si", $insurance, $patient_id);
$stmtInsurance->execute();
$stmtInsurance->close();

if ($appointment_stmt->affected_rows > 0) {
    echo "Appointment successfully created.";
} else {
    echo "Error creating appointment: " . $conn->error;
}

$stmt->close();
$appointment_stmt->close();
$conn->close();
?>

