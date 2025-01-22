<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $date_of_visit = $_POST['date_of_visit'] ?? null;
    $time_of_visit = $_POST['time_of_visit'] ?? null;
    $chief_complaint = $_POST['chief_complaint'] ?? null;
    $time_of_release = $_POST['time_of_released'] ?? null;
    $total_hrs = $_POST['total_hrs'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $emp_id = $_POST['emp_id'] ?? null;

    // Check required fields
    if ($date_of_visit && $time_of_visit && $chief_complaint && $time_of_visit && $time_of_release && $total_hrs && $remarks && $emp_id) {
        try {
            // Validate emp_id exists
            $checkEmpSql = "SELECT emp_id FROM employees WHERE emp_id = ?";
            $checkEmpStmt = $conn->prepare($checkEmpSql);
            $checkEmpStmt->execute([$emp_id]);

            if ($checkEmpStmt->rowCount() === 0) {
                echo '<script>alert("Error: Employee ID does not exist.");</script>';
                echo '<script>window.history.back();</script>';
                exit();
            }

            // Insert data
            $sql = "INSERT INTO tbl_confinement (date_of_visit, time_of_visit, chief_complaint, time_of_released, total_hrs, remarks, emp_id) 
                    VALUES (?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$date_of_visit, $time_of_visit, $chief_complaint, $time_of_release, $total_hrs, $remarks, $emp_id]);

            echo '<script>window.location.href="clinic_admin.php#form_section";</script>';
            exit();
        } catch (PDOException $e) {
            echo '<script>alert("Database error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
            echo '<script>window.history.back();</script>';
            exit();
        }
    } else {
        echo '<script>alert("Error: Missing required data fields.");</script>';
        echo '<script>window.history.back();</script>';
        exit();
    }
}
?>