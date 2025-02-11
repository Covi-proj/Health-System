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

    <?php

    include('edit_delete_dbconn.php');

    // Check if 'emp_id' is passed in the URL
    if (isset($_GET['emp_id'])) {
        $emp_id = $_GET['emp_id'];  // Get the emp_id from the query string
    
        // Initialize variables to hold the employee data
        $emp_no = $name = $age = $bday = $gender = $division = $company = '';

        try {
            // Fetch the employee data from the database
            $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = :emp_id");
            $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
            $stmt->execute();
            $fetch_fit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fetch_fit) {
                // Populate form fields with fetched data
                $emp_no = $fetch_fit['emp_no'];
                $name = $fetch_fit['name'];
                $age = $fetch_fit['age'];
                $bday = $fetch_fit['bday'];
                $gender = $fetch_fit['gender'];
                $division = $fetch_fit['division'];
                $company = $fetch_fit['company'];
            } else {
                echo "No Employee found with this ID.";
                exit;
            }
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
            exit;
        }
    } else {
        echo "No Employee ID provided.";
        exit;
    }

    ?>

    <!-- Eligibility Form -->
    <div class="form-container">
        <h2>Medicine</h2>
        <form action="insert_med.php" method="POST" enctype="multipart/form-data">
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
                    <label for="name">Date :</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Reason :</label>
                    <input type="text" id="time" name="reason" placeholder="Reason" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Medicine :</label>
                    <select id="companyFilter" class="form-select" name="medicine" required onchange="fetchSupply()">
                        <option value="">--Select Medicine--</option>
                        <?php
                        $conn = mysqli_connect("localhost", "root", "", "e_system");
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Populate dropdown options
                        $sqloption = "SELECT DISTINCT Med_name FROM medicines";
                        $result = mysqli_query($conn, $sqloption);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $selected = (isset($_GET['Med_name']) && $_GET['Med_name'] === $row['Med_name']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($row['Med_name']) . '" ' . $selected . '>' . htmlspecialchars($row['Med_name']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No Medicines Found</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Supply :</label>
                    <input type="text" id="supply" name="supply" placeholder="Supply" required readonly>
                </div>

                <script>
                    function fetchSupply() {
                        const medicine = document.getElementById("companyFilter").value;

                        if (medicine) {
                            // Send an AJAX request to fetch the supply for the selected medicine
                            const xhr = new XMLHttpRequest();
                            xhr.open("POST", "getSupply.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    const response = JSON.parse(xhr.responseText);
                                    document.getElementById("supply").value = response.supply || "Not Available";
                                }
                            };
                            xhr.send("Med_name=" + encodeURIComponent(medicine));
                        } else {
                            document.getElementById("supply").value = ""; // Clear supply field if no medicine is selected
                        }
                    }
                </script>



            </div>
            <!-- Row 4 -->
            <div class="form-row">


                <div class="form-group">
                    <label for="RR">Quantity :</label>
                    <input type="number" id="quantity" name="quantity" placeholder="e. g. 7 pieces" required>
                </div>


                <div class="form-group">
                    <label for="remarks">Nurse on Duty :</label>
                    <input id="text" id="nod" name="nod" placeholder="e.g. Name of Nurse on Duty" required>
                </div>

                <div class="form-group">
                    <label for="remarks">Note :</label>
                    <input id="text" id="note" name="note" placeholder="Note" required>
                </div>

            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Submit</button>
                <button type="button" class="btn-cancel"
                    onclick="window.location.href='clinic_admin.php#medication_tab';">Cancel</button>
            </div>
        </form>
    </div>
</body>
<script>
    // Get today's date
    const today = new Date();
    const formattedDate = today.toISOString().split('T')[0]; // Format it as YYYY-MM-DD

    // Set the value of the date input
    document.getElementById('date').value = formattedDate;
</script>

</html>