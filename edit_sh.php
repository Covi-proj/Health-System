<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Home | Health-e</title>
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
    if (isset($_GET['emp_id']) && isset($_GET['sh_id'])) {
        $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
        $sh_id = $_GET['sh_id'];    // Get the sh_id from the query string
    
        // Initialize variables to hold the employee data
        $emp_no = $name = $age = $bday = $gender = $division = $company = '';
        $assessment = $diagnosis = $remarks = $sore_throat = $body_pain = $headache = $fever = $cough_colds = $lbm = $loss_ts = '';

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
            $stmt = $conn->prepare("SELECT * FROM tbl_senthome WHERE sh_id = :sh_id ORDER BY sh_id DESC LIMIT 1");
            $stmt->bindParam(':sh_id', $sh_id, PDO::PARAM_INT);
            $stmt->execute();
            $fetch_sh = $stmt->fetch(PDO::FETCH_ASSOC);  // Changed to FETCH_ASSOC to ensure array
    
            if ($fetch_sh) {
                // Populate form fields with fetched fit-to-work data
                $assessment = $fetch_sh['assessment'];
                $diagnosis = $fetch_sh['diagnosis'];
                $remarks = $fetch_sh['remarks'];
                $sore_throat = $fetch_sh['sore_throat'];
                $body_pain = $fetch_sh['body_pain'];
                $headache = $fetch_sh['headache'];
                $fever = $fetch_sh['fever'];
                $cough_colds = $fetch_sh['cough_colds'];
                $lbm = $fetch_sh['lbm'];
                $loss_ts = $fetch_sh['loss_ts'];
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
        <h2>Edit Sent Home</h2>
        <form action="insert_sht.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1 -->
            <!-- Row 1 -->
            <div class="form-row">
                <!-- Employees Form -->
                <div class="form-group">
                    <input type="hidden" name="emp_id" value="<?= htmlspecialchars($emp_id) ?>">

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
                    <label for="reason">Reason :</label>
                    <input type="text" id="reason" name="remarks" value="<?= htmlspecialchars($remarks) ?>">
                </div>
                <div class="form-group">
                    <label for="assessment">Assessment :</label>
                    <input type="text" id="assessment" name="assessment" placeholder="Assessment"
                        value="<?= htmlspecialchars($assessment) ?>">
                </div>
                <div class="form-group">
                    <label for="diagnosis">Diagnosis :</label>
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="e.g. Headache"
                        value="<?= htmlspecialchars($diagnosis) ?>">
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks :</label>
                    <input type="text" id="remarks" name="remarks" placeholder="Supply"
                        value="<?= htmlspecialchars($remarks) ?>">
                </div>

            </div>

            <!-- Row 4 -->
            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="O2_sat">Sore Throat:
                        <input type="checkbox" id="to" name="sore_throat" value="Yes" <?= ($sore_throat == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

                <div class="form-group col-md-6">
                    <label for="RR">Body Pain:
                        <input type="checkbox" id="date_ofabs" name="body_pain" value="Yes" <?= ($body_pain == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="remarks">Headache:
                        <input type="checkbox" id="text" name="headache" value="Yes" <?= ($headache == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

                <div class="form-group col-md-6">
                    <label for="remarks">Cough/Colds:
                        <input type="checkbox" id="text" name="cough_colds" value="Yes" <?= ($cough_colds == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="remarks">Fever:
                        <input type="checkbox" id="text" name="fever" value="Yes" <?= ($fever == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="remarks">LBM:
                        <input type="checkbox" id="text" name="lbm" value="Yes" <?= ($lbm == 'Yes') ? 'checked' : '' ?>>
                    </label>
                </div>

                <div class="form-group col-md-6">
                    <label for="remarks">Loss of Taste/Smell:
                        <input type="checkbox" id="text" name="loss_ts" value="Yes" <?= ($loss_ts == 'Yes') ? 'checked' : '' ?>>
                    </label>
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