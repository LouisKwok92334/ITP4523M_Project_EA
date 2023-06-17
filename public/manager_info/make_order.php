<?php
    include '../includes/auth.php';
    require_once("../connection/mysqli_conn.php");

    // fetch the available products
    $result = mysqli_query($conn, "SELECT * FROM Item WHERE stockItemQty > 0 ORDER BY itemName ASC");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        /* Some basic CSS for table formatting */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <form action="order_process.php" method="POST">
        <!-- Assume that managerName and purchaseManagerID come from the logged-in user -->
        <input type="hidden" name="purchaseManagerID" value="<?php echo $_SESSION['purchaseManagerID']; ?>">
        <input type="hidden" name="managerName" value="<?php echo $_SESSION['managerName']; ?>">
        <label for="deliveryAddress">Delivery Address:</label>
        <input type="text" id="deliveryAddress" name="deliveryAddress" required><br>
        <label for="deliveryDate">Delivery Date:</label>
        <input type="date" id="deliveryDate" name="deliveryDate" required><br>
        <label for="itemID">Item:</label>
        <select id="itemID" name="itemID" required>
            <?php foreach($products as $product): ?>
                <option value="<?php echo $product['itemID']; ?>"><?php echo $product['itemName']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="orderQty">Quantity:</label>
        <input type="number" id="orderQty" name="orderQty" min="1" required><br>
        <input type="submit" value="Place Order">
    </form>
    <h2>Available Products:</h2>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>In Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td><img src="<?php echo '../images/' . $product['itemImage']; ?>" alt="<?php echo $product['itemName']; ?>" width="100"></td>
                <td><?php echo $product['itemName']; ?></td>
                <td><?php echo $product['itemPrice']; ?></td>
                <td><?php echo $product['stockItemQty']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
