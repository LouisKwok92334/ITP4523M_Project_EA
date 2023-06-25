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
  <title>generate report</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/generate_report.css">
  <link rel="shortcut icon" href="../images/Logo3.png" type="image/x-icon">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <section>
  <div class="container">
      <h1>Report</h1>
      <table id="report-table">
        <thead>
          <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Item Image</th>
            <th>Total Number for Each Order Item</th>
            <th>Total Sales Amount ($)</th>
          </tr>
        </thead>
        <tbody>
          <!-- Report data will be added here using JavaScript -->
        </tbody>
      </table>
    </div>
  </section>
  <?php include '../includes/footer.php'; ?>
  <script src="../js/generate_report.js"></script>
</body>
</html>