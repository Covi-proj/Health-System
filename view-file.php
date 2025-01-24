<?php
// Check if the 'file' or 'with_without_cert' parameter is passed
if (isset($_GET['file']) && !empty($_GET['file'])) {
    $filePath = htmlspecialchars($_GET['file']);  // Sanitize the file path

    // Check if the file exists
    if (file_exists($filePath)) {
        // Get the file extension to handle image and PDF display
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // For image files, display the image
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo '<img src="' . $filePath . '" alt="Image" style="max-width: 100%; height: auto;">';
        }
        // For PDF files, display the PDF
        elseif ($fileExtension == 'pdf') {
            echo '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="600px" />';
        }
        // For other file types, display a message
        else {
            echo '<p>Cannot display this file type.</p>';
        }
    } else {
        echo '<p>File not found.</p>';
    }
} elseif (isset($_GET['with_without_cert']) && !empty($_GET['with_without_cert'])) {
    // If 'with_without_cert' is provided, use it
    $filePath = htmlspecialchars($_GET['with_without_cert']);  // Sanitize the file path

    // Check if the file exists
    if (file_exists($filePath)) {
        // Get the file extension to handle image and PDF display
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

        // For image files, display the image
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo '<img src="' . $filePath . '" alt="Image" style="max-width: 100%; height: auto;">';
        }
        // For PDF files, display the PDF
        elseif ($fileExtension == 'pdf') {
            echo '<embed src="' . $filePath . '" type="application/pdf" width="100%" height="600px" />';
        }
        // For other file types, display a message
        else {
            echo '<p>Cannot display this file type.</p>';
        }
    } else {
        echo '<p>File not found.</p>';
    }
} else {
    echo '<p>No file specified.</p>';
}
?>