<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs and assign default values
    $edc = $_POST['edc'] ?? '';
    $date_sub = $_POST['date_sub'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $start_leave = $_POST['start_leave'] ?? '';
    $l_end = $_POST['l_end'] ?? '';
    $note = $_POST['note'] ?? '';
    $btw = $_POST['btw'] ?? '';
    $adr = $_POST['adr'] ?? '';
    $dar = $_POST['dar'] ?? '';
    $cdr = $_POST['cdr'] ?? '';
    $emp_id = $_POST['emp_id'] ?? '';

    // Validate required fields
    if (!empty($edc) && !empty($date_sub) && !empty($remarks) && !empty($start_leave) && !empty($l_end) && !empty($note) && !empty($btw) && !empty($adr) && !empty($dar) && !empty($cdr) && !empty($emp_id)) {
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
            $sql = "INSERT INTO tbl_pregnant_notif (edc, date_sub, remarks, start_leave, l_end, note, btw, adr, dar, cdr, emp_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$edc, $date_sub, $remarks, $start_leave, $l_end, $note, $btw, $adr, $dar, $cdr, $emp_id]);

            echo '<script>window.location.href="clinic_admin.php#form_section";</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Database error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
        }
    } else {
        echo '<script>alert("Error: Missing required fields.");</script>';
    }
}
?>