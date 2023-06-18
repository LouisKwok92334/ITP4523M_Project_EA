<?php
    include '../includes/auth.php';
    require_once("../connection/mysqli_conn.php");

    // Initialize the shopping cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // fetch the available products
    $sql = "SELECT itemID, supplierID, itemName, ImageFile, itemDescription, stockItemQty, price FROM Item WHERE stockItemQty > 0";
    $result = $conn->query($sql);

    $reviewOrder = false;
    $orderDetails = [];
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // If an item is added to the cart
        if(isset($_POST['addToCart'])) {
            $itemID = $_POST['itemID'];
            $orderQty = $_POST['orderQty'];

            // Add the item to the shopping cart
            $_SESSION['cart'][$itemID] = $orderQty;

            $message = "Item added to cart!";
        }
        elseif(isset($_POST['updateQty'])) {
            $itemID = $_POST['updateItemID'];
            $newQty = $_POST['newQty'];
    
            // Update the quantity in the shopping cart
            $_SESSION['cart'][$itemID] = $newQty;
    
            // Update message
            $message = "Item quantity updated!";
        }
        // If item is removed from the cart
        elseif(isset($_POST['removeItem'])) {
            $itemID = $_POST['removeItemID'];
    
            // Remove the item from the shopping cart
            unset($_SESSION['cart'][$itemID]);
    
            // Update message
            $message = "Item removed from cart!";
        }
        // If order review is confirmed
        elseif(isset($_POST['confirm'])) {
            $reviewOrder = true;
            $orderDetails = $_POST;
        }
        // If the order is placed
        elseif(isset($_POST['placeOrder'])) {
            // Get the submitted form data
            $purchaseManagerID = $_POST['purchaseManagerID'];
            $managerName = $_POST['managerName'];
            $deliveryAddress = $_POST['deliveryAddress'];
            $deliveryDate = $_POST['deliveryDate'];

            // Create the order
            $orderDateTime = date('Y-m-d H:i:s');
            $sql = "INSERT INTO Orders (purchaseManagerID, orderDateTime, deliveryAddress, deliveryDate) VALUES ('$purchaseManagerID', '$orderDateTime', '$deliveryAddress', '$deliveryDate')";
            if(mysqli_query($conn, $sql)) {
                $orderID = mysqli_insert_id($conn); // Get the ID of the newly created order

                // For each item in the cart
                foreach ($_SESSION['cart'] as $itemID => $orderQty) {
                    // Get the item details
                    $result = mysqli_query($conn, "SELECT * FROM Item WHERE itemID = $itemID");
                    $item = mysqli_fetch_assoc($result);
                    $itemPrice = $item['price'];
                    $newStockQty = $item['stockItemQty'] - $orderQty;

                    // Create the order item
                    $sql = "INSERT INTO OrdersItem (orderID, itemID, orderQty, itemPrice) VALUES ($orderID, $itemID, $orderQty, $itemPrice)";
                    mysqli_query($conn, $sql);

                    // Update the item stock quantity
                    $sql = "UPDATE Item SET stockItemQty = $newStockQty WHERE itemID = $itemID";
                    mysqli_query($conn, $sql);
                }

                // Clear the shopping cart
                $_SESSION['cart'] = [];

                $message = "Order placed successfully!";
            } else {
                $message = "Failed to place order!";
            }
        }
    }

    // Reset the result cursor
    mysqli_data_seek($result, 0);
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
        img {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <a href="../index.php">Back</a>
    <h2>Shopping Cart</h2>
    <?php if(!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $itemID => $qty): ?>
                <tr>
                    <td><?php echo $itemID; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="updateItemID" value="<?php echo $itemID; ?>">
                            <input type="number" name="newQty" value="<?php echo $qty; ?>" min="1">
                            <input type="submit" name="updateQty" value="Update Quantity">
                        </form>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="removeItemID" value="<?php echo $itemID; ?>">
                            <input type="submit" name="removeItem" value="Remove Item">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST">
            <input type="submit" name="clearCart" value="Clear Cart">
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <h2>Available Products:</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // reset result cursor
            mysqli_data_seek($result, 0);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["itemID"] . "</td>";
                    echo "<td>" . $row["supplierID"] . "</td>";
                    echo "<td>" . $row["itemName"] . "</td>";
                    echo "<td><img src='../images/" . $row["ImageFile"] . "' alt='" . $row["itemName"] . "'></td>";
                    echo "<td>" . $row["itemDescription"] . "</td>";
                    echo "<td>" . $row["stockItemQty"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>";
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='itemID' value='".$row["itemID"]."'>";
                    echo "<input type='number' name='orderQty' min='1' required>";
                    echo "<input type='submit' name='addToCart' value='Add to Cart'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No results found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <?php if(!empty($_SESSION['cart'])): ?>
        <h2>Confirm Order:</h2>
        <form method="POST">
            <!-- Assume that managerName and purchaseManagerID come from the logged-in user -->
            <input type="hidden" name="purchaseManagerID" value="<?php echo $_SESSION['purchaseManagerID']; ?>">
            <input type="hidden" name="managerName" value="<?php echo $_SESSION['managerName']; ?>">

            <label for="deliveryAddress">Delivery Address:</label>
            <input type="text" id="deliveryAddress" name="deliveryAddress" required><br>
            <label for="deliveryDate">Delivery Date:</label>
            <input type="date" id="deliveryDate" name="deliveryDate" required><br>
            <input type="submit" name="placeOrder" value="Place Order">
        </form>
    <?php endif; ?>
</body>

</html>