<?php
// Database connection (PDO)
$host = 'localhost';
$dbname = 'e_system';
$username = 'root';
$password = '';
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if a file was uploaded
if (isset($_POST['submit']) && isset($_FILES['dataFile'])) {
    // Uploaded file details
    $file = $_FILES['dataFile'];
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Allowed file types
    $allowedFormats = ['csv', 'xls', 'xlsx'];
    if (!in_array($fileType, $allowedFormats)) {
        echo "Unsupported file format. Please upload a CSV, XLS, or XLSX file.";
        exit();
    }

    // Handle file types
    if ($fileType === 'csv') {
        handleCsv($file['tmp_name'], $pdo);
    } elseif (in_array($fileType, ['xls', 'xlsx'])) {
        echo "Excel file support (xls/xlsx) is not implemented. Convert to CSV for now.";
    } else {
        echo "File type not recognized.";
    }
}

// Function to process CSV files
function handleCsv($filePath, $pdo)
{
    if (($handle = fopen($filePath, 'r')) !== FALSE) {
        fgetcsv($handle); // Skip header row

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $emp_no = $data[0];
            $name = $data[1];
            $division = $data[2];
            $company = $data[3];

            // Insert data into the database
            $sql = "INSERT INTO employees (emp_no, name, division, company) VALUES (:emp_no, :name, :division, :company)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':emp_no', $emp_no, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':division', $division);
            $stmt->bindParam(':company', $company); // Corrected placeholder
            $stmt->execute();

            echo "Row imported: $emp_no, $name, $division, $company<br>";
        }

        fclose($handle);
    } else {
        echo "Failed to open CSV file.";
    }
}

header('location: view_masterlist.php');
?>
