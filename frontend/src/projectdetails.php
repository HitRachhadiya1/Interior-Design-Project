<?php include 'config.php' ?>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.php");
    exit();
}

require_once "database.php";

// Check if project ID is provided
if (!isset($_GET["project_id"])) {
    header("Location: projects.php");
    exit();
}

// Retrieve project details from the database
$project_id = $_GET["project_id"];
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM projects WHERE id = ? AND uid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $project_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    // Project not found or unauthorized access
    header("Location: projects.php");
    exit();
}

$project = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $updated_project_name = $_POST["project-name"];
    $updated_project_type = $_POST["project-type"];
    $updated_project_currency = $_POST["project-currency"];
    $updated_measurement_type = $_POST["measurement-type"];
    $updated_project_address = $_POST["project-address"];
    $updated_description = $_POST["project-description"];
    $updated_client_name = $_POST["client-name"];
    $updated_client_number = $_POST["client-number"];

    $update_sql = "UPDATE projects SET pname = ?, ptype = ?, currency = ?, measurement = ?, paddress = ?, description = ?, client_name = ?, client_number = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssssssssi", $updated_project_name, $updated_project_type, $updated_project_currency, $updated_measurement_type, $updated_project_address, $updated_description, $updated_client_name, $updated_client_number, $project_id);

    if (mysqli_stmt_execute($update_stmt)) {
        echo "Project updated successfully.";
        // Redirect to project detail page with updated project ID
        header("Location: projectdetails.php?project_id=" . $project_id);
        exit();
    } else {
        echo "Error updating project: " . mysqli_error($conn);
    }

    mysqli_stmt_close($update_stmt);
}

if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.php");
    exit();
}

require_once "database.php";

// Check if project ID is provided
if (!isset($_GET["project_id"])) {
    header("Location: projects.php");
    exit();
}

// Retrieve project details from the database
$project_id = $_GET["project_id"];
$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM projects WHERE id = ? AND uid = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $project_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    // Project not found or unauthorized access
    header("Location: projects.php");
    exit();
}

$project = mysqli_fetch_assoc($result);

// Retrieve existing products associated with the project
$product_sql = "SELECT * FROM products WHERE project_id = ?";
$product_stmt = mysqli_prepare($conn, $product_sql);
mysqli_stmt_bind_param($product_stmt, "i", $project_id);
mysqli_stmt_execute($product_stmt);
$product_result = mysqli_stmt_get_result($product_stmt);
$products = mysqli_fetch_all($product_result, MYSQLI_ASSOC);

mysqli_close($conn);



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://b0mh0lt.github.io/freeCodeCamp/_assets/css/material-components700.min.css" />
    <link rel="stylesheet" href="projectdetails.css">
</head>

