<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    if (!isset($_SESSION["user_id"])) {
        header("Location: loginPage.php");
        exit();
    }

    $project_name = $_POST["project-name"];
    $project_type = $_POST["project-type"];
    $project_currency = $_POST["project-currency"];
    $measurement_type = $_POST["measurement-type"];
    $project_address = $_POST["project-address"];
    $description = $_POST["project-description"];
    $client_name = $_POST["client-name"]; // Add client name
    $client_number = $_POST["client-number"]; // Add client number
    $user_id = $_SESSION["user_id"];

    // if (empty($project_name) || empty($project_type) || empty($project_currency) || empty($measurement_type) || empty($project_address) || empty($description) || empty($client_name) || empty($client_number)) {
    //     echo "All fields are required. <br>";
    //     echo "Project Name: " . $project_name . "<br>";
    //     echo "Project Type: " . $project_type . "<br>";
    //     echo "Project Currency: " . $project_currency . "<br>";
    //     echo "Measurement Type: " . $measurement_type . "<br>";
    //     echo "Project Address: " . $project_address . "<br>";
    //     echo "Description: " . $description . "<br>";
    //     echo "Client Name: " . $client_name . "<br>";
    //     echo "Client Number: " . $client_number;
    //     exit();
    // }

    require_once "database.php";

    $sql = "INSERT INTO projects (uid, pname, ptype, currency, measurement, paddress, description, client_name, client_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "issssssss", $user_id, $project_name, $project_type, $project_currency, $measurement_type, $project_address, $description, $client_name, $client_number);

    if (mysqli_stmt_execute($stmt)) {
        echo "Project created successfully.";
        header("Location: projects.php");
        exit();
    } else {
        echo "Error creating project: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Project</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://b0mh0lt.github.io/freeCodeCamp/_assets/css/material-components700.min.css" />
    <link rel="stylesheet" href="newProject.css">
</head>

<body>
    <?php include 'navigationbar.php'; ?>
    <section class="home-section">
        <a href="projects.php" class="box">
            <div class="arrow right"></div>
            <div class="text">Back</div>
        </a>

        <div class="mdc-card">
            <form id="project-form" class="project-details" action="newproject.php" method="post">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Details</h3>
                        </div>

                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-name" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-name" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project name" name="project-name" required />
                                <span class="mdc-floating-label">Project Name</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>

                    </div>
                    <div class="typecotainer">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Project Type:</h3>
                        </div>
                        <select name="project-type" id="project-type">
                            <option value="">Select</option>
                            <option value="Single Residential">Single Residential</option>
                            <option value="Multi Residential">Multi Residential</option>
                            <option value="Property Staging">Property Staging</option>
                            <option value="Hospitality">Hospitality</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Retail">Retail</option>
                            <option value="Institutional">Institutional</option>
                            <option value="Government">Government</option>
                            <option value="Community Space">Community Space</option>
                            <option value="Other">Other</option>
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
                                    placeholder="Enter project currency" name="project-currency" required />
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
                        <div class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container"
                            id="radiocontainer">
                            <div class="mdc-form-field">
                                <div class="mdc-radio">
                                    <input class="mdc-radio__native-control" name="measurement-type" type="radio"
                                        value="0">
                                    <!-- Add name attribute -->
                                    <div class="mdc-radio__background">
                                        <div class="mdc-radio__outer-circle"></div>
                                        <div class="mdc-radio__inner-circle"></div>
                                    </div>
                                    <div class="mdc-radio__ripple"></div>
                                </div>
                                <label>Matric</label>
                            </div>
                            <div class="mdc-form-field">
                                <div class="mdc-radio">
                                    <input class="mdc-radio__native-control" name="measurement-type" type="radio"
                                        value="1">
                                    <!-- Add name attribute -->
                                    <div class="mdc-radio__background">
                                        <div class="mdc-radio__outer-circle"></div>
                                        <div class="mdc-radio__inner-circle"></div>
                                    </div>
                                    <div class="mdc-radio__ripple"></div>
                                </div>
                                <label>Imperial</label>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__inner">
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop">
                            <h3>Project Address</h3>
                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="project-address" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="project-address" class="mdc-text-field__input" type="text"
                                    placeholder="Enter project address" name="project-address" required />
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
                                    placeholder="Enter project description" name="project-description" required />
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
                                    placeholder="Enter client name" name="client-name" required />
                                <span class="mdc-floating-label">Client Name</span>
                                <span class="mdc-line-ripple"></span>
                            </label>

                        </div>
                        <div
                            class="mdc-layout-grid__cell--span-4-phone mdc-layout-grid__cell--span-8-tablet mdc-layout-grid__cell--span-12-desktop name-container">
                            <label for="client-number" class="mdc-text-field mdc-text-field--filled w-100">
                                <input id="client-number" class="mdc-text-field__input" type="text"
                                    placeholder="Enter client number" name="client-number" required />
                                <span class="mdc-floating-label">Client Number</span>
                                <span class="mdc-line-ripple"></span>
                            </label>
                        </div>
                    </div>

                </div>
                <button id="submitbtn" type="submit" name="submit" class="mdc-button mdc-button--raised">Submit</button>

            </form>
        </div>
    </section>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/jquery351.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/material-components700.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/brands.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/solid.min.js"></script>
    <script src="https://b0mh0lt.github.io/freeCodeCamp/_assets/js/fontawesome.min.js"></script>
    <script src="newproject.js"></script>

</body>

</html>