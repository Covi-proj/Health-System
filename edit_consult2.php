<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if 'c_id' is passed in the URL
if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];
} else {
    echo "No consultation ID provided!";
    exit;
}

// Initialize form data
$pnt_name = $division = $company = $c_date = $bp = $temp = $HR = $RR = $O2_sat = $medicine = $qty = $remarks = '';

try {
    // Fetch the consultation data
    $stmt = $conn->prepare("SELECT * FROM consultation WHERE c_id = :c_id");
    $stmt->bindParam(':c_id', $c_id, PDO::PARAM_INT);
    $stmt->execute();
    $consultation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($consultation) {
        // Populate form fields
        $pnt_name = $consultation['pnt_name'];
        $division = $consultation['division'];
        $company = $consultation['company'];
        $c_date = $consultation['c_date'];
        $bp = $consultation['bp'];
        $temp = $consultation['temp'];
        $HR = $consultation['HR'];
        $RR = $consultation['RR'];
        $O2_sat = $consultation['O2_sat'];
        $medicine = $consultation['medicine'];
        $qty = $consultation['qty'];
        $remarks = $consultation['remarks'];
    } else {
        echo "No consultation found with this ID.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pnt_name = $_POST['pnt_name'];
    $division = $_POST['division'];
    $company = $_POST['company'];
    $c_date = $_POST['c_date'];
    $bp = $_POST['bp'];
    $temp = $_POST['temp'];
    $HR = $_POST['HR'];
    $RR = $_POST['RR'];
    $O2_sat = $_POST['O2_sat'];
    $medicine = $_POST['medicine'];
    $qty = $_POST['qty'];
    $remarks = $_POST['remarks'];

    try {
        // Update query
        $stmt = $conn->prepare(
            "UPDATE consultation 
            SET 
                pnt_name = :pnt_name,
                division = :division,
                company = :company,
                c_date = :c_date,
                bp = :bp,
                temp = :temp,
                HR = :HR,
                RR = :RR,
                O2_sat = :O2_sat,
                medicine = :medicine,
                qty = :qty,
                remarks = :remarks
            WHERE c_id = :c_id"
        );

        // Bind parameters
        $stmt->bindParam(':pnt_name', $pnt_name);
        $stmt->bindParam(':division', $division);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':c_date', $c_date);
        $stmt->bindParam(':bp', $bp);
        $stmt->bindParam(':temp', $temp);
        $stmt->bindParam(':HR', $HR);
        $stmt->bindParam(':RR', $RR);
        $stmt->bindParam(':O2_sat', $O2_sat);
        $stmt->bindParam(':medicine', $medicine);
        $stmt->bindParam(':qty', $qty);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':c_id', $c_id);

        // Execute query
        $stmt->execute();
        echo "<p style='color: green;'>Consultation updated successfully!</p>";
    } catch (PDOException $e) {
        echo "Update Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Consultation | Health-e</title>
    <link rel="icon" href="icon.jfif" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            padding-top: 70px;
        }

        .navbar {
            background-color: #ffff;
            color: black;
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .navbar .logo img {
            height: 40px;
            margin-right: 10px;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            margin: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-submit {
            background-color: #8B0000;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-submit:hover {
            background-color: #660000;
        }

        .btn-cancel {
            background-color: #ccc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #aaa;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <img src="unnamed.png" alt="Logo">
            Health-e
        </div>
    </nav>

    <!-- Consultation Form -->
    <div class="form-container">
        <h2>Consultation Form</h2>
        <form action="post2.php" method="POST">
        <input type="hidden" name="c_id" value="<?= htmlspecialchars($c_id) ?>">
            <!-- Row 1 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Patient Name</label>
                    <input type="text" id="name" name="pnt_name" placeholder="Full Name"  value="<?= htmlspecialchars($pnt_name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="division">Division</label>
                    <input type="text" id="division" name="division" placeholder="Division" value="<?= htmlspecialchars($division) ?>" required>
                </div>
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company" value="<?= htmlspecialchars($company) ?>" required>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="c_date">Consultation Date</label>
                    <input type="date" id="c_date" name="c_date" value="<?= htmlspecialchars($c_date) ?>" required>
                </div>
                <div class="form-group">
                    <label for="bp">Blood Pressure</label>
                    <input type="text" id="bp" name="bp" placeholder="BP" value="<?= htmlspecialchars($bp) ?>" required>
                </div>
                <div class="form-group">
                    <label for="temp">Temperature</label>
                    <input type="text" id="temp" name="temp" placeholder="Temperature" value="<?= htmlspecialchars($temp) ?>" required>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="HR">Heart Rate (HR)</label>
                    <input type="text" id="HR" name="HR" placeholder="Heart Rate"  value="<?= htmlspecialchars($HR) ?>" required>
                </div>
                <div class="form-group">
                    <label for="RR">Respiratory Rate (RR)</label>
                    <input type="text" id="RR" name="RR" placeholder="Respiratory Rate" value="<?= htmlspecialchars($RR) ?>" required>
                </div>
                <div class="form-group">
                    <label for="O2_sat">Oxygen Saturation (O2 Sat)</label>
                    <input type="text" id="O2_sat" name="O2_sat" placeholder="O2 Sat" value="<?= htmlspecialchars($O2_sat) ?>"  required>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="medicine">Medicine</label>
                    <input type="text" id="medicine" name="medicine" placeholder="Medicine" value="<?= htmlspecialchars($medicine) ?>" required>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" id="qty" name="qty" placeholder="Quantity" value="<?= htmlspecialchars($qty) ?>" required>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea id="remarks" name="remarks" rows="4" placeholder="Remarks or concerns" required><?= htmlspecialchars($remarks) ?></textarea>
                
            </div>

           
            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Save</button>
                <a class="btn-cancel" href="super-admin.php#balances">Cancel</a>
              
            </div>
        </form>
    </div>
</body>
</html>
