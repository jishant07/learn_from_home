<?php
session_start();
include_once('../../model/config.php');
//include_once('../model/functions.php');
error_reporting(E_ALL);

extract($_GET);
extract($_POST);
//echo'<pre>',print_r($_POST);
//print_r($_FILES);
$tid = $_SESSION['tid'];
$qid = $_GET['id'];
if($type=='evolution'){
	$starttime = date('H:i:s',strtotime($starttime));
	$endtime = date('H:i:s',strtotime($endtime));
	$newopendate=$sdate.' '.$starttime;
	$newclosedate=$sdate.' '.$endtime;
	$sql = "INSERT INTO tbl_evolution(teacher,class,subject,evolutiontype,opendate,closedate,created) 
	VALUES('$tid','$class','$subject','exam','$newopendate','$newclosedate',NOW())";
	if(mysqli_query($conn,$sql)) echo '1' ; echo '0';	
}

if($type=='fillblank'){	
	$fillblankque=addslashes(trim($fillblankque));
	$description=addslashes(trim($description));
	$fillblankanswer = implode(',',$fillblankanswer);
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	
	$sql= "update tbl_fillblank set question='$fillblankque',answer='$fillblankanswer',uploadflag='$uploadflag',marks='$fillblankanswermarks',description='$description' where id='$id'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_fill_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_fillblank set referdoc='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$fillblankanswermarks,$oldmarks);	
	}
	
}
if($type=='matchans'){
	$qmatch=addslashes(trim($qmatch));
	$description=addslashes(trim($description));
	$matchrowq=implode('-',$matchrowq);
	$matchrowopt=implode('-',$matchrowopt);
	$matchrowans=implode('-',$matchrowans);
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="update tbl_match set question='$qmatch',cols1='$matchrowq',cols2='$matchrowopt',answer='$matchrowans',marks='$matchmarks',uploadflag='$uploadflag',description='$description' where id='$id'";
	if(mysqli_query($conn,$sql)){		
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_match_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_match set referdoc='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$matchmarks,$oldmarks);		
	}
}
	
if($type=='singlechoice'){
	$singlechoicequestion=addslashes(trim($singlechoicequestion));
	$description=addslashes(trim($description));
	$singlechoiceans=implode('-',$singlechoiceans);	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="update tbl_singlechoice set question='$singlechoicequestion',options='$singlechoiceans',answer='$singleanswer',marks='$singlemarks',description='$description',uploadflag='$uploadflag' where id='$id'";
	
	if(mysqli_query($conn,$sql)){
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_singlechoice_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_singlechoice set referdoc='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$singlemarks,$oldmarks);		
	}
	
}

if($type=='multiplechoice'){
	$multiplechoicequestion=addslashes(trim($multiplechoicequestion));
	$description=addslashes(trim($description));
	$muloption=implode('-',$muloption);	
	$ans=implode('-',$ans);	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql="update tbl_multiplechoice set question='$multiplechoicequestion',options='$muloption',answer='$ans',marks='$multiplemarks',description='$description',uploadflag='$uploadflag' where id='$id'";
	//exit;
	if(mysqli_query($conn,$sql)){
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_mulchoice_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_multiplechoice set referdoc='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$multiplemarks,$oldmarks);			
	}		
}
if($type=='freetext'){
	$freetextquestion=addslashes(trim($freetextquestion));
	$txtanswer=addslashes(trim($txtanswer));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql = "update tbl_freetext set question='$freetextquestion',answer='$txtanswer',marks='$freetextmarks',uploadflag='$uploadflag' where id='$id'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_free_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_freetext set document='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$freetextmarks,$oldmarks);	
	}	
}

if($type=='uploadimage'){		
	$uoloadimagequestion=addslashes(trim($uoloadimagequestion));
	$discription=addslashes(trim($description));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$sql = "update tbl_questiondoc set question='$uoloadimagequestion',marks='$picsmarks',answer='$discription' where id='$id'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['refdoc']['name']!=""){
			$fname=$evid.'_'.$id.'_doc_'.$_FILES['refdoc']['name'];	
			$target = '../../uploads/evaluation/referdoc/'.$fname;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
				$sqlu="update tbl_questiondoc set document='$fname' where id='$id'";
				mysqli_query($conn,$sqlu);
			}
		}
		evaluationUpdate($id,$picsmarks,$oldmarks);		
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

function evaluationUpdate($id,$newmarks,$oldmarks){
	global $conn;
	$diff = $newmarks-$oldmarks;
	$sql="update tbl_evolution set totmarks=totmarks+'$diff' where id='$id'";
	mysqli_query($conn,$sql);
}
?>