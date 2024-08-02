<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.php");
    exit();
}

require_once "database.php";

// Handle adding a new item to the to-do list
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["new_item"])) {
    $item_name = $_POST["new_item"]; // Change "item_name" to "new_item"
    $user_id = $_SESSION["user_id"];

    $sql = "INSERT INTO todo_items (user_id, item_name) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $item_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


// Handle completing an item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["complete_item"])) {
    $item_id = $_POST["item_id"];

    $sql = "UPDATE todo_items SET completed = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Handle deleting an item
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_item"])) {
    $item_id = $_POST["item_id"];

    $sql = "DELETE FROM todo_items WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$user_id = $_SESSION["user_id"];

// Fetch uncompleted todo items
$sql_todo = "SELECT * FROM todo_items WHERE user_id = ? AND completed = 0";
$stmt_todo = mysqli_prepare($conn, $sql_todo);
mysqli_stmt_bind_param($stmt_todo, "i", $user_id);
mysqli_stmt_execute($stmt_todo);
$result_todo = mysqli_stmt_get_result($stmt_todo);
$todo_items = mysqli_fetch_all($result_todo, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_todo);

// Fetch completed todo items
$sql_completed = "SELECT * FROM todo_items WHERE user_id = ? AND completed = 1";
$stmt_completed = mysqli_prepare($conn, $sql_completed);
mysqli_stmt_bind_param($stmt_completed, "i", $user_id);
mysqli_stmt_execute($stmt_completed);
$result_completed = mysqli_stmt_get_result($stmt_completed);
$completed_items = mysqli_fetch_all($result_completed, MYSQLI_ASSOC);
mysqli_stmt_close($stmt_completed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="todolist.css">
</head>
<body>
    <?php include 'navigationbar.php'; ?>
     
    <section class="home-section">
        <div class="header">To Do List</div>
        <div class="container">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="add-task-form">
            <input type="text" name="new_item" placeholder="Add New Task" required>
                <button type="submit"+
                >Add</button>
            </form>
            <div class="todo">
                <p class="label">To Do</p>
                <?php foreach ($todo_items as $item): ?>
                    <div class="todoitems">
                        <?php echo $item["item_name"]; ?>
                        <div class="options-dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                            <div class="dropdown-content">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $item["id"]; ?>">
                                    <button type="submit" name="complete_item" class="completebtn">Complete</button>
                                </form>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $item["id"]; ?>">
                                    <button type="submit" name="delete_item" class="completebtn">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="completed">
                <p class="label">Completed</p>
                <?php foreach ($completed_items as $item): ?>
                    <div class="completeditems">
                        <?php echo $item["item_name"]; ?>
                        <div class="options-dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                            <div class="dropdown-content">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $item["id"]; ?>">
                                    <button type="submit" name="delete_item" class="completebtn">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</body>
</html>
