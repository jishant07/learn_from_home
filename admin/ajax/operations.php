<?php
session_start();
include_once('../../model/config.php');
include_once('functions/functions.php');
error_reporting(0);

extract($_GET);
extract($_POST);
//echo'<pre>',print_r($_POST);
//print_r($_FILES);
$tid = $_SESSION['tid'];
if($type=='evolution'){
	$starttime = date('H:i:s',strtotime($starttime));
	$endtime = date('H:i:s',strtotime($endtime));
	$newopendate=$sdate.' '.$starttime;
	$newclosedate=$sdate.' '.$endtime;
	$sql = "INSERT INTO tbl_evolution(teacher,class,subject,evolutiontype,opendate,closedate,created) 
	VALUES('$tid','$class','$subject','exam','$newopendate','$newclosedate',NOW())";
	if(mysqli_query($conn,$sql)){
	$id = mysqli_insert_id($conn);
	$_SESSION['evid'] = $id;
	$_SESSION['tot_marks']=0;
	$_SESSION['qcount']=0;
	$arr['evid']=$id;
	$comments="Exam is given";		
		$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'exams&id='.$id,'tableid'=>$id,'tablename'=>'tbl_evolution','comments'=>$comments,'status'=>'1');
	saveNotification($arr);
	echo json_encode($arr);
	}
}
if(isset($_GET['evid']) && $_GET['evid']!=''){
	$evid = $_GET['evid'];
	$sql = "select totmarks,totquestions from tbl_evolution where id='$evid'";
	$res = mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	$_SESSION['tot_marks']=$row['totmarks'];
	$_SESSION['qcount']=$row['totquestions'];
}
else $evid = $_SESSION['evid'];
if($type=='fillblank'){	
	$fillblankque=addslashes(trim($fillblankque));
	$description=addslashes(trim($description));
	$fillblankanswer = implode(',',$fillblankanswer);
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql= "insert into tbl_fillblank(evid,question,answer,uploadflag,marks,description)
		values('$evid','$fillblankque','$fillblankanswer','$uploadflag','$fillblankanswermarks','$description')";
		///exit;
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_fill_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_fillblank set referdoc='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}	
		evaluationUpdate($evid,$fillblankanswermarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$fillblankanswermarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);
	}
	
}
if($type=='matchans'){
	$qmatch=addslashes(trim($qmatch));
	$description=addslashes(trim($description));
	$matchrowq=implode('-',$matchrowq);
	$matchrowopt=implode('-',$matchrowopt);
	$matchrowans=implode('-',$matchrowans);
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="INSERT INTO tbl_match(evid,question,cols1,cols2,answer,marks,uploadflag,description) VALUES('$evid','$qmatch','$matchrowq','$matchrowopt','$matchrowans','$matchmarks','$uploadflag','$description')";
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_match_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_match set referdoc='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}	
		evaluationUpdate($evid,$matchmarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$matchmarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);
	}
}
	
if($type=='singlechoice'){
	$singlechoicequestion=addslashes(trim($singlechoicequestion));
	$description=addslashes(trim($description));
	$singlechoiceans=implode('-',$singlechoiceans);	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="INSERT INTO tbl_singlechoice(evid,question,options,answer,marks,description,uploadflag) VALUES('$evid','$singlechoicequestion','$singlechoiceans','$singleanswer','$singlemarks','$description','$uploadflag')";
	
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_singlechoice_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_singlechoice set referdoc='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($evid,$singlemarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$singlemarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;	
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);
	}
	
}

if($type=='multiplechoice'){
	$multiplechoicequestion=addslashes(trim($multiplechoicequestion));
	$description=addslashes(trim($description));
	$muloption=implode('-',$muloption);	
	$ans=implode('-',$ans);	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="INSERT INTO tbl_multiplechoice(evid,question,options,answer,marks,description,uploadflag) VALUES('$evid','$multiplechoicequestion','$muloption','$ans','$multiplemarks','$description','$uploadflag')";
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_mulchoice_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_multiplechoice set referdoc='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($evid,$multiplemarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$multiplemarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);	
	}
		
}
if($type=='freetext'){
	$freetextquestion=addslashes(trim($freetextquestion));
	$txtanswer=addslashes(trim($txtanswer));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql = "INSERT INTO tbl_freetext(evid,question,answer,marks,uploadflag) VALUES ('$evid','$freetextquestion','$txtanswer','$freetextmarks','$uploadflag')";
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_free_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_freetext set document='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($evid,$freetextmarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$freetextmarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;	
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);
	}	
}

if($type=='uploadimage'){		
	$uoloadimagequestion=addslashes(trim($uoloadimagequestion));
	$discription=addslashes(trim($discription));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql = "INSERT INTO tbl_questiondoc(evid,question,marks,answer,uploadflag) VALUES ('$evid','$uoloadimagequestion','$picsmarks','$discription','1')";
	if(mysqli_query($conn,$sql)){
		$lid = mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$lid.'_doc_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_questiondoc set document='$fname' where id='$lid'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($evid,$picsmarks);
		$_SESSION['tot_marks']=$_SESSION['tot_marks']+$picsmarks;
		$_SESSION['qcount']=$_SESSION['qcount']+1;	
		$arr['tot_marks']=$_SESSION['tot_marks'];
		$arr['qcount']=$_SESSION['qcount'];
		echo json_encode($arr);
	}	
}
if($type=='submitmarks'){
	$ansid = $query['ansid'];
	$marks = $query['marks'];
	if(trim($ansid)!=''){
		 $sql="update tbl_answer set marks='$marks' where id='$ansid'";
		mysqli_query($conn,$sql);
	}
}
if($type=='publish'){
	$evid = $query['evid'];
	$s = $query['s']==0?1:0;
	if(trim($evid)!=''){
		$sql="update tbl_evolution set status='$s' where id='$evid'";
		mysqli_query($conn,$sql);
	}
	if($s==1){
		$comments="New evaluation is given given";
		$arr=array('from_id'=>$_SESSION['uid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'evaluation','tableid'=>$evid,'tablename'=>'tbl_evolution','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
	}
}

function evaluationUpdate($id,$marks){
	global $conn;
	$sql="update tbl_evolution set totquestions=totquestions+1,totmarks=totmarks+'$marks' where id='$id'";
	mysqli_query($conn,$sql);
}
?>