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
<html>
<head>
    <title>Login</title>
    <!-- Add your CSS file here -->
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #ccc;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
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


