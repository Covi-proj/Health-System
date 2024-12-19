<?php
// Database connection
include('edit_delete_dbconn.php');

if (isset($_POST['med_id'])) {
    $medId = $_POST['med_id']; // Get the medicine ID from the URL
} else {
    echo "No medicine ID provided!";
    exit;
}

// Initialize variables for form data, in case they aren't set yet
$med_name = '';
$quantity = '';
$date_receive = '';
$receiver = '';

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the med_id is available in POST data
    if (isset($_POST['med_id'])) {
        $medId = $_POST['med_id']; // Ensure we have the med_id in the POST data
    }
    $med_name = $_POST['Med_name'];
    $quantity = $_POST['quantity'];
    $date_receive = $_POST['date_receive'];
    $receiver = $_POST['receiver'];

    try {
        // Prepare your SQL query to update the medicine record
        $stmt = $conn->prepare("UPDATE medicines SET Med_name = :med_name, quantity = :quantity, date_receive = :date_receive, receiver = :receiver WHERE med_id = :med_id");

        // Bind parameters to the prepared statement
        $stmt->bindParam(':med_name', $med_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':date_receive', $date_receive);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':med_id', $medId);

        // Execute the query
        $stmt->execute();
        $message = "Medicine updated successfully!";
        $status = "success";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // If it's not a POST request, fetch the existing data for the medicine
    try {
        $stmt = $conn->prepare("SELECT * FROM medicines WHERE med_id = :med_id");
        $stmt->bindParam(':med_id', $medId);
        $stmt->execute();
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medicine) {
            $med_name = $medicine['Med_name'];
            $quantity = $medicine['quantity'];
            $date_receive = $medicine['date_receive'];
            $receiver = $medicine['receiver'];
        } else {
            $message = "No medicine found with this ID.";
            $status = "error";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
}
header('location: clinic_admin.php#payment');
?>