<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if the form is submitted
$userId = $_GET['id']; // Or fetch from a form, depending on your method
$username = $_POST['username']; // Example post data
$password = $_POST['password'];
$name = $_POST['name'];
$account_type = $_POST['account_type'];

try {
    // Prepare your SQL query
    $stmt = $conn->prepare("UPDATE users SET username = :username, password = :password, name = :name, account_type = :account_type WHERE id = :id");

    // Bind parameters to the prepared statement
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':account_type', $account_type);
    $stmt->bindParam(':id', $userId);

    // Execute the query
    $stmt->execute();
    $message = "User updated successfully!";
    $status = "success";
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
    $status = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Modal Styles */
        .modal {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 100px;
            text-align: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 300px;
        }
        .modal-header {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .modal-body {
            margin-bottom: 20px;
        }
        .modal-footer {
            margin-top: 10px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn:disabled {
            background-color: #ccc;
        }
    </style>
</head>
<body>

<?php if (isset($message)) { ?>
    <script type="text/javascript">
        // Show modal if there's a message
        window.onload = function() {
            var modal = document.getElementById("myModal");
            var modalMessage = document.getElementById("modalMessage");
            var modalStatus = "<?php echo $status; ?>";

            modal.style.display = "block";
            modalMessage.innerHTML = "<?php echo $message; ?>";

            if (modalStatus == "success") {
                document.getElementById("goBackBtn").style.display = "inline-block";
            }
        }
    </script>
<?php } ?>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Status</h2>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
        </div>
        <div class="modal-footer">
            <button id="goBackBtn" class="btn" onclick="window.history.back();">Go Back</button>
            <button class="btn" onclick="window.location.href='super-admin.php';">Go to Dashboard</button>
        </div>
    </div>
</div>

</body>
</html>
