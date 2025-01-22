<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs and assign default values
    $reason = $_POST['reason'] ?? '';
    $assessment = $_POST['assessment'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $sore_throat = $_POST['sore_throat'] ?? 'No';
    $body_pain = $_POST['body_pain'] ?? 'No';
    $headache = $_POST['headache'] ?? 'No';
    $fever = $_POST['fever'] ?? 'No';
    $cough_colds = $_POST['cough_colds'] ?? 'No';
    $lbm = $_POST['lbm'] ?? 'No';
    $loss_ts = $_POST['loss_ts'] ?? 'No';
    $emp_id = $_POST['emp_id'] ?? '';

    // Validate required fields
    if (!empty($reason) && !empty($assessment) && !empty($diagnosis) && !empty($remarks) && !empty($emp_id)) {
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
            $sql = "INSERT INTO tbl_senthome (reason, assessment, diagnosis, remarks, sore_throat, body_pain, headache, fever, cough_colds, lbm, loss_ts, emp_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$reason, $assessment, $diagnosis, $remarks, $sore_throat, $body_pain, $headache, $fever, $cough_colds, $lbm, $loss_ts, $emp_id]);

            echo '<script>alert("Data inserted successfully!"); window.location.href="clinic_admin.php#form_section";</script>';
        } catch (PDOException $e) {
            echo '<script>alert("Database error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
        }
    } else {
        echo '<script>alert("Error: Missing required fields.");</script>';
    }
}
?>