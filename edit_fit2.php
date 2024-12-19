
<?php
// Database connection
include('edit_delete_dbconn.php');

// Check if 'c_id' is passed in the URL
if (isset($_GET['f_id'])) {
    $f_id = $_GET['f_id'];
} else {
    echo "No consultation ID provided!";
    exit;
}

// Initialize form data
$f_date = $time_in = $time_out = $patient_name = $diagnosis = $ftw = $date_ofabs = $Med_name = $remarks = $nod = '';

try {
    // Fetch the consultation data
    $stmt = $conn->prepare("SELECT * FROM fit_to_work WHERE f_id = :f_id");
    $stmt->bindParam(':f_id', $f_id, PDO::PARAM_INT);
    $stmt->execute();
    $fetch_fit = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fetch_fit) {
        // Populate form fields
        $f_date = $fetch_fit['f_date'];
        $s_date = $fetch_fit['s_date'];
        $e_date = $fetch_fit['e_date'];
        $time_in = $fetch_fit['time_in'];
        $patient_name = $fetch_fit['patient_name'];
        $diagnosis = $fetch_fit['diagnosis'];
        $ftw = $fetch_fit['ftw'];
        $date_ofabs = $fetch_fit['date_ofabs'];
        $Med_name = $fetch_fit['Med_name'];
        $remarks = $fetch_fit['remarks'];
        $nod = $fetch_fit['nod'];
        
    } else {
        echo "No Eligibility found with this ID.";
        exit;
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $f_date = $_POST['f_date'];
    $s_date = $fetch_fit['s_date'];
    $e_date = $fetch_fit['e_date'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $patient_name = $_POST['patient_name'];
    $diagnosis = $_POST['diagnosis'];
    $ftw = $_POST['ftw'];
    $date_ofabs = $_POST['date_ofabs'];
    $Med_name = $_POST['Med_name'];
    $remarks = $_POST['remarks'];
    $nod = $_POST['nod'];
    

    try {
        // Update query
        $stmt = $conn->prepare(
            "UPDATE fit_to_work 
            SET 
                f_date = :f_date,
                s_date = :s_date,
                e_date = :e_date,
                time_in = :time_in,
                patient_name = :patient_name,
                diagnosis = :diagnosis,
                ftw = :ftw,
                date_ofabs = :date_ofabs,
                Med_name = :Med_name,
                remarks = :remarks,
                nod = :nod
            WHERE f_id = :f_id"
        );

        // Bind parameters
        $stmt->bindParam(':f_date', $f_date);
        $stmt->bindParam(':s_date', $s_date);
        $stmt->bindParam(':e_date', $e_date);
        $stmt->bindParam(':time_in', $time_in);
        $stmt->bindParam(':patient_name', $patient_name);
        $stmt->bindParam(':diagnosis', $diagnosis);
        $stmt->bindParam(':ftw', $ftw);
        $stmt->bindParam(':date_ofabs', $date_ofabs);
        $stmt->bindParam(':Med_name', $Med_name);
        $stmt->bindParam(':remarks', $remarks);
        $stmt->bindParam(':nod', $nod);

        // Execute query
        $stmt->execute();
        
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

        .logo{

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
            font-weight:bold;
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

    <!-- Eligibility Form -->
    <div class="form-container">
        <h2> Edit Fit to Work</h2>
        <form action="post_fit.php" method="POST">
            <!-- Row 1 -->
            <input type="hidden" name="f_id" value="<?= htmlspecialchars($f_id) ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Date</label>
                    <input type="date" id="date" name="f_date" value="<?= htmlspecialchars($f_date) ?>" equired>
                </div>

                <div class="form-group">
                    <label for="name">Start Date</label>
                    <input type="date" id="date" name="s_date" value="<?= htmlspecialchars($s_date) ?>" equired>
                </div>

                <div class="form-group">
                    <label for="name">End Date</label>
                    <input type="date" id="date" name="e_date" value="<?= htmlspecialchars($e_date) ?>" equired>
                </div>

                <div class="form-group">
                    <label for="division">Time</label>
                    <input type="time" id="time-in" name="time_in" value="<?= htmlspecialchars($time_in) ?>" required>
                </div>
              
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                
                <div class="form-group">
                    <label for="bp">Patient Name</label>
                    <input type="text" id="patient_name" name="patient_name" placeholder="Patient Name" value="<?= htmlspecialchars($patient_name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="temp">Diagnosis</label>
                    <input type="text" id="diagnosis" name="diagnosis" placeholder="Diagnosis" value="<?= htmlspecialchars($diagnosis) ?>" required>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="HR" >Eligibility</label>
                    <select id="ftw" name="ftw" placeholder = "status" style ="height: 40px; border-radius: 10px;" required>
                        <option><?= htmlspecialchars($ftw) ?></option>
                        <option value="Fit to Work">Fit to Work</option>
                        <option value="Not Fit to Work">Not Fit to Work</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="RR">Date of Abence(s) Covered</label>
                    <input type="text" id="date_ofabs" name="date_ofabs" placeholder="Date of Absence" value="<?= htmlspecialchars($date_ofabs) ?>" required>
                </div>
                <div class="form-group">
                    <label for="O2_sat">Medicine</label>
                    <input type="text" id="Med_name" name="Med_name" placeholder="(e.g. Paracetamol)" value="<?= htmlspecialchars($Med_name) ?>" required>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="medicine">NOD</label>
                    <input type="text" id="nod" name="nod" placeholder="Notice of Determination" value="<?= htmlspecialchars($nod) ?>" required>
                </div>
                <div class="form-group">
                <label for="remarks">Remarks</label>
                <input id="text" name="remarks" rows="4" placeholder="Remarks or concerns" value="<?= htmlspecialchars($remarks) ?>"required>
                </div>
            </div>

            <!-- Remarks -->
            <div class="form-group">
               
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Save</button>
                <a class="btn-cancel" href="super-admin.php#fit">Cancel</a>
            
            </div>
        </form>
    </div>
</body>
</html>
<script>
    