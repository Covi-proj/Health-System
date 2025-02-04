<?php
include('edit_delete_dbconn.php');

// Get `sc_id` from GET or POST
$sc_id = $_GET['sc_id'] ?? $_POST['sc_id'] ?? null;
if (!$sc_id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data when accessed via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM tbl_specialcase WHERE sc_id = :sc_id");
        $stmt->bindParam(':sc_id', $sc_id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo "No data found with this ID.";
            exit;
        }

        // Pre-set checkbox values
        $no_exp = ($consultation['no_exp'] == 'Yes') ? 'Yes' : '';
        $max_ot = ($consultation['max_ot'] == 'Yes') ? 'Yes' : '';
        $nss = ($consultation['nss'] == 'Yes') ? 'Yes' : '';
        $asfmcf = ($consultation['asfmcf'] == 'Yes') ? 'Yes' : '';
        $ffcpm = ($consultation['ffcpm'] == 'Yes') ? 'Yes' : '';
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
}

// Handle form submission (update data) when accessed via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare(
            "UPDATE tbl_specialcase SET 
                date = :date,
                diagnosis = :diagnosis,
                retain = :retain,
                no_exp = :no_exp,
                max_ot = :max_ot,
                nss = :nss,
                asfmcf = :asfmcf,
                ffcpm = :ffcpm,
                provide_chair = :provide_chair,
                remarks = :remarks,
                status_ = :status_,
                cu = :cu
            WHERE sc_id = :sc_id"
        );

        // Execute the statement with bound parameters
        $stmt->execute([
            ':date' => $_POST['reason'] ?? null,
            ':diagnosis' => $_POST['diagnosis'] ?? null,
            ':retain' => $_POST['retain'] ?? null,
            ':no_exp' => isset($_POST['no_exp']) ? 'Yes' : 'No',
            ':max_ot' => isset($_POST['max_ot']) ? 'Yes' : 'No',
            ':nss' => isset($_POST['nss']) ? 'Yes' : 'No',
            ':asfmcf' => isset($_POST['asfmcf']) ? 'Yes' : 'No',
            ':ffcpm' => isset($_POST['ffcpm']) ? 'Yes' : 'No',
            ':provide_chair' => isset($_POST['provide_chair']) ? 'Yes' : 'No',
            ':remarks' => $_POST['remarks'] ?? null,
            ':status_' => $_POST['status_'] ?? null,
            ':cu' => $_POST['cu'] ?? null,
            ':sc_id' => $sc_id
        ]);

        // Success message
        echo "<p style='color: green;'>Record updated successfully!</p>";

        // Redirect to another page
        header('location: clinic_admin.php#form_section');
        exit;
    } catch (PDOException $e) {
        // Error handling
        echo "Update Error: " . $e->getMessage();
        exit;
    }
}
?>