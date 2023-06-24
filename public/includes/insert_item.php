<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

$response = [];

// Get the current user's supplierID
$supplierID = $_SESSION['supplierID'];
$response['supplierID'] = $supplierID;

// Generate a new itemID
$result = $conn->query("SELECT MAX(itemID) AS maxItemID FROM Item");
$row = $result->fetch_assoc();
$itemID = $row['maxItemID'] + 1;
$response['itemID'] = $itemID;

// Get other item information from the request body
$itemName = $_POST['itemName'];
$itemDescription = $_POST['itemDescription'];
$stockItemQty = (int)$_POST['stockItemQty'];
$price = (float)$_POST['price'];
$response['itemName'] = $itemName;
$response['itemDescription'] = $itemDescription;
$response['stockItemQty'] = $stockItemQty;
$response['price'] = $price;

// Upload image
if ($_FILES['ImageFile']['error'] === UPLOAD_ERR_OK) {
    $ImageFile = $_FILES['ImageFile']['name'];
    move_uploaded_file($_FILES['ImageFile']['tmp_name'], '../images/' . $ImageFile);
    $response['ImageFile'] = $ImageFile;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Failed to upload image']);
    exit();
}

// Insert the item into the database
$stmt = $conn->prepare("INSERT INTO Item (itemID, supplierID, itemName, ImageFile, itemDescription, stockItemQty, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssdi", $itemID, $supplierID, $itemName, $ImageFile, $itemDescription, $stockItemQty, $price);

if ($stmt->execute()) {
  $response['status'] = 'success';
} else {
  http_response_code(500);
  echo json_encode(['error' => $stmt->error]);
  exit();
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>