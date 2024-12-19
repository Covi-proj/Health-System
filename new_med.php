<?php
session_start(); 
// Ensure you include the database connection file
include "edit_delete_dbconn.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Med_name = $_POST['Med_name'] ?? null;
    $quantity = $_POST['quantity'] ?? null;
    $date_receive = $_POST['date_receive'] ?? null;
    $receiver = $_POST['receiver'] ?? null;

    // Only proceed if all fields are filled
    if ($Med_name && $quantity && $date_receive) {
        try {
            // Insert query
            $sql = "INSERT INTO medicines (Med_name, quantity, date_receive, receiver) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$Med_name, $quantity, $date_receive, $receiver]);

            // Set success message in session for use in the UI
            
            header('location: clinic_admin.php#payment');
            exit();
            // Redirect to the same page to display the message

        } catch (PDOException $e) {
            echo 'error'; // Handle errors here
        }
    } else {
        echo 'No records found'; // Handle missing data
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="icon.jfif" type="image/png">
   <!-- Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

   <title>New Medicine | Inventory</title>
</head>

<body>
   <nav class="navbar navbar-light justify-content-center fs-3 mb-5" style="background-color: #00ff5573;">
      Medicine Inventory
   </nav>

   <div class="container">
      <div class="text-center mb-4">
         <h3>New Medicine</h3>
         <p class="text-muted">Complete the form below to add a new medicines</p>
      </div>

      <div class="container d-flex justify-content-center">
         <form action="" method="post" style="width:50vw; min-width:300px;">
            <div class="row mb-3">
               <div class="col">
                  <label class="form-label">Medicine Name:</label>
                  <input type="text" class="form-control" name="Med_name" placeholder="(e.g. paracetamol)" required>
               </div>

               <div class="col">
                  <label class="form-label">Quantity:</label>
                  <input type="number" class="form-control" name="quantity" required>
               </div>
            </div>

            
            <div class="mb-3">

                  <label for="appointmentDate">Date Received:</label>
                  <input type="date" class="form-control" id="appointmentDate" name="date_receive" required>

                  <label class="form-label">Receiver:</label>
                  <input type="text" class="form-control" name="receiver" required>

            </div>

            <div>
               <button type="submit" class="btn btn-success" name="submit">Save</button>
               <a href="clinic_admin.php#payment" class="btn btn-danger">Cancel</a>
            </div>
         </form>
      </div>
   </div>

   <!-- Success message display in the payment section -->
   <?php
   if (isset($_GET['msg'])) {
       echo "<div class='alert alert-success text-center'>{$_GET['msg']}</div>";
   }
   ?>

   <!-- Here you would have your payment table, possibly something like this: -->
   <div id="payment">
      <!-- Your existing table or content to display medicines will go here -->
      <!-- Make sure it updates properly with the new data from the database -->
   </div>

   <!-- Bootstrap -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</body>
<script>
 const today = new Date().toISOString().split('T')[0];
    // Set the value of the input field
    document.getElementById('appointmentDate').value = today;

</script>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

