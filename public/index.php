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
    <title>Supply Management System</title>
    <!-- Add your CSS file here -->
    <link rel="stylesheet" type="text/css" href="css/style.css" />
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
</body>
</html>
