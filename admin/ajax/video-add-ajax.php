<?php include '../model/config.php'; ?>
<?php session_start(); ?>
<?php include '../permission.php' ?>
<?php include '../model/functions.php' ?>

  <?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// echo "<pre>";
		// print_r($_POST);
		//exit();
		if (isset($_POST['vcid'])) {
			   $vcid = $_POST['vcid'];
			   $vid_type = $_POST['vid_type'];
			   if($vid_type!=='on_demand'){
				   $vid_class = $_POST['vid_class'];
				   $vid_div = $_POST['vid_div'];
				   $vid_sub = $_POST['vid_sub'];
				   $vid_classtype = $_POST['vid_classtype'];
				   
			   }
			   $vtitle = $_POST['vtitle'];
			   $vid_teacher = $_POST['vid_teacher'];
			   //$description = $_POST['description'];
			   $description = addslashes(trim($_POST['description']));
			   if(isset($_POST['vid_format'])){
			   		echo 'vid_format:',$vid_format = $_POST['vid_format'];
			   		if($_POST['vid_format']=='link'){
			   			$vlink = $_POST['vlink'];
			   		}
			   		else{
			   			$vlink=$_POST['vlink'];
			   		}
			   }
			   
			   $sub_start_at = $_POST['sub_start_at'];
			   $sub_end_at = $_POST['sub_end_at'];
			   $product_image = $_FILES["fileToUpload"]["name"];
			   $status = $_POST['status'];

			   //Live
			   if($vid_type!=='on_demand'){
				   $vlink=$_POST['vlink'];
				$insert_record = "INSERT INTO video (vc_id, vid_type ,vtitle, vdesc, aws_link, enb, vid_teacher,sub_start_at,sub_end_at,vid_class,vid_div,vid_sub,vid_classtype) VALUES ('$vcid', '$vid_type' , '$vtitle', '$description' , '$vlink',  '$status', '$vid_teacher', '$sub_start_at', '$sub_end_at','$vid_class','$vid_div','$vid_sub','$vid_classtype')";
			   }
			   //OnDemand
			   else{
			   		$insert_record = "INSERT INTO video (vc_id, vid_type ,vtitle, vdesc, aws_link, enb, vid_teacher,sub_start_at,sub_end_at,vid_format) VALUES ('$vcid', '$vid_type' , '$vtitle', '$description' , '$vlink',  '$status', '$vid_teacher', '$sub_start_at', '$sub_end_at','$vid_format')";
			   }
				//echo $insert_record;exit;

				if ($conn->query($insert_record) == TRUE) {
					$vlastid = $conn->insert_id;
					$comments="New video $vtitle is uploaded.";
					$arr=array('from_id'=>$_SESSION['uid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'video&id='.$vlastid,'tableid'=>$vlastid,'tablename'=>'video','comments'=>$comments,'status'=>'1');
					saveNotification($arr);
					//Video Upload Starts
					if(isset($_POST['vid_format'])){
						if($_POST['vid_format']=='video'){
						 	$maxsize_vid = 52428800; // 50MB
						 	$vid_name = $_FILES['fileToUpload1']['name'];

					       	$target_vid_dir = "../uploads/videos/ondemand/";
					       	$target_vid_file = $target_vid_dir . $_FILES["fileToUpload1"]["name"];
					       	// Select file type
   							$videoFileType = strtolower(pathinfo($target_vid_file,PATHINFO_EXTENSION));
   							// Valid file extensions
       						$extensions_arr = array("mp4");
   						 	// Check extension
							if(in_array($videoFileType,$extensions_arr) ){
								// Check file size
					          	if(($_FILES['fileToUpload1']['size'] >= $maxsize_vid) || ($_FILES['fileToUpload1']["size"] == 0)){
						            echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>File too large. File must be less than 50MB.</div>";
					          	}
					          	else
					          	{
						            // Upload
						            if(move_uploaded_file($_FILES['fileToUpload1']['tmp_name'],$target_vid_file)){
					            	$update_record1 = "UPDATE video SET vid_path='$vid_name' WHERE vid_id='$vlastid'";
					            	$conn->query($update_record1);
						              echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Upload successfully</div>";
						            }
					          	}
							}
							else{
								echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>Please select MP4 video.</div>";
					       }
						}
					}
					//Video Upload Ends
					$product_link = '../uploads/images/videothumb/';
					if (!file_exists($product_link)) {
						mkdir($product_link, 0777, true);
					}
					$random = rand(1111,9999);
					$fgname = $random.$product_image;
					$fname  = str_replace(' ', '-', $fgname);
					$link = $product_link . '/' .$fname;
					$update_record = "UPDATE video SET vthumb='$fgname' WHERE vid_id='$vlastid'";
					include '../model/dbfunction2.php';
					$target_dir = '../uploads/images/videothumb/';
					upload_img($fname, $target_dir);
					if ($conn->query($update_record) === TRUE) {
						echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Video thumb added successfully</div>";
					} else {
						echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>Error in updating video thumbnail</div>";
					}

				} else {
					echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>Error occur in inserting record</div>";
					
				}
			}

	}
	$conn->close();
	?>
