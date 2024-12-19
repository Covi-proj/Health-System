<?php
session_start();
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']); // Clear the message after displaying it
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
.error-message {
    color: #fff;
    background-color: #ff4d4d; /* Red background for error */
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    display: block;
}


</style>
<body>
    <div class="reset-container">
        <h1>Reset Your Password</h1>
        <p>Enter your name to confirm and reset your password.</p>

        
        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        
        <form action="reset_name.php" method="POST" id="reset-form">
        
            <input type="text" id="name" name="name" class="name" placeholder="Enter your name" required>

            
            <button type="submit" class="reset-btn" style = "font-weight: bold; font-size: 20px;" >Next</button>
        </form>
        
        <p class="footer-text" style = "margin-top: 20px;">Remembered your password? <a href="login.php">Login</a></p>
    </div>

    <script src="scripts.js"></script>

    <script> 

            



    </script>
</body>
</html>

