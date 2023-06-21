<?php
    include '../includes/auth_check.php';
    require_once("../connection/mysqli_conn.php");

    $sql = "SELECT
            Orders.orderID,
            Supplier.supplierID,
            Supplier.companyName,
            Supplier.contactName,
            Supplier.contactNumber,
            Orders.orderDateTime,
            Orders.deliveryAddress,
            Orders.deliveryDate,
            Item.itemID,
            Item.ImageFile,
            Item.itemName,
            OrdersItem.orderQty,
            OrdersItem.orderQty * OrdersItem.itemPrice as TotalOrderAmount
        FROM
            Orders
        JOIN
            OrdersItem ON Orders.orderID = OrdersItem.orderID
        JOIN
            Item ON OrdersItem.itemID = Item.itemID
        JOIN
            Supplier ON Item.supplierID = Supplier.supplierID
        ORDER BY Supplier.supplierID";
    $result = $conn->query($sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    echo json_encode($data);
?>