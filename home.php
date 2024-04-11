<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}
$email = $_SESSION['email'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home.css">
    <title>Chat</title>
</head>

<body>
    <nav>
        <ul>
            <li>
                <div>Welcome
                    <?php echo $username; ?>
            </li>
            </div>
            <li><a href="<?php echo "profile.php?email=$email"; ?>">MyProfile</a></li>
            <li class="search-bar">
                <input type="text" id="search" placeholder="name or email .................. leave blank to see all users">
                <button class="search-btn" onclick="search()">Search</button>
            </li>
            <li class='logout'><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <div class="contacts" id="contacts"></div>
        <div id="contents">
            <div class="chat-container">
                <div id="chat-box"></div>
                <input type="text" id="user-input" placeholder="Type your message...">
                <button id="send-btn"onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
    <input type="text" id="r_id" style="display:none" value="<?php
        if(isset($_GET['id'])){
            echo $_GET['id'];
        }
    ?>">
    <script src="home.js"></script>
</body>

</html>