<?php
session_start();
require_once "database.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.php");
    exit();
}

// Check if project ID is provided
if (!isset($_GET["project_id"])) {
    header("Location: projects.php");
    exit();
}

// Retrieve project ID from the URL
$project_id = $_GET["project_id"];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Array to hold product details
    $products = array();

    // Retrieve existing product IDs associated with the project
    $existing_product_ids = array();
    $existing_product_ids_query = "SELECT id FROM products WHERE project_id = ?";
    $existing_product_ids_stmt = mysqli_prepare($conn, $existing_product_ids_query);
    mysqli_stmt_bind_param($existing_product_ids_stmt, "i", $project_id);
    mysqli_stmt_execute($existing_product_ids_stmt);
    $existing_product_ids_result = mysqli_stmt_get_result($existing_product_ids_stmt);
    while ($row = mysqli_fetch_assoc($existing_product_ids_result)) {
        $existing_product_ids[] = $row['id'];
    }
    mysqli_stmt_close($existing_product_ids_stmt);

    // Loop through each product description
    foreach ($_POST['productDescription'] as $key => $description) {
        // Create an array to store product details
        $product = array(
            'id' => isset($_POST['product_id'][$key]) ? $_POST['product_id'][$key] : null,
            'description' => $description,
            'product_details' => $_POST['productionDetails'][$key],
            'productName' => $_POST['productName'][$key],
            'brand' => $_POST['brand'][$key],
            'width' => $_POST['width'][$key],
            'color' => $_POST['color'][$key],
            'length' => $_POST['length'][$key],
            'height' => $_POST['height'][$key],
            'finish' => $_POST['finish'][$key],
            'depth' => $_POST['depth'][$key],
            'qty' => $_POST['qty'][$key],
            'material' => $_POST['material'][$key],
            'supplier' => $_POST['supplier'][$key],
            'status' => $_POST['status'][$key]
        );

        // Add the product details to the products array
        $products[] = $product;
    }

    // Process each product
    foreach ($products as $product) {
        if ($product['id']) {
            // Update existing product
            $update_sql = "UPDATE products SET description = ?, product_details = ?, productName = ?, brand = ?, width = ?, color = ?, length = ?, height = ?, finish = ?, depth = ?, qty = ?, material = ?, supplier = ?, status = ? WHERE id = ? AND project_id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ssssssssssssssi", $product['description'], $product['product_details'], $product['productName'], $product['brand'], $product['width'], $product['color'], $product['length'], $product['height'], $product['finish'], $product['depth'], $product['qty'], $product['material'], $product['supplier'], $product['status'], $product['id'], $project_id);
            mysqli_stmt_execute($update_stmt);
            mysqli_stmt_close($update_stmt);

            // Remove product ID from existing product IDs array
            $key = array_search($product['id'], $existing_product_ids);
            if ($key !== false) {
                unset($existing_product_ids[$key]);
            }
        } else {
            // Insert new product
            $insert_sql = "INSERT INTO products (project_id, description, product_details, productName, brand, width, color, length, height, finish, depth, qty, material, supplier, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "issssssssssssss", $project_id, $product['description'], $product['product_details'], $product['productName'], $product['brand'], $product['width'], $product['color'], $product['length'], $product['height'], $product['finish'], $product['depth'], $product['qty'], $product['material'], $product['supplier'], $product['status']);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
        }
    }

    // Delete remaining products that are not included in the received data
    foreach ($existing_product_ids as $product_id) {
        $delete_sql = "DELETE FROM products WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $product_id);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);
    }

    // Redirect after successful insertion
    header("Location: projectdetails.php?project_id=" . $project_id);
    exit();
} else {
    // Form not submitted
    header("Location: projectdetails.php?project_id=" . $project_id);
    exit();
}
mysqli_close($conn);
?>