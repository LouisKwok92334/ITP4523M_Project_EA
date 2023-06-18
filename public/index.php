<?php
  session_start();

  if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
      // User is not logged in. Redirect them to the login page
      header('Location: login.php');
      exit;
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supply Management System</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
</head>
<body>
    <nav>
        <ul>
            <?php
              if ($_SESSION['role'] === 'purchase_manager') {
                echo '<li><a href="manager_info/make_order.php">Make Order</a></li>';
                echo '<li><a href="manager_info/view_order.php">View Order</a></li>';
                echo '<li><a href="manager_info/update_info.php">Update Information</a></li>';
                echo '<li><a href="manager_info/delete_order.php">Delete Order</a></li>';
              } elseif ($_SESSION['role'] === 'supplier') {
                echo '<li><a href="supplier/insert_item.php">Insert Item</a></li>';
                echo '<li><a href="supplier/edit_item.php">Edit Item</a></li>';
                echo '<li><a href="supplier/generate_report.php">Generate Report</a></li>';
                echo '<li><a href="supplier/delete_item.php">Delete Item</a></li>';
              }
            ?>
        </ul>
    </nav>
    
    <h1>Welcome to the Supply Management System</h1>
    <p>This is a system for managing orders and supplies.</p>
    
    <a href="logout.php">Logout</a>
    <script src="js/navbar.js"></script>
</body>
</html>
