<?php

session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message after displaying it

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "e_system";    

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$error_message = '';
$confirmed_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && !empty(trim($_POST['name']))) {
        $name = trim($_POST['name']);

        // Prepare SQL query to check if the name exists in the database
        $query = "SELECT id FROM users WHERE Name = :name";
        
        // Prepare the statement
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userId = $user['id'];
            $confirmed_message = "Verified: " . htmlspecialchars($name); 
            // Redirect to password reset or next step page
            
        } else {
            $_SESSION['error_message'] = "User not found.";
            // Redirect back to the form to display the message
            header("Location: resetform.php");
            exit();
        }
    }
}

// Close the database connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="icon.jfif" type="image/png">
</head>
<style>
    /* Reset basic styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Container for the reset form */
.reset-container {
    background-color: #fff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    text-align: center;
}

/* Heading and text styles */
h1 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #333;
}

p {
    font-size: 14px;
    color: #555;
    margin-bottom: 25px;
}

/* Label styles */
label {
    font-size: 14px;
    color: #333;
    margin-bottom: 5px;
    display: block;
    text-align: left;
}

/* Input field styles */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input:focus {
    border-color: #007bff;
    outline: none;
}

/* Button styles */
.reset-btn {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}

.reset-btn:hover {
    background-color: #0056b3;
}

/* Footer text for login link */
.footer-text {
    font-size: 14px;
    color: #555;
}

.footer-text a {
    color: #007bff;
    text-decoration: none;
}

.footer-text a:hover {
    text-decoration: underline;
}
.name {
      font-size: 20px;
      color: #4CAF50; /* Green color for confirmation */
      margin-top: 10px;
      font-weight: bold;
}
.error-message {
    font-size: 16px;
    color: red;
    margin-top: 20px;

}
</style>
<body>
    <div class="reset-container">
        <h1>Enter your new Password</h1>
    

        <!-- Displaying the error message if set -->
        
        <form action="change_password.php" method="POST" id="reset-form" onsubmit="return validatePassword()">
    <!-- Display the confirmed name for verification -->
    

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <!-- Hidden input to pass the confirmed name to PHP -->
    <input type="hidden" name="confirmedMessage" value="<?php echo htmlspecialchars($confirmedMessage); ?>">

    <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userId); ?>">

    <label for="newPassword" style="font-weight: bold;">New Password</label>
    <input type="password" id="newPassword" name="password" required>

    <label for="confirmPassword" style="font-weight: bold;">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required>

    <button type="submit" class="reset-btn" style="font-weight: bold; font-size: 20px;">Change Password</button>
</form>



    <p id="error-message" style="color: red; font-weight: bold; display: none; margin-top: 20px; font-size: 15px;">Passwords do not match. Please try again.</p>

        
        <p class="footer-text" style="margin-top: 20px;">Remembered your password? <a href="login.php">Login</a></p>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
<script>

function validatePassword() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                document.getElementById('error-message').style.display = 'block';
                return false; // Prevent form submission
            } else {
                document.getElementById('error-message').style.display = 'none';
                return true; // Allow form submission
            }
        }


</script>