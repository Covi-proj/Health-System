<?php
// Database connection
$host = 'localhost';
$db   = 'e_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $empNo = $_POST['emp_no'];
        $name = $_POST['name'];
        $division = $_POST['division'];
        $company = $_POST['company'];

        // Check if the employee already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE emp_no = ?");
        $stmt->execute([$empNo]);
        $employeeExists = $stmt->fetchColumn();

        if ($employeeExists) {
            // If employee exists, show a message
            header('Location: view_masterlist.php?message=Employee already exists');
            exit;
        } else {
            // Insert data into the database if the employee doesn't exist
            $stmt = $pdo->prepare("INSERT INTO employees (emp_no, name, division, company) VALUES (?, ?, ?, ?)");
            $stmt->execute([$empNo, $name, $division, $company]);

            // Redirect back with success
            header('Location: view_masterlist.php?message=Employee added successfully');
            exit;
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
