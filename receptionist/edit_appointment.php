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

// Get the appointment ID from the URL
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id) {
    // Fetch the appointment details
    $sql = "
    SELECT a.a_id, a.patient_id, a.doctor_id, a.date, a.time, a.reason, 
           p.p_name, p.p_email, p.p_contact, p.insurance, 
           d.d_name
    FROM appointment a
    JOIN patient p ON a.patient_id = p.p_id
    JOIN doctor d ON a.doctor_id = d.d_id
    WHERE a.a_id = $appointment_id";
    
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Store appointment details into variables
        $patient_id = $row['patient_id'];
        $doctor_id = $row['doctor_id'];
        $date = $row['date'];
        $time = $row['time'];
        $reason = $row['reason'];
        $patient_name = $row['p_name'];
        $email = $row['p_email'];
        $phone = $row['p_contact'];
        $insurance = $row['insurance'];
        $doctor_name = $row['d_name'];
    }
} else {
    echo "Invalid appointment ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit an Appointment | Health Care Clinic</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="x-icon" href="HCC.ico">

    <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <!-- nice select -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha256-mLBIhmBvigTFWPSCtvdu6a76T+3Xyt+K571hupeFLg4=" crossorigin="anonymous" />
    <!-- datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
</head>
<body>

<!-- Edit Appointment section -->
<section class="book_section layout_padding">
  <div class="container">
    <div class="row">
      <div class="col">
        <form role="form" method="post" action="update1.php">

          <!-- Hidden input for appointment ID -->
          <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">

          <h4 id="appointment">
            EDIT <span>APPOINTMENT</span>
          </h4>

          <div class="form-row">
            <div class="form-group col-lg-4">
              <label for="inputPatientName">Select Patient</label>
              <select class="form-control" name="patient" id="inputPatientName" required>
                <option value="">Select Patient</option>
                <?php
                // Fetch patients for the dropdown
                $patients_sql = "SELECT p_id, p_name FROM patient";
                $patients_result = $conn->query($patients_sql);
                if ($patients_result->num_rows > 0) {
                    while ($patient = $patients_result->fetch_assoc()) {
                        $selected = ($patient['p_id'] == $patient_id) ? 'selected' : '';
                        echo "<option value='{$patient['p_id']}' $selected>{$patient['p_name']}</option>";
                    }
                }
                ?>
              </select>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputEmail">Email</label>
              <input type="email" class="form-control" id="inputEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputPhone">Phone Number</label>
              <input type="tel" class="form-control" id="inputPhone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-lg-4">
              <label for="inputDate">Select Date</label>
              <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>" required>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputTime">Select Time</label>
              <input type="time" name="time" class="form-control" value="<?php echo htmlspecialchars($time); ?>" required>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputDoctorName">Select Doctor</label>
              <select class="form-control" name="doctor_id" id="inputDoctorName" required>
                <option value="">Select Doctor</option>
                <?php
                // Fetch doctors for the dropdown
                $doctor_sql = "SELECT d_id, d_name FROM doctor";
                $doctor_result = $conn->query($doctor_sql);
                while ($doctor = $doctor_result->fetch_assoc()) {
                    $selected = ($doctor['d_id'] == $doctor_id) ? 'selected' : '';
                    echo "<option value='{$doctor['d_id']}' $selected>{$doctor['d_name']}</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-lg-4">
              <label for="inputInsurance">Insurance</label>
              <select class="form-control" name="insurance" required>
                <option value="Yes" <?php if ($insurance == 'Yes') echo 'selected'; ?>>Yes</option>
                <option value="No" <?php if ($insurance == 'No') echo 'selected'; ?>>No</option>
              </select>
            </div>

            <div class="form-group col-lg-8">
              <label for="inputReason">Reason for Visit</label>
              <textarea class="form-control" rows="3" id="inputReason" name="reason" placeholder="Reason"><?php echo htmlspecialchars($reason); ?></textarea>
            </div>
          </div>

          <div class="btn-box">
            <button type="submit" class="btn">Update Now</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>

</body>
</html>
