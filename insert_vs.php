<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $bp = $_POST['bp'] ?? null;
    $temp = $_POST['temp'] ?? null;
    $pr =$_POST['pr'] ?? null;
    $rr = $_POST['rr'] ?? null;
    $ol = $_POST['ol'] ?? null;
    $note = $_POST['note'] ?? null;
    $emp_id = $_POST['emp_id'] ?? null;

    // Handle file upload


    // Check required fields
    if ($date && $time && $bp && $temp && $pr && $rr && $ol && $note && $emp_id) {
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
            $sql = "INSERT INTO tbl_vitalsgn (date, time, bp, temp, pr, rr, ol, note, emp_id) 
                    VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$date, $time, $bp, $temp, $pr, $rr, $ol, $note, $emp_id]);


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