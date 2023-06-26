<?php
    include '../includes/auth.php';
    require_once("../connection/mysqli_conn.php");

    // Initialize the shopping cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // fetch the available products
    $sql = "SELECT 
                Item.itemID, Item.itemName, Item.itemDescription, Item.stockItemQty, Item.price, 
                Supplier.supplierID, Supplier.companyName, Supplier.contactName ,Supplier.contactNumber, Supplier.address,
                Item.ImageFile
            FROM 
                Item
            INNER JOIN 
                Supplier ON Item.supplierID = Supplier.supplierID
            WHERE
                Item.stockItemQty > 0";
    $result = $conn->query($sql);

    $orderDetails = [];
    $totalOrderAmount = 0;
    $message = "";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $_SESSION['items'][$row["itemID"]] = $row;
        }
    }

    // 定義showDetails函式
    function showDetails($itemID) {
        if(isset($_SESSION['items'][$itemID])) {
            $item = $_SESSION['items'][$itemID];
            $_SESSION['message'] = "ItemID: " . $item["itemID"] . "<br>itemName: " . $item["itemName"] . "<br>itemDescription: " . $item["itemDescription"] . "<br>stockItemQty: " . $item["stockItemQty"] . "<br>price: " . $item["price"];
            $_SESSION['message'] .= "<br><br>Supplier: " . $item["supplierID"] . "<br>companyName: " . $item["companyName"] . "<br>contactName: " . $item["contactName"] . "<br>contactNumber: " . $item["contactNumber"] . "<br>address: " . $item["address"];
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // If an item is added to the cart
        if(isset($_POST['addToCart'])) {
            $itemID = $_POST['itemID'];
            $orderQty = $_POST['orderQty'];

            // Add the item to the shopping cart
            $_SESSION['cart'][$itemID] = $orderQty;
        } 
        elseif(isset($_POST['itemID'])) {
            showDetails($_POST['itemID']);
        }
        elseif(isset($_POST['updateQty'])) {
            $itemID = $_POST['updateItemID'];
            $newQty = $_POST['updateQty'];
        
            // Update the quantity in the shopping cart
            $_SESSION['cart'][$itemID] = $newQty;
            
            // After update quantity, check if any item has 0 quantity, if yes, remove it
            foreach ($_SESSION['cart'] as $itemID => $qty) {
                if ($qty <= 0) {
                    unset($_SESSION['cart'][$itemID]);
                }
            }
        }
        // If item is removed from the cart
        elseif(isset($_POST['removeItem'])) {
            $itemID = $_POST['removeItemID'];
    
            // Remove the item from the shopping cart
            unset($_SESSION['cart'][$itemID]);
        }
        elseif(isset($_POST['clearCart'])) {
            // Clear the shopping cart
            $_SESSION['cart'] = [];
        }
        // If the order is placed
        elseif(isset($_POST['placeOrder'])) {
            // Get the submitted form data
            $purchaseManagerID = $_POST['purchaseManagerID'];
            $managerName = $_POST['managerName'];
            $deliveryAddress = $_POST['deliveryAddress'];
            $deliveryDate = $_POST['deliveryDate'];
            $orderDateTime = date('Y-m-d H:i:s');

            // Calculate the total order amount
            $totalOrderAmount = 0;
            foreach ($_SESSION['cart'] as $itemID => $orderQty) {
                $totalOrderAmount += $_SESSION['items'][$itemID]['price'] * $orderQty;
            }

            // Get the maximum orderID and add 1
            $result = mysqli_query($conn, "SELECT MAX(orderID) as maxOrderID FROM Orders");
            $row = mysqli_fetch_assoc($result);
            $newOrderID = $row['maxOrderID'] + 1;

            // Insert the order into the database
            $sql = "INSERT INTO Orders (orderID, purchaseManagerID, orderDateTime, deliveryAddress, deliveryDate) VALUES ('$newOrderID', '$purchaseManagerID', '$orderDateTime', '$deliveryAddress', '$deliveryDate')";
            if(mysqli_query($conn, $sql)) {  // Ensure this query has been executed
                $orderID = mysqli_insert_id($conn); // Get the ID of the newly created order
            }

            // For each item in the cart
            foreach ($_SESSION['cart'] as $itemID => $orderQty) {
                // Get the item details
                $result = mysqli_query($conn, "SELECT * FROM Item WHERE itemID = $itemID");
                $item = mysqli_fetch_assoc($result);
                $itemPrice = $item['price'];
                $newStockQty = $item['stockItemQty'] - $orderQty;

                // 在此处添加代码以计算折扣信息
                $discountInfo = callDiscountCalculatorAPI($totalOrderAmount);
                $discountRate = $discountInfo['DiscountRate'];
                $itemTotalAmount = $itemPrice * $orderQty;
                $itemDiscountAmount = $itemTotalAmount * $discountRate;
                $discountedItemPrice = $itemPrice - ($itemDiscountAmount / $orderQty);

                // Create the order item
                $sql = "INSERT INTO OrdersItem (orderID, itemID, orderQty, itemPrice) VALUES ($newOrderID, $itemID, $orderQty, $discountedItemPrice)";
                mysqli_query($conn, $sql);

                // Update the item stock quantity
                $sql = "UPDATE Item SET stockItemQty = $newStockQty WHERE itemID = $itemID";
                mysqli_query($conn, $sql);
            }

            // Clear the shopping cart
            $_SESSION['cart'] = [];

            if(mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Order placed successfully!";
            } else {
                $_SESSION['message'] = "Failed to place order!";
            }
        
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        }
    }

    function callDiscountCalculatorAPI($totalOrderAmount) {
        $url = "http://localhost:8000/api/discountCalculator?TotalOrderAmount=" . urlencode($totalOrderAmount);
        $ch = curl_init($url);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            // Handle error
            return [
                'error' => curl_error($ch),
            ];
        }
    
        curl_close($ch);
    
        return json_decode($response, true);
    }

    // Reset the result cursor
    mysqli_data_seek($result, 0);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>make order</title>
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../css/navbar.css">
  <link rel="stylesheet" type="text/css" href="../css/modal.css">
  <link rel="stylesheet" type="text/css" href="../css/make_order.css">
  <link rel="shortcut icon" href="../images/Logo3.png" type="image/x-icon">
</head>
<body>
  <?php include '../includes/header.php'; ?>
  <section>
    <div class="container">
        <div class="shopping-header">
            <h1>Your Shopping Cart</h1>
            <div class="shopping">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="quantity"><?php echo count($_SESSION['cart']); ?></span>
            </div>
        </div>
        <div class="list">
            <?php
            mysqli_data_seek($result, 0);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="item">';
                    echo '<div class="img-container">';
                    echo '<img src="../images/' . $row["ImageFile"] . '"/>';
                    echo '<div class="overlay">
                                <form method="POST">
                                    <input type="hidden" name="itemID" value="' . $row["itemID"] . '">
                                    <input class="details-btn" type="submit" value="Details">
                                </form>
                            </div>';
                    echo '</div>';
                    echo '<div class="title">' . $row["itemName"] . '</div>';
                    echo '<div class="price">$' . number_format($row["price"], 2) . '</div>';
                    echo '<div class="price"> Stock: ' . $row["stockItemQty"] . '</div>';
                    echo "<form method='POST'>";
                    echo "<input type='hidden' name='itemID' value='".$row["itemID"]."'>";
                    echo "<input class='count' type='number' name='orderQty' min='1' value='1' required> ";
                    echo "<input class='addToCart' type='submit' name='addToCart' value='Add to Cart'>";
                    echo "</form>";
                    echo '</div>';
                }
            } else {
                echo "No results found.";
            }
            ?>
        </div>
    </div>
    <div class="card">
        <h1>Cart</h1>
        <form method="POST">
            <input class="clear-Cart" type="submit" name="clearCart" value="Clear Cart">
        </form>
        <ul class="listCard">
            <?php if(!empty($_SESSION['cart'])): ?>
            <div class="list-card">
                <?php 
                    $totalPrice = 0;
                    foreach ($_SESSION['cart'] as $itemID => $qty):
                        $resultItem = mysqli_query($conn, "SELECT * FROM Item WHERE itemID = $itemID");
                        $item = mysqli_fetch_assoc($resultItem);
                        $itemName = $item['itemName'];
                        $itemImage = $item['ImageFile'];
                        $itemPrice = $item['price'];
                        $lineTotal = $itemPrice * $qty;
                        $totalPrice += $lineTotal;

                    // Calculate the discount
                    $discountRate = 0;
                    if ($totalPrice >= 10000) {
                        $discountRate = 0.13;
                    } elseif ($totalPrice >= 5000) {
                        $discountRate = 0.06;
                    } elseif ($totalPrice >= 3000) {
                        $discountRate = 0.03;
                    }

                    $discountAmount = $totalPrice * $discountRate;
                    $finalTotal = $totalPrice - $discountAmount;
                    $totalPrice = $finalTotal;
                ?>
                <li>
                    <div><img src='../images/<?php echo $itemImage; ?>' alt='<?php echo $itemName; ?>'></div>
                    <div><?php echo $itemName; ?></div>
                    <div>$ <?php echo number_format($lineTotal, 2); ?></div>
                    <form method="POST">
                        <button class="button-trash" type="submit" name="removeItem" value="">
                            <input type="hidden" name="removeItemID" value="<?php echo $itemID; ?>">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    <div>
                        <form method="POST">
                            <input type="hidden" name="updateItemID" value="<?php echo $itemID; ?>">
                            <input type="hidden" name="updateQty" value="<?php echo $qty - 1; ?>">
                            <button class="button-change" type="submit">-</button>
                        </form>
                        <form method="POST" id="updateForm<?php echo $itemID; ?>">
                            <input type="hidden" name="updateItemID" value="<?php echo $itemID; ?>">
                            <input class="count" type="number" name="updateQty" value="<?php echo $qty; ?>" min="1" onchange="submitForm('<?php echo $itemID; ?>')">
                        </form>
                        <form method="POST">
                            <input type="hidden" name="updateItemID" value="<?php echo $itemID; ?>">
                            <input type="hidden" name="updateQty" value="<?php echo $qty + 1; ?>">
                            <button class="button-change" type="submit">+</button>
                        </form>
                    </div>
                </li>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </ul>
        <div class="checkout">
            <div class="confirm-order">
                <form method="POST">
                    <label for="managerName">Manager Name:</label>
                    <input type="text" id="managerName" name="managerName" value="<?php echo $_SESSION['managerName']; ?>" readonly><br>
                    <label for="purchaseManagerID">Purchase Manager ID:</label>
                    <input type="text" id="purchaseManagerID" name="purchaseManagerID" value="<?php echo $_SESSION['purchaseManagerID']; ?>" readonly><br>
                    <label for="deliveryAddress">Delivery Address:</label>
                    <input type="text" id="deliveryAddress" name="deliveryAddress" required><br>
                    <label for="deliveryDate">Delivery Date:</label>
                    <input type="date" id="deliveryDate" name="deliveryDate" required><br>
                    <?php if(!empty($_SESSION['cart'])): ?>
                    <input class="placeOrder" type="submit" name="placeOrder" value="Place Order">
                    <?php endif; ?>
                </form>
            </div>
            <div>
                <?php if(!empty($_SESSION['cart'])): ?>
                    <div class="total">Total: <?php echo $totalPrice; ?></div>
                <?php else: ?>
                    <div class="total">Total: 0</div>
                <?php endif; ?>
                <div class="closeShopping">Close</div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    <?php include '../includes/modal.php'; ?>
    <script src="../js/make_order.js"></script>
</body>
</html>