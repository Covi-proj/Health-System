<?php
include('edit_delete_dbconn.php');

// Check if the consultation ID is provided
$c_id = $_POST['c_id'] ?? null;
if (!$c_id) {
    echo "No consultation ID provided!";
    exit;
}

// Fetch the data if it's a GET request
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

// Handle the update when the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $fields = [
        'emp', 'pnt_name', 'division', 'company', 'c_date', 'rcv', 'bp', 'temp', 
        'HR', 'RR', 'O2_sat', 'medicine', 'diagnosis', 'remarks'
    ];

    foreach ($fields as $field) {
        if (empty($_POST[$field])) {
            echo "Error: The field '$field' is required!";
            exit;
        }
    }

    try {
        $stmt = $conn->prepare(
            "UPDATE consultation SET 
                emp = :emp, 
                pnt_name = :pnt_name, 
                division = :division, 
                company = :company, 
                c_date = :c_date,
                rcv = :rcv, 
                bp = :bp, 
                temp = :temp, 
                HR = :HR, 
                RR = :RR, 
                O2_sat = :O2_sat, 
                medicine = :medicine, 
                qty = :qty, 
                diagnosis = :diagnosis, 
                remarks = :remarks 
            WHERE c_id = :c_id"
        );

        // Bind parameters and execute
        $stmt->execute([
            ':emp' => $_POST['emp'],
            ':pnt_name' => $_POST['pnt_name'],
            ':division' => $_POST['division'],
            ':company' => $_POST['company'],
            ':c_date' => $_POST['c_date'],
            ':rcv' => $_POST['rcv'],
            ':bp' => $_POST['bp'],
            ':temp' => $_POST['temp'],
            ':HR' => $_POST['HR'],
            ':RR' => $_POST['RR'],
            ':O2_sat' => $_POST['O2_sat'],
            ':medicine' => $_POST['medicine'],
            ':qty' => $_POST['qty'],
            ':diagnosis' => $_POST['diagnosis'],
            ':remarks' => $_POST['remarks'],
            ':c_id' => $c_id,
        ]);

        // Redirect to the balances section after successful update
        header('Location: clinic_admin.php#balances');
        exit;
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
        exit;
    }
}
?>
