<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM projects WHERE uid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link rel="stylesheet" href="projects.css" />
</head>

<body>
    <?php include 'navigationbar.php'; ?>
    <section class="home-section">
        <div class="home-content">
            <div class="text">Projects</div>
            <form action="newproject.php" method="GET">
                <button class="icon-btn add-btn" type="submit">
                    <div class="add-icon"></div>
                    <div class="btn-txt">New Project</div>
                </button>
            </form>
        </div>
        <div class="card-container">
            <?php foreach ($projects as $project): ?>
                <div class="card">
                    <h3><?php echo $project['pname']; ?></h3>
                    <div class="clintdetails">
                        <p class="title">Project Type : </p>
                        <p><?php echo $project['ptype']; ?></p>
                    </div>
                    <div class="clintdetails">
                        <p class="title">Client Name : </p>
                        <p><?php echo $project['client_name']; ?></p>
                    </div>
                    <div class="clintdetails">
                        <p class="title">Client Number : </p>
                        <p><?php echo $project['client_number']; ?></p>
                    </div>
                    <a href="projectdetails.php?project_id=<?php echo $project['id']; ?>" class="arrow-link">
                        <div class="arrow-icon">
                            <i class="bx bx-right-arrow-alt"></i>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        </div>
    </section>
</body>

</html>