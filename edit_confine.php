<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confinement | Health-e</title>
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
    if (isset($_GET['emp_id']) && isset($_GET['con_id'])) {
        $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
        $con_id = $_GET['con_id'];    // Get the f_id from the query string
    
        // Initialize variables to hold the employee data
        $emp_no = $name = $age = $bday = $gender = $division = $company = '';
        $date_of_visit = $time_of_visit = $chief_complaint = $time_of_released = $total_hrs = $remarks = '';

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
            $stmt = $conn->prepare("SELECT * FROM tbl_confinement WHERE con_id = :con_id ORDER BY con_id DESC LIMIT 1");
            $stmt->bindParam(':con_id', $con_id, PDO::PARAM_INT);
            $stmt->execute();
            $fetch_con = $stmt->fetch(PDO::FETCH_ASSOC);  // Changed to FETCH_ASSOC to ensure array
    
            if ($fetch_con) {
                // Populate form fields with fetched fit-to-work data
                $date_of_visit = $fetch_con['date_of_visit'];
                $time_of_visit = $fetch_con['time_of_visit'];
                $chief_complaint = $fetch_con['chief_complaint'];
                $time_of_released = $fetch_con['time_of_released'];
                $total_hrs = $fetch_con['total_hrs'];
                $remarks = $fetch_con['remarks'];

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
        <h2>Edit Confinement</h2>
        <form action="insert_confine.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1 -->
            <!-- Row 1 -->
            <div class="form-row">
                <input type="hidden" name="emp_id" value="<?= htmlspecialchars($emp_id) ?>">
                <div class="form-group">
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
                    <label for="date_of_visit">Date of Visit :</label>
                    <input type="date" id="date" name="date_of_visit" value="<?= htmlspecialchars($date_of_visit) ?>">
                </div>

                <div class="form-group">
                    <label for="time_of_visit">Time of Visit :</label>
                    <input type="time" id="time_of_visit" name="time_of_visit" placeholder="Time of Visit" value="<?= htmlspecialchars($time_of_visit) ?>">
                </div>

                <div class="form-group">
                    <label for="chief_complaint">Chief Complaint :</label>
                    <input type="text" id="chief_complaint" name="chief_complaint" placeholder="e.g. Headache" value="<?= htmlspecialchars($chief_complaint) ?>">
                </div>

                <div class="form-group">
                    <label for="time_of_released">Time of Released :</label>
                    <input type="time" id="time_of_released" name="time_of_released" placeholder="Time of Release" value="<?= htmlspecialchars($time_of_released) ?>">
                </div>

            </div>
            
            <div class="form-row">

                <div class="form-group">
                    <label for="total_hrs">Total of Hours :</label>
                    <input type="text" id="total_hrs" name="total_hrs" placeholder="Total Hours" readonly value="<?= htmlspecialchars($total_hrs) ?>">
                </div>



                <div class="form-group">
                    <label for="remarks">Remarks :</label>
                    <input id="remarks" name="remarks" placeholder="Remarks" value="<?= htmlspecialchars($remarks) ?>">
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

<script>
    // Attach event listeners to both inputs
    document.getElementById("time_of_visit").addEventListener("input", calculateTotalTime);
    document.getElementById("time_of_released").addEventListener("input", calculateTotalTime);

    function calculateTotalTime() {
        // Get the values of the time inputs
        const timeVisit = document.getElementById("time_of_visit").value;
        const timeReleased = document.getElementById("time_of_released").value;

        if (timeVisit && timeReleased) {
            // Parse the times into Date objects for calculation
            const [visitHour, visitMinute] = timeVisit.split(':').map(Number);
            const [releaseHour, releaseMinute] = timeReleased.split(':').map(Number);

            // Calculate the total minutes for each time
            const visitTotalMinutes = visitHour * 60 + visitMinute;
            const releaseTotalMinutes = releaseHour * 60 + releaseMinute;

            // Calculate the difference in minutes
            let diffMinutes = releaseTotalMinutes - visitTotalMinutes;

            // Handle negative differences (time spans over midnight)
            if (diffMinutes < 0) {
                diffMinutes += 24 * 60; // Add 24 hours in minutes
            }

            // Convert total minutes to hours and minutes
            const diffHrs = Math.floor(diffMinutes / 60);
            const diffMins = diffMinutes % 60;

            // Display the result in the total_hrs field
            document.getElementById("total_hrs").value = `${diffHrs} hours and ${diffMins} minutes`;
        }
    }
</script>

</html>