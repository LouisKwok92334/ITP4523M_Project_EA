<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

$itemID = isset($_GET['itemID']) ? $_GET['itemID'] : die();

$query = "DELETE FROM items WHERE itemID=?";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $itemID);

if ($stmt->execute()) {
    echo "Item deleted.";
} else {
    echo "Item not deleted.";
}

$stmt->close();
$conn->close();
?>