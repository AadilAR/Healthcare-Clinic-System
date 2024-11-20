<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if the user is logged in
if (!isset($_SESSION['name'])) {
     // Redirect to login if not logged in
     header("Location: ../login/login.html");
     exit();
}

// Get the user's name from the session
$user_name = $_SESSION['name'];
 
// Fetch doctors from the database
$sql = "SELECT d_id, d_name FROM doctor"; // Adjust according to your doctor table structure
$result = $conn->query($sql);

// Initialize variables
$patient_id = $patient_name = $email = $date = $time = $doctor = $phone = $reason = $insurance_value = ""; // Added insurance_value

// Check if ID is set
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Prepare statement to fetch appointment details
    $sql = "SELECT a.*, p.insurance FROM appointment a INNER JOIN patient p ON a.patient_id = p.p_id WHERE a.a_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $patient_id = $row['patient_id']; // Get the patient_id from the appointment
        $date = $row['date'];
        $time = $row['time'];
        $doctor = $row['doctor_name']; // Updated to fetch from the new column
        $reason = $row['reason'];
        $insurance_value = $row['insurance']; // Fetch insurance status
    }
    $stmt->close();
}

// Fetch all patients for the dropdown
$patients_sql = "SELECT p_id, p_name, p_email, p_contact, insurance FROM patient"; // Ensure this includes insurance
$patients_result = $conn->query($patients_sql);
?>



<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="icon" type="x-icon" href="HCC.ico">
  

  <title>Receptionist | Health Care Clinic</title>


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

  <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <div class="header_top">
        <div class="container">
          <div class="contact_nav">
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Call : 077-556-8493
              </span>
            </a>
            <a href="mailto: hcc@gmail.com">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                Email : hcc@gmail.com
              </span>
            </a>
            
          </div>
        </div>
      </div>
      <div class="header_bottom">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="index.html">
              <img src="..\lr\images\logo (2).png" alt="">
            </a>


            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <div class="d-flex mr-auto flex-column flex-lg-row align-items-center">
                <ul class="navbar-nav  ">
                  
                  <li class="nav-item">
                    <a class="nav-link" href="#appointment">Make an Appointment</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="patientstable.php">All Appointments</a>
                  </li>
                  
                </ul>
              </div>
              <div class="quote_btn-container">
                <a href="../lr/logout.php">
                  <i class="fa fa-user" aria-hidden="true"></i>
                  <span>
                    Sign Out
                  </span>
                </a>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
      <div class="dot_design">
        <img src="images/dots.png" alt="">
      </div>
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container ">
              <div class="row">
                <div class="col-md-6">
                  <div class="detail-box">
                    
                    <h1>
                          Welcome Back <br>
                      <span>
                          <?php echo htmlspecialchars($user_name); ?>
                      </span>
                    </h1>
                    
                    <a href="tel:+94775568493">
                      Contact Us
                    </a>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="img-box">
                    <img src="images/slider-img.jpg" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>

    </section>
    <!-- end slider section -->
  </div>


