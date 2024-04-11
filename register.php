<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: home.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['email'])) {
        $name = $_POST['name'];
        $pass = $_POST['pass'];
        $email = $_POST['email'];

        if (!empty($name) && !empty($pass) && !empty($email)) {

            require 'db_info.php';
            try {
                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            } catch (Exception $mysqli_sql_exception) {
                $_SESSION['conn_error'] = TRUE;
                header("Location: register.php");
                exit;
            }
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $querry = "INSERT INTO `users` (`email`, `name`, `password`) VALUES ('$email', '$name', '$pass')";
                try {
                    $result = mysqli_query($conn, $querry);
                } catch (Exception $mysqli_sql_exception) {
                    $_SESSION["dup_entry"] = mysqli_error($conn);
                    $_SESSION["name"] = $name;
                    $_SESSION["pass"] = $pass;
                    mysqli_close($conn);
                    header("Location: register.php");
                    exit;
                }

                mysqli_close($conn);
                $_SESSION['reg_success'] = TRUE;
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['inv_email'] = TRUE;
                $_SESSION["name"] = $name;
                $_SESSION["pass"] = $pass;
                header("Location: register.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="loginStyle.css">
</head>

<body>
    <div class="form-container">
        <div class="error-message"><?php
        if (isset($_SESSION["conn_error"])) {    
            echo "we are facing some database issues.<br> try after sometime <br>";
            session_unset();
            session_destroy();
        }
       ?></div>
        <div class="error-message"><?php
        $set_name=$set_pass="";
        if (isset($_SESSION["dup_entry"])) {
            // echo "The email address is already taken<br>";
            echo $_SESSION["dup_entry"];
            $set_name=$_SESSION["name"];
            $set_pass=$_SESSION["pass"];
            session_unset();
            session_destroy();
        }
        ?></div>
        <div class="error-message"><?php
        if (isset($_SESSION["inv_email"])) {    
            echo "Please enter a valid Email-ID<br>";
            $set_name=$_SESSION["name"];
            $set_pass=$_SESSION["pass"];
            session_unset();
            session_destroy();
        }
       ?></div>
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" maxlength="30"
                value="<?php echo $set_name; ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" maxlength="40" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="text" id="password" name="pass" maxlength="16"
                value="<?php echo $set_pass; ?>" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <br><br>
    <div class="links">Already Registered?
      <a href="index.php">LogIn</a>
    </div>
    </div>
</body>
</html>