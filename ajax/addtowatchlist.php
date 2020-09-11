<?php include '../model/config.php';
session_start(); 
$stdid = $_SESSION["eid"];
//print_r($_REQUEST);
if(isset($_GET['vid'])){
	$type=$_GET['type'];
	
$vid=$_GET['vid'];
$cid=$_GET['cid'];
if($type=='add')
$sql="insert into videowatchlist(stdid,vid,course,dateadded) values('$stdid','$vid','$cid',NOW())";
else $sql="delete from videowatchlist where stdid='$stdid' and vid='$vid'";
if(mysqli_query($conn,$sql)) echo '1'; else echo '2';
}
?>
