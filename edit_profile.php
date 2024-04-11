<?php
session_start();
$name = $bio = "";
if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['email'];
require 'db_info.php';

try {
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
} catch (Exception $mysqli_sql_exception) {
    echo "<div class='error-message'>we are facing some database issues.<br> try after sometime</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST["name"]) && isset($_POST['bio'])) {
        $name = $_POST['name'];
        $bio = $_POST['bio'];
        $querry = "UPDATE users SET name = '$name', bio = '$bio' WHERE email = '$email'";
        try {
            mysqli_query($conn, $querry);
        } catch (Exception $mysqli_sql_exception) {
            mysqli_close($conn);
            echo "<div class='error-message'>we are facing some database issues.<br> try after sometime</div>";
            exit;
        }

        mysqli_close($conn);
        // $_SESSION['update_success'] = TRUE;
        header("Location: profile.php?email=".htmlspecialchars($email));
        exit;
    }
}


$querry = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $querry);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $bio = $row['bio'];
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="loginStyle.css">
    <title>Edit Profile</title>
</head>

<body>
    <div class="back-btn" style="
        top:1%;
        left:1%;
        position:fixed;
    ">
        <a href="profile.php?email=<?php echo htmlspecialchars($email); ?>">back</a>
    </div>

    <div class="form-container">
        <h2>Update Profile</h2>
        <form action="edit_profile.php" method="POST">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" maxlength="30" value="<?php echo $name; ?>" required>
            </div>
            <div>
                <label for="bio">Bio:</label>
                <textarea name="bio" id="bio" cols="50" rows="3" placeholder="About Yourself"
                    ><?php echo $bio;?></textarea>
            </div>
            <br>
            <button type="submit">Save Changes</button>
        </form>
        <br><br>
    </div>

</body>

</html>