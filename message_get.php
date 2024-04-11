<?php
session_start();
if(!isset($_SESSION["email"])){
    header("Location: index.php");
    exit;
}
$s_id=$_SESSION['user_id'];

require 'db_info.php';

try {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
} catch (Exception $mysqli_sql_exception) {
    echo "error happened:<br> ";
    echo mysqli_connect_error();
    exit;
}
if(isset($_GET['id'])){
    $r_id = $_GET['id'];
    $sql ="UPDATE messages SET seen = 'yes' WHERE sender_id = '$r_id' AND receiver_id= '$s_id' ";
    try{
        mysqli_query( $conn, $sql );
    } catch(Exception $mysqli_sql_exception) {
        exit;
    }
    $querry = "SELECT * FROM messages WHERE (sender_id='$s_id' AND receiver_id='$r_id') OR(sender_id='$r_id' AND receiver_id='$s_id') ORDER BY sent_at";
    $result = mysqli_query($conn, $querry);
    $querry_2 = "SELECT * FROM users WHERE user_id='$r_id'";
    $result_2 = mysqli_query($conn, $querry_2);
    $r_name = mysqli_fetch_array($result_2)["name"];
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            if($row["sender_id"] == $r_id){
                echo "<div class='message'><strong>$r_name:</strong><div>".$row['message']."</div></div>";
            }
            else{
                echo "<div class='mymessage'><strong>You:</strong><div>".$row['message']."</div>";
                if($row['seen']=='yes'){
                    echo "<div class='seen'>seen by $r_name</div>";
                } else{
                    echo "<div class='seen'>unseen</div>";
                }
                echo "</div>";
            }
        }
    }
} else{
    $querry = "SELECT * FROM global_messages ORDER BY sent_at";
    $result = mysqli_query($conn, $querry);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            if($row["sender_id"] == $s_id){
                echo "<div class='mymessage'><strong>You:</strong><div>".$row['message']."</div></div>";
            }
            else{
                echo "<div class='message'><strong>".$row['name'].":</strong><div>".$row['message']."</div></div>";
            }
        }
    }
}
mysqli_close($conn);
?>