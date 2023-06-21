<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include '../includes/auth_check.php';
    require_once("../connection/mysqli_conn.php");

    $purchaseManagerID = $_SESSION['purchaseManagerID'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $contactNumber = $_POST['contactNumber'];
    $warehouseAddress = $_POST['warehouseAddress'];

    // 首先检查当前密码是否正确
    $query = "SELECT password FROM PurchaseManager WHERE purchaseManagerID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $purchaseManagerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['password'] === $currentPassword) {
            // 如果当前密码正确，更新信息
            $query = "UPDATE PurchaseManager SET password = ?, contactNumber = ?, warehouseAddress = ? WHERE purchaseManagerID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $newPassword, $contactNumber, $warehouseAddress, $purchaseManagerID);
            mysqli_stmt_execute($stmt);
            echo json_encode(["success" => true, "message" => "Success"]);
        } else {
            // 如果当前密码不正确，返回错误消息
            http_response_code(400);
            echo json_encode(["success" => false, "error" => "Incorrect current password"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Not found"]);
    }
?>
