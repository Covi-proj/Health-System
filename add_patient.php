<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data or set to NULL if not provided
    $emp = $_POST['emp'] ?? null;
    $pnt_name = $_POST['pnt_name'] ?? null;
    $division = $_POST['division'] ?? null;
    $company = $_POST['company'] ?? null;
    $c_date = $_POST['c_date'] ?? null;
    $rcv = $_POST['rcv'] ?? null;
    $bp = $_POST['bp'] ?? null;
    $temp = $_POST['temp'] ?? null;
    $HR = $_POST['HR'] ?? null;
    $RR = $_POST['RR'] ?? null;
    $O2_sat = $_POST['O2_sat'] ?? null;
    $medicine = $_POST['medicine'] ?? null;
    $qty = $_POST['qty'] ?? null;
    $diagnosis = $_POST['diagnosis'] ?? null;
    $remarks = $_POST['remarks'] ?? null;

    try {
        // SQL query for inserting the data
        $sql = "INSERT INTO consultation (emp, pnt_name, division, company, c_date, rcv, bp, temp, HR, RR, O2_sat, medicine, qty, diagnosis, remarks) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute
        $stmt->execute([
            $emp ?: null,
            $pnt_name ?: null,
            $division ?: null,
            $company ?: null,
            $c_date ?: null,
            $rcv ?: null,
            $bp ?: null,
            $temp ?: null,
            $HR ?: null,
            $RR ?: null,
            $O2_sat ?: null,
            $medicine ?: null,
            $qty ?: null,
            $diagnosis ?: null,
            $remarks ?: null
        ]);

        // Redirect to the desired section
        echo '<script>window.location.href="clinic_admin.php#balances";</script>';
        exit();
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
}
?>
