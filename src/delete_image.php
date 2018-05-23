<?php
include('session.php'); //Ensure they can't delete an image if they aren't logged in

if(isset($_GET['img'])) { //Make sure there is an image specified to be deleted
    $img = "./".($_GET['img']); //Create the image path
    $fileparts = pathinfo($img); //Get a pathinfo variable to check extensions
    
    if($fileparts['extension'] == "png" || $fileparts['extension'] == "jpg") { //Ensure it's an image type we are dealing with. This is VERY important, as this script will
                                                                                //Delete any file that is passed to it.
        if(unlink($img)) { //Attempt to delete the file
           alert("Image deleted!"); //Alert the user that the file was deleted
            if(isset($_GET['page'])) { //Check if we need to redirect the user back to a specific page
                $page = $_GET['page']; //Get the page the user was on
                header("location:welcome.php?page=".$page); //Send them back to that page
            } else {
                header("location:welcome.php"); //They didn't request a page to be sent back to, send them back to the main screen
            }
        } else { //There was an error deleting the file
            echo "Error deleting file!";
        }
        
    } else { //The file that was entered was not an image. 
        echo "Invalid image type!";
    header("location:welcome.php");
    }
} else {
    echo "No image found!"; //The file wasn't found
    header("location:welcome.php");
}

function alert($msg) { //Attempt to create a JS alert for the user
    echo "<script>alert('$msg');</script>";
}
?>