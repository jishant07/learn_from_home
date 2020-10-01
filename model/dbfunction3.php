<?php

 function upload_img($f, $target){
//$target_dir = "../uploads/images/slides/";
//$fname = $_FILES["fileToUpload"]["name"];

$target_dir = $target; 
//$path = '../uploads/images/slides/';
$target_file = $target_dir . basename($f);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image



if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
       // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
       
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
         exit();
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    echo "<div align='center'>Sorry, file already exists.</div>";
    echo"<div align='center'>If you want to  still upload same image then rename it and upload it</div>";
    $uploadOk = 0;
   exit();
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 2048000) {
    echo "<div align='center'>Sorry, your file is too large. It should not above 2mb</div>";
    $uploadOk = 0;
     exit();
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<div align='center'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
    $uploadOk = 0;
     exit();
}



// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<div align='center'>Sorry, your file was not uploaded.</div>";
     exit();
// if everything is ok, try to upload file
} else 
{

   
                      $source_file = $_FILES['uploadImg']['tmp_name'];
                      $target_file = $target_dir . $_FILES['uploadImg']['name']; 
          
                      //$image_name = $_FILES['uploadImg']['name'];

    $success = compress_image($source_file, $target_file, $width, $height, $quality);
                      if($success) {
                        // Optional. The original file is uploaded to the server only for the comparison purpose.
                        copy($source_file, $target_file . $_FILES['uploadImg']['name']);
                                echo "<div align='center' style='color:#5d8d00'; font-weight:bold;>";
                                  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                                echo "</div>"; 
            
                       } else {
                             echo "<div align='center'>Sorry, there was an error uploading your file.</div>";
                    }
}

}



?>