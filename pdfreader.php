<?php
if($_GET['type']=='book') {
	$file='uploads/my_books/'.$_GET['file']; $filename='uploads/my_books/'.$_GET['file'];
}
if($_GET['type']=='study') {
	$file='uploads/study_material/'.$_GET['file'];
    $filename='uploads/study_material/'.$_GET['file'];
}
if($_GET['type']=='course') {
	$file='uploads/coursedocuments/'.$_GET['file'];
    $filename='uploads/coursedocuments/'.$_GET['file'];
}
// exit;
header('Content-type: application/pdf'); 
  
header('Content-Disposition: inline; filename="' . $filename . '"'); 
  
header('Content-Transfer-Encoding: binary'); 
header('Content-Length:'.filesize($filename)); 
  
header('Accept-Ranges: bytes'); 
  
// Read the file 
@readfile($file); 
  
	
	?>
