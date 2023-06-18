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
    <link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>
    <div class="toggle-btn" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <nav id="sidebar">
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

    <div class="page-content-wrapper">
        <h1>Welcome to the Yummy Restaurant Management System</h1>
        <p>This is a system for managing orders and supplies.</p>
        <a href="logout.php">Logout</a>
    </div>

    <script>
        function toggleSidebar(){
           document.getElementById("sidebar").classList.toggle('active');
           document.querySelector(".page-content-wrapper").classList.toggle('move-content');
        }
    </script>
</body>
</html>
