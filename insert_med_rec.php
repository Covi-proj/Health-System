<?php
include('edit_delete_dbconn.php');

$med_id = $_POST['med_id'] ?? null;
if (!$med_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_medicine WHERE med_id = :med_id");
        $stmt->bindParam(':med_id', $med_id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo "No data found with this ID.";
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
            "UPDATE tbl_medicine SET 
                date = :date,
                reason = :reason, 
                medicine = :medicine,  
                supply = :supply, 
                quantity = :quantity, 
                nod = :nod,
                note = :note,
                guest_name = :guest_name
            WHERE med_id = :med_id"
        );
        

        $stmt->execute([
            ':date' => $_POST['date'],
            ':reason' => $_POST['reason'],
            ':medicine' => $_POST['medicine'],
            ':supply' => $_POST['supply'],
            ':quantity' => $_POST['quantity'],
            ':nod' => $_POST['nod'],
            ':note' => $_POST['note'],
            ':guest_name' => $_POST['guest_name'],

            ':med_id' => $med_id,
        ]);

        echo "<p style='color: green;'>Record updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: clinic_admin.php#form_section');
    exit;
}
?>