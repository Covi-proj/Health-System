<?php
include('edit_delete_dbconn.php');

$id = $_POST['id'] ?? null;
if (!$id) {
    echo "No ID provided!";
    exit;
}

// Fetch the data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo "No users found with this ID.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare(
            "UPDATE users SET 
                username = :username, 
                password = :password, 
                name = :name, 
                account_type = :account_type 
                
            WHERE id = :id"
        );

        $stmt->execute([
            ':username' => $_POST['username'],
            ':password' => $_POST['password'],
            ':name' => $_POST['name'],
            ':account_type' => $_POST['account_type'],
           
           
            ':id' => $id,
        ]);

        echo "<p style='color: green;'>User updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }

    header('location: super-admin.php');
    exit;
}
?>
