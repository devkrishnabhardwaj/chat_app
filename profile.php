<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
  <div class="back-btn">
    <a href="home.php">back</a>
  </div>
<?php
require 'db_info.php';
    try {
      $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    } catch (Exception $mysqli_sql_exception) {
      echo "<div class='error-message'>we are facing some database issues.<br> try after sometime</div>";
      exit;
    }

if (isset($_GET['email'])) {
    $email = mysqli_real_escape_string($conn, $_GET['email']);

    $querry = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $querry);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        echo "<div class='profile'>";
        echo "<h1>User Profile</h1>";
        echo "<div class='profile-info'>";
        echo "<div><strong>Name:</strong> {$user['name']}</div>";
        echo "<div><strong>Email:</strong> {$user['email']}</div>";
        echo "<div><strong>Bio:</strong>{$user['bio']}</div>";
        echo "</div>"; 
        if(isset($_SESSION['email'])){
          if($user['email'] == $_SESSION['email']){
            echo  '<div class="edit-btn"><a href="edit_profile.php">Edit</a></div>';
          }
          else{
            echo  '<div class="edit-btn"><a href="connect.php?id='.htmlspecialchars($user['user_id']).'">Message</a></div>';
          }
        }
        echo "</div>"; 
    } else {
        echo "<div class='error-message'>User not found.</div>";
    }
    mysqli_free_result($result);
} else {
    echo "<div class='error-message'>Email parameter is missing.</div>";
}
mysqli_close($conn);
?>
</body>
</html>