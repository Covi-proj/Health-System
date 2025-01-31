<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master List</title>

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
    </style>
</head>

<body>
    <!-- Main Section -->
    <section class="py-5">
        <div class="container">

            <!-- Section Header -->
            <div class="section-header text-center mb-4">
                <h1>Master List</h1>
                <p>A comprehensive list of employees and their details.</p>
            </div>
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
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <div class=form-row>
                            <div class="form-group">
                                <select id="companyFilter" class="form-select" required>
                                    <option value="" disabled selected>--Select Company--</option>
                                    <option value="HEPC">HEPC</option>
                                    <option value="POWERLANE">POWERLANE</option>
                                    <option value="HR TEAM ASIA">HR TEAM ASIA</option>
                                    <option value="HERU">HERU</option>
                                    <option value="NCH">NCH</option>
                                    <option value="CSC">CSC</option>
                                    <option value="IISSI">IISSI</option>
                                    <option value="EIPC">IEPC</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row"></div>

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
                                    echo '<tr class="employee-row" data-company="' . htmlspecialchars($item['company']) . '">';
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
                    </tbody>
                </table>
                <p id="noDataMessage" style="display:none; text-align: center; color: red;">No data found</p>

                <div id="companyCounts">
                    <!-- List of company counts will be dynamically updated here -->
                </div>
                <a class="btn-cancel mt-3 d-inline-block" href="super-admin.php#balances">Back</a>

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
                            <input type="text" class="form-control" id="editBday" name="bday" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <input type="radio" id="male" name="gender" value="Male" required>
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="female" name="gender" value="Female">
                                    <label for="female">Female</label>
                                </div>
                                <div>
                                    <input type="radio" id="other" name="gender" value="Other">
                                    <label for="other">Other</label>
                                </div>
                            </div>
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
        $(document).ready(function () {
            // Attach click event to Edit buttons
            $('#employeeTable').on('click', '.btn-edit', function () {
                const row = $(this).closest('tr'); // Get the row of the clicked button

                // Extract data from the table row
                const empId = $(this).data('emp-id'); // Employee ID (stored in the button as data-attribute)
                const empNo = row.find('td:eq(0)').text(); // Employee No.
                const name = row.find('td:eq(1)').text(); // Name
                const age = row.find('td:eq(2)').text(); // Age (assumed to be in 3rd column)
                const bday = row.find('td:eq(3)').text(); // Birthday (assumed to be in 4th column)
                const gender = row.find('td:eq(4)').text(); // Gender (assumed to be in 5th column)
                const division = row.find('td:eq(5)').text(); // Section/Dept. (assumed to be in 6th column)
                const company = row.find('td:eq(6)').text(); // Company (assumed to be in 7th column)

                // Populate modal fields
                $('#editEmpId').val(empId);
                $('#editEmpNo').val(empNo);
                $('#editName').val(name);
                $('#editAge').val(age);
                $('#editBday').val(bday);

                // Set gender
                // Set gender radio button based on the value
                if (gender.toLowerCase() === 'male') {
                    $('#male').prop('checked', true);
                } else if (gender.toLowerCase() === 'female') {
                    $('#female').prop('checked', true);
                } else {
                    $('#other').prop('checked', true);
                }

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