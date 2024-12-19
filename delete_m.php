<?php
// Database connection
include('edit_delete_dbconn.php');

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, output an error message
    echo "<div class='error-message'>Connection failed: " . $e->getMessage() . "</div>";
    exit();  // Stop further script execution
}

// Initialize variables for the modal message
$message = "";
$status = "";

if (isset($_GET['emp_id'])) {
    $med_id = $_GET['emp_id'];

    try {
        // Prepare the DELETE statement
        $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = :emp_id");

        // Bind the user ID to the prepared statement
        $stmt->bindParam(':emp_id', $med_id);

        // Execute the DELETE query
        $stmt->execute();

        // Check if the query affected any rows (i.e., the user was deleted)
        if ($stmt->rowCount() > 0) {
            $message = "Employee deleted successfully!";
            $status = "success";
        } else {
            $message = "No user found with this ID!";
            $status = "warning";
        }
    } catch (PDOException $e) {
        // Catch any exceptions and display the error
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // If no 'id' is passed, show an error
    $message = "User ID is missing!";
    $status = "warning";
}
header('location: view_masterlist.php');
?>