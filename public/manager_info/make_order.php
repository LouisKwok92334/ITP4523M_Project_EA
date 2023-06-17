<?php
include '../../includes/auth.php';

// Database connection
// assuming that you have already configured these values
$host = 'localhost';
$db   = 'ProjectDB';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Generate an Order ID
        $stmt = $pdo->prepare('SELECT MAX(orderID) AS max FROM Orders');
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_id = $row['max'] + 1;

        // Insert a new row into the Orders table
        $stmt = $pdo->prepare('INSERT INTO Orders (orderID, purchaseManagerID, orderDateTime, deliveryAddress, deliveryDate) VALUES (?, ?, NOW(), ?, ?)');
        $stmt->execute([$order_id, $_POST['purchaseManagerID'], $_POST['deliveryAddress'], $_POST['deliveryDate']]);

        // For each selected product, insert a row into the OrderDetails table and update the stock
        $total_amount = 0;
        foreach ($_POST['orderQty'] as $product_id => $orderQty) {
            if ($orderQty > 0) {
                // Get the product info
                $stmt = $pdo->prepare('SELECT * FROM Item WHERE itemID = ?');
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                // Insert a row into the OrdersItem table
                $stmt = $pdo->prepare('INSERT INTO OrdersItem (orderID, itemID, orderQty, itemPrice) VALUES (?, ?, ?, ?)');
                $stmt->execute([$order_id, $product['itemID'], $orderQty, $product['price']]);

                // Update the stock
                $stmt = $pdo->prepare('UPDATE Item SET stockItemQty = stockItemQty - ? WHERE itemID = ?');
                $stmt->execute([$orderQty, $product['itemID']]);

                // Add to the total order amount
                $total_amount += $orderQty * $product['price'];
            }
        }

        // Commit the transaction
        $pdo->commit();

        echo "Order placed successfully!";
    } catch (\PDOException $e) {
        // Roll back the transaction if anything goes wrong
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>New Order</title>
</head>
<body>
<form action="make_order.php" method="post">
    <label for="purchaseManagerID">Purchase Manager ID:</label><br>
    <input type="number" id="purchaseManagerID" name="purchaseManagerID"><br>
    <label for="deliveryAddress">Delivery Address:</label><br>
    <input type="text" id="deliveryAddress" name="deliveryAddress"><br>
    <label for="deliveryDate">Delivery Date:</label><br>
    <input type="date" id="deliveryDate" name="deliveryDate"><br>
    
    <!-- You should generate these fields dynamically based on the products in your database -->
    <label for="product1">Product 1:</label><br>
    <input type="number" id="product1" name="orderQty[1]"><br>
    <label for="product2">Product 2:</label><br>
    <input type="number" id="product2" name="orderQty[2]"><br>
    <!-- ... -->
    
    <input type="submit" value="Submit">
</form>
</body>
</html>
