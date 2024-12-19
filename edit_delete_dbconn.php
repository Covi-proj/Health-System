<?php
// Include your database connection file or directly create the connection
$servername = "localhost";  // Replace with your server details
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "e_system";  // Replace with your database name

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, output an error message
    echo "Connection failed: " . $e->getMessage();
}
?>
