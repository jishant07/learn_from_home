<?php
$target_dir = "uploads/images/";
//$fname = $_FILES["fileToUpload"]["name"];
$path = 'uploads/images/';
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["upload"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
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
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "<div align='center'>Sorry, your file is too large.</div>";
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
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "<div align='center'>";
       echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      echo "</div>"; 
    } else {
        echo "<div align='center'>Sorry, there was an error uploading your file.</div>";
    }
}
?>
