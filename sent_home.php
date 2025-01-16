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

    <!-- Eligibility Form -->
    <div class="form-container">
        <h2>Sent Home</h2>
        <form action="add_fit.php" method="POST" enctype="multipart/form-data">
            <!-- Row 1 -->
            <!-- Row 1 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="name">Employee No. :</label>
                    <input type="text" id="emp" name="emp_no" value="" required>
                </div>

                <div class="form-group">
                    <label for="name">Name :</label>
                    <input type="text" id="age" name="name" value="" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Age :</label>
                    <input type="text" id="age" name="age" value="" readonly>
                </div>

                <div class="form-group">
                    <label for="name">Birthday :</label>
                    <input type="text" id="bday" name="bday" value="" readonly>
                </div>



            </div>
            <!-- Row 2 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="temp">Gender :</label>
                    <input type="text" id="diagnosis" name="gender" value="" readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Section/Dept :</label>
                    <input type="text" id="diagnosis" name="division" value="" readonly>
                </div>

                <div class="form-group">
                    <label for="temp">Company :</label>
                    <input type="text" id="company" name="company" value="" readonly>
                </div>


            </div>

            <!-- Row 3 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="name">Reason :</label>
                    <input type="date" id="date" name="date_of_visit" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Assessment :</label>
                    <input type="text" id="time" name="time_of_visit" placeholder="Time of Visit" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Diagnosis :</label>
                    <input type="text" id="from" name="chief_complaint" placeholder="e.g. Headache" required>
                </div>

                <div class="form-group">
                    <label for="O2_sat">Remarks :</label>
                    <input type="text" id="to" name="time_of_released" placeholder="Supply" required>
                </div>



            </div>
            <!-- Row 4 -->
            <div class="form-row">

                <div class="form-group">
                    <label for="O2_sat">Sore Throat : <input type="checkbox" id="to" name="time_of_released"
                            placeholder="Supply" required></label>
                </div>

                <div class="form-group">
                    <label for="RR">Body Pain : <input type="checkbox" id="date_ofabs" name="total_hrs"
                            placeholder="Total of hrs." required></label>

                </div>

                <div class="form-group">
                    <label for="remarks">Headache : <input type="checkbox" id="text" id="nod" name="remarks"
                            placeholder="Remarks"></label>

                </div>

                <div class="form-group">
                    <label for="remarks">Fever : <input type="checkbox" id="text" id="note" name="nod"
                            placeholder="Note"></label>
                </div>

                <div class="form-group">
                    <label for="remarks">Cough/Colds : <input type="checkbox" id="text" id="note" name="nod"
                            placeholder="Note"></label>
                </div>

                <div class="form-group">
                    <label for="remarks">LBM : <input type="checkbox" id="text" id="note" name="nod"
                            placeholder="Note"></label>
                </div>

            </div>

            <div class="form-group">

                <div class="form-group">
                    <label for="remarks">Loss Tate/Smell : <input type="checkbox" id="text" id="note" name="nod"
                            placeholder="Note"></label>
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

</html>