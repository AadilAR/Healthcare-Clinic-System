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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Loop through the treat_plan inputs and update the patient table
    foreach ($_POST['treat_plan'] as $appointment_id => $treat_plan) {
        // Update the treatment plan for the patient associated with the appointment
        $sql = "UPDATE patient 
                JOIN appointment ON patient.p_id = appointment.patient_id 
                SET patient.treat_plan = ? 
                WHERE appointment.a_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $treat_plan, $appointment_id);
        $stmt->execute();
    }

    // Redirect back to the appointments page after the update
    header("Location: doctor_appointments.php");
    exit();
}

$conn->close();
?>
