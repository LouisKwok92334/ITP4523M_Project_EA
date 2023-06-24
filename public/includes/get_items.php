<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

$sql = "SELECT itemID, supplierID, itemName, ImageFile, itemDescription, stockItemQty, price FROM Item";
$result = $conn->query($sql);
$items = array();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    array_push($items, $row);
  }
}

echo json_encode($items);

$conn->close();
?>