<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="navigatonbar.css" />
    <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <!-- <i class="bx bxl-c-plus-plus"></i> -->
            <span class="logo_name">CODEACE</span>
        </div>
        <ul class="nav-links">
            <!-- <li>
            <a href="homepage.php">
                <i class="bx bx-grid-alt"></i>
                <span class="link_name">Dashboard</span>
            </a>
        </li> -->
            <li>
                <a href="projects.php">
                    <i class="bx bx-collection"></i>
                    <span class="link_name">Projects</span>
                </a>
            </li>
            <!-- <li>
            <a href="#">
                <i class="bx bx-line-chart"></i>
                <span class="link_name">Purchase Order</span>
            </a>
            
        </li>
        <li>
            <a href="index.php">
                <i class="bx bx-compass"></i>
                <span class="link_name">Invoice</span>
            </a>
            
        </li> -->
            <li>
                <a href="todolist.php">
                    <i class="bx bx-history"></i>
                    <span class="link_name">To Do List</span>
                </a>

            </li>
            <!-- <li>
                <a href="#">
                    <i class="bx bx-cog"></i>
                    <span class="link_name">Setting</span>
                </a>

            </li> -->
            <li>
                <div class="profile-details">
                    <!-- <div class="profile-content">
                    <img src="../../assets/profile.png" alt="profileImg">
                </div> -->
                    <div class="name-job">
                        <div class="profile_name">Logout</div>
                    </div>
                    <a href="logout.php"><i class="bx bx-log-out"></i></a>
                </div>
            </li>
        </ul>
    </div>
    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement;
                arrowParent.classList.toggle("showMenu");
            });
        }
    </script>
</body>

</html>