<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'e_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user's name, password, and username
    $stmt = $pdo->prepare("SELECT name, password, username FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Securely output the values
        $user_name = htmlspecialchars($user['name']); // User's full name
        $username = htmlspecialchars($user['username']); // Username
        $user_password = htmlspecialchars($user['password']); // User's password
    } else {
        $user_name = "Guest"; // Fallback if user not found
        $user_password = "";
        $username = ""; // Fallback for username
    }
} catch (PDOException $e) {
    $user_name = "Error retrieving name.";
    $user_password = ""; // Empty password on error
    $username = ""; // Empty username on error
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List | Health-e</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <link rel="icon" href="icon.jfif" type="image/png">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .table-wrapper {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            font-size: 13px;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .section-header h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
        }

        .section-header p {
            color: #6c757d;
            font-size: 1rem;
        }

        .btn-action {
            display: inline-block;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .dataTables_filter input {
            border-radius: 5px;
        }

        .form-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-container input[type="file"] {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            font-size: 14px;
            color: #333;
        }

        .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background: #0056b3;
        }

        /* Customizing the select box appearance */
        .form-select {
            border-radius: 5px;
            padding: 0.375rem 1.5rem 0.375rem 0.75rem;
            /* Padding for a more spacious feel */
            background-color: #f8f9fa;
            /* Light background color */
            font-size: 1rem;
            /* Make text slightly larger for readability */
        }

        .input-group-text {
            background-color: #007bff;
            color: white;
            border-radius: 5px 0 0 5px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f9f9f9;
            /* Slight off-white background */
            padding: 10px 10px;
            /* Increased padding for more breathing space */
            border-bottom: 2px solid #dcdcdc;
            /* Subtle bottom border */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /* Light shadow for depth */
        }

        /* Left content styling */
        /* Navbar Left */
        .navbar-left {
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
            transition: transform 0.3s ease;
            /* Smooth scale transition */
        }

        .navbar-logo:hover {
            transform: scale(1.1);
            /* Subtle zoom effect on hover */
        }

        .logo-text {
            font-size: 28px;
            font-weight: 800;
            margin-left: 20px;
            color: #333;
            letter-spacing: 1px;
            transition: color 0.3s ease;
            /* Smooth color transition */
        }

        .logo-text:hover {
            color: #007BFF;
            /* Hover effect with primary brand color */
        }

        /* Date & Time */

        /* Navbar Right */
        /* Navbar Left */
        .navbar-left {
            display: flex;
            align-items: center;
            padding-left: 20px;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }

        .logo-text {
            font-size: 26px;
            font-weight: 700;
            margin-left: 15px;
            margin-top: 5px;
            color: #333;
        }

        /* Date & Time */
        .date-time-container p {
            font-size: 14px;
            color: #777;
            font-weight: 300;
            margin-left: 20px;
        }

        /* Navbar Right */
        .navbar-right {
            display: flex;
            align-items: center;
            padding-right: 20px;
            margin-top: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            font-size: 16px;
            color: #444;
            font-weight: 500;
            margin-right: 10px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
            transition: border-color 0.3s;
        }

        .user-avatar:hover {
            border-color: #007BFF;
            /* Light blue border on hover */
        }

        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;

        }

        /* Basic styling for dropdown */
        .user-info .dropdown {
            position: relative;
            display: inline-block;
        }

        .user-info .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            z-index: 1;
        }

        .user-info .dropdown-menu li {
            padding: 8px 16px;
            cursor: pointer;
        }

        .user-info .dropdown-menu li:hover {
            background-color: #f1f1f1;
        }

        /* Display dropdown menu on hover */
        .user-info .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="navbar-left">
            <div class="logo-container">
                <img src="unnamed.png" alt="Health-e Logo" class="navbar-logo">
                <span class="logo-text"></span>
            </div>
            <div class="date-time-container">
                <h5>Employee List</h5>
            </div>
        </div>

        <div class="navbar-right">
            <div class="user-info">
                <div class="dropdown">
                    <p class="user-name"><?php echo $username; ?></p>
                    <ul class="dropdown-menu">
                        <li class="fa fa-sign-out-alt"><a href="logout.php"
                                style="text-decoration: none; font-weight: bold;"> Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <script>
            document.querySelector('.user-name').addEventListener('click', function () {
                var dropdown = this.closest('.dropdown').querySelector('.dropdown-menu');
                dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
            });
        </script>
    </div>
    </div>
    <!-- Main Section -->
    <section class="py-5">
        <div class="container">

            <!-- Section Header -->

            <div class="container mt-5">

                <!-- Add Employee Button -->

            </div>

            <!-- Modal -->
            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Import Masterlist</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <!-- Modal Body -->
                        <div class="modal-body">
                            <form action="upload_excel.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="dataFile" class="form-label">Select File(Accepted File: CVS Excel
                                        file)</label>
                                    <input type="file" class="form-control" name="dataFile" id="dataFile"
                                        accept=".csv, .xls, .xlsx" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="submit" class="btn btn-success">Upload File</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap 5 JS Bundle (includes Popper.js) -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Table Wrapper -->
            <div class="table-wrapper">

                <div class="mb-3">
                    <label for="companyFilter" class="form-label">Filter:</label>
                    <div class="input-group">

                        <div class=form-row>
                            <div class="form-group">
                                <select id="companyFilter" class="form-select" required>
                                    <option value="">--Select Company--</option>
                                    <?php
                                    // Database connection settings
                                    $host = 'localhost';
                                    $db = 'e_system';
                                    $user = 'root';
                                    $pass = '';

                                    // Create PDO instance
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $sqloption = "SELECT DISTINCT company FROM employees";

                                    try {
                                        $stmt = $pdo->prepare($sqloption);
                                        $stmt->execute();
                                    } catch (PDOException $e) {
                                        echo 'Error: ' . $e->getMessage();
                                    }

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = (isset($_GET['company']) && $_GET['company'] === $row['company']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['company']) . '" ' . $selected . '>' . htmlspecialchars($row['company']) . '</option>';
                                    }
                                    ?>
                                </select>


                            </div>


                        </div>

                        <div class="form-row">

                            <div class="form-group" style="margin-left: 10px;">
                                <select id="dept" class="form-select" required>
                                    <option value="">--Select Department--</option>
                                    <?php
                                    // Database connection settings
                                    $host = 'localhost';
                                    $db = 'e_system';
                                    $user = 'root';
                                    $pass = '';

                                    // Create PDO instance
                                    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    $sqloption = "SELECT DISTINCT division FROM employees";

                                    try {
                                        $stmt = $pdo->prepare($sqloption);
                                        $stmt->execute();
                                    } catch (PDOException $e) {
                                        echo 'Error: ' . $e->getMessage();
                                    }

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = (isset($_GET['division']) && $_GET['division'] === $row['division']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($row['division']) . '" ' . $selected . '>' . htmlspecialchars($row['division']) . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#uploadModal"
                                style="font-weight: bold; margin-bottom: 10px; background-color:rgb(19, 128, 2);"><i
                                    class="fa fa-file-import"></i>
                                Import Masterlist
                            </button>
                        </div>

                        <div class="form-group" style="margin-left: 10px;">

                            <button type="button" id="btnAddEmployee" class="btn btn-primary"
                                style="font-weight: bold; margin-bottom: 10px; "><i class="fa fa-user-plus"></i>
                                Add Employee
                            </button>

                        </div>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                        <div class="form-group" style="margin-left: 10px;">
                            <button type="button" id="btnAddEmployee" class="btn btn-primary"
                                style="font-weight: bold; margin-bottom: 10px;" data-toggle="modal"
                                data-target="#employeeHeadcountModal">
                                <i class="fa fa-user-plus"></i> View Employee Headcount
                            </button>
                        </div>
                    </div>

                </div>

                <table id="employeeTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Section/Dept.</th>
                            <th>Company</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection settings
                        $host = 'localhost';
                        $db = 'e_system';
                        $user = 'root';
                        $pass = '';

                        try {
                            // Create PDO instance
                            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Query to fetch data from the "employees" table
                            $stmt = $pdo->prepare("SELECT * FROM employees");
                            $stmt->execute();

                            // Fetch all rows as an associative array
                            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Check if data exists
                            if ($data) {
                                foreach ($data as $item) {
                                    echo '<tr class="employee-row" data-company="' . htmlspecialchars($item['company']) . '" data-division="' . htmlspecialchars($item['division']) . '">';
                                    echo '<td>' . htmlspecialchars($item['emp_no'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['name'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['age'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['bday'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['gender'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['division'] ?? 'N/A') . '</td>';
                                    echo '<td>' . htmlspecialchars($item['company'] ?? 'N/A') . '</td>';
                                    echo '<td class="text-center">';
                                    echo '<a href="#" class="btn btn-sm btn-primary btn-edit btn-action" data-emp-id="' . htmlspecialchars($item['emp_id']) . '"><i class="fas fa-edit"></i> Edit</a>';
                                    echo '<a href="delete_m.php?emp_id=' . htmlspecialchars($item['emp_id']) . '" class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i> Delete</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                // Adjusted colspan to match the number of columns in the table
                                echo '<tr><td colspan="8" class="text-center">No employees found</td></tr>';
                            }
                        } catch (PDOException $e) {
                            // Adjusted colspan to match the number of columns in the table
                            echo '<tr><td colspan="8" class="text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</td></tr>';
                        }
                        ?>

                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const dept = document.getElementById("dept");
                                const rows = document.querySelectorAll(".employee-row");

                                if (!dept || rows.length === 0) return; // Ensure elements exist

                                dept.addEventListener("change", function () {
                                    const selectedDept = this.value.trim().toLowerCase();

                                    rows.forEach(row => {
                                        const division = row.getAttribute("data-division");
                                        if (division) {
                                            const divisionLower = division.trim().toLowerCase();
                                            if (selectedDept === "" || divisionLower === selectedDept) {
                                                row.style.display = "";
                                            } else {
                                                row.style.display = "none";
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    </tbody>
                </table>
                <p id="noDataMessage" style="display:none; text-align: center; color: red;">No data found</p>

                <div class="modal fade" id="employeeHeadcountModal" tabindex="-1" role="dialog"
                    aria-labelledby="employeeHeadcountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="employeeHeadcountModalLabel">Employee Headcount</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="companyCounts">
                                    <!-- List of company counts will be dynamically updated here -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>



    <!-- Add Employee Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="addForm" action="add_employee.php" method="POST">
                        <div class="mb-3">
                            <label for="addEmpNo" class="form-label">Employee No.:</label>
                            <input type="text" class="form-control" id="addEmpNo" name="emp_no" required>
                        </div>

                        <div class="mb-3">
                            <label for="addName" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="addName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="addage" class="form-label">Age:</label>
                            <input type="number" class="form-control" id="addage" name="age" required>
                        </div>

                        <div class="mb-3">
                            <label for="addbday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="addbday" name="bday" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <input type="radio" id="male" name="gender" value="Male" required>
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="female" name="gender" value="Memale">
                                    <label for="female">Female</label>
                                </div>
                                <div>
                                    <input type="radio" id="other" name="gender" value="other">
                                    <label for="other">Other</label>
                                </div>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label for="addDivision" class="form-label">Section/Dept.:</label>
                            <input type="text" class="form-control" id="addDivision" name="division" required>
                        </div>

                        <div class="mb-3">
                            <label for="addCompany" class="form-label">Company:</label>

                            <select id="addCompany" name="company" class="form-select" required>
                                <option>--Select Company--</option>
                                <option value="HEPC">HEPC</option>
                                <option value="POWERLANE">PRI</option>
                                <option value="HR TEAM ASIA">HRT</option>
                                <option value="HERU">HERU</option>
                                <option value="NCH">NCH</option>
                                <option value="CSC">CSC</option>
                                <option value="IISSI">IISSI</option>
                                <option value="EIPC">IEPC</option>
                            </select>

                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Employee Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="editForm" action="update_employee.php" method="POST">
                        <!-- Hidden field to store Employee ID -->
                        <input type="hidden" name="emp_id" id="editEmpId">

                        <div class="mb-3">
                            <label for="editEmpNo" class="form-label">Employee No.</label>
                            <input type="text" class="form-control" id="editEmpNo" name="emp_no" required>
                        </div>

                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="editAge" class="form-label">Age</label>
                            <input type="text" class="form-control" id="editAge" name="age" required>
                        </div>

                        <div class="mb-3">
                            <label for="editBday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="editBday" name="bday" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select id="editGender" class="form-select" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="editDivision" class="form-label">Section/Dept.</label>
                            <input type="text" class="form-control" id="editDivision" name="division" required>
                        </div>

                        <div class="mb-3">
                            <label for="editCompany" class="form-label">Company</label>
                            <input type="text" class="form-control" id="editCompany" name="company" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Update Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#employeeTable').DataTable({
                paging: true,        // Enable pagination
                searching: true,     // Enable search
                lengthChange: true,  // Allow users to change the number of rows displayed
                pageLength: 10,      // Default number of rows per page
                ordering: true,      // Enable column sorting
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Add Employee - Open Add Modal
            $('#btnAddEmployee').click(function () {
                $('#addModal').modal('show');
            });

            // Edit Employee - Open Edit Modal
            $('.btn-edit').click(function (e) {
                e.preventDefault();

                // Get employee data from the button's data attributes
                var empId = $(this).data('emp-id');
                var empNo = $(this).data('emp-no');
                var empName = $(this).data('name');
                var empDivision = $(this).data('division');
                var empCompany = $(this).data('company');

                // Populate the Edit Modal form fields with the data
                $('#editEmpId').val(empId);
                $('#editEmpNo').val(empNo);
                $('#editName').val(empName);
                $('#editDivision').val(empDivision);
                $('#editCompany').val(empCompany);

                // Show the Edit Modal
                $('#editModal').modal('show');
            });
        });
    </script>


    <script>


        // Get today's date
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0]; // Format it as YYYY-MM-DD

        // Set the value of the date input
        document.getElementById('editBday').value = formattedDate;



        $(document).ready(function () {
            $('#employeeTable').on('click', '.btn-edit', function () {
                const row = $(this).closest('tr'); // Get the row of the clicked button

                // Extract data from the table row
                const empId = $(this).data('emp-id'); // Employee ID (stored in the button as data-attribute)
                const empNo = row.find('td:eq(0)').text(); // Employee No.
                const name = row.find('td:eq(1)').text(); // Name
                const age = row.find('td:eq(2)').text(); // Age
                const bday = row.find('td:eq(3)').text(); // Birthday
                const gender = row.find('td:eq(4)').text().trim().replace(/\u00A0/g, ''); // Clean any unexpected spaces
                const division = row.find('td:eq(5)').text(); // Section/Dept.
                const company = row.find('td:eq(6)').text(); // Company

                // Populate modal fields
                $('#editEmpId').val(empId);
                $('#editEmpNo').val(empNo);
                $('#editName').val(name);
                $('#editAge').val(age);
                $('#editBday').val(bday);
                $('#editGender').val(gender); // Updated for <select>
                $('#editDivision').val(division);
                $('#editCompany').val(company);

                // Show the modal
                $('#editModal').modal('show');
            });
        });





    </script>
    <!--Set total entries each company-->
    <script>
        $(document).ready(function () {
            // Function to update counts for each company
            function updateCompanyCounts() {
                let counts = {};

                // Count rows for each company
                $('#employeeTable tbody tr').each(function () {
                    const company = $(this).data('company');
                    if (company) {
                        counts[company] = (counts[company] || 0) + 1;
                    }
                });

                // Display counts
                $('#companyCounts').empty(); // Clear previous counts
                for (const company in counts) {
                    $('#companyCounts').append(`<li>${company}: ${counts[company]} employees</li>`);
                }
            }

            // Filter the table based on selected company
            $('#companyFilter').change(function () {
                const selectedCompany = $(this).val().toLowerCase(); // Get selected company
                let rowsVisible = false; // Flag to check if any row is visible

                // Filter table rows based on company
                $('#employeeTable tbody tr').each(function () {
                    const company = $(this).data('company').toLowerCase(); // Get company of the current row
                    if (selectedCompany === '' || company === selectedCompany) {
                        $(this).show(); // Show row if it matches or no filter is selected
                        rowsVisible = true; // Mark that at least one row is visible
                    } else {
                        $(this).hide(); // Hide row if it doesn't match
                    }
                });

                // Show or hide the "No data found" message based on visibility of rows
                if (!rowsVisible) {
                    $('#noDataMessage').show(); // Show the message if no rows are visible
                } else {
                    $('#noDataMessage').hide(); // Hide the message if there are visible rows
                }

                updateCompanyCounts(); // Update counts when filtering
            });

            // Initial count display
            updateCompanyCounts();
        });
    </script>
</body>

</html>