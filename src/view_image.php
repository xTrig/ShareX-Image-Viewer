<?php


include '../sharexdb.php';
$imageTitle = "";
$imageName;
$uploadDate;
$description;

if(isset($_GET['img'])) {
    
    $conn = getShareXConnection();
    $relImage = mysqli_real_escape_string($conn, $_GET['img']);
    $imageName = $relImage;
    
    $sql = "SELECT * FROM images WHERE name = '$relImage'";
    
    $result = $conn->query($sql);
    
    
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imageTitle = $row["title"];
        if($imageTitle == null) {
            $imageTitle = $row['name'];
        }
        $description = $row['description'];
        $uploadDate = $row['date'];
    } else { //The image wasn't in the database. Let's try to add it if it exists!
        if(file_exists($imageName)) {
            $createdDate = date('Y-m-d H:i:s', filectime($imageName));
            $sql = "INSERT INTO images (name, date) VALUES ('$imageName', '$createdDate')";
            
            if($conn->query($sql)) {
                echo "Image not found in database. Inserted data successfully!";
            } else {
                echo $conn->error;
            }
            
        } else {
            $imageTitle = "Image not found!";
        }
        
    }
    
    $conn->close();
    
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
     <h1><?php echo $imageTitle; ?></h1>
       <div id="imageContainer" style="padding-bottom: 10px;">
           <img src="./<?php echo $imageName ?>" class='img-thumbnail'>
       </div>
       <div id="imageDescription">
            <h2><?php echo $description; ?></h2>
       </div>  
     <br>
       
   </div>
   </body>

</html>