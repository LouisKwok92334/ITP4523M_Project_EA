<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

// Get the order ID and order item ID from the request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
if (isset($data['orderID']) && isset($data['itemID'])) {
    $orderID = intval($data['orderID']);
    $itemID = intval($data['itemID']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Missing orderID or itemID']);  // Updated
    exit();
}

// First, start a transaction
$conn->begin_transaction();

try {
    // Get the quantity of the order item before deletion
    $result = $conn->query("SELECT * FROM OrdersItem WHERE orderID = $orderID AND itemID = $itemID");
    $orderItem = $result->fetch_assoc();
    $quantity = $orderItem['orderQty'];

    // Delete the specified order item from the OrdersItem table
    $conn->query("DELETE FROM OrdersItem WHERE orderID = $orderID AND itemID = $itemID");

    // Check if there is still any order item related to the order
    $result = $conn->query("SELECT * FROM OrdersItem WHERE orderID = $orderID");

    if ($result->num_rows == 0) {
        // Now we can safely delete the order from Orders table
        $conn->query("DELETE FROM Orders WHERE orderID = $orderID");
    }

    // Update the stock quantity for the item
    $conn->query("UPDATE Item SET stockItemQty = stockItemQty + $quantity WHERE itemID = $itemID");

    // Commit the transaction
    $conn->commit();
} catch (Exception $e) {
    // If we catch an exception, rollback the transaction
    $conn->rollback();
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

echo json_encode(array('status' => 'success'));
?>
