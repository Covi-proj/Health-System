<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if the ID is provided in the POST data (for the update operation)
if (isset($_POST['id'])) {
    $id = $_POST['id']; // Get the user ID from the POST data
} else {
    echo "No user ID provided!";
    exit;
}

// Initialize variables for form data, in case they aren't set yet
$name = '';
$username = '';
$password = '';

// Handle the POST request when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the ID is available in POST data
    $id = $_POST['id']; // Get the ID from the POST data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare your SQL query to update the user record
        $stmt = $conn->prepare("UPDATE users SET name = :name, username = :username, password = :password WHERE id = :id");

        // Bind parameters to the prepared statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);

        // Execute the query
        $stmt->execute();
        $message = "User updated successfully!";
        $status = "success";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // If it's not a POST request, fetch the existing data for the user (GET request)
    try {
        $stmt = $conn->prepare("SELECT name, username, password FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Correctly bind the 'id'
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $name = $user['name'];
            $username = $user['username'];
            $password = $user['password'];
        } else {
            $message = "No user found with this ID.";
            $status = "error";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
}

// Redirect back to the profile page
header('Location: super-admin.php#profile');
exit;
?>
