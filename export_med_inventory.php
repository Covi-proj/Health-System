<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current month and year
$currentMonth = date('m'); // e.g., 12 for December
$currentYear = date('Y'); // e.g., 2024

// Modify the SQL query to filter by the current month and year
$sql = "SELECT Med_name, quantity, date_receive 
        FROM medicines 
        WHERE MONTH(date_receive) = $currentMonth AND YEAR(date_receive) = $currentYear";

$result = $conn->query($sql);

$filename = 'Medicine_Record_Archive_' . date('F_Y') . '.csv'; // e.g., Medicine_Record_Archive_December_2024.csv
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array('Medicine', 'Quantity', 'Date Received'));

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
