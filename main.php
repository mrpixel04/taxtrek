

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAXTREK</title>
   
      
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"/>

   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
     
    <style>
       
        .navbar {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #8000ff, #ff00cc); /* Purple to Pink */
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Highlight active menu item */
        .nav-item.active {
            background-color: #fff;
            border-radius: 20px;
            padding: 6px;
        }

       
        .navbar-toggler-icon {
            background-color: #fff;
        }

    
        .navbar-nav .nav-link {
            color: #fff;
        }

      
        .navbar-nav .nav-item.active .nav-link {
            color: #000;
        }

         /* Style navbar brand */
        .navbar-brand {
            color: #fff; /* Set font color to white */
            font-size: 24px; /* Set font size to 24px */
        }


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        
        <a class="navbar-brand" href="#">TAXTREK</a>

       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php if ($_GET['page'] == 'page_data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=page_data.php">Data Taskforce</a>
                </li>
                <li class="nav-item <?php if ($_GET['page'] == 'upload_data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=upload_data.php">Muatnaik</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
<?php 



     $page = isset($_GET['page'])?$_GET['page']:''; 



    if ($page == 'page_data.php' || empty($page)) {
        include('page_data.php');
    }elseif ($page == 'upload_data.php' || empty($page)) {
         include('page_upload_data.php');
    }elseif ($page == 'page_edit_data.php' || empty($page)) {
         include('page_edit_data.php');
    } elseif ($page == 'editalamat.php') {
        include('editalamat.php');
    } else {
         include('page_data.php');
    }



?>
</div>


 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<!--
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAXTREK</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">
     
    <style>
       
        .navbar {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #8000ff, #ff00cc); /* Purple to Pink */
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Highlight active menu item */
        .nav-item.active {
            background-color: #fff;
            border-radius: 20px;
            padding: 6px;
        }

       
        .navbar-toggler-icon {
            background-color: #fff;
        }

    
        .navbar-nav .nav-link {
            color: #fff;
        }

      
        .navbar-nav .nav-item.active .nav-link {
            color: #000;
        }

         /* Style navbar brand */
        .navbar-brand {
            color: #fff; /* Set font color to white */
            font-size: 24px; /* Set font size to 24px */
        }


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        
        <a class="navbar-brand" href="#">TAXTREK</a>

       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php //if ($_GET['page'] == 'upload_data.php') echo 'active'; ?>">
                    <a class="nav-link" href="?page=upload_data.php">Muatnaik</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
<?php 

    /*

     $page = isset($_GET['page'])?$_GET['page']:''; 

     if ($page == 'upload_data.php' || empty($page)) {
  
         include('page_upload_data.php');
    } else {
       
         include('page_upload_data.php');
    }
    */



?>
</div>


 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>

-->

