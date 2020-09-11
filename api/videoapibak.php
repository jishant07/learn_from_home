<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
include '../model/config.php';

$filelocation="http://flowrow.com/lfh/";
$type = $_GET['action'];
$arr = array();
if($type=='search'){
	$studentID=$_GET['studentID'];
	$videoID=$_GET['videoID'];
	if($studentID!='' && $videoID!=''){	
		$sql="SELECT watchtime,vid_format,vid_path,aws_link FROM video v, videotrack t WHERE v.vid_id=t.video AND student='$studentID' AND t.video='$videoID' ORDER BY id DESC LIMIT 1";
		$sq_video = $conn -> query($sql);
		$row_vid = $sq_video->fetch_assoc();
		
		$rw = $sq_video->num_rows;
		if ($rw >= 1){
			$vid_fmrt = $row_vid['vid_format'];
			if($vid_fmrt=='link'){
			  $arr['videolink'] =  $row_vid['aws_link'] ;
			}
			else{
			  $arr['videolink'] = $filelocation."uploads/videos/ondemand/".$row_vid['vid_path'] ;
			}
			$arr['playtime'] = $row_vid['watchtime'];
		} else{
			
			$sql="SELECT vid_format,aws_link,vid_path FROM video WHERE vid_id='$videoID'";
			$sq_video = $conn -> query($sql);
			$row_vid = $sq_video->fetch_assoc();
			$vid_fmrt = $row_vid['vid_format'];
			if($vid_fmrt=='link'){
			  $arr['videolink'] =  $row_vid['aws_link'] ;
			}
			else{
			  $arr['videolink'] = $filelocation."uploads/videos/ondemand/".$row_vid['vid_path'] ;
			}
			$arr['playtime'] = 0;			
		}
	}	
//print_r($arr);
echo json_encode($arr);	
}
else if($type=='insert'){
	$studentID=$_GET['studentID'];
	$videoID=$_GET['videoID'];
	$pauseTime=$_GET['pauseTime'];
	$sql="INSERT INTO videotrack(student,video,watchtime,datewatched) VALUES('$studentID','$videoID','$pauseTime',NOW())";
	if($conn -> query($sql)) $arr['message']='Success';else $arr['message']='Fail'; 
	echo json_encode($arr);	
	
}
else if($type=='update'){
	$studentID=$_GET['studentID'];
	$videoID=$_GET['videoID'];
	$pauseTime=$_GET['pauseTime'];
	$sql = "select id from videotrack WHERE student='$studentID' AND video='$videoID'";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){	
	$sql="UPDATE videotrack SET watchtime='$pauseTime',datewatched=NOW() WHERE student='$studentID' AND video='$videoID'";
	}
	else{
		$sql="INSERT INTO videotrack(student,video,watchtime,datewatched) VALUES('$studentID','$videoID','$pauseTime',NOW())";	
	}
	if($conn -> query($sql)) $arr['message']='Success';else $arr['message']='Fail'; 
	echo json_encode($arr);	
	
}
?>