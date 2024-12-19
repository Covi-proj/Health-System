<?php
// Start the session at the beginning of the script
session_start();

// Database connection
$host = 'localhost';  
$dbname = 'e_system'; 
$username = 'root';  
$password = '';  

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $userName = trim($_POST['username']);
    $userPassword = trim($_POST['password']);
    $fullName = trim($_POST['name']);
    $accountType = $_POST['account_type'];

    // Simple validation
    if (!empty($userName) && !empty($userPassword) && !empty($fullName) && !empty($accountType)) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Check if the username already exists
            $checkQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->bindParam(':username', $userName);
            $checkStmt->execute();
            $userExists = $checkStmt->fetchColumn();

            if ($userExists) {
                $_SESSION['message'] = '<div class="message error">Error: Username already exists. Please choose another one.</div>';
            } else {
                // Prepare SQL query to insert user
                $query = "INSERT INTO users (username, password, name, account_type) 
                          VALUES (:username, :password, :full_name, :account_type)";

                // Prepare statement
                $stmt = $pdo->prepare($query);

                // Bind parameters
                $stmt->bindParam(':username', $userName);
                $stmt->bindParam(':password', $userPassword);
                $stmt->bindParam(':full_name', $fullName);
                $stmt->bindParam(':account_type', $accountType);

                // Execute the query and check for success
                if ($stmt->execute()) {
                    $_SESSION['message'] = '<div class="message success">User has been successfully added.</div>';
                } else {
                    $_SESSION['message'] = '<div class="message error">Error: Could not add user.</div>';
                }
            }
        } catch (PDOException $e) {
            $_SESSION['message'] = '<div class="message error">Connection failed: ' . $e->getMessage() . '</div>';
        }
    } else {
        $_SESSION['message'] = '<div class="message error">All fields are required.</div>';
    }
}

// Check if session message is set
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    // Clear the message after displaying it to prevent it from showing again
    unset($_SESSION['message']);
} else {
    $message = '';  // No message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Modal Background */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        /* Modal Content */
        .modal-content {
            background-color: #ffffff;
            margin: 10% auto;
            padding: 0;
            border-radius: 15px;
            width: 80%;
            max-width: 450px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slide-in 0.4s ease-out;
        }

        /* Modal Animation */
        @keyframes slide-in {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Modal Header */
        .modal-header {
            background-color: #8B0000;
            color: #ffffff;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }

        /* Message Box Styling */
        .message {
            padding: 20px;
            font-size: 16px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            
        }

        /* Close Button */
        .close {
            color: #ffffff;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover {
            color: #e0e0e0;
        }

        /* Back Button Styling */
        .back-btn {
            padding: 12px 18px;
            font-size: 16px;
            background-color: #28a745;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            margin: 20px auto;
            display: inline-block;
            transition: background-color 0.3s;
            justify-content: center;
            text-align: center;
            margin-left: 174px;
        }

        .back-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                Notification
                <span class="close">&times;</span>
            </div>

            <!-- Display the PHP message here -->
            <?php echo $message; ?>

            <!-- Back Button -->
            <a href="javascript:history.back()" class="back-btn">Go Back</a>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById('myModal');
        
        // Show the modal when the page loads (if a message exists)
        window.onload = function() {
            if (<?php echo isset($message) && $message != '' ? 'true' : 'false'; ?>) {
                modal.style.display = "block";
            }
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
