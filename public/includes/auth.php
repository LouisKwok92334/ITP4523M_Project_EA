<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 如果用戶沒有登入，則將他們重定向到登入頁面
    header('Location: ../login.php');
    exit;
}

$allowed_pages = [];

// 根據不同的角色設定可以訪問的頁面
if ($_SESSION['role'] == 'supplier') {
    $allowed_pages = ['index.php', 'edit_item.php', 'delete_item.php', 'generate_report.php', 'insert_item.php'];
} elseif ($_SESSION['role'] == 'purchase_manager') {
    $allowed_pages = ['index.php', 'delete_order.php', 'make_order.php', 'update_info.php', 'view_order.php'];
}

// 取得當前頁面的名稱
$current_page = basename($_SERVER['PHP_SELF']);

// 檢查當前頁面是否在允許的頁面列表中
if (!in_array($current_page, $allowed_pages)) {
    // 如果不在，則將用戶重定向到 index.php
    header('Location: ../index.php');
    exit;
}