<!-- book section -->
<section class="book_section layout_padding">
  <div class="container">
    <div class="row">
      <div class="col">
        <form role="form" method="post" action="enter.php">

          <!-- Hidden input for appointment ID -->
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($appointment_id); ?>">

          <h4 id="appointment">
            BOOK <span>APPOINTMENT</span>
          </h4>

          <div class="form-row ">
            <div class="form-group col-lg-4">
              <label for="inputPatientName">Select Patient</label>
              <select class="form-control" name="patient" id="patient" onchange="fetchPatientDetails()" required>
                <option value="">Select Patient</option>
                <?php
                if ($patients_result->num_rows > 0) {
                  while($row = $patients_result->fetch_assoc()) {
                    $selected = ($row['p_id'] == $patient_id) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($row['p_id']) . '" ' . $selected . '>' . htmlspecialchars($row['p_name']) . '</option>';
                  }
                } else {
                  echo '<option value="">No patients available</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputEmail">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
            </div>

            <div class="form-group col-lg-4">
              <label for="inputPhone">Phone Number</label>
              <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" readonly>
            </div>
          </div>

          <div class="form-row ">
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
              <select class="form-control" name="doctor_id" required>
                <option value="">Select Doctor</option>
                <?php
                // Fetch doctors from the database
                $doctor_sql = "SELECT d_id, d_name FROM doctor";
                $doctor_result = $conn->query($doctor_sql);
                while($doctor = $doctor_result->fetch_assoc()) {
                  echo "<option value='{$doctor['d_id']}'>{$doctor['d_name']}</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <div class="form-row ">
            <div class="form-group col-lg-4">
              <label for="insurance">Insurance</label>
              <select class="form-control" name="insurance" id="insurance" required>
                <option value="">Select Insurance</option>
                <option value="Yes" <?php if($insurance_value == 'Yes') echo 'selected'; ?>>Yes</option>
                <option value="No" <?php if($insurance_value == 'No') echo 'selected'; ?>>No</option>
              </select>
            </div>

            

            <div class="form-group col-lg-8">
              <label for="inputReason">Reason for Visit</label>
              <textarea class="form-control" rows="3" id="reason" name="reason" placeholder="Reason"><?php echo htmlspecialchars($reason); ?></textarea>
            </div>
          </div>

          <div class="btn-box">
            <button type="submit" class="btn">Submit Now</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</section>



  <!-- end book section -->




  <!-- info section -->
  <section class="info_section ">
    <div class="container">
      <div class="info_top">
        <div class="info_logo">
          <a href="">
            <img src="..\lr\images\logo (2).png" alt="">
          </a>
        </div>
        <div class="info_form">
          <form action="">
            <input type="email" placeholder="Your email">
            <button>
              Subscribe
            </button>
          </form>
        </div>
      </div>
      <div class="info_bottom layout_padding2">
        <div class="row info_main_row">
          <div class="col-md-6 col-lg-3">
            <h5>
              Address
            </h5>
            <div class="info_contact">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Location
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call 077-556-8493
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope"></i>
                <span>
                  hcc@gmail.com
                </span>
              </a>
            </div>
            <div class="social_box">
              <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
              <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="info_links">
              <h5>
                Useful link
              </h5>
              <div class="info_links_menu">
                <a class="active" href="index.html">
                  Home
                </a>
                <a href="about.html">
                  About
                </a>
                <a href="treatment.html">
                  Treatment
                </a>
                <a href="doctor.html">
                  Doctors
                </a>
                <a href="testimonial.html">
                  Testimonial
                </a>
                <a href="contact.html">
                  Contact us
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-3">
            <div class="info_post">
              <h5>
                OPENING HOURS
              </h5>
              <div class="post_box">
                <p>Monday - Friday</p>
                <p>06:00 AM - 10:00 PM</p>
                <br>
                <p>Saturday</p>
                <p>09:00 AM - 06:00 PM</p>
                <br>
                <p>Sunday - Closed</p>
              </div>
            </div>
          </div>
          
          
        </div>
      </div>
    </div>
  </section>
  <!-- end info_section -->


  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By Health Care Clinic
        
      </p>
    </div>
  </footer>
  <!-- footer section -->

  <!-- jQery -->
  <script src="js/jquery-3.4.1.min.js"></script>
  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- nice select -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js" integrity="sha256-Zr3vByTlMGQhvMfgkQ5BtWRSKBGa2QlspKYJnkjZTmo=" crossorigin="anonymous"></script>
  <!-- owl slider -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- datepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>

  <script>
          function fetchPatientDetails() {
               var patientId = document.getElementById("patient").value;
               if (patientId) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "get_patient_details.php?p_id=" + patientId, true);
                    xhr.onload = function() {
                         if (this.status === 200) {
                              var response = JSON.parse(this.responseText);
                              document.getElementById("email").value = response.p_email;
                              document.getElementById("phone").value = response.p_contact;
                              
                              // Set the insurance value
                              document.getElementById("insurance").value = response.insurance; // Assuming insurance is the name of your dropdown
                         }
                    }
                    xhr.send();
               } else {
                    document.getElementById("email").value = "";
                    document.getElementById("phone").value = "";
                    document.getElementById("insurance").value = ""; // Clear insurance dropdown if no patient is selected
               }
          }
     </script>


</body>

</html>