<?php
  session_start();

  // Database connection
  require_once("connection/mysqli_conn.php");

  $messageType = '';
  $messageContent = '';

  if (isset($_COOKIE['username'])) {
    $remembered_username = $_COOKIE['username'];
  } else {
    $remembered_username = '';
  }

  if (isset($_POST['username'], $_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if user is a supplier
    $result = mysqli_query($conn, "SELECT * FROM Supplier WHERE supplierID = '$username' AND password = '$password'");
    $user = mysqli_fetch_assoc($result);

    if ($user) {
      $_SESSION['loggedin'] = true;
      $_SESSION['role'] = 'supplier';
      $_SESSION['supplierID'] = $user['supplierID'];

    if (isset($_POST['remember'])) {
        setcookie('username', $username, time() + 60 * 60 * 24 * 30);
    }
    
    $_SESSION['messageType'] = 'success';
    $_SESSION['messageContent'] = 'Welcome Yummy Restaurant ^__^';
    
    $_SESSION['shouldRedirect'] = true;  // Set the flag
    } else {
      // Check if user is a purchase manager
      $result = mysqli_query($conn, "SELECT * FROM PurchaseManager WHERE purchaseManagerID = '$username' AND password = '$password'");
      $user = mysqli_fetch_assoc($result);

      if ($user) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'purchase_manager';
        $_SESSION['purchaseManagerID'] = $user['purchaseManagerID'];
        $_SESSION['managerName'] = $user['managerName'];
    
        if (isset($_POST['remember'])) {
            setcookie('username', $username, time() + 60 * 60 * 24 * 30);
        }
        
        $_SESSION['messageType'] = 'success';
        $_SESSION['messageContent'] = 'Welcome Yummy Restaurant ^__^';
        
        $_SESSION['shouldRedirect'] = true;  // Set the flag
    } else {
          // Incorrect login
          $_SESSION['messageType'] = 'error';
          $_SESSION['messageContent'] = 'Incorrect username or password';
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
  <script src="https://kit.fontawesome.com/22b529d74e.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/login.css" />
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="shortcut icon" href="./images/Logo3.png" type="image/x-icon">
</head>
<body>
  <div class="wrapper">
    <div class="restaurant-bg"></div>
    <img src="./images/Logo.png" class="logo" alt="Logo">
    <div class="login-form">
      <form action="login.php" method="post">
        <div class="login-display">
          <div>
            <div class="title">Yummy <span class="highlight">Restaurant</span> Group</div>
          </div>
          <div>
            <div class="subtitle">Please log in for an account</div>
            <label for="username" class="label-blue">Username:</label>
            <input type="text" id="username" name="username" class="rounded-input" required value="<?php echo htmlspecialchars($remembered_username); ?>">
            <label for="password" class="label-blue">Password:</label>
            <input type="password" id="password" name="password" class="rounded-input" required>
            <div class="remember-display">
              <input type="checkbox" name="remember" class="remember">
              <span class="remember">Remember me</span>
              <span class="forgot">Forgot password?</span>
            </div>
            <div id="login_message">
                <?php 
                if (isset($_SESSION['messageType']) && $_SESSION['messageType'] === 'error'): ?>
                <div class="error_display">
                    <img class="error_icon" src="https://mweb-cdn.karousell.com/build/alert-error-2kjjYk_Edi.svg" title="">
                    <span class="massage"><?php echo htmlspecialchars($_SESSION['messageContent']); ?></span>
                </div>
                <?php 
                elseif (isset($_SESSION['messageType']) && $_SESSION['messageType'] === 'success'): ?>
                <div class="success_display">
                    <i class="success_icon fa-solid fa-circle-check"></i>
                    <span class="massage"><?php echo htmlspecialchars($_SESSION['messageContent']); ?></span>
                </div>
                <script>
                  setTimeout(function() {
                      window.location.href = 'index.php';
                  }, 2000);  // Redirect after 2 seconds
                </script>
                <?php 
                endif;
                // Remove the message from the session
                unset($_SESSION['messageType']);
                unset($_SESSION['messageContent']);
                ?>
            </div>
            <div>
               <input type="submit" class="login" value="Login">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
</body>
</html>