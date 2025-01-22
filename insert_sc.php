<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $case_no = $_POST['case_no'] ?? '';
    $date = $_POST['date'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $retain = $_POST['retain'] ?? '';
    $no_exp = $_POST['no_exp'] ?? 'No';
    $max_ot = $_POST['max_ot'] ?? 'No';
    $nss = $_POST['nss'] ?? 'No';
    $asfmcf = $_POST['asfmcf'] ?? 'No';
    $ffcpm = $_POST['ffcpm'] ?? 'No';
    $provide_chair = $_POST['provide_chair'] ?? 'No';
    $remarks = $_POST['remarks'] ?? '';
    $status_ = $_POST['status_'] ?? '';
    $cu = $_POST['cu'] ?? '';
    $emp_id = $_POST['f_id'] ?? ''; // Adjusted for hidden input field

    if (!empty($case_no) && !empty($date) && !empty($diagnosis) && !empty($emp_id)) {
        try {
            $checkEmpSql = "SELECT emp_id FROM employees WHERE emp_id = ?";
            $checkEmpStmt = $conn->prepare($checkEmpSql);
            $checkEmpStmt->execute([$emp_id]);

            if ($checkEmpStmt->rowCount() === 0) {
                echo '<script>alert("Error: Employee ID does not exist.");</script>';
                exit();
            }

            $sql = "INSERT INTO tbl_specialcase (case_no, date, diagnosis, retain, no_exp, max_ot, nss, asfmcf, ffcpm, provide_chair, remarks, status_, cu, emp_id) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$case_no, $date, $diagnosis, $retain, $no_exp, $max_ot, $nss, $asfmcf, $ffcpm, $provide_chair, $remarks, $status_, $cu, $emp_id]);

            echo '<script>window.location.href="clinic_admin.php#form_section";</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Database error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
        }
    } else {
        echo '<script>alert("Error: Missing required fields. Please ensure case number, date, diagnosis, and employee ID are filled out.");</script>';
    }
}
?>