<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Case | Health-e</title>
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

    // Check if 'emp_id' and 'f_id' are passed in the URL
    if (isset($_GET['emp_id']) && isset($_GET['sc_id'])) {
        $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
        $sc_id = $_GET['sc_id'];    // Get the f_id from the query string
    
        // Initialize variables to hold the employee data
        $emp_no = $name = $age = $bday = $gender = $division = $company = '';
        $case_no = $date = $diagnosis = $retain = $no_exp = $max_ot = $nss = $asfmcf = $ffcpm = $provide_chair = $remarks = $status_ = $cu = '';

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

            // Fetch fit-to-work data for the employee using cons_id
            $stmt = $conn->prepare("SELECT * FROM tbl_specialcase WHERE sc_id = :sc_id ORDER BY sc_id DESC LIMIT 1");
            $stmt->bindParam(':sc_id', $sc_id, PDO::PARAM_INT);
            $stmt->execute();
            $fetch_sc = $stmt->fetch(PDO::FETCH_ASSOC);  // Changed to FETCH_ASSOC to ensure array
    
            if ($fetch_sc) {
                // Populate form fields with fetched fit-to-work data
                $case_no = $fetch_sc['case_no'];
                $date = $fetch_sc['date'];
                $diagnosis = $fetch_sc['diagnosis'];
                $retain = $fetch_sc['retain'];
                $no_exp = $fetch_sc['no_exp'];
                $max_ot = $fetch_sc['max_ot'];
                $nss = $fetch_sc['nss'];
                $asfmcf = $fetch_sc['asfmcf'];
                $ffcpm = $fetch_sc['ffcpm'];
                $provide_chair = $fetch_sc['provide_chair'];
                $remarks = $fetch_sc['remarks'];
                $status_ = $fetch_sc['status_'];
                $cu = $fetch_sc['cu'];

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
        <h2> Edit Special Case</h2>
        <form action="insert_sc.php" method="POST" enctype="multipart/form-data">
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

            </div>

            <!-- Row 3 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="name">Case No. :</label>
                    <input type="text" id="case_no" name="case_no" value="<?= htmlspecialchars($case_no) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Date :</label>
                    <input type="date" id="time" name="date" value="<?= htmlspecialchars($date) ?>">
                </div>



                <script>
                    // Simulating a starting case number
                    let currentCaseNo = 0;
                    // Function to generate the next case number
                    function generateCaseNumber() {
                        currentCaseNo++; // Increment the case number
                        return currentCaseNo.toString().padStart(5, '0'); // Pad the number with leading zeros
                    }
                    // Automatically populate the input field with the generated case number
                    document.getElementById("case_no").value = generateCaseNumber();
                </script>

                <div class="form-group">
                    <label for="O2_sat">Diagnosis :</label>
                    <input type="text" id="time" name="diagnosis" placeholder="Diagnosis"
                        value="<?= htmlspecialchars($diagnosis) ?>">
                </div>

                <div class="form-group">
                    <label for="O2_sat">Retain Am Shift :</label>
                    <input type="date" id="from" name="retain">
                </div>



            </div>

            <div class=form-row>


                <div class="form-group">

                    <label for="O2_sat">No Exposure to Chem, XRF, Soldering : <input type="checkbox" id="to"
                            name="no_exp" value="Yes" <?= ($no_exp == 'Yes') ? 'checked' : '' ?>></label>

                </div>

                <div class="form-group">

                    <label for="O2_sat">Max 2hrs OT : <input type="checkbox" id="to" name="max_ot" value="Yes"
                            <?= ($max_ot == 'Yes') ? 'checked' : '' ?>></label>

                </div>

            </div>

            <div class=form-row>

                <div class="form-group">

                    <label for="O2_sat">No Sunday Shift : <input type="checkbox" id="to" name="nss" value="Yes"
                            <?= ($nss == 'Yes') ? 'checked' : '' ?>></label>

                </div>

                <div class="form-group">

                    <label for="O2_sat">Always Secure FTW/MED Cert From : <input type="checkbox" id="to" name="asfmcf"
                            value="Yes" <?= ($asfmcf == 'Yes') ? 'checked' : '' ?>></label>

                </div>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label for="O2_sat">FF UP To COM. Physician Monthly : <input type="checkbox" id="to" name="ffcpm"
                            value="Yes" <?= ($ffcpm == 'Yes') ? 'checked' : '' ?>></label>

                </div>

                <div class="form-group">

                    <label for="O2_sat">Provide Chair : <input type="checkbox" id="to" name="provide_chair" value="Yes"
                            <?= ($provide_chair == 'Yes') ? 'checked' : '' ?>></label>

                </div>

            </div>

            <!-- Row 4 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="RR">Remarks :</label>
                    <input type="text" id="date_ofabs" name="remarks" placeholder="Remarks"
                        value="<?= htmlspecialchars($remarks) ?>">
                </div>

            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="remarks">Status :</label>
                    <select name="status_" >
                        <option value="N/A">--Status--</option>
                        <option value="Continuous Monitoring"  <?= $status_ === "Continuous Monitoring" ? "selected" : "" ?>>Continuous Monitoring</option>
                        <option value="End of Special Case" <?= $status_ === "End of Special Case" ? "selected" : "" ?>>End of Special Case</option>
                        <option value="Resigned"  <?= $status_ === "Resigned" ? "selected" : "" ?>>Resigned</option>
                        <option value="AWOL/RTC"  <?= $status_ === "AWOL/RTC" ? "selected" : "" ?>>AWOL/RTC</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="remarks">Controlled/Uncontrolled :</label>
                    <select id="fruit" name="cu" value="<?= htmlspecialchars($cu) ?>">
                        <option value="N/A">--Status--</option>
                        <option value="Controlled" <?= $cu === "Controlled" ? "selected" : "" ?>>Controlled</option>
                        <option value="Uncontrolled" <?= $cu=== "Uncontrolled" ? "selected" : "" ?>>Uncontrolled</option>
                    </select>
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