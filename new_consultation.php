<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Form | Health-e</title>
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
            <img src="unnamed.png" alt="Logo" style="height: 40px;">
            Health-e
        </div>
       
    </nav>

    <!-- Consultation Form -->
    <div class="form-container">
        <h2>Consultation Form</h2>
        <form action="add_patient.php" method="POST">
            <!-- Row 1 -->
            <div class="form-row">

            <div class="form-group">
                <label for="emp">Employee No.</label>
                <input type="text" id="emp" name="emp" placeholder="HEPC - 1020" />
                </div>

                <div class="form-group">
                <label for="name">Patient Name</label>
                <input type="text" id="name" name="pnt_name" placeholder="Full Name" />
            </div>

                <div class="form-group">
                <label for="division">Dept./Section</label>
                <input type="text" id="division" name="division" placeholder="(e.g. IT Department)" />
                </div>

                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company" placeholder="Company" />
                </div>
        <script>
                // Function to search for user data based on employee ID
function searchUser() {
    var emp = document.getElementById('emp').value;

    // Ensure the emp field isn't empty before proceeding
    if (!emp) {
        alert("Please enter Employee No.");
        return;
    }

    // Perform an asynchronous request to the server
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_scan.php?emp_no=' + encodeURIComponent(emp), true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Response:', xhr.responseText); // Debug server response
            try {
                var userData = JSON.parse(xhr.responseText);

                // Update fields with received data
                if (userData.error) {
                    alert(userData.error); // Handle the case where no user is found
                } else {
                    document.getElementById('name').value = userData.name || '';
                    document.getElementById('division').value = userData.division || '';
                    document.getElementById('company').value = userData.company || '';
                   
                }
            } catch (e) {
                console.error('Error parsing JSON response:', e);
            }
        } else {
            console.error('Error: HTTP status', xhr.status, xhr.statusText);
        }
    };

    xhr.onerror = function () {
        console.error('Network error');
    };

    xhr.send();
}

// Add keypress event to detect Enter key
document.getElementById('emp').addEventListener('keypress', function (event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission
        searchUser(); // Call the function to fetch and auto-fill data
    }
});

</script>
            </div>

            <!-- Row 2 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="c_date">Consultation Date</label>
                    <input type="date" id="c_date" name="c_date">
                </div>

                <div class="form-group">
                    <label for="bp">Reason for Clinic Visit</label>
                    <input type="text" id="rcv" name="rcv" placeholder="Reason of Visit">
                </div>

                <div class="form-group">
                    <label for="bp">Blood Pressure</label>
                    <input type="text" id="bp" name="bp" placeholder="BP">
                </div>
                <div class="form-group">
                    <label for="temp">Temperature</label>
                    <input type="text" id="temp" name="temp" placeholder="Temperature">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="HR">Heart Rate (HR)</label>
                    <input type="text" id="HR" name="HR" placeholder="Heart Rate">
                </div>
                <div class="form-group">
                    <label for="RR">Respiratory Rate (RR)</label>
                    <input type="text" id="RR" name="RR" placeholder="Respiratory Rate">
                </div>
                <div class="form-group">
                    <label for="O2_sat">Oxygen Saturation (O2 Sat)</label>
                    <input type="text" id="O2_sat" name="O2_sat" placeholder="O2 Sat">
                </div>
            </div>

            <!-- Row 4 -->
            <div class="form-row">
                <div class="form-group">
                    <label for="medicine">Medicine</label>
                    <input type="text" id="medicine" name="medicine" placeholder="Medicine">
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" id="qty" name="qty" placeholder="Quantity">
                </div>
            </div>

            <div class="form-group">
                <label for="remarks">Diagnosis</label>
                <textarea id="diagnosis" name="diagnosis"  placeholder="Diagnosis" ></textarea>
            </div>

            <!-- Remarks -->
            <div class="form-group">
                <label for="remarks">Special Case</label>
                <textarea id="remarks" name="remarks" rows="4" placeholder="Special Case" ></textarea>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Submit</button>
                <a class="btn-cancel" href="clinic_admin.php#balances">Cancel</a>
           
            </div>
        </form>
    </div>
</body>
</html>
