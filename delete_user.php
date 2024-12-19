<?php
// Database connection
include('edit_delete_dbconn.php');

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, output an error message
    echo "<div class='error-message'>Connection failed: " . $e->getMessage() . "</div>";
    exit();  // Stop further script execution
}

// Initialize variables for the modal message
$message = "";
$status = "";

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        // Prepare the DELETE statement
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");

        // Bind the user ID to the prepared statement
        $stmt->bindParam(':id', $userId);

        // Execute the DELETE query
        $stmt->execute();

        // Check if the query affected any rows (i.e., the user was deleted)
        if ($stmt->rowCount() > 0) {
            $message = "User deleted successfully!";
            $status = "success";
        } else {
            $message = "No user found with this ID!";
            $status = "warning";
        }
    } catch (PDOException $e) {
        // Catch any exceptions and display the error
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // If no 'id' is passed, show an error
    $message = "User ID is missing!";
    $status = "warning";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Modal Styles */
        .modal {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: none; 
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
        /* Message Styles */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .error-message {
            background-color: #f44336;
            color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .warning-message {
            background-color: #ff9800;
            color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>

<?php if ($message != "") { ?>
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
            <h2>Action Status</h2>
        </div>
        <div class="modal-body">
            <p id="modalMessage"></p>
        </div>
        <div class="modal-footer">
            <button id="goBackBtn" class="btn" onclick="window.history.back();">Go Back</button>
            <button class="btn" onclick="window.location.href='dashboard.php';">Go to Dashboard</button>
        </div>
    </div>
</div>

</body>
</html>