<body>
    <?php include 'navigationbar.php'; ?>
    <section class="home-section">
        <a class="box" href="projects.php">
            <div class="arrow right"></div>
            <div class="text">Back</div>
        </a>
        <div class="mdc-card">
            <form id="product-form" class="project-details" action="projectdetails.php?project_id=<?= $project_id ?>"
                method="post" enctype="multipart/form-data">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <!-- Project Name -->
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Details</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-name" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-name" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project name" name="project-name"
                                    value="<?= $project['pname'] ?>" required />
                                <span class="mdc-floating-label">Project Name</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                    <!-- Project Type -->
                    <div class="typecotainer">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Project Type:</h3>
                        </div>
                        <select name="project-type" id="project-type">
                            <option value="">Select</option>
                            <?php
                            // Available project types
                            $available_project_types = array(
                                "Single Residential",
                                "Multi Residential",
                                "Property Staging",
                                "Hospitality",
                                "Commercial",
                                "Retail",
                                "Institutional",
                                "Government",
                                "Community Space",
                                "Other"
                            );

                            // Generate options dynamically
                            foreach ($available_project_types as $type) {
                                $selected = ($project['ptype'] == $type) ? "selected" : "";
                                echo "<option value='$type' $selected>$type</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Currency</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-currency" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-currency" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project currency" name="project-currency"
                                    value="<?= $project['currency'] ?>" required />
                                <span class="mdc-floating-label">Project Currency</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Measurements</h3>
                        </div>
                        <div class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop flex-column"
                            id="radiocontainer">
                            <div class="mdc-form-field">
                                <?php
                                // Available measurement types
                                $available_measurement_types = array(
                                    array("value" => "0", "label" => "Metric"),
                                    array("value" => "1", "label" => "Imperial")
                                );

                                // Generate radio inputs dynamically
                                foreach ($available_measurement_types as $type) {
                                    $checked = ($project['measurement'] == $type['value']) ? "checked" : "";
                                    echo "<div class='mdc-radio'>
                      <input class='mdc-radio__native-control' name='measurement-type' type='radio' value='{$type['value']}' $checked>
                      <div class='mdc-radio__background'>
                          <div class='mdc-radio__outer-circle'></div>
                          <div class='mdc-radio__inner-circle'></div>
                      </div>
                      <div class='mdc-radio__ripple'></div>
                      <label>{$type['label']}</label>
                     </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3 id="paddress">Project Address</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-address" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-address" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project address" name="project-address"
                                    value="<?= $project['paddress'] ?>" required />
                                <span class="mdc-floating-label">Project Address</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Description</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-description" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-description" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project description" name="project-description"
                                    value="<?= $project['description'] ?>" required />
                                <span class="mdc-floating-label">Project Description</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Client Details</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="client-name" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="client-name" class="mdc-text-field__input" type="text"
                                    placeholder="Enter client name" name="client-name"
                                    value="<?= $project['client_name'] ?>" required />
                                <span class="mdc-floating-label">Client Name</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="client-number" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="client-number" class="mdc-text-field__input" type="text"
                                    placeholder="Enter client number" name="client-number"
                                    value="<?= $project['client_number'] ?>" required />
                                <span class="mdc-floating-label">Client Number</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>

                </div>
                <button id="submitbtn" type="submit" name="submit" class="mdc-button mdc-button--raised"
                    id->Update</button>
            </form>
        </div>
        <hr>
        <div class="title">
            Products Section
        </div>
        <hr>
        <form id="project-form" class="project-details" action="save_project.php?project_id=<?= $project_id ?>"
            method="post" enctype="multipart/form-data">
            <div class="productContainer">
                <div class="section">
                    <?php foreach ($products as $product): ?>
                        <div class="productCard">
                            <!-- <input type="file" id="project-photo" name="project-photo"><br> -->
                            <div class="productdetail">
                                <input type="text" name="productDescription[]" id="productDescription"
                                    placeholder="Product Description" value="<?= $product['description'] ?>"><br>
                                <!-- <textarea type="text" name="productionDetails[]" id="productionDetails"
                                    placeholder="PRODUCT DETAILS" value="<?= $product['product_details'] ?>"></textarea> -->

                            </div>
                            <div class="productd">
                                <input type="text" name="productName[]" id="productName" placeholder="-"
                                    value="<?= $product['productName'] ?>"><br>
                                <p class="ctitle">PRODUCT NAME</p>
                                <input type="text" name="brand[]" id="brand" placeholder="-"
                                    value="<?= $product['brand'] ?>"><br>
                                <p class="ctitle">BRAND</p>
                            </div>
                            <div class="productd">
                                <input type="number" name="width[]" id="width" placeholder="-"
                                    value="<?= $product['width'] ?>"><br>
                                <p class="ctitle">WIDTH (MM)</p>
                                <input type="text" name="color[]" id="color" placeholder="-"
                                    value="<?= $product['color'] ?>"><br>
                                <p class="ctitle">COLOR</p>
                            </div>
                            <div class="productd">
                                <input type="number" name="length[]" id="legth" placeholder="-"
                                    value="<?= $product['length'] ?>"><br>
                                <p class="ctitle">LENGTH (MM)</p>
                            </div>
                            <div class="productd">
                                <input type="number" name="height[]" id="height" placeholder="-"
                                    value="<?= $product['height'] ?>"><br>
                                <p class="ctitle">HEIGHT(MM)</p>
                                <input type="text" name="finish[]" id="finish" placeholder="-"
                                    value="<?= $product['finish'] ?>"><br>
                                <p class="ctitle">FINISH</p>
                            </div>
                            <div class="productd">
                                <input type="number" name="depth[]" id="depth" placeholder="-"
                                    value="<?= $product['depth'] ?>"><br>
                                <p class="ctitle">DEPTH (MM)</p>
                            </div>
                            <div class="productd">
                                <input type="number" name="qty[]" id="qty" placeholder="-"
                                    value="<?= $product['qty'] ?>"><br>
                                <p class="ctitle">QTY</p>
                                <input type="text" name="material[]" id="material" placeholder="-"
                                    value="<?= $product['material'] ?>"><br>
                                <p class="ctitle">MATERIAL</p>
                            </div>
                            <div class="productd">
                                <input type="text" name="supplier[]" id="supplier" placeholder="-"
                                    value="<?= $product['supplier'] ?>"><br>
                                <p class="ctitle">SUPPLIER</p>
                            </div>

                            <div class="productd">
                                <select name="status[]" id="product-status">
                                    <option value="Draft" <?= ($product['status'] == 'Draft') ? 'selected' : '' ?>>
                                        Draft</option>
                                    <option value="Quoting" <?= ($product['status'] == 'Quoting') ? 'selected' : '' ?>>
                                        Quoting</option>
                                    <option value="Internal Review" <?= ($product['status'] == 'Internal Review') ? 'selected' : '' ?>>Internal Review</option>
                                    <option value="Client Review" <?= ($product['status'] == 'Client Review') ? 'selected' : '' ?>>Client Review</option>
                                    <option value="Closed" <?= ($product['status'] == 'Closed') ? 'selected' : '' ?>>
                                        Closed</option>
                                    <option value="Rejected" <?= ($product['status'] == 'Rejected') ? 'selected' : '' ?>>
                                        Rejected</option>
                                    <option value="Approved" <?= ($product['status'] == 'Approved') ? 'selected' : '' ?>>
                                        Approved</option>
                                    <option value="Ordered" <?= ($product['status'] == 'Ordered') ? 'selected' : '' ?>>
                                        Ordered</option>
                                    <option value="Payment Due" <?= ($product['status'] == 'Payment Due') ? 'selected' : '' ?>>
                                        Payment Due</option>
                                    <option value="In Production" <?= ($product['status'] == 'In Production') ? 'selected' : '' ?>>In Production</option>
                                    <option value="In Transit" <?= ($product['status'] == 'In Transit') ? 'selected' : '' ?>>In
                                        Transit</option>
                                    <option value="Installed" <?= ($product['status'] == 'Installed') ? 'selected' : '' ?>>
                                        Installed</option>
                                    <option value="Delivered" <?= ($product['status'] == 'Delivered') ? 'selected' : '' ?>>
                                        Delivered</option>
                                </select>
                                <button class="delete-product-btn" type="button">Delete</button>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
            <div class="addProduct">
                <a class="addproduct" href="#">
                    New Product
                </a>
            </div>
            <br>
            <button id="submitbtn" type="submit" name="submit" class="mdc-button mdc-button--raised" id->Save
                Products</button>
        </form>



        <br>
    </section>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/jquery351.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/material-components700.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/brands.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/solid.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/fontawesome.min.js"></script>
    <script src="newproject.js"></script>
    <script>
        $(document).ready(function () {
            // Handle click event for delete product button
            $(".delete-product-btn").click(function () {
                // Find the parent product card
                var productCard = $(this).closest(".productCard");
                // Confirm with the user before deleting
                if (confirm("Are you sure you want to delete this product?")) {
                    // Remove the product card from the DOM
                    productCard.remove();
                }
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $(".addproduct").click(function () {
                var newProductHTML = `
        <div class="productCard">
          <div class="productdetail">
            <input type="text" name="productDescription[]" id="productDescription" placeholder="Product Description"><br>
            <textarea type="text" name="productionDetails[]" id="productionDetails" placeholder="PRODUCT DETAILS"></textarea>
          </div>
          <div class="productd">
            <input type="text" name="productName[]" id="productName" placeholder="-"><br>
            <p class="ctitle">PRODUCT NAME</p>
            <input type="text" name="brand[]" id="brand" placeholder="-"><br>
            <p class="ctitle">BRAND</p>
          </div>
          <div class="productd">
            <input type="number" name="width[]" id="width" placeholder="-"><br>
            <p class="ctitle">WIDTH (MM)</p>
            <input type="text" name="color[]" id="color" placeholder="-"><br>
            <p class="ctitle">COLOR</p>
          </div>
          <div class="productd">
            <input type="number" name="length[]" id="legth" placeholder="-"><br>
            <p class="ctitle">LENGTH (MM)</p>
          </div>
          <div class="productd">
            <input type="number" name="height[]" id="height" placeholder="-"><br>
            <p class="ctitle">HEIGHT(MM)</p>
            <input type="text" name="finish[]" id="finish" placeholder="-"><br>
            <p class="ctitle">FINISH</p>
          </div>
          <div class="productd">
            <input type="number" name="depth[]" id="depth" placeholder="-"><br>
            <p class="ctitle">DEPTH (MM)</p>
          </div>
          <div class="productd">
            <input type="number" name="qty[]" id="qty" placeholder="-"><br>
            <p class="ctitle">QTY</p>
            <input type="text" name="material[]" id="material" placeholder="-"><br>
            <p class="ctitle">MATERIAL</p>
          </div>
          <div class="productd">
            <input type="text" name="supplier[]" id="depth" placeholder="-"><br>
            <p class="ctitle">SUPPLIER</p>
          </div>
          <div class="productd">
            <select name="status[]" id="product-status">
                <option value="Draft">Draft</option>
                <option value="Quoting" >Quoting</option>
                <option value="Internal Review">Internal Review</option>
                <option value="Client Review" >Client Review</option>
                <option value="Closed" >Closed</option>
                <option value="Rejected" >Rejected</option>
                <option value="Approved" >Approved</option>
                <option value="Ordered" >Ordered</option>
                <option value="Payment Due">Payment Due</option>
                <option value="In Production" >In Production</option>
                <option value="In Transit" >In Transit</option>
                <option value="Installed" >Installed</option>
                <option value="Delivered" >Delivered</option>
            </select>
            </div>
        </div>
      `;

                $(".section").append(newProductHTML);
            });
        });

    </script>
</body>

</html>