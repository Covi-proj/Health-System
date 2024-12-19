<?php
session_start();

$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "e_system";    

// PDO connection (using try-catch for better error handling)
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Get the posted data
$user = $_POST['username'];
$pass = $_POST['password'];

// Sanitize input to prevent SQL injection
$user = $pdo->quote($user);
$pass = $pdo->quote($pass);

// Modify the query to fetch the user's name as well
$sql = "SELECT id, username, name, account_type FROM users WHERE username=$user AND password=$pass";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt->rowCount() > 0) {
        // User found
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['name'];  // Store the user's name in the session
        

        if($row['account_type'] === 'Admin'){

            $redirectUrl = 'super-admin.php';
        } else {

            $redirectUrl = 'clinic_admin.php';

        }
        
        // Return success response
        echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
    } else {
        // User not found
        echo json_encode([
            'status' => 'error',
            'message' => '<div class="alert alert-danger" style="font-weight: bold; color: red; text-align: center;">Invalid username or password</div>'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '<div class="alert alert-danger">Database query failed: ' . $e->getMessage() . '</div>']);
}

// Close the PDO connection
$pdo = null;
?>
