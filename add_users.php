<?php
session_start();
include "edit_delete_dbconn.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $name = $_POST['name'] ?? null;
    $account_type = $_POST['account_type'] ?? null;

    if ($username && $password && $name && $account_type) {
        try {
            // Debugging: Check input values
            var_dump($username, $password, $name, $account_type);

            // Insert data without password hashing
            $sql = "INSERT INTO users (username, password, name, account_type) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$username, $password, $name, $account_type]);

            echo 'User added successfully'; // Debug success message

            // Redirect to the desired section
            echo '<script>window.location.href="super-admin.php";</script>';
            exit();

        } catch (PDOException $e) {
            echo 'Database error: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        echo 'Missing data fields';
    }
}
?>
