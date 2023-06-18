<?php
  session_start();

  // Database connection
  require_once("connection/mysqli_conn.php");

  if (isset($_POST['username'], $_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if user is a supplier
    $result = mysqli_query($conn, "SELECT * FROM Supplier WHERE supplierID = '$username' AND password = '$password'");
    $user = mysqli_fetch_assoc($result);

    if ($user) {
      $_SESSION['loggedin'] = true;
      $_SESSION['role'] = 'supplier';
      header('Location: index.php');
      exit;
    } else {
      // Check if user is a purchase manager
      $result = mysqli_query($conn, "SELECT * FROM PurchaseManager WHERE purchaseManagerID = '$username' AND password = '$password'");
      $user = mysqli_fetch_assoc($result);

      if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'purchase_manager';
        // Store the Purchase Manager's ID and Name in the session
        $_SESSION['purchaseManagerID'] = $user['purchaseManagerID'];  // replace with the actual column name in your DB
        $_SESSION['managerName'] = $user['managerName'];  // replace with the actual column name in your DB
        header('Location: index.php');
        exit;
      } else {
        // Incorrect login
        echo "Incorrect username or password!";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css" />
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>


