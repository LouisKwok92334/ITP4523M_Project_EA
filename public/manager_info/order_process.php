<?php
    include '../includes/auth.php';
    require_once("../connection/mysqli_conn.php");

    // get the submitted form data
    $purchaseManagerID = $_POST['purchaseManagerID'];
    $managerName = $_POST['managerName'];
    $deliveryAddress = $_POST['deliveryAddress'];
    $deliveryDate = $_POST['deliveryDate'];
    $itemID = $_POST['itemID'];
    $orderQty = $_POST['orderQty'];

    // get the item details
    $result = mysqli_query($conn, "SELECT * FROM Item WHERE itemID = $itemID");
    $item = mysqli_fetch_assoc($result);
    $itemPrice = $item['price'];
    $newStockQty = $item['stockItemQty'] - $orderQty;

    // create the order
    $orderDateTime = date('Y-m-d H:i:s');
    $sql = "INSERT INTO Orders (purchaseManagerID, orderDateTime, deliveryAddress, deliveryDate) VALUES ($purchaseManagerID, '$orderDateTime', '$deliveryAddress', '$deliveryDate')";
    mysqli_query($conn, $sql);
    $orderID = mysqli_insert_id($conn); // get the ID of the newly created order

    // create the order item
    $sql = "INSERT INTO OrdersItem (orderID, itemID, orderQty, itemPrice) VALUES ($orderID, $itemID, $orderQty, $itemPrice)";
    mysqli_query($conn, $sql);

    // update the item stock quantity
    $sql = "UPDATE Item SET stockItemQty = $newStockQty WHERE itemID = $itemID";
    mysqli_query($conn, $sql);

    // redirect to a success page
    header("Location: order_success.php");
?>
