<?php
    include '../includes/auth.php';
    require_once("../connection/mysqli_conn.php");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>update information</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/update_information.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>
    <section>
        <form id="updateInfoForm">
            <h2>Update Information</h2>
            
            <label for="currentPassword">Current Password:</label>
            <input type="password" id="currentPassword" name="currentPassword" required>

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>

            <label for="confirmPassword">Confirm New Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>
            
            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" required>

            <label for="warehouseAddress">Warehouse Address:</label>
            <input type="text" id="warehouseAddress" name="warehouseAddress" required>

            <div id="errorMessage"></div>
            <button type="submit">Submit</button>
        </form>
    </section>
  <?php include '../includes/footer.php'; ?>
  <script src="../js/update_information.js"></script>
</body>
</html>