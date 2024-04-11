<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $r_id = $_GET['id'];
    $s_id = $_SESSION['user_id'];

    require 'db_info.php';

    try {
        $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    } catch (Exception $mysqli_sql_exception) {
        echo "error happened:<br> ";
        echo mysqli_connect_error();
        exit;
    }

    $querry = "SELECT * FROM user_connections WHERE (user_id = $r_id AND connected_user_id = $s_id) OR (user_id = $s_id AND connected_user_id = $r_id)";
    $result = mysqli_query($conn, $querry);
    if (mysqli_num_rows($result) > 0) {
        mysqli_close($conn);
        header("Location: home.php?id=".htmlspecialchars($r_id)); 
        exit;
    } else {
        $querry = "INSERT INTO user_connections (user_id, connected_user_id) VALUES ('$s_id', '$r_id')";
        try{
            mysqli_query($conn, $querry);
        } catch (Exception $mysqli_sql_exception) {
            echo mysqli_error($conn);
            mysqli_close( $conn);
            exit;
        }
        mysqli_close($conn);
        header("Location: home.php?id=".htmlspecialchars($r_id)); 
        exit;
    }
}
?>