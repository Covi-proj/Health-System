<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Use the correct column name 'f_date' in the query
$sql = "SELECT * FROM fit_to_work WHERE MONTH(f_date) = $currentMonth AND YEAR(f_date) = $currentYear";
$result = $conn->query($sql);

$filename = 'Fit_to_Work_Record_' . date('Y_m') . '.csv'; // Add current month and year to the filename
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, array(
    'Record Id','f_date', 'Time-in', 'Time-out', 'Patient Name', 'Diagnosis',
    'Eligibility','Date of Absences','Medicine', 'Remarks', 'NOD'
));

// Fetch and write the data
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$conn->close();
?>
