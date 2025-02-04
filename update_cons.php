<?php
include('edit_delete_dbconn.php');

$cons_id = $_POST['cons_id'] ?? null;
if (!$cons_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_consultation WHERE cons_id = :cons_id");
        $stmt->bindParam(':cons_id', $cons_id, PDO::PARAM_INT);
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
            "UPDATE tbl_consultation SET 
                date = :date,
                diagnosis = :diagnosis,
                physician = :physician,
                remarks = :remarks,
                status = :status
            
            WHERE cons_id = :cons_id"
        );

        $stmt->execute([
            ':date' => $_POST['date'],
            ':diagnosis' => $_POST['diagnosis'],
            ':physician' => $_POST['physician'],
            ':remarks' => $_POST['remarks'],
            ':status' => $_POST['status'],
            
            ':cons_id' => $cons_id,
        ]);

        echo "<p style='color: green;'>Record updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: clinic_admin.php#form_section');
    exit;
}
?>
