<?php
include('edit_delete_dbconn.php');

$c_id = $_POST['c_id'] ?? null;
if (!$c_id) {
    echo "No consultation ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM consultation WHERE c_id = :c_id");
        $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo "No consultation found with this ID.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare(
            "UPDATE consultation SET 
                pnt_name = :pnt_name, 
                division = :division, 
                company = :company, 
                c_date = :c_date, 
                bp = :bp, 
                temp = :temp, 
                HR = :HR, 
                RR = :RR, 
                O2_sat = :O2_sat, 
                medicine = :medicine, 
                qty = :qty, 
                remarks = :remarks 
            WHERE c_id = :c_id"
        );

        $stmt->execute([
            ':pnt_name' => $_POST['pnt_name'],
            ':division' => $_POST['division'],
            ':company' => $_POST['company'],
            ':c_date' => $_POST['c_date'],
            ':bp' => $_POST['bp'],
            ':temp' => $_POST['temp'],
            ':HR' => $_POST['HR'],
            ':RR' => $_POST['RR'],
            ':O2_sat' => $_POST['O2_sat'],
            ':medicine' => $_POST['medicine'],
            ':qty' => $_POST['qty'],
            ':remarks' => $_POST['remarks'],
            ':c_id' => $c_id,
        ]);

        echo "<p style='color: green;'>Consultation updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: super-admin.php#balances');
    exit;
}
?>
