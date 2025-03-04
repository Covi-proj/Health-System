<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'e_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user's name, password, and username
    $stmt = $pdo->prepare("SELECT name, password, username FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Securely output the values
        $user_name = htmlspecialchars($user['name']); // User's full name
        $username = htmlspecialchars($user['username']); // Username
        $user_password = htmlspecialchars($user['password']); // User's password
    } else {
        $user_name = "Guest"; // Fallback if user not found
        $user_password = "";
        $username = ""; // Fallback for username
    }
} catch (PDOException $e) {
    $user_name = "Error retrieving name.";
    $user_password = ""; // Empty password on error
    $username = ""; // Empty username on error
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="icon.jfif" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Health-e | Patient Medical Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    th,
    tr {

        font-size: 14px;

    }

    .navbar {
        background-color: white;
        color: black;
        padding: 15px;
        font-size: 18px;
        font-weight: bold;
        position: sticky;
        top: 0;
        z-index: 100;
        display: flex;
        justify-content: space-between;
        /* Space between left and right content */
        align-items: center;
        /* Align items vertically */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar .logo {
        font-size: 28px;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .navbar .navbar-logo {
        width: 75px;
        height: auto;
        margin-right: 10px;
    }

    .navbar .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        /* Adds space between text and image */
    }

    .navbar .user-name {
        font-weight: normal;
        color: black;
        margin: 0;
    }

    .navbar .user-image {
        width: 30px;
        /* Adjust size as needed */
        height: 30px;
        border-radius: 50%;
        /* Makes it a circular image */
        object-fit: cover;
    }
</style>

<body>
    <div class="navbar">
        <div class="left-content">
            <span class="logo">
                <img src="unnamed.png" alt="Health-e Logo" class="navbar-logo">Health-e
            </span>


        </div>
        <div class="right-content">
            <div class="user-info">
                <p class="user-name"><?php echo $user_name; ?></p>
                <img src="avatar.jpg" alt="User Profile" class="user-image">


            </div>
        </div>
    </div>

    <?php

include('edit_delete_dbconn.php');

// Check if 'emp_id' is passed in the URL
if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string

    // Initialize variables to hold the employee data
    $emp_no = $name = $age = $bday = $gender = $division = $company = '';

    try {
        // Fetch the employee data from the database
        $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
        $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch_fit = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fetch_fit) {
            // Populate form fields with fetched data
            $emp_no = $fetch_fit['emp_no'];
            $name = $fetch_fit['name'];
            $age = $fetch_fit['age'];
            $bday = $fetch_fit['bday'];
            $gender = $fetch_fit['gender'];
            $division = $fetch_fit['division'];
            $company = $fetch_fit['company'];
        } else {
            echo "No Employee found with this ID.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
} else {
    echo '<div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 5px; border: 1px solid #ffeeba; font-family: Arial, sans-serif; text-align: center; font-size: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <strong>Warning:</strong> No Employee ID provided.
</div>';

    exit;
}


?>


    <div class="container mt-4">
    <a href="clinic_admin.php" class="btn btn-primary mb-3" style="font-weight: bold;">Go Back</a>
        <h2 class="mb-3">Medical History</h2>
        
        <div class="mb-3"></div>
        <div class="card mb-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <p class = "emp_no"><strong>Employee ID:</strong> <?= htmlspecialchars($emp_no) ?></p>
                        <p class = "name"><strong>Name:</strong> <?= htmlspecialchars($name) ?></pc>
                    </div>
                    <div class="col-md-6">
                        <p class = "age"><strong>Age:</strong> <?= htmlspecialchars($age) ?></p>
                        <p class = "division"><strong>Department:</strong> <?= htmlspecialchars($division) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class = "bday"><strong>Birthdate:</strong> <?= htmlspecialchars($bday) ?></p>
                        <p class = "gender"><strong>Gender:</strong> <?= htmlspecialchars($gender) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class = "company"><strong>Company:</strong> <?= htmlspecialchars($company) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="recordTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#ft">Fit to Work</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#medicine">Medicine</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#vital_signs">Vital Signs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#consult">Consultations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#confine">Confinement</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#sent">Sent Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#preg">Pregnant Notification</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#special_case">Special Case</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#report">Incident Accident Report</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Fit to Work Tab -->
            <div class="tab-pane fade show active" id="ft">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>From</th>
                            <th>To</th>
                            <th>No. days Absent</th>
                            <th>Reason</th>
                            <th>File</th>
                            <th>Nurse on Duty</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data
                            $stmt = $pdo->prepare("
                    SELECT tbl_fittowork.*, employees.emp_no, employees.name
                    FROM tbl_fittowork
                    LEFT JOIN employees ON tbl_fittowork.emp_id = employees.emp_id
                ");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['time'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['from_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['to_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['nfa'] ?? 'N/A') . ' day/s</td>';
                                    echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';

                                    // Handle file download
                                    echo '<td>';
                                    if (!empty($item['with_without_cert'])) {
                                        $filePath = htmlspecialchars($item['with_without_cert']);
                                        $fileName = basename($filePath);
                                        echo '<a href="download.php?file=' . urlencode($filePath) . '">' . $fileName . '</a>';
                                    } else {
                                        echo 'N/A';
                                    }
                                    echo '</td>';

                                    echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                // Display "No records found"
                                echo '<tr><td colspan="11" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            // Handle database errors
                            echo '<tr><td colspan="11" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                
            </div>


            <!-- Medicine Tab -->
            <div class="tab-pane fade" id="medicine">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th style="width: 100px;">Name</th>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Medicine</th>
                            <th>Supply</th>
                            <th>Quantity</th> <!-- Fixed spelling of "Quantity" -->
                            <th>Nurse on Duty</th>
                            <th>Note</th>
                        </tr> <!-- Added missing closing </tr> -->
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data
                            $stmt = $pdo->prepare("
                                SELECT tbl_medicine.*, employees.emp_no, employees.name
                                FROM tbl_medicine
                                LEFT JOIN employees ON tbl_medicine.emp_id = employees.emp_id
                                ");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['medicine'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['supply'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['quantity'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <!-- Vital Signs Tab -->
            <div class="tab-pane fade" id="vital_signs">
                <table class="table table-striped">
                    <thead>
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>From</th>
                            <th>To</th>
                            <th>No. days Absent</th>
                            <th>Reason</th>
                            <th>File</th>
                            <th>Nurse on Duty</th>
                            <th style="width :90px;">Note</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data
                            $stmt = $pdo->prepare("
                                    SELECT tbl_fittowork.*, employees.emp_no, employees.name
                                    FROM tbl_fittowork
                                    LEFT JOIN employees ON tbl_fittowork.emp_id = employees.emp_id
                                    ");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['time'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['from_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['to_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['nfa'] ?? 'N/A') . ' day/s </td>';
                                    echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                    // Handle file download
                                    echo '<td>';
                                    if (!empty($item['with_without_cert'])) {
                                        $filePath = htmlspecialchars($item['with_without_cert']);  // Sanitize the file path
                                        $fileName = basename($filePath);  // Extract just the file name
                                        echo '<a href="download.php?file=' . urlencode($filePath) . '">' . $fileName . '</a>'; // Display the file name as the link text
                                    } else {
                                        echo 'N/A';  // Display 'N/A' if the file path is empty
                                    }
                                    echo '</td>';

                                    echo '<td>' . htmlspecialchars($item['nod'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                    // Action buttons
                                    echo '<td class="text-center">';
                                    echo '<a href="edit_fitwork.php?emp_id=' . $item['emp_id'] . '&f_id=' . $item['f_id'] . '" class="link-dark fas fa-pen-to-square"></a>';

                                    echo '<a href="delete_fit.php?f_id=' . htmlspecialchars($item['f_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                // Display "No records found"
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            // Handle database errors
                            echo '<tr><td colspan="9" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>


                    </tbody>
                </table>
            </div>

            <!-- Consultations Tab -->
            <div class="tab-pane fade" id="consult">
                <table class="table table-striped">
                    <thead>
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Diagnosis</th>
                            <th>Physician</th>
                            <th>Remarks</th>
                            <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data
                            $query = "
                                SELECT 
                                tbl_consultation.date, 
                                employees.emp_id, 
                                employees.emp_no, 
                                employees.name, 
                                tbl_consultation.diagnosis, 
                                tbl_consultation.physician, 
                                tbl_consultation.remarks, 
                                tbl_consultation.status, 
                                tbl_consultation.cons_id
                                FROM tbl_consultation
                                LEFT JOIN employees ON tbl_consultation.emp_id = employees.emp_id
                                ";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();

                            // Fetch all rows
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';

                                    echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['physician'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['status'] ?? 'N/A') . '</td>';
                                    echo '<td>';
                                    echo '<a href="edit_cons.php?emp_id=' . urlencode($item['emp_id']) . '&cons_id=' . urlencode($item['cons_id']) . '" class="link-dark fas fa-pen-to-square"></a>';
                                    echo '<a href="delete_consult.php?cons_id=' . htmlspecialchars($item['cons_id']) . '" class="link-dark fas fa-trash" style="margin-left: 10px;"></a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="8" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>


                    </tbody>
                </table>
            </div>

            <!-- Confinement Tab -->
            <div class="tab-pane fade" id="confine">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date of Visit</th>
                            <th>Time of Visit</th>
                            <th>Chief Complaint</th>
                            <th>Time of Released</th>
                            <th>Total Hours Confined</th>
                            <th>Remarks</th>

                        </tr> <!-- Added missing closing </tr> -->
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM tbl_confinement LEFT JOIN employees ON tbl_confinement.emp_id = employees.emp_id");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date_of_visit'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['time_of_visit'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['chief_complaint'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['time_of_released'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['total_hrs'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <!-- Sent Home Tab -->
            <div class="tab-pane fade" id="sent">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Reason</th>
                            <th>Assessment</th>
                            <th>Diagnosis</th>
                            <th>Remarks</th>
                            <th>Sore Throat</th>
                            <th>Body Pain</th>
                            <th>Headache</th>
                            <th>Fever</th>
                            <th>Cough/Colds</th>
                            <th>LBM</th>
                            <th>Loss Taste/Smell</th>

                        </tr> <!-- Added missing closing </tr> -->
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM tbl_senthome LEFT JOIN employees ON tbl_senthome.emp_id = employees.emp_id");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['reason'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['assessment'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['sore_throat'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['body_pain'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['headache'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['fever'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['cough_colds'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['lbm'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['loss_ts'] ?? 'N/A') . '</td>';

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pregnant Notification Tab -->
            <div class="tab-pane fade" id="preg">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>EDC</th>
                            <th>Date Submited</th>
                            <th>Remarks</th>
                            <th>Start Leave</th>
                            <th>Leave End</th>
                            <th>Note</th>
                            <th>Back to Work</th>
                            <th>Arpron Date Released</th>
                            <th>Date Arpon Returned</th>
                            <th>Chair Released</th>

                        </tr> <!-- Added closing </tr> -->
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM tbl_pregnant_notif LEFT JOIN employees ON tbl_pregnant_notif.emp_id = employees.emp_id");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['edc'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date_sub'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['start_leave'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['l_end'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['note'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['btw'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['adr'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['dar'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['cdr'] ?? 'N/A') . '</td>';

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Special Case Tab -->
            <div class="tab-pane fade" id="special_case">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th style="width: 100px;">Name</th>
                            <th>Case No.</th>
                            <th>Date</th>
                            <th>Diagnosis</th>
                            <th>Retain Am Shift</th>
                            <th style="width: 600px;">No Exposure, To Chem, XRF, Soldering</th>
                            <th>Max HRS OT</th>
                            <th>No Sunday Shift</th>
                            <th style="width: 600px;">Always Secure FTW/ Med Cert Form</th>
                            <th>FF Up to Secure FTW/MED Cert Form</th>
                            <th>Provide Chair</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Controlled/Uncontrolled</th>

                        </tr> <!-- Added closing </tr> -->
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM tbl_specialcase LEFT JOIN employees ON tbl_specialcase.emp_id = employees.emp_id");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['case_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['diagnosis'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['retain'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['no_exp'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['max_ot'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['nss'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['asfmcf'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['ffcpm'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['provide_chair'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['status_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['cu'] ?? 'N/A') . '</td>';

                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Incident Accident Report Tab -->
            <div class="tab-pane fade" id="report">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr class="med">
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Date of Incident</th>
                            <th>Time of Incident</th>
                            <th>Place of incident</th>
                            <th>Nature of incident</th>
                            <th>Part of the body Affected</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Days Lost</th>
                            <th>Date of Absence</th>
                            <th>File</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "tbl_confinement" table and join it with the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM tbl_incident_report LEFT JOIN employees ON tbl_incident_report.emp_id = employees.emp_id");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr>';
                                    echo '<td style = "display: none;">' . htmlspecialchars($item['emp_id'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['date'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['time_i'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['place_i'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['nature_i'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['part_b_a'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['remarks'] ?? 'N/A') . '</td>';
                                    echo '<td style = "background-color:#0044cc; color: white; font-weight: bold; border-bottom: 1px solid black;">' . htmlspecialchars($item['status_'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['d_lost'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['d_absence'] ?? 'N/A') . '</td>';

                                    // Handle file download
                                    echo '<td>';
                                    if (!empty($item['file'])) {
                                        $filePath = htmlspecialchars($item['file']);  // Sanitize the file path
                                        $fileName = basename($filePath);  // Extract just the file name
                        
                                        // Display a link with the file name that opens in a new tab
                                        echo '<a href="' . $filePath . '" target="_blank">View ' . $fileName . '</a>';
                                    } else {
                                        echo 'N/A';  // Display 'N/A' if the file is empty
                                    }
                                    echo '</td>';

                                    // Action buttons
                        
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">No records found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="10" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
function filterEmployeeRecords(empId) {
    let rows = document.querySelectorAll("tbody tr");
    let hasRecords = false;

    rows.forEach(row => {
        let cells = row.querySelectorAll("td"); // Get all cells
        
        if (cells.length > 0) {
            let rowEmpId = cells[0].textContent.trim(); // Read first column
            console.log("Row emp_id:", rowEmpId, "| Expected emp_id:", empId);
            
            if (rowEmpId === empId) {
                row.style.display = "table-row";
                hasRecords = true;
            } else {
                row.style.display = "none";
            }
        }
    });

    let noRecordsRow = document.getElementById("no-records-row");
    if (noRecordsRow) {
        noRecordsRow.style.display = hasRecords ? "none" : "table-row";
    }
}

// Call the function on page load with the specific emp_id
window.onload = function () {
    const urlParams = new URLSearchParams(window.location.search);
    const empId = urlParams.get("emp_id");
    console.log("Extracted emp_id:", empId);
    if (empId) {
        filterEmployeeRecords(empId);
    }
};

</script>
</html>