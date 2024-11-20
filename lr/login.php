<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Role selected in the dropdown

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'clinic');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Determine which table to check based on the role
    if ($role == 'Doctor') {
        $sql = "SELECT * FROM doctor WHERE d_email = '$username' AND d_password = '$password'";
        $redirectPage = "../doctor/doctor_portal.php";
    } elseif ($role == 'Nurse') {
        $sql = "SELECT * FROM nurse WHERE n_email = '$username' AND n_password = '$password'";
        $redirectPage = "../nurse/nurse_portal.php";
    } elseif ($role == 'Receptionist') {
        $sql = "SELECT * FROM receptionist WHERE r_email = '$username' AND r_password = '$password'";
        $redirectPage = "../Receptionist/appointment.php";
    } elseif ($role == 'Patient') {
        $sql = "SELECT * FROM patient WHERE p_email = '$username' AND p_password = '$password'";
        $redirectPage = "../User/user_portal.php";
    } else {
        echo "Invalid role selected.";
        exit;
    }

    // Execute the query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Store user name in session
        session_start();
        $_SESSION['name'] = $user['d_name'] ?? $user['n_name'] ?? $user['r_name'] ?? $user['p_name'];
        $_SESSION['role'] = $role; // Store role in session
        header("Location: " . $redirectPage);
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password'); window.location.href = 'login.html';</script>";
    }

    // Close the connection
    $conn->close();
}
?>
