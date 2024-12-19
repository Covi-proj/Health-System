<?php
// Database connection parameters
$host = "localhost";
$dbname = "e_system";
$username = "root";
$password = "";

// Create a PDO instance for database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Get the user ID from the request
$emp = $_GET['emp_no'] ?? ''; // Fallback if emp_id is not provided

if (!$emp) {
    echo json_encode(['error' => 'Employee No. is required']);
    exit;
}

// Prepare the SQL query
$query = "SELECT name, division, company FROM employees WHERE emp_no = :emp_no"; // Assuming you want to filter by emp_id
$stmt = $conn->prepare($query);

// Bind the employee ID to the parameter
$stmt->bindParam(':emp_no', $emp, PDO::PARAM_STR);

// Execute the query
$stmt->execute();

// Check if any result is returned
if ($stmt->rowCount() > 0) {
    // Fetch the user data
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return the user data as JSON
    header('Content-Type: application/json');
    echo json_encode($userData);
} else {
    // No user found, handle accordingly
    echo json_encode(['error' => 'User not found']);
}

// Close the database connection
$conn = null;
?>
