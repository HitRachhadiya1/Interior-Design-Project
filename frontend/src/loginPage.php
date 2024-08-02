<?php
session_start();
if (isset($_SESSION["user"])) {
    header("Location: homepage.php");
    exit(); // Prevent further execution
} elseif (isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit(); // Prevent further execution
}

$errorMessage = '';

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        if (password_verify($password, $user["password"])) {
            // Start session (if not already started)
            if (!isset($_SESSION["user_id"])) {
                session_start();
            }

            // Store user ID and user type in session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_type"] = $user["usertype"];

            // Redirect based on user type
            if ($user["usertype"] == "user") {
                header("Location: projects.php");
                exit(); // Prevent further execution
            } elseif ($user["usertype"] == "admin") {
                header("Location: index.php");
                exit(); // Prevent further execution
            }
        } else {
            $errorMessage = "<div class='error_msg'>Password does not match</div>";
        }
    } else {
        $errorMessage = "<div class='error_msg'>Email does not match</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginPage.css">
</head>

<body>
    <div class="container">
        <form class="login-form" action="loginPage.php" method="post">
            <h2>Login</h2>

            <!-- Input fields -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <!-- Error message display -->
            <?php
            if ($errorMessage) {
                echo $errorMessage;
            }
            ?>

            <button type="submit" name="login">Login</button>

            <!-- Link to registration page -->
            <div class="input-group">
                <p>Don't have an account? <a href="registration.php">Register now</a></p>
            </div>
        </form>
    </div>
</body>

</html>