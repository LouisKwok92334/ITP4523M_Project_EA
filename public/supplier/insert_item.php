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
  <title>insert item</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/insert_item.css">
  <link rel="shortcut icon" href="../images/Logo3.png" type="image/x-icon">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <section>
    <div class="insert-item-container">
        <h2>Insert new item</h2>
        <form id="insert-item-form">
            <label for="supplierID">Supplier ID:</label>
            <input type="text" id="supplierID" name="supplierID" readonly>

            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required>

            <label for="itemImage">Item Image:</label>
            <input type="file" id="itemImage" name="itemImage" required>

            <label for="itemDescription">Item Description:</label>
            <textarea id="itemDescription" name="itemDescription" required></textarea>

            <label for="stockItemQty">Stock Item Quantity:</label>
            <input type="number" id="stockItemQty" name="stockItemQty" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <button type="submit" id="submit">Insert Item</button>
        </form>
    </div>
</section>
  <?php include '../includes/footer.php'; ?>
  <script src="../js/insert_item.js"></script>
</body>
</html>