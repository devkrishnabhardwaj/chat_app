<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}
if (!isset($_POST["message"])) {
    exit;
}
$s_id = $_SESSION['user_id'];
$name = $_SESSION['username'];
$message = htmlspecialchars($_POST["message"]);

require 'db_info.php';

try {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
} catch (Exception $mysqli_sql_exception) {
    echo "error happened:<br> ";
    echo mysqli_connect_error();
    exit;
}
if ($_POST['r_id'] == "") {

    $querry = "INSERT INTO global_messages (sender_id, name, message) VALUES ('$s_id','$name','$message')";
    try{
        mysqli_query( $conn, $querry);
    } catch (Exception $mysqli_sql_exception) {
        mysqli_close( $conn);
        exit;
    }
} else {
    $r_id = $_POST['r_id'];
    $querry ="INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$s_id','$r_id','$message')";
    try{
        mysqli_query($conn, $querry);
    } catch(Exception $mysqli_sql_exception) {
        echo mysqli_error($conn);
        mysqli_close( $conn);
        exit;
    }
}
mysqli_close( $conn);
?>