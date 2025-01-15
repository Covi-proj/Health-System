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

    <!-- Eligibility Form -->
    <div class="form-container">
        <h2>Fit to Work</h2>
        <form action="add_fit.php" method="POST">
            <!-- Row 1 -->

            <div class="form-row">

                <div class="form-group">
                    <label for="name">Employee No. :</label>
                    <input type="text" id="emp" name="emp_no" required>
                </div>

                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" id="age" name="name" required>
                </div>

                <div class="form-group">
                    <label for="name">Age :</label>
                    <input type="text" id="age" name="age" required>
                </div>

                <div class="form-group">
                    <label for="name">Birthday :</label>
                    <input type="text" id="bday" name="bday" required>
                </div>

                <!--
                <div class="form-group">
                    <label for="name">Start Date</label>
                    <input type="date" id="date" name="s_date" required>
                </div>

                <div class="form-group">
                    <label for="name">End Date</label>
                    <input type="date" id="date" name="e_date" required>
                </div>

                <div class="form-group">
                    <label for="division">Time</label>
                    <input type="time" id="time-in" name="time_in" required>
                </div>
    -->
            </div>
            <!-- Row 2 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="temp">Gender :</label>
                    <input type="text" id="diagnosis" name="gender" required>
                </div>

                <div class="form-group">
                    <label for="temp">Section/Dept :</label>
                    <input type="text" id="diagnosis" name="division" required>
                </div>

                <div class="form-group">
                    <label for="temp">Company :</label>
                    <input type="text" id="company" name="company" required>
                </div>


            </div>

            <!-- Row 3 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="name">Date :</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Time :</label>
                    <input type="time" id="time" name="time" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">From :</label>
                    <input type="date" id="from" name="from_" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">To :</label>
                    <input type="date" id="to" name="to_" required>
                </div>

                <div class="form-group">
                    <label for="RR">Total Days of Absence</label>
                    <input type="text" id="date_ofabs" name="date_ofabs" placeholder="(e.g. 7 days)" required>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="remarks">Reason :</label>
                    <input type="text" id="text" name="remarks" rows="4" placeholder="Remarks" required>
                </div>


                <div class="form-group">
                    <label for="medicine">Nurse on Duty :</label>
                    <input type="text" id="nod" name="nod" placeholder="Nurse on duty" required>
                </div>

                <div class="form-group">
                    <label for="medicine">With/Without Medical Certificate :</label>
                    <input type="file" id="nod" name="nod" placeholder="Nurse on duty" required>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks :</label>
                    <input id="text" name="remarks" rows="4" placeholder="Remarks" required>
                </div>


            </div>

            <div class="form-row">

                <div class="form-group">
                    <label for="remarks">Nurse on Duty :</label>
                    <input id="text" id = "nod" name="nod" placeholder = "e.g. Name of Nurse on Duty" required>
                </div>

                <div class="form-group">
                    <label for="remarks">Note :</label>
                    <input id="text" id = "note" name="note" placeholder = "Note" required>
                </div>

            </div>

            <!-- Remarks -->
            <div class="form-group">

            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn-submit">Submit</button>
                <button type="button" class="btn-cancel"
                    onclick="window.location.href='clinic_admin.php#';">Cancel</button>
            </div>
        </form>
    </div>
</body>

</html>