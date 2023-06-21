<?php
    include '../includes/auth_check.php';
    require_once("../connection/mysqli_conn.php");

    $purchaseManagerID = $_SESSION['purchaseManagerID'];
    $query = "SELECT contactNumber, warehouseAddress FROM PurchaseManager WHERE purchaseManagerID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $purchaseManagerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Not found"]);
    }
?>
