<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAXTREK</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Include Poppins font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
     
    <style>
        /* Apply Poppins font to the navbar */
        .navbar {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: 'Poppins', sans-serif;
        
        }

        /* Highlight active menu item */
        .nav-item.active {
            background-color: lightgray;
        }

        

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- Title aligned to the left -->
        <a class="navbar-brand" href="#">TAXTREK</a>

        <!-- Navbar Toggler Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <!--
                <li class="nav-item <?php if ($_GET['page'] == 'dashboard.php' || empty($_GET['page'])) echo 'active'; ?>">
                    <a class="nav-link" href="?page=dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item <?php if ($_GET['page'] == 'data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=data.php">Data Taskforce</a>
                </li>
                 <li class="nav-item <?php if ($_GET['page'] == 'pageimages.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=pageimages.php">Images Management</a>
                </li>
            -->
                <li class="nav-item <?php if ($_GET['page'] == 'upload_data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=upload_data.php">Muatnaik</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <!-- Content loaded dynamically based on the "page" query parameter -->
    <?php

    $page = $_GET['page'] ?$_GET['page']:''; // Get the "page" query parameter

    /*
    if ($page == 'dashboard.php' || empty($page)) {
        include('dashboard.php');
    } elseif ($page == 'data.php') {
        include('data.php');
     } elseif ($page == 'pageimages.php') {
        include('pageimages.php');
    } elseif ($page == 'upload_data.php') {;
        include('upload_data.php');
    } elseif ($page == 'printnotis.php') {
        include('printnotis.php');
    } elseif ($page == 'editdatanotis.php') {
        include('editdatanotis.php');
    } else {
        echo 'Page not found'; // Handle invalid or unsupported pages
        include('dashboard.php');
    }
    */
     if ($page == 'upload_data.php' || empty($page)) {
         include('upload_data.php');
    } else {
        echo 'Page not found'; // Handle invalid or unsupported pages
         include('upload_data.php');
    }

   

    ?>
</div>

<!-- Include Bootstrap JS (optional, for responsive features) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
