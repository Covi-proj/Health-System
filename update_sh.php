<?php
include('edit_delete_dbconn.php');

$sh_id = $_GET['sh_id'] ?? $_POST['sh_id'] ?? null;
if (!$sh_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_senthome WHERE sh_id = :sh_id");
        $stmt->bindParam(':sh_id', $sh_id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo "No data found with this ID.";
            exit;
        }

        // Pre-set checkbox values to 'Yes' or '' based on the fetched data
        $sore_throat = ($consultation['sore_throat'] == 'Yes') ? 'Yes' : '';
        $body_pain = ($consultation['body_pain'] == 'Yes') ? 'Yes' : '';
        $headache = ($consultation['headache'] == 'Yes') ? 'Yes' : '';
        $fever = ($consultation['fever'] == 'Yes') ? 'Yes' : '';
        $cough_colds = ($consultation['cough_colds'] == 'Yes') ? 'Yes' : '';
        $lbm = ($consultation['lbm'] == 'Yes') ? 'Yes' : '';
        $loss_ts = ($consultation['loss_ts'] == 'Yes') ? 'Yes' : '';
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
}

// Handle the form submission (update the data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare(
            "UPDATE tbl_senthome SET 
                reason = :reason,
                assessment = :assessment,
                diagnosis = :diagnosis,
                remarks = :remarks,
                sore_throat = :sore_throat,
                body_pain = :body_pain,
                headache = :headache,
                fever = :fever,
                cough_colds = :cough_colds,
                lbm = :lbm,
                loss_ts = :loss_ts
            WHERE sh_id = :sh_id"
        );

        $stmt->execute([
            ':reason' => $_POST['reason'],
            ':assessment' => $_POST['assessment'],
            ':diagnosis' => $_POST['diagnosis'],
            ':remarks' => $_POST['remarks'],
            ':sore_throat' => isset($_POST['sore_throat']) ? 'Yes' : 'No', // Handle checkbox values
            ':body_pain' => isset($_POST['body_pain']) ? 'Yes' : 'No',
            ':headache' => isset($_POST['headache']) ? 'Yes' : 'No',
            ':fever' => isset($_POST['fever']) ? 'Yes' : 'No',
            ':cough_colds' => isset($_POST['cough_colds']) ? 'Yes' : 'No',
            ':lbm' => isset($_POST['lbm']) ? 'Yes' : 'No',
            ':loss_ts' => isset($_POST['loss_ts']) ? 'Yes' : 'No',
            ':sh_id' => $sh_id,
        ]);

        echo "<p style='color: green;'>Record updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: clinic_admin.php#form_section');
    exit;
}
?>