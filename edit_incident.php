<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>incident Report | Health-e</title>
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


    <?php
    include('edit_delete_dbconn.php');

    // Check if 'emp_id' and 'sh_id' are passed in the URL
    if (isset($_GET['emp_id']) && isset($_GET['ir_id'])) {
        $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
        $ir_id = $_GET['ir_id'];    // Get the sh_id from the query string
    
        // Initialize variables to hold the employee data
        $emp_no = $name = $age = $bday = $gender = $division = $company = '';
        $date = $time_i = $place_i = $nature_i = $part_b_a = $remarks = $status_ = $d_lost = $d_absence = $file = '';

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

            // Fetch fit-to-work data for the employee using sh_id
            $stmt = $conn->prepare("SELECT * FROM tbl_incident_report WHERE ir_id = :ir_id ORDER BY ir_id DESC LIMIT 1");
            $stmt->bindParam(':ir_id', $ir_id, PDO::PARAM_INT);
            $stmt->execute();
            $fetch_ir = $stmt->fetch(PDO::FETCH_ASSOC);  // Changed to FETCH_ASSOC to ensure array
    
            if ($fetch_ir) {
                // Populate form fields with fetched fit-to-work data
                $date = $fetch_ir['date'];
                $time_i = $fetch_ir['time_i'];
                $place_i = $fetch_ir['place_i'];
                $nature_i = $fetch_ir['nature_i'];
                $part_b_a = $fetch_ir['part_b_a'];
                $remarks = $fetch_ir['remarks'];
                $status_ = $fetch_ir['status_'];
                $d_lost = $fetch_ir['d_lost'];
                $d_absence = $fetch_ir['d_absence'];
                $file = $fetch_ir['file'];


            } else {
                // Handle case where no fit-to-work data is found
                $nfa = 'Not Available';  // Example for placeholder
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            exit;
        }
    } else {
        echo '<div style="padding: 20px; background-color: #fff3cd; color: #856404; border-radius: 5px; border: 1px solid #ffeeba; font-family: Arial, sans-serif; text-align: center; font-size: 16px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <strong>Warning:</strong> No Employee ID or Fit-to-Work ID provided.
    </div>';
        exit;
    }
    ?>
    <!-- Eligibility Form -->
    <div class="form-container">
        <h2>Incident Report</h2>
        <form action="insert_incident.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1 -->
            <!-- Row 1 -->
            <div class="form-row">
                <!-- Employees Form -->
                <div class="form-group">
                    <input type="hidden" name="f_id" value="<?= htmlspecialchars($emp_id) ?>">
                    <label for="name">Employee No. :</label>
                    <input type="text" id="emp" name="emp_no" value="<?= htmlspecialchars($emp_no) ?>" required>
                </div>

                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" id="age" name="name" value="<?= htmlspecialchars($name) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Age :</label>
                    <input type="text" id="age" name="age" value="<?= htmlspecialchars($age) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Birthday :</label>
                    <input type="text" id="bday" name="bday" value="<?= htmlspecialchars($bday) ?>" readonly>
                </div>



            </div>
            <!-- Row 2 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="temp">Gender :</label>
                    <input type="text" id="diagnosis" name="gender" value="<?= htmlspecialchars($gender) ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Section/Dept :</label>
                    <input type="text" id="diagnosis" name="division" value="<?= htmlspecialchars($division) ?>"
                        readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Company :</label>
                    <input type="text" id="company" name="company" value="<?= htmlspecialchars($company) ?>" readonly>
                </div>

                <!-- Employees form -->
            </div>

            <!-- Row 3 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="name">Date of Incident :</label>
                    <input type="date" id="date" name="date" required value="<?= htmlspecialchars($date) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Time of Incident :</label>
                    <input type="time" id="time" name="time_i" required value="<?= htmlspecialchars($time_i) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Place of Incident :</label>
                    <input type="text" id="from" name="place_i" placeholder="Place of Incident" required
                        value="<?= htmlspecialchars($place_i) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="O2_sat">Nature of Incident : </label>
                    <input type="text" id="to" name="nature_i" placeholder="Nature of Incident" required
                        value="<?= htmlspecialchars($nature_i) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Part of the Body Affected : </label>
                    <input type="text" id="to" name="part_b_a" placeholder="e.g., Leg" required
                        value="<?= htmlspecialchars($part_b_a) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Remarks : </label>
                    <input type="text" id="to" name="remarks" placeholder="Remarks" required
                        value="<?= htmlspecialchars($remarks) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="O2_sat">Status : </label>
                    <select name="status_" required>
                        <option value="N/A">--Status--</option>
                        <option value="Sent Home" <?= $status_ === "Sent Home" ? "selected" : "" ?>>Sent Home</option>
                        <option value="Sent Back to Work" <?= $status_ === "Sent Back to Work" ? "selected" : "" ?>>Sent
                            Back to Work</option>
                        <option value="Fit to Work" <?= $status_ === "Fit to Work" ? "selected" : "" ?>>Fit to Work
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Days Lost : </label>
                    <input type="number" id="to" name="d_lost" required value="<?= htmlspecialchars($d_lost) ?>">
                </div>

                <div class="form-group">
                    <label for="remarks">Date of Absence :</label>
                    <input type="date" id="date_absence" name="d_absence" required
                        value="<?= htmlspecialchars($d_absence) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="RR">Document :</label>
                    <input type="file" id="file" name="total_hrs" required <?= htmlspecialchars($file) ?>">
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