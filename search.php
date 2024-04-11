<?php
session_start();
if (!isset($_GET['str'])) {
    exit;
}
echo "<div class='profile-parent'>";

$str = $_GET['str'];

require 'db_info.php';

try {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
} catch (Exception $mysqli_sql_exception) {
    echo "error happened:<br> ";
    echo mysqli_connect_error();
    exit;
}

$querry = "SELECT * FROM users WHERE name LIKE '%" . $str . "%'" . " OR email ='$str'";
$result = mysqli_query($conn, $querry);
$flag=0;
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // echo "<a href='profile.php?email=".htmlspecialchars($row['email'])."'>".$row["name"]."</a><br>";
        if ($row['email'] != $_SESSION['email']) {
            $flag= 1;
            echo "<div class='profile'>";
            echo "<div class='profile-info'>";
            echo "<div class='name'><strong>Name:</strong> {$row['name']}</div>";
            // echo "<div><strong>Email:</strong> {$row['email']}</div>";
            echo "</div>";
            echo "<div class='profile-info'>";
            echo '<div class="see"><a href="profile.php?email=' . htmlspecialchars($row['email']).'">Profile</a></div>';
            echo "</div>";
            echo "<div class='profile-info'>";
            echo '<div class="msg"><a href="connect.php?id=' . htmlspecialchars($row['user_id']).'">Message</a></div>';
            echo "</div>";
            echo "</div>";
        }
    }
} 
if($flag==0){

    echo "<div class='profile'><strong>NO USER FOUND</strong></div>";
}
echo "</div>";
?>
<style>
    .profile-parent {
        height: auto;
        margin: 0;
        font-family: Arial, sans-serif;
        max-width: 100%;
        height: 100vh;
        overflow-y: scroll;
        padding: 10px;
    }
    
    .profile {
        display: flex;
        margin-left: 10px;
        width: 1100px;
        background-color: #f0f0f0;
        padding: 8px;
        border-radius: 5px;
        /* cursor: pointer; */
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .profile-info {
        margin-top: 20px;
    }

    .profile-info .name {
        margin-bottom: 10px;
    }
    .edit-btn {
        /* text-align: right; */
        cursor: pointer;
    }
    
    .see a,.msg a, .name {
        text-decoration: underline;
        color: #007bff;
        font-weight: bold;
        padding: 10px 20px;
        border: 2px solid #007bff;
        border-radius: 5px;
        background-color: #ffffff;
        display: inline-block;
    }
    .name {
        text-decoration: none;
        width: 800px;

    }
    .see{
        margin-left: 50px;
    }
    .msg{
        margin-left: 5px;
    }

    .see a:hover,.msg a:hover {
        background-color: #007bff;
        color: #ffffff;
    }
    .profile-info strong {
        font-weight: bold;
        margin-right: 5px;
    }
</style>