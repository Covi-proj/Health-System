<?php
include('edit_delete_dbconn.php');

// Check if 'emp_id' is passed in the URL
if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
    $f_id = $_GET['emp_id'];    // Get the f_id from the query string

    // Initialize variables to hold the employee data
    $emp_no = $name = $age = $bday = $gender = $division = $company = '';
    $f_date = $time = $from_ = $to_ = $nfa = $reason = $with_without_cert = $remarks = $nod = $note = $emp_id2 = '';

    try {
        // Fetch the employee data from the database
        $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
        $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch_emp = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fetch_emp) {
            // Populate form fields with fetched employee data
            $emp_no = $fetch_emp['emp_no'];
            $name = $fetch_emp['name'];
            $age = $fetch_emp['age'];
            $bday = $fetch_emp['bday'];
            $gender = $fetch_emp['gender'];
            $division = $fetch_emp['division'];
            $company = $fetch_emp['company'];
        } else {
            echo "No Employee found with this ID.";
            exit;
        }

        // Fetch fit-to-work data for the employee
        $stmt = $conn->prepare("SELECT * FROM tbl_fittowork WHERE f_id = :f_id ORDER BY f_id DESC LIMIT 1");
        $stmt->bindParam(':f_id', $f_id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch_fit = $stmt->fetch(PDO::FETCH_ASSOC);  // Changed to FETCH_ASSOC to ensure array

        if ($fetch_fit) {
            // Populate form fields with fetched fit-to-work data
            $f_date = $fetch_fit['date'];
            $time = $fetch_fit['time'];
            $from_ = $fetch_fit['from_'];
            $to_ = $fetch_fit['to_'];
            $nfa = $fetch_fit['nfa'];
            $reason = $fetch_fit['reason'];
            $with_without_cert = $fetch_fit['with_without_cert'];
            $remarks = $fetch_fit['remarks'];
            $nod = $fetch_fit['nod'];
            $note = $fetch_fit['note'];
            $emp_id2 = $fetch_fit['emp_id'];
        } else {
            // Handle case where no fit-to-work data is found (if needed)
            $nfa = 'Not Available';  // Example for placeholder
        }

    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
        exit;
    }
} else {
    echo '<div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 5px; border: 1px solid #ffeeba; font-family: Arial, sans-serif; text-align: center; font-size: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <strong>Warning:</strong> No Employee ID provided.
</div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eligibility Form | Health-e</title>
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

    <div class="form-container">
        <h2>Edit Fit to Work</h2>
        <form action="update_fit.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <!-- Employee Information -->
                <div class="form-group">
                    <input type="hidden" name="emp_id" value="<?= htmlspecialchars($emp_id) ?>">
                    <label for="emp_no">Employee No. :</label>
                    <input type="text" id="emp_no" name="emp_no" value="<?= htmlspecialchars($emp_no) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="age">Age :</label>
                    <input type="text" id="age" name="age" value="<?= htmlspecialchars($age) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="bday">Birthday :</label>
                    <input type="text" id="bday" name="bday" value="<?= htmlspecialchars($bday) ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender :</label>
                    <input type="text" id="gender" name="gender" value="<?= htmlspecialchars($gender) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="division">Section/Dept :</label>
                    <input type="text" id="division" name="division" value="<?= htmlspecialchars($division) ?>"
                        readonly>
                </div>

                <div class="form-group">
                    <label for="company">Company :</label>
                    <input type="text" id="company" name="company" value="<?= htmlspecialchars($company) ?>" readonly>
                </div>
            </div>

            <!-- Fit to Work Data -->
            <div class="form-row">
            <input type="hidden" name="emp_id2" value="<?= htmlspecialchars($emp_id2) ?>">

                <div class="form-group">
                    <label for="f_date">Date:</label>
                    <input type="date" id="f_date" name="f_date" value="<?= htmlspecialchars($f_date) ?>" required>
                </div>

                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?= htmlspecialchars($time) ?>" required>
                </div>

                <div class="form-group">
                    <label for="from_">From:</label>
                    <input type="date" id="from_" name="from_" value="<?= htmlspecialchars($from_) ?>" required
                        oninput="calculateDays()">
                </div>

                <div class="form-group">
                    <label for="to_">To:</label>
                    <input type="date" id="to_" name="to_" value="<?= htmlspecialchars($to_) ?>" required
                        oninput="calculateDays()">
                </div>

                <div class="form-group">
                    <label for="nfa">NFA:</label>
                    <input type="text" id="nfa" name="nfa" value="<?= htmlspecialchars($nfa) ?>" readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="reason">Reason:</label>
                    <textarea id="reason" name="reason" rows="3" placeholder="Reason"
                        required><?= htmlspecialchars($reason) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cert">With/Without Certificate:</label>
                    <?php if (!empty($with_without_cert)): ?>
                        <div>Current Certificate: <?= htmlspecialchars($with_without_cert) ?></div>
                    <?php endif; ?>
                    <input type="file" id="cert" name="with_without_cert" <?= empty($with_without_cert) ? 'required' : '' ?>>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <textarea id="remarks" name="remarks" placeholder="Remarks" rows="3"
                        required><?= htmlspecialchars($remarks) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="note">Note:</label>
                    <textarea id="note" name="note" placeholder="Note"
                        rows="3"><?= htmlspecialchars($note) ?></textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Save</button>
                <a href="clinic_admin.php#form_section" class="btn-cancel" style = "text-decoration: none;">Cancel</a>
            </div>
        </form>

        <script>
            function calculateDays() {
                const fromDate = document.getElementById('from_').value;
                const toDate = document.getElementById('to_').value;

                if (fromDate && toDate) {
                    const from = new Date(fromDate);
                    const to = new Date(toDate);

                    if (to >= from) {
                        const diffTime = Math.abs(to - from);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Include the start day
                        document.getElementById('nfa').value = `${diffDays} days`;
                    } else {
                        document.getElementById('nfa').value = 'Invalid date range';
                    }
                }
            }
        </script>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>