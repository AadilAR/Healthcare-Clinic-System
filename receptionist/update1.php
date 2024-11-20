<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form values
$appointment_id = intval($_POST['appointment_id']);
$patient_id = intval($_POST['patient']);
$doctor_id = intval($_POST['doctor_id']);
$date = $_POST['date'];
$time = $_POST['time'];
$reason = $_POST['reason'];
$insurance = $_POST['insurance']; // Insurance value from form

// Start transaction
$conn->begin_transaction();

try {
    // Update appointment details using prepared statement
    $update_appointment_sql = "UPDATE appointment SET doctor_id = ?, date = ?, time = ?, reason = ? WHERE a_id = ?";
    $stmt = $conn->prepare($update_appointment_sql);
    $stmt->bind_param("isssi", $doctor_id, $date, $time, $reason, $appointment_id);

    if ($stmt->execute()) {
        // Update insurance in the patient table using prepared statement
        $update_patient_sql = "UPDATE patient SET insurance = ? WHERE p_id = ?";
        $stmt_patient = $conn->prepare($update_patient_sql);
        $stmt_patient->bind_param("si", $insurance, $patient_id);

        if ($stmt_patient->execute()) {
            // Commit the transaction if both updates succeed
            $conn->commit();
            header("Location: patientstable.php");
        } else {
            // Rollback transaction if patient update fails
            $conn->rollback();
            echo "Error updating patient insurance: " . $stmt_patient->error;
        }
        $stmt_patient->close();
    } else {
        // Rollback transaction if appointment update fails
        $conn->rollback();
        echo "Error updating appointment: " . $stmt->error;
    }
    $stmt->close();
} catch (Exception $e) {
    // Rollback transaction in case of any exception
    $conn->rollback();
    echo "Transaction failed: " . $e->getMessage();
}

$conn->close();
?>

