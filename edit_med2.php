
<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if the 'med_id' is passed in the URL (GET request)
if (isset($_GET['med_id'])) {
    $medId = $_GET['med_id']; // Get the medicine ID from the URL
} else {
    echo "No medicine ID provided!";
    exit;
}

// Initialize variables for form data, in case they aren't set yet
$med_name = '';
$quantity = '';
$date_receive = '';

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $med_name = $_POST['Med_name']; // Example post data from the form
    $quantity = $_POST['quantity'];
    $date_receive = $_POST['date_receive'];
    $receiver = $_POST['receiver'];

    try {
        // Prepare your SQL query to update the medicine record
        $stmt = $conn->prepare("UPDATE medicines SET Med_name = :med_name, quantity = :quantity, date_receive = :date_receive, receiver = :receiver  WHERE med_id = :med_id");

        // Bind parameters to the prepared statement
        $stmt->bindParam(':med_name', $med_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':date_receive', $date_receive);
        $stmt->bindParam(':receiver', $receiver);
        $stmt->bindParam(':med_id', $medId);

        // Execute the query
        $stmt->execute();
        $message = "Medicine updated successfully!";
        $status = "success";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
} else {
    // If it's not a POST request, fetch the existing data for the medicine
    try {
        $stmt = $conn->prepare("SELECT * FROM medicines WHERE med_id = :med_id");
        $stmt->bindParam(':med_id', $medId);
        $stmt->execute();
        $medicine = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($medicine) {
            $med_name = $medicine['Med_name'];
            $quantity = $medicine['quantity'];
            $date_receive = $medicine['date_receive'];
            $date_receive = $medicine['receiver'];
        } else {
            $message = "No medicine found with this ID.";
            $status = "error";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $status = "error";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.jfif" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Edit Medicine</title>
</head>
<style>
    .btn-cancel {
    display: inline-block;
    text-decoration: none;
    color: #fff;
    background-color: #ff4d4d; /* Soft red */
    padding: 10px 20px;
    border-radius: 5px; /* Rounded corners for a modern look */
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-cancel:hover {
    background-color: #e04343; /* Slightly darker red on hover */
    transform: translateY(-2px); /* Lift effect */
}

.btn-cancel:active {
    background-color: #cc3b3b; /* Even darker red on click */
    transform: translateY(0); /* Neutralize lift effect */
}

.btn-cancel span {
    vertical-align: middle; /* Ensures text is centered */
}

</style>
<body>
    <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
        Medicine Inventory
    </nav>

    <div class="container">
        <div class="text-center mb-4">
            <h3>Edit Medicine</h3>
            <p class="text-muted">Update the information for the selected medicine</p>
        </div>

        <div class="container d-flex justify-content-center">
            <?php if (isset($medicine)): ?>
                <form action="bago2.php" method="post" style="width:50vw; min-width:300px;">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Medicine Name:</label>
                            <input type="text" class="form-control" name="Med_name" value="<?= htmlspecialchars($medicine['Med_name']) ?>" required>
                        </div>

                        <div class="col">
                            <label class="form-label">Quantity:</label>
                            <input type="number" class="form-control" name="quantity" value="<?= htmlspecialchars($medicine['quantity']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="appointmentDate">Date Received:</label>
                        <input type="date" class="form-control" id="appointmentDate" name="date_receive" value="<?= htmlspecialchars($medicine['date_receive']) ?>" required>

                        <label class="form-label">Receiver:</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($medicine['receiver']) ?>"  name="receiver" required>

                    </div>

                    <input type="hidden" name="med_id" value="<?= htmlspecialchars($medicine['med_id']) ?>">

                    <div>
                        <button type="submit" class="btn btn-success">Update</button>
                        <a class="btn-cancel" href="super-admin.php#payment">Cancel</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
