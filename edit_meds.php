<?php

include('edit_delete_dbconn.php');

// Ensure required parameters are set
if (!isset($_GET['emp_id'], $_GET['med_id'], $_GET['guest_name'])) {
    echo '<div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 5px; border: 1px solid #ffeeba; font-family: Arial, sans-serif; text-align: center; font-size: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <strong>Warning:</strong> No Employee ID, Medicine ID, or Guest Name provided.
</div>';
    exit;
}

$emp_id = htmlspecialchars($_GET['emp_id']);
$med_id = htmlspecialchars($_GET['med_id']);
$guest_name = htmlspecialchars($_GET['guest_name']);

// Initialize variables
$emp_no = $name = $age = $bday = $gender = $division = $company = '';
$date = $reason = $medicine = $supply = $quantity = $nod = $note = '';

try {
    // Determine if guest or employee
    if (!empty($guest_name)) {
        $emp_no = 'Guest';
        $name = $guest_name;
    } else {
        // Fetch employee data
        $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
        $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch_emp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fetch_emp) {
            throw new Exception("No Employee found with this ID.");
        }

        // Assign employee details
        $emp_no = $fetch_emp['emp_no'] ?? 'N/A';
        $name = $fetch_emp['name'] ?? 'N/A';
        $age = $fetch_emp['age'] ?? 'N/A';
        $bday = $fetch_emp['bday'] ?? 'N/A';
        $gender = $fetch_emp['gender'] ?? 'N/A';
        $division = $fetch_emp['division'] ?? 'N/A';
        $company = $fetch_emp['company'] ?? 'N/A';
    }

    // Fetch medicine data
    if (!empty($guest_name)) {
        $stmt = $conn->prepare("SELECT * FROM tbl_medicine WHERE med_id = :med_id AND guest_name = :guest_name ORDER BY med_id DESC LIMIT 1");
        $stmt->bindParam(':med_id', $med_id, PDO::PARAM_INT);
        $stmt->bindParam(':guest_name', $guest_name, PDO::PARAM_STR);
    } else {
        $stmt = $conn->prepare("SELECT * FROM tbl_medicine WHERE med_id = :med_id AND emp_id = :emp_id ORDER BY med_id DESC LIMIT 1");
        $stmt->bindParam(':med_id', $med_id, PDO::PARAM_INT);
        $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    $fetch_med = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fetch_med) {
        $date = $fetch_med['date'] ?? '';
        $reason = $fetch_med['reason'] ?? '';
        $medicine = $fetch_med['medicine'] ?? '';
        $supply = $fetch_med['supply'] ?? '';
        $quantity = $fetch_med['quantity'] ?? '';
        $nod = $fetch_med['nod'] ?? '';
        $note = $fetch_med['note'] ?? '';
    }

} catch (PDOException $e) {
    echo "<div style='color: red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
} catch (Exception $e) {
    echo "<div style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Form | Health-e</title>
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

        .logo {

            font-weight: bold;
            font-size: 20px;

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
            <img src="unnamed.png" alt="Logo" style="height: 40px;">
            Health-e
        </div>

    </nav>


    <!-- Edit Medicine -->
    <div class="form-container">
        <h2>Edit Medicine</h2>
        <form action="update_med_rec.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1 -->
            <div class="form-row">
                <input type="hidden" name="emp_id" value="<?= htmlspecialchars($emp_id) ?>">
                <input type="hidden" name="med_id" value="<?= htmlspecialchars($med_id) ?>">
                <div class="form-group">
                    <label for="name">Employee No. :</label>
                    <input type="text" id="emp" name="emp_no" value="<?= htmlspecialchars($emp_no) ?>" required>
                </div>

                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" id="guest_name" name="guest_name" value="<?= htmlspecialchars($name ?: $guest_name) ?>" <?= empty($guest_name) ? 'readonly' : '' ?>>
                </div>

                <div class="form-group">
                    <label for="name">Age :</label>
                    <input type="text" id="age" name="age" value="<?= htmlspecialchars($age ?: 'N/A') ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Birthday :</label>
                    <input type="text" id="bday" name="bday" value="<?= htmlspecialchars($bday ?: 'N/A') ?>" readonly>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="temp">Gender :</label>
                    <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($gender ?: 'N/A') ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Section/Dept :</label>
                    <input type="text" id="division" name="division" value="<?= htmlspecialchars($division ?: 'N/A') ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Company :</label>
                    <input type="text" id="company" name="company" value="<?= htmlspecialchars($company ?: 'N/A') ?>" readonly>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Date :</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
                </div>

                <div class="form-group">
                    <label for="reason">Reason :</label>
                    <input type="text" id="reason" name="reason" placeholder="Reason" value="<?= htmlspecialchars($reason) ?>" required>
                </div>

                <div class="form-group">
                    <label for="medicine">Medicine :</label>
                    <input type="text" id="medicine" name="medicine" placeholder="Medicine" value="<?= htmlspecialchars($medicine) ?>" required>
                </div>

                <div class="form-group">
                    <label for="supply">Supply :</label>
                    <input type="text" id="supply" name="supply" placeholder="Supply" value="<?= htmlspecialchars($supply) ?>" required>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="quantity">Quantity :</label>
                    <input type="number" id="quantity" name="quantity" placeholder="e.g. 7 pieces" value="<?= htmlspecialchars($quantity) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nod">Nurse on Duty :</label>
                    <input type="text" id="nod" name="nod" placeholder="e.g. Name of Nurse on Duty" value="<?= htmlspecialchars($nod) ?>" required>
                </div>

                <div class="form-group">
                    <label for="note">Note :</label>
                    <input type="text" id="note" name="note" placeholder="Note" value="<?= htmlspecialchars($note) ?>" required>
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Submit</button>
                <button type="button" class="btn-cancel"
                    onclick="window.location.href='clinic_admin.php#form_section';">Cancel</button>
            </div>
        </form>
    </div>
</body>

</html>