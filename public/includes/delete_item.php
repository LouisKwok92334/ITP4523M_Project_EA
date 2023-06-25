<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

$itemID = isset($_GET['itemID']) ? $_GET['itemID'] : die();

// Check if there are any related orders for the given itemID
$query_orders = "SELECT * FROM OrdersItem WHERE itemID=?";
$stmt_orders = $conn->prepare($query_orders);
$stmt_orders->bind_param('i', $itemID);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

// If there are no related orders, proceed with deleting the item
if ($result_orders->num_rows === 0) {
    $query_delete = "DELETE FROM item WHERE itemID=?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param('i', $itemID);

    if ($stmt_delete->execute()) {
        echo "Item deleted.";
    } else {
        echo "Item not deleted.";
    }

    $stmt_delete->close();
} else {
    echo "Cannot delete item with existing related orders.";
}

$stmt_orders->close();
$conn->close();
?>