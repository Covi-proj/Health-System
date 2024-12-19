<?php
include('edit_delete_dbconn.php');

$f_id = $_POST['f_id'] ?? null;
if (!$f_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM fit_to_work WHERE f_id = :f_id");
        $stmt->bindParam(':f_id', $f_id, PDO::PARAM_INT);
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
            "UPDATE fit_to_work SET 
                f_date = :f_date,
                s_date = :s_date, 
                e_date = :e_date,  
                 time_in = :time_in, 
                patient_name = :patient_name, 
                diagnosis = :diagnosis, 
                ftw = :ftw, 
                date_ofabs = :date_ofabs, 
                Med_name = :Med_name, 
                remarks = :remarks, 
                nod = :nod
            
            WHERE f_id = :f_id"
        );

        $stmt->execute([
            ':f_date' => $_POST['f_date'],
            ':s_date' => $_POST['e_date'],
            ':e_date' => $_POST['s_date'],
            ':time_in' => $_POST['time_in'],
            ':patient_name' => $_POST['patient_name'],
            ':diagnosis' => $_POST['diagnosis'],
            ':ftw' => $_POST['ftw'],
            ':date_ofabs' => $_POST['date_ofabs'],
            ':Med_name' => $_POST['Med_name'],
            ':remarks' => $_POST['remarks'],
            ':nod' => $_POST['nod'],
           
            ':f_id' => $f_id,
        ]);

        echo "<p style='color: green;'>Consultation updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: clinic_admin.php#fit');
    exit;
}
?>
