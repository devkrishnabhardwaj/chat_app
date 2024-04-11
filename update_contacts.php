<?php
session_start();
    if(!isset($_SESSION["email"])) {
        exit;
    }
?>
<div class="contacts-head">Active Chats</div>
<div id="<?php
    if(!isset($_GET['id'])){
        echo 'active_chat';
    }
?>" 
class="contact-item" onclick="chatWith('')"><div class="unseen"></div>GLOBAL CHAT</div>
<?php
    require 'db_info.php';

    try {
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    } catch (Exception $mysqli_sql_exception) {
        echo "error happened:<br> ";
        echo mysqli_connect_error();
        exit;
    }

    $querry = "SELECT * FROM user_connections WHERE user_id = " . $_SESSION['user_id'] . " OR connected_user_id =" . $_SESSION['user_id'];
    $result = mysqli_query($conn, $querry);

    
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $_SESSION['user_id'];
            if ($id == $row['user_id']) {
                $id = $row['connected_user_id'];
            } else {
                $id = $row['user_id'];
            }
            $querry = "SELECT * FROM users WHERE user_id = '$id'";
            $resutl_2 = mysqli_query($conn, $querry);
            $row_2 = mysqli_fetch_assoc($resutl_2);
            
            $unseen = "";
            $s_id = $_SESSION['user_id'];
            $querry_2 = "SELECT * FROM messages WHERE seen ='no' AND sender_id='$id' AND receiver_id ='$s_id'";                
            $result_3 = mysqli_query($conn, $querry_2);
            if(mysqli_num_rows($result_3) > 0){
                $unseen = mysqli_num_rows($result_3);
                $unseen = ($unseen<10)?('0'.$unseen):($unseen);
            }

            $contact_status="";
            if(isset($_GET['id']) && $_GET['id']==$id){
                $contact_status = 'active_chat';
            }
            echo "<div class=\"contact-item \" id=\"$contact_status\" onclick=\"chatWith('" . $id . "')\">" . $row_2['name'];
            if($unseen!=""){
                echo "<div class='unseen'>&nbsp $unseen </div>";
            }
            echo "</div>";
        }
    }
    mysqli_close($conn);
?>