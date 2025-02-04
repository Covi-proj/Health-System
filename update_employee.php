<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $host = 'localhost';
    $db = 'e_system';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve form data
        $emp_id = $_POST['emp_id'];
        $emp_no = $_POST['emp_no'];
        $name = $_POST['name'];
        $age = $_POST['age'];
        $bday = $_POST['bday'];
        $gender = $_POST['gender'];
        $division = $_POST['division'];
        $company = $_POST['company'];

        // Update query
        $stmt = $pdo->prepare("UPDATE employees SET emp_no = ?, name = ?, age = ?, bday= ? , gender = ?, division = ?, company = ? WHERE emp_id = ?");
        $stmt->execute([$emp_no, $name, $age, $bday, $gender, $division, $company, $emp_id]);

        // Redirect or show success message
        header('Location: view_masterlist.php?success=1');
        exit();
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
