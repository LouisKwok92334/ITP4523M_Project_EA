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
  <title>Edit item</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/edit_item.css">
  <link rel="shortcut icon" href="../images/Logo3.png" type="image/x-icon">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <section>
    <div class="container">
      <h1>Edit Item</h1>
      <table id="item-table">
        <thead>
          <tr>
            <th>Item ID</th>
            <th>Supplier ID</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Item Image</th>
            <th>Stock Item Quantity</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Table content will be populated using JavaScript -->
        </tbody>
      </table>

      <div id="edit-item-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <form id="edit-item-form">
            <div class="form-group">
              <label for="supplier-id">Supplier ID:</label>
              <input type="text" id="supplier-id" name="supplierID" readonly>
            </div>
            <div class="form-group">
              <label for="item-name">Item Name:</label>
              <input type="text" id="item-name" name="itemName" required>
            </div>
            <div class="form-group">
              <label for="item-description">Item Description:</label>
              <input type="text" id="item-description" name="itemDescription" required>
            </div>
            <div class="form-group">
              <label for="item-image">Item Image:</label>
              <input type="file" id="item-image" name="ImageFile">
            </div>
            <div class="form-group">
              <label for="stock-item-quantity">Stock Item Quantity:</label>
              <input type="number" id="stock-item-quantity" name="stockItemQty" required>
            </div>
            <div class="form-group">
              <label for="price">Price:</label>
              <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <button type="button" id="cancel-btn">Cancel</button>
            <button type="submit" id="update-btn">Update Item</button>
            <button type="button" id="delete-btn">Delete Item</button>
            <div id="delete-confirm-modal" class="modal">
              <div class="modal-content">
                <h2>Confirm Delete</h2>
                <p>Are you sure you want to delete this item?</p>
                <button id="confirm-delete-btn" class="delete-btn">Yes, delete it</button>
                <button id="cancel-delete-btn" class="cancel-btn">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php include '../includes/footer.php'; ?>
  <script src="../js/edit_item.js"></script>
</body>
</html>