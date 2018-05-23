<?php
   include('session.php'); //Ensure the user can't access this page without being signed in
    define('RESULTS_PER_PAGE', 15); //How many images per page
    define('IMAGEPATH', './'); //The location of the images. Defaults to the working directory.
    

    $page = 0; //Keep track of what page the user is on. Default is 0.

    if(isset($_GET['page'])) { //Check if the user wants to change pages
        $page = $_GET['page'] - 1; //Subtract one from the page number, for math reasons later on.
    }

    function insertImageSelection($startNo, $amount) { //Function to display pages on the website. Called from the HTML code below.
        $ignore = array("logo.png", "delete.png", "save.png"); //Blacklisted files
        
        
        $images = getImages($startNo, $amount); //Get the images we want to view. $startNo is calculated from ($page * $RESULTS_PER_PAGE), and amount is $RESULTS_PER_PAGE by default.
        
        foreach($images as $filename) { //Loop through the images to remove blacklisted ones
            $imageFile = basename($filename); //get the base filename
            if(in_array($imageFile, $ignore)) { //Check if this file is in the blacklist
                
                if(count($images) == 1) { //We need this in order to remove the last blacklisted item from array
                    $images = array();
                    continue;
                }
                $key = array_search($imageFile, $images); //Get the key for the current image
                unset($images[$key]); //Remove it from the array
            }
        }
        
        if(count($images) <= 0) { //We removed all the images, or none were found.
            echo "<h1>No images were found!</h1>";
            return;
        }
        
        //Start displaying the images on the page
        foreach($images as $filename){
            $imageFile = basename($filename);
            
            echo "<a href='./$imageFile'><img src='./$imageFile' class='img-thumbnail ctx' id='$imageFile'></a>"; //Img needs 'ctx' class for context menu to appear. The rest is bootstrap styling

        }
    }

    function getImages($startNo, $amount) { //Determine which images a user is requesting.
        $images = glob(IMAGEPATH.'*.{jpg,JPG,jpeg,JPEG,png,PNG}',GLOB_BRACE); //Load all of the files in the working directory
        $imageSelection = array(); //Create the array of images to be returned
        
        //Some math to make sure numbers are in bounds
        if($amount > count($images)) { //The number of images requested is larger than the amount of images we have remaining.
            $amount = count($images) - $startNo;
        }
        if($startNo > count($images)) { //The startNo is greater than the amount of images we have
            $startNo = count($images);
            $amount = 1;
        }

        for($i = $startNo; $i < $startNo + $amount; $i++) { //Loops through the selected indicies and add them to an array.
            array_push($imageSelection, $images[$i]);
        }

        return $imageSelection; //Return the images to show
    }
?>
<html>

   <head>
      <title>Screenshot Viewer</title>
       <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
      <link rel="stylesheet" href="contextMenu.css">
       <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
       <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
      <script src="./assets/get.js"></script>
      <script src="./assets/buttons.js"></script>
      <script src="./assets/contextMenu.js"></script>
      <script src="./assets/menu.js"></script>
   </head>

   <body class="bg-warning"> <!--To change our background colour in bootstrap-->
       
   <div class="container text-center">
     <?php include('header.php') ?> <!--Code to add a header to our webpage. Replace with any PHP code to include a header logo -->
     <h1>Welcome <?php echo $_SESSION['login_user']?>!</h1>
     <br>
       <div id="imageContainer">
           <?php insertImageSelection($page * RESULTS_PER_PAGE, RESULTS_PER_PAGE); ?> <!--Insert images here-->
       </div>

       <!--Page controls-->
       <button id="prevBtn">Previous</button>
       <button id="nextBtn">Next</button>
       <!--End of page controls-->
     <?php include('footer.php'); ?>
   </div>
   </body>

</html>
