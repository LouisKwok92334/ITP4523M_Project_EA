<?php
    include '../includes/auth_check.php';
    require_once("../connection/mysqli_conn.php");

    function getItems($conn) {
        $sql = "SELECT itemID, itemName, ImageFile, price FROM Item";
        $result = mysqli_query($conn, $sql);
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $items;
    }

    function getOrders($conn) {
        $sql = "SELECT itemID, orderQty, itemPrice FROM OrdersItem";
        $result = mysqli_query($conn, $sql);
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $orders;
    }

    echo json_encode([
        "items" => getItems($conn),
        "orders" => getOrders($conn)
    ]);
?>