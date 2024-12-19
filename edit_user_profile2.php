<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if the 'id' is passed in the URL (GET request)
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the user ID from the URL
} else {
    echo "No user ID provided!";
    exit;
}

// Initialize variables for form data
$name = '';
$username = '';
$password = '';

// Handle POST request for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; // Get posted data
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL query to update user record
        $stmt = $conn->prepare("UPDATE users SET name = :name, username = :username, password = :password WHERE id = :id");

        // Bind parameters to the prepared statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $id);

        // Execute the query
        $stmt->execute();

        // Redirect or show a success message
        $message = "User updated successfully!";
        $status = "success";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // Handle GET request to fetch existing user data
    try {
        // Query the user table
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $name = $user['name'];
            $username = $user['username'];
            $password = $user['password'];
        } else {
            echo "No user found with this ID.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.jfif" type="image/png">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>

<style>
/* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.edit-profile-container {
    background: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    box-sizing: border-box;
    text-align: center;
}

/* Heading */
.edit-profile-container h2 {
    margin-bottom: 20px;
    color: #333;
}

/* Form Styles */
form label {
    display: block;
    text-align: left;
    margin: 10px 0 5px;
    font-size: 14px;
    color: #555;
}

form input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 14px;
}

form input:focus {
    border-color: #007bff;
    outline: none;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.save-btn, .cancel-btn {
    padding: 10px 20px;
    font-size: 14px;
    text-decoration: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-btn {
    background-color: #007bff;
    color: white;
    border: none;
}

.save-btn:hover {
    background-color: #0056b3;
}

.cancel-btn {
    background-color: #ccc;
    color: black;
    text-align: center;
}

.cancel-btn:hover {
    background-color: #999;
}
</style>

<body>
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <form action="update_profile2.php" method="POST">
            <!-- Full Name -->
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" 
                   value="<?= htmlspecialchars($name) ?>" required>
            
            <!-- Username -->
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" 
                   value="<?= htmlspecialchars($username) ?>" required>
            
            <!-- Password -->
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your new password" 
                   value="<?= htmlspecialchars($password) ?>" required>

            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            
            <!-- Submit and Cancel Buttons -->
            <div class="action-buttons">
                <button type="submit" class="save-btn">Save Changes</button>
                <a class="cancel-btn" href="super-admin.php#profile">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
