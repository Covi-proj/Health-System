<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if 'c_id' is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "No consultation ID provided!";
    exit;
}

// Initialize form data
$username = $password = $name = $account_type = '';

try {
    // Fetch the consultation data
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fetch_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fetch_user) {
        // Populate form fields
        $username = $fetch_user['username'];
        $password = $fetch_user['password'];
        $name = $fetch_user['name'];
        $account_type = $fetch_user['account_type'];
     
    } else {
        echo "No Eligibility found with this ID.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $account_type = $_POST['account_type'];

    try {
        // Update query
        $stmt = $conn->prepare(
            "UPDATE users
            SET 
                username = :username,
                password = :password,
                name = :name,
                account_type = :account_type
            WHERE id = :id"
        );

        // Bind parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':account_type', $account_type);
        
        // Execute query
        $stmt->execute();
        
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="icon.jfif" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

.form-container {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 400px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #007bff;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.form-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.submit-btn {
    background-color: #007bff;
    color: white;
}

.submit-btn:hover {
    background-color: #0056b3;
}

.cancel-btn {
    background-color: #6c757d;
    color: white;
}

.cancel-btn:hover {
    background-color: #5a6268;
}
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    background-color: white;
}

.form-group select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}
a.cancel-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

a.cancel-btn:hover {
    background-color: #5a6268;
    transform: scale(1.03);
}

a.cancel-btn:active {
    background-color: #4e555b;
    box-shadow: none;
    transform: scale(0.97);
}


</style>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <form action = "post_users.php" method = "POST" id="createUserForm">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="name" placeholder="Enter full name" value="<?= htmlspecialchars($name) ?>" required>
            </div>
      
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="form-group">
                <label for="userType">User Type</label>
                <select id="userType" name="account_type">
                    <option value="" disabled selected><?= htmlspecialchars($account_type) ?></option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" value="<?= htmlspecialchars($password) ?>"required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn submit-btn">Submit</button>
                <a class="cancel-btn" href="super-admin.php">Cancel</a>
               
            </div>
        </form>
    </div>
</body>
</html>
