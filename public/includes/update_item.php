<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

$itemID = $_POST['itemID'];
$supplierID = $_SESSION['supplierID'];
$itemName = $_POST['itemName'];
$itemDescription = $_POST['itemDescription'];
$stockItemQty = $_POST['stockItemQty'];
$price = $_POST['price'];

// 处理图片上传
$imagePath = '';
if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $imagePath = $target_file;
    } else {
        echo "Error uploading image. Item not updated.";
        exit();
    }
}

$query = "UPDATE Item SET supplierID=?, itemName=?, itemDescription=?, stockItemQty=?, price=?";

if ($imagePath !== '') {
    $query .= ", ImageFile=?";
}

$query .= " WHERE itemID=?";

$stmt = $conn->prepare($query);

if ($imagePath !== '') {
    $stmt->bind_param('sssdssi', $supplierID, $itemName, $itemDescription, $stockItemQty, $price, $imagePath, $itemID);
} else {
    $stmt->bind_param('sssdii', $supplierID, $itemName, $itemDescription, $stockItemQty, $price, $itemID);
}

if ($stmt->execute()) {
    // Check affected rows
    $affected_rows = $stmt->affected_rows;
    if ($affected_rows > 0) {
        echo "Item updated.";
    } else {
        echo "No changes were made.";
    }
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>