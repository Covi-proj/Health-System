<?php
include('edit_delete_dbconn.php');

$con_id = $_POST['con_id'] ?? null;
if (!$con_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_confinement WHERE con_id = :con_id");
        $stmt->bindParam(':con_id', $con_id, PDO::PARAM_INT);
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
            "UPDATE tbl_confinement SET 
                date_of_visit = :date_of_visit,
                time_of_visit = :time_of_visit,
                chief_complaint = :chief_complaint,
                time_of_released = :time_of_released,
                total_hrs = :total_hrs,
                remarks = :remarks
            
            WHERE con_id = :con_id"
        );

        $stmt->execute([
            ':date_of_visit' => $_POST['date_of_visit'],
            ':time_of_visit' => $_POST['time_of_visit'],
            ':chief_complaint' => $_POST['chief_complaint'],
            ':time_of_released' => $_POST['time_of_released'],
            ':total_hrs' => $_POST['total_hrs'],
            ':remarks' => $_POST['remarks'],
            
            ':con_id' => $con_id
        ]);

        echo "<p style='color: green;'>Record updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: clinic_admin.php#form_section');
    exit;
}
?>
