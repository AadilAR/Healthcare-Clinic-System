<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "clinic";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];
    $password = $_POST['pass'];

    // Check role and insert into the appropriate table
    if ($role === "Doctor") {
        $specialization = $_POST['specialization']; // Get specialization from form
        $sql = "INSERT INTO doctor (d_name, d_email, d_age, specialization, d_contact, d_password) VALUES ('$name', '$email', '$age', '$specialization', '$contact', '$password')";
    } elseif ($role === "Nurse") {
        $sql = "INSERT INTO nurse (n_name, n_email, n_age, n_contact, n_password) VALUES ('$name', '$email', '$age', '$contact', '$password')";
    } elseif ($role === "Receptionist") {
        $sql = "INSERT INTO receptionist (r_name, r_email, r_age, r_contact, r_password) VALUES ('$name', '$email', '$age', '$contact', '$password')";
    } elseif ($role === "Patient") {
        $sql = "INSERT INTO patient (p_name, p_email, p_age, p_contact, p_password) VALUES ('$name', '$email', '$age', '$contact', '$password')";
    } else {
        echo "Invalid role selected.";
        exit;
    }

    // Execute the query and redirect on success
    if ($conn->query($sql) === TRUE) {
        // Redirect to login page after successful registration
        header("Location: login.html");
        exit(); // Always call exit after redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $conn->close();
?>
