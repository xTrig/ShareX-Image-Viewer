<?php
include 'session.php'; //Ensure they can't edit an image if they aren't signed in.
include '../sharexdb.php';

$errorMsg = "";

$imageTitle = "";
$imageName = "";
$imageDescription = "";
$imageDate = "";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['img'])) {
        $conn = getShareXConnection();

        $imageName = mysqli_real_escape_string($conn, $_GET['img']);

        if(file_exists($imageName)) {
            $sql = "SELECT * FROM images WHERE name = '$imageName'";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $imageTitle = $row['title'];
                $imageDescription = $row['description'];
                $imageDate = $row['date'];
            }
            $conn->close();

        } 

    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     if(!empty($_POST)) {
         $conn = getShareXConnection();
         if(isset($_POST['img']) && isset($_POST['title']) && isset($_POST['description'])) {
             $imageName = mysqli_real_escape_string($conn, $_POST['img']);
             $imageTitle = mysqli_real_escape_string($conn, $_POST['title']);
             $imageDescription = mysqli_real_escape_string($conn, $_POST['description']);
             
             $sql = "UPDATE images SET title = '$imageTitle', description = '$imageDescription' WHERE name = '$imageName'";
             
             if($conn->query($sql)) {
                 header("location:view_image.php?img=$imageName");
             } else {
                 $error = $conn->error;
                 echo "There was an error: $error";
             }
         } else {
             echo "Invalid form parameters. Found $_POST";
         }
     }
}


?>


<html>

   <head>
      <title><?php echo $imageTitle; ?></title>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
       <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
   </head>

   <body class="bg-warning"> <!--To change our background colour in bootstrap-->
       
   <div class="container text-center">
     <?php include('header.php') ?> <!--Code to add a header to our webpage. Replace with any PHP code to include a header logo -->
     <h1>Editing <?php echo $imageName; ?></h1>
       <div id="imageContainer" style="padding-bottom: 10px;">
           <?php echo "<a href='./$imageName'><img src='./$imageName' class='img-thumbnail'></a>"; ?>
       </div>
       <form action="./edit_image.php" method="post">
        <div class="form-group">
            <label for="title">Title</label>
            <?php echo "<input type='text' class='form-control' id='title' name='title' placeholder='Enter a title for this image' value='$imageTitle'>"; ?>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <?php echo "<input type='text' class='form-control' id='description' name='description' placeholder='Enter a description for this image' value='$imageDescription'>"; ?>
        </div>  
           
           <?php echo "<input type='hidden' id='img' name='img' value='$imageName'>"; ?>
           <button type="submit" class="btn btn-primary">Submit</button>
       </form> 
     <br>
       
   </div>
   </body>

</html>