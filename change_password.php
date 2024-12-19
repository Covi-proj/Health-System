<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_system";

// Establish a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $userId = $_POST['userId'];
    $newPassword = $_POST['password'];  // Directly use the password without hashing

    // Prepare SQL query to update the password
    $query = "UPDATE users SET password = :password WHERE id = :id";
    
    if ($stmt = $pdo->prepare($query)) {
        // Bind the password and user ID parameters
        $stmt->bindParam(":password", $newPassword, PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Success message with design and link to login
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Update Success</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    background-color: #f0f4f8;
                    margin: 0;
                }
                .message-container {
                    text-align: center;
                    background-color: #ffffff;
                    border: 2px solid #28a745;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .message-container h1 {
                    color: #28a745;
                    font-size: 24px;
                    margin: 0 0 10px;
                }
                .message-container p {
                    font-size: 16px;
                    color: #555;
                    margin: 0 0 20px;
                }
                .message-container a {
                    color: #ffffff;
                    background-color: #28a745;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    font-size: 16px;
                }
                .message-container a:hover {
                    background-color: #218838;
                }
            </style>
        </head>
        <body>
            <div class="message-container">
                <h1>Password Updated Successfully!</h1>
                <p>Your password has been successfully updated. You can now log in with your new password.</p>
                <a href="login.php">Back to Login</a>
            </div>
        </body>
        </html>
        ';
    } else {
        echo "Failed to update password. Please try again.";
    }
}
?>
