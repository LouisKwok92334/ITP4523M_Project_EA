<?php
include '../includes/auth_check.php';
require_once("../connection/mysqli_conn.php");

if (isset($_SESSION['supplierID'])) {
    // If the user is logged in, return their supplier ID
    echo json_encode(array(
        'status' => 'success',
        'supplierID' => $_SESSION['supplierID']
    ));
} else {
    // If the user is not logged in, return an error
    http_response_code(401);
    echo json_encode(array('status' => 'error', 'message' => 'Not logged in'));
}
?>
