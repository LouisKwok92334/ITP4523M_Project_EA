<?php
    include '../includes/auth_check.php';
    require_once("../connection/mysqli_conn.php");

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

    $products = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
?>