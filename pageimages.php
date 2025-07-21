<?php


include("classes/conn.php");


// Step 1: Query the database to get unique folder names
//$sql = "SELECT DISTINCT folder_name FROM TBL_DATA_IMAGES";
$sql = "SELECT * FROM TBL_DATA_IMAGES";
$result = $connection->query($sql);

if ($result->num_rows > 0) {
    $folders = $result->fetch_all(MYSQLI_ASSOC);
}

// Step 3: Display the folder images in a gallery
if (isset($_GET['foldercurrselect'])) {
    $folderSelect = $_GET['foldercurrselect'];


    $sql = "SELECT imgs_list_fn FROM TBL_DATA_IMAGES WHERE bil = '$folderSelect'";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $images = explode("||", $row['imgs_list_fn']);
    }
}


?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
<style style="text/css">

     .folder-link {
        transition: transform 0.3s ease;
    }

    .folder-link:hover {
        transform: scale(1.1);
    }

</style>



<div class="container">
        <div class="row">
            <div class="col-8">
                <div class="card shadow">
                    <div class="card-body">
                         <h5 class="card-title">Image Folders</h5>

                         <div class="row">
                             <?php

                                if (isset($folders)) {
                                    foreach ($folders as $folder) {
                                        //echo print_r($folder);
                                        $folderName = $folder['bil'];
                                        //http://localhost/flutterapps/api/taskforce/main.php?page=pageimages.php
                                        echo "<div class='col-3'><a href='http://localhost/flutterapps/api/taskforce/main.php?page=pageimages.php&foldercurrselect=$folderName' class='text-center folder-link'>
                                            <i class='fas fa-folder fa-3x'></i><br/>$folderName</a></div>";
                                    }
                                }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Folder Images</h5>
                           <div class="my-3 text-danger">
                                <strong>For deleting images:</strong> Check the checkboxes and press the delete button. <strong>To add additional images:</strong> You can upload an image and press the upload new image button.
                            </div>

                        <div class="row row-cols-4 row-cols-sm-4 row-cols-md-4 row-cols-lg-4">
                            <?php
                            if (isset($images)) {
                                echo "<form method='post' action='delete_images.php'>"; // Create a form for deleting images
                                foreach ($images as $image) {
                                    echo "<div class='col'>";
                                    echo "<label class='d-block'>";
                                    echo "<input class='form-check-input' type='checkbox' name='selectedImages[]' value='$image'>"; // Add a checkbox for each image
                                    echo "<a href='http://localhost/flutterapps/api/taskforce/imagestaskforce/$folderSelect/$image' data-bs-toggle='modal' data-bs-target='#imageModal'>";
                                    echo "<img src='http://localhost/flutterapps/api/taskforce/imagestaskforce/$folderSelect/$image' alt='$image' style='max-width: 100%;'>";
                                    echo "</a>";
                                    echo "</label>";
                                    echo "</div>";
                                }
                                echo "<div class='col-12 text-center mt-3'>";
                                echo "<button type='submit' class='btn btn-danger' name='deleteImages'>Delete</button>"; // Add a delete button
                                echo "</div>";
                                echo "</form>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
       </div>


</div><!-- end container -->

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               
                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                            
                               if (isset($images)) {
                                    $active = true;
                                    foreach ($images as $image) {
                                        echo "<div class='carousel-item " . ($active ? "active" : "") . "'>
                                                <img src='http://localhost/flutterapps/api/taskforce/imagestaskforce/$folderSelect/$image' class='d-block w-100' alt='$image'>
                                              </div>";
                                        $active = false;
                                    }
                                }

                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#imageCarousel" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#imageCarousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            
            </div>
        </div>
    </div>
</div>
