<?php
session_start();
include "edit_delete_dbconn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $f_date = $_POST['f_date'] ?? null;
    $time_in = $_POST['time_in'] ?? null;
    $time_out = $_POST['time_out'] ?? null;
    $patient_name = $_POST['patient_name'] ?? null;
    $diagnosis = $_POST['diagnosis'] ?? null;
    $ftw = $_POST['ftw'] ?? null;
    $date_ofabs = $_POST['date_ofabs'] ?? null;
    $Med_name = $_POST['Med_name'] ?? null;
    $remarks = $_POST['remarks'] ?? null;
    $nod = $_POST['nod'] ?? null;
   

    if ($f_date && $time_in && $time_out && $patient_name && $diagnosis && $ftw && $date_ofabs && $Med_name && $nod) {
        try {
            $sql = "INSERT INTO fit_to_work (f_date, time_in, time_out, patient_name, diagnosis, ftw, date_ofabs, Med_name, remarks, nod) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$f_date, $time_in, $time_out, $patient_name, $diagnosis, $ftw, $date_ofabs, $Med_name, $remarks, $nod]);
    

            // Redirect to the desired section
            echo '<script>window.location.href="super-admin.php#fit";</script>';
            exit();

        } catch (PDOException $e) {
            echo 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        echo 'Missing data fields';
    }
}
?>
