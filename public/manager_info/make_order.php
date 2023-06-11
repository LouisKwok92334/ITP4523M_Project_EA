<?php
include '../../includes/auth.php';

// 從這裡開始編寫你的其他程式碼...

// 可能是一個訂單表單
echo '<h1>Make an Order</h1>';
echo '<form action="submit_order.php" method="post">';
echo '<label for="product">Product:</label><br>';
echo '<input type="text" id="product" name="product" required><br>';
echo '<label for="quantity">Quantity:</label><br>';
echo '<input type="number" id="quantity" name="quantity" required><br>';
echo '<input type="submit" value="Submit Order">';
echo '</form>';