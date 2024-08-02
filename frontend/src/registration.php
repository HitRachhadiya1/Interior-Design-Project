<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: homepage.php");
    exit;
} elseif (isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit;
}

$errors = array();
$errors = [];
$registration_success = false;

if (isset($_POST["submit"])) {
    // Form submission logic
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Error checking
    if (empty($email) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // Database connection and email check
    require_once "database.php";
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $rowCount = mysqli_num_rows($result);

    if ($rowCount > 0) {
        array_push($errors, "Email already exists!");
    }

    // If there are no errors, proceed with registration
    if (count($errors) == 0) {
        $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
        if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $passwordHash);
            mysqli_stmt_execute($stmt);

            // Registration success, set session variable
            $_SESSION['registration_success'] = true;
            // Redirect to avoid form resubmission
            header("Location: registration.php");
            exit;
        } else {
            die("Something went wrong.");
        }
    }
}

// Check if there is a success message in the session
if (isset($_SESSION['registration_success'])) {
    $registration_success = true;
    // Unset the session variable to prevent persistent display
    unset($_SESSION['registration_success']);
}
?>

<!-- HTML and form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="registration.css">
</head>

<body>
    <div class="container">
        <form class="login-form" action="registration.php" method="post">
            <h2>Create an Account</h2>

            <!-- Input fields -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your Email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="input-group">
                <label for="repeat_password">Repeat Password</label>
                <input type="password" id="repeat_password" name="repeat_password" placeholder="Repeat your password"
                    required>
            </div>

            <!-- Error and success message display -->
            <?php
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='error_msg'>$error</div>";
                }
            }

            if ($registration_success) {
                echo "<div class='rege_success'>You have been registered successfully.</div>";
            }
            ?>

            <!-- Register button -->
            <button type="submit" name="submit">Register</button>

            <!-- Link to login page -->
            <div class="input-group">
                <p>Already have an account? <a href="loginPage.php">Login</a></p>
            </div>
        </form>
    </div>
</body>

</html>