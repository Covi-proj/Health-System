<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs and assign default values
    $date = $_POST['date'] ?? '';
    $time_i = $_POST['time_i'] ?? '';
    $place_i = $_POST['place_i'] ?? '';
    $nature_i = $_POST['nature_i'] ?? '';
    $part_b_a = $_POST['part_b_a'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $status_ = $_POST['status_'] ?? '';
    $d_lost = $_POST['d_lost'] ?? '';
    $d_absence = $_POST['d_absence'] ?? '';
    $emp_id = $_POST['f_id'] ?? '';

    // File handling
    $file = '';
    if (isset($_FILES['total_hrs']) && $_FILES['total_hrs']['error'] == 0) {
        $fileTmpPath = $_FILES['total_hrs']['tmp_name'];
        $fileName = $_FILES['total_hrs']['name'];
        $fileSize = $_FILES['total_hrs']['size'];
        $fileType = $_FILES['total_hrs']['type'];

        // Define the upload path
        $uploadDir = 'uploads/';
        $uploadFilePath = $uploadDir . basename($fileName);

        // Move the file to the uploads directory
        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            $file = $uploadFilePath; // Store the file path for database insertion
        } else {
            echo '<script>alert("Error uploading file.");</script>';
            exit();
        }
    } else {
        echo '<script>alert("File not uploaded or an error occurred.");</script>';
        exit();
    }

    // Validate required fields
    if (!empty($date) && !empty($time_i) && !empty($place_i) && !empty($nature_i) && !empty($part_b_a) && !empty($remarks) && !empty($status_) && !empty($d_lost) && !empty($d_absence) && !empty($file) && !empty($emp_id)) {
        try {
            // Validate emp_id exists
            $checkEmpSql = "SELECT emp_id FROM employees WHERE emp_id = ?";
            $checkEmpStmt = $conn->prepare($checkEmpSql);
            $checkEmpStmt->execute([$emp_id]);

            if ($checkEmpStmt->rowCount() === 0) {
                echo '<script>alert("Error: Employee ID does not exist.");</script>';
                exit();
            }

            // Insert data into the database
            $sql = "INSERT INTO tbl_incident_report (date, time_i, place_i, nature_i, part_b_a, remarks, status_, d_lost, d_absence, file, emp_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$date, $time_i, $place_i, $nature_i, $part_b_a, $remarks, $status_, $d_lost, $d_absence, $file, $emp_id]);

            echo '<script>window.location.href="clinic_admin.php#form_section";</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Database error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
        }
    } else {
        echo '<script>alert("Error: Missing required fields or file upload failed.");</script>';
    }
}
?>