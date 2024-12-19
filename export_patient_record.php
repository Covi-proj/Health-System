<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e_system";

// Establish a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current month and year
$currentMonth = date('m'); // Get the current month (e.g., 12 for December)
$currentYear = date('Y'); // Get the current year (e.g., 2024)

// Modify the SQL query to filter records based on the current month and year
$sql = "SELECT * FROM consultation WHERE MONTH(c_date) = $currentMonth AND YEAR(c_date) = $currentYear";
$result = $conn->query($sql);

// Set up the filename with the current month and year
$filename = 'Patient_Consultation_Record_' . date('F_Y') . '.csv'; // e.g., "Patient_Consultation_Record_December_2024.csv"

// Set the headers to prompt a file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream and write column headers
$output = fopen('php://output', 'w');
fputcsv($output, array(
    'Patient Id', 'Patient Name', 'Division', 'Company', 'Date',
    'Blood Pressure', 'Temperature', 'Heart Rate', 'Respiratory Rate', 
    'Oxygen Saturation', 'Medicine', 'Quantity', 'Remarks'
));

// Write rows from the database to the CSV file
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Close output stream and database connection
fclose($output);
$conn->close();
?>
