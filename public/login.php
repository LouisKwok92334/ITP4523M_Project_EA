<?php
  session_start();

  // Database connection
  $host = 'localhost';
  $db   = 'ProjectDB';
  $user = 'root'; // replace with your MySQL username
  $pass = '';     // replace with your MySQL password
  $charset = 'utf8mb4';

  $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
  try {
     $pdo = new PDO($dsn, $user, $pass);
  } catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }

  if (isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if user is a supplier
    $stmt = $pdo->prepare('SELECT * FROM Supplier WHERE supplierID = ? AND password = ?');
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
      $_SESSION['loggedin'] = true;
      $_SESSION['role'] = 'supplier';
      header('Location: index.php');
      exit;
    } else {
      // Check if user is a purchase manager
      $stmt = $pdo->prepare('SELECT * FROM PurchaseManager WHERE purchaseManagerID = ? AND password = ?');
      $stmt->execute([$username, $password]);
      $user = $stmt->fetch();

      if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'purchase_manager';
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


