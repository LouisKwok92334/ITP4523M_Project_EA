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
  <title>view order</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/view_orders.css">
  <link rel="shortcut icon" href="../images/Logo3.png" type="image/x-icon">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <section>
    <form id="sort-form">
      <label for="sort-by">Sort by:</label>
      <select id="sort-by">
        <option value="orderID">Order ID</option>
        <option value="supplierID">Supplier ID</option>
        <option value="companyName">Supplier's Company Name</option>
        <option value="contactName">Supplier's Contact Name</option>
        <option value="contactNumber">Supplier's Contact Number</option>
        <option value="orderDateTime">Order Date & Time</option>
        <option value="deliveryAddress">Delivery Address</option>
        <option value="deliveryDate">Delivery Date</option>
        <option value="itemID">Item ID</option>
        <option value="itemName">Item Name</option>
        <option value="orderQty">Order Quantity</option>
        <option value="TotalOrderAmount">Total Order Amount</option>
      </select>

      <label for="direction">Direction:</label>
      <select id="direction">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </form>
    <table id="orderTable">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Supplier ID</th>
          <th>Supplier's Company Name</th>
          <th>Supplier's Contact Name</th>
          <th>Supplier's Contact Number</th>
          <th>Order Date & Time</th>
          <th>Delivery Address</th>
          <th>Delivery Date</th>
          <th>Item ID</th>
          <th>Item Image</th>
          <th>Item Name</th>
          <th>Order Quantity</th>
          <th>Total Order Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </section>
  <div id="delete-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <p>Are you sure you want to delete this order?</p>
      <button id="confirm-delete">Confirm</button>
      <button id="cancel-delete">Cancel</button>
    </div>
  </div>
  <?php include '../includes/footer.php'; ?>
  <script src="../js/view_orders.js"></script>
</body>
</html>