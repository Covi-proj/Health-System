<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $date = $_POST['date'] ?? null;
    $reason = $_POST['reason'] ?? null;
    $medicine = $_POST['medicine'] ?? null;
    $supply = $_POST['supply'] ?? null;
    $quantity = htmlspecialchars($_POST['quantity'] ?? null);
    $nod = htmlspecialchars($_POST['nod'] ?? null);
    $note = htmlspecialchars($_POST['note'] ?? null);
    $emp_id = $_POST['emp_id'] ?? null;

    // Handle file upload


    // Check required fields
    if ($date && $reason && $medicine && $supply && $quantity && $nod && $note && $emp_id) {
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
            $sql = "INSERT INTO tbl_medicine (date, reason, medicine, supply, quantity, nod, note, emp_id) 
                    VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$date, $reason, $medicine, $supply, $quantity, $nod, $note, $emp_id]);


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