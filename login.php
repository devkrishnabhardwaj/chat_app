<?php
session_start();
if (isset($_SESSION["email"])) {
  header("Location: home.php");
  exit;
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST["email"]) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    require 'db_info.php';

    try {
      $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    } catch (Exception $mysqli_sql_exception) {
      $_SESSION['conn_error'] = TRUE;
      header("Location: login.php");
      exit;
    }
    $valid_password = "";
    $valid_username = "";
    $user_id ="";
    $querry = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $querry);
    if (mysqli_num_rows($result) == 1) {
      while($row = mysqli_fetch_assoc($result)) {
        $valid_password = $row["password"];
        $valid_username = $row["name"];
        $user_id = $row["user_id"];
      }
      if($pass == $valid_password) {
        mysqli_close($conn);
        $_SESSION["email"] = $email;
        $_SESSION["username"] = $valid_username;
        $_SESSION["user_id"] = $user_id;
        header("Location: home.php");
        exit;
      }
      else {
        mysqli_close($conn);
        $_SESSION["inv_pass"] = $valid_username;
        $_SESSION["name"] = $email;
        header("Location: login.php");
        exit;
      } 
    } 
    else {
      mysqli_close($conn);
      $_SESSION["inv_email"] = $email;
      header("Location: login.php");
      exit;
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
  <link rel="stylesheet" type="text/css" href="loginStyle.css">
</head>

<body>
  <div class="form-container">
    <div class="success-message"><?php
      if (isset($_SESSION['reg_success'])) {
        echo "Registration Successful. <br>";
        session_unset();
        session_destroy();
      }
      ?></div>
    <div class="error-message"><?php
      if (isset($_SESSION['conn_error'])) {
        echo "we are facing some database issues.<br> try after sometime";
        session_unset();
        session_destroy();
      }
      ?></div>
    <div class="error-message"><?php
      if (isset($_SESSION['inv_email'])) {
        echo "The email <br>".$_SESSION['inv_email']."  is Not registered.";
        session_unset();
        session_destroy();
      }
      ?></div>
    <h2>Login</h2>
    <form method="post" action="login.php">
      <div>
        <label for="username">Username:</label>
        <input type="email" id="username" name="email" value ="<?php
        if (isset($_SESSION["name"])) {
          echo $_SESSION["name"];
        }?>" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="pass" required>
      </div>
      <div class="error-message"><?php
        if (isset($_SESSION['inv_pass'])) {
          echo "you typed wrong Password ".$_SESSION['inv_pass'];
          session_unset();
          session_destroy();
        }
        ?></div>
      <button type="submit">Login</button>
    </form>
    <br><br>
    <div class="links">
        Don't have account? 
      <a href="register.php">Register</a>
    </div>
  </div>
</body>
</html>