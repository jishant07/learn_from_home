<?php
 if(isset($_FILES['fileToUpload']['name']) && @$_FILES['fileToUpload']['name'] != "") {
                if($_FILES['fileToUpload']['error'] > 0) {
                  echo '<h4>Increase post_max_size and upload_max_filesize limit in php.ini file.</h4>';
                } 
                else {
                  if($_FILES['fileToUpload']['size'] / 1024 <= 5120) { // 5MB
                    if($_FILES['fileToUpload']['type'] == 'image/jpeg' || 
                       $_FILES['fileToUpload']['type'] == 'image/pjpeg' || 
                       $_FILES['fileToUpload']['type'] == 'image/png' ||
                       $_FILES['fileToUpload']['type'] == 'image/gif'){
                      

                      //$image_name = $_FILES['uploadImg']['name'];
                      $success = compress_image($source_file, $target_file, $width, $height, $quality);
                      if($success) {
                        // Optional. The original file is uploaded to the server only for the comparison purpose.
                       // copy($source_file, $target_dir . $_FILES['fileToUpload']['name']);
                         echo "<div align='center' style='color:#5d8d00'; font-weight:bold;>";
                                   echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                         echo "</div>"; 

                      }
                    } else{
                        echo "<div align='center' style='color:#cc0000'; font-weight:bold; >Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
                        $uploadOk = 0;
                       exit();

                    }
                  } else {
                    echo '<h4>Image should be maximun 5MB in size!</h4>';
                  }
                }
              } else {
                echo "<h4>Please select an image first!</h4>";
              }

?>