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

    // Query to count consultations grouped by month
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(c_date, '%M %Y') AS month, COUNT(*) AS count FROM consultation GROUP BY month ORDER BY DATE_FORMAT(c_date, '%Y-%m') ASC");
    $stmt->execute();

    // Fetch all results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
