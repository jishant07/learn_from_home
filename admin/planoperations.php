<?php
session_start();
include_once('../model/config.php');
include_once('../model/functions.php');
error_reporting(0);
//$type = $_REQUEST['type'];

$tid = $_SESSION['tid'];
if(!isset($_SESSION['exam']['qcount'])) $_SESSION['exam']['qcount']=0;
if(isset($_SESSION['exam']['secid'])) $secid = $_SESSION['exam']['secid'];
extract($_POST);
extract($_GET);
//echo'type',$type;
if($type=='evaluation'){
	$sqlc="SELECT id FROM tbl_evolution WHERE class='$class' AND date_format(opendate,'%Y-%m-%d')='$sdate'";
	$resc = mysqli_query($conn,$sqlc);
	if(mysqli_num_rows($resc)==0){		
		$starttime = date('H:i:s',strtotime($starttime));
		$endtime = date('H:i:s',strtotime($endtime));
		$newopendate=$sdate.' '.$starttime;
		$newclosedate=$sdate.' '.$endtime;
		
		$sql = "INSERT INTO tbl_evolution(teacher,class,subject,evolutiontype,totmarks,totsections,opendate,closedate,created) 
		VALUES('$tid','$class','$subject','exam','$tot_marks','$totsections','$newopendate','$newclosedate',NOW())";
		//exit;
		mysqli_query($conn,$sql);
		$id = mysqli_insert_id($conn);
		$_SESSION['exam']['evid'] = $id;
		$_SESSION['exam']['totmarks'] = $tot_marks;
		$_SESSION['exam']['totsections'] = $totsections;
		unset($_SESSION['exam']['current']);
		unset($_SESSION['exam']['qcount']);
		unset($_SESSION['exam']['questionsforsection']);
		
		$_SESSION['exam']['tot_marks']=0;
		$_SESSION['exam']['qcount']=0;
		$arr['evid']=$id;
		$comments="Exam is given";		
			$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'exams&id='.$id,'tableid'=>$id,'tablename'=>'tbl_evolution','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
	}
else {
		echo "You have already created exam for this date";		
	}	
	//echo json_encode($arr);
}

if(isset($_SESSION['exam']['getsecid']) && $_SESSION['exam']['getsecid']!=''){
$secid = $_SESSION['exam']['getsecid'];
$sql = "select q.*,s.name,s.marks as totmarks from tbl_questionsections q,tbl_section s where section='$secid' and s.id=q.section";
$res = mysqli_query($conn,$sql);
$arr=array();
$mqarr=array();
$i=0;
while($row=mysqli_fetch_array($res)){
	$marr['marks'][$i]=$row['marks'];
	$qarr['questions'][$i]=$row['questions'];
$i++;	
}
}
else $secid=''; 


$evid = $_SESSION['exam']['evid'];
if($type=='fillblank'){	
	$marks=$fillblankanswermarks;
	$fillblankque=addslashes(trim($fillblankque));
	$description=addslashes(trim($description));
	$fillblankanswer = implode(',',$fillblankanswer);
	
	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';	
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{		
			$sql= "insert into tbl_fillblank(evid,section,question,answer,uploadflag,marks,description)
			values('$evid','$secid','$fillblankque','$fillblankanswer','$uploadflag','$fillblankanswermarks','$description')";
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_fill_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_fillblank set referdoc='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}	
				//evaluationUpdate($evid,$fillblankanswermarks);
				/*$_SESSION['tot_marks']=$_SESSION['tot_marks']+$fillblankanswermarks;
				
				$arr['tot_marks']=$_SESSION['tot_marks'];
				$arr['qcount']=$_SESSION['qcount'];
				*/
				evaluationUpdate($evid);
				$_SESSION['exam']['questionsforsection'][]=$secid;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				//$_SESSION['totq']=$totq;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);
			}
		}
	}
}
//echo $type;exit;
if($type=='matchans'){
	//echo'sdf';
	$qmatch=addslashes(trim($qmatch));
	$description=addslashes(trim($description));
	$matchrowq=implode('-',$matchrowq);
	$matchrowopt=implode('-',$matchrowopt);
	$matchrowans=implode('-',$matchrowans);
	$marks = $matchmarks;
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	//echo '$secid',$secid;
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{
			$sql="INSERT INTO tbl_match(evid,section,question,cols1,cols2,answer,marks,uploadflag,description) VALUES('$evid','$secid','$qmatch','$matchrowq','$matchrowopt','$matchrowans','$matchmarks','$uploadflag','$description')";
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_match_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_match set referdoc='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}	
				evaluationUpdate($evid);
				$_SESSION['exam']['tot_marks']=$_SESSION['exam']['tot_marks']+$matchmarks;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;
				$arr['tot_marks']=$_SESSION['exam']['tot_marks'];
				$arr['qcount']=$_SESSION['exam']['qcount'];
				
				$_SESSION['exam']['questionsforsection'][]=$secid;
				//$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				//$_SESSION['totq']=$totq;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);
				//echo json_encode($arr);
			}
		}
	}	
}
	
if($type=='singlechoice'){
	$singlechoicequestion=addslashes(trim($singlechoicequestion));
	$description=addslashes(trim($description));
	$singlechoiceans=implode('-',$singlechoiceans);	
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$marks = $singlemarks;
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{	
			$sql="INSERT INTO tbl_singlechoice(evid,section,question,options,answer,marks,description,uploadflag) VALUES('$evid','$secid','$singlechoicequestion','$singlechoiceans','$singleanswer','$singlemarks','$description','$uploadflag')";
			
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_singlechoice_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_singlechoice set referdoc='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}
				evaluationUpdate($evid);
				$_SESSION['exam']['tot_marks']=$_SESSION['exam']['tot_marks']+$singlemarks;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;				
				$arr['tot_marks']=$_SESSION['exam']['tot_marks'];
				$arr['qcount']=$_SESSION['exam']['qcount'];
				$_SESSION['exam']['questionsforsection'][]=$secid;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);
				//echo json_encode($arr);
			}
		}
	}	
}

if($type=='multiplechoice'){
	$multiplechoicequestion=addslashes(trim($multiplechoicequestion));
	$description=addslashes(trim($description));
	$muloption=implode('-',$muloption);	
	$ans=implode('-',$ans);	
	$marks = $multiplemarks;
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{	
			$sql="INSERT INTO tbl_multiplechoice(evid,section,question,options,answer,marks,description,uploadflag) VALUES('$evid','$secid','$multiplechoicequestion','$muloption','$ans','$multiplemarks','$description','$uploadflag')";
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_mulchoice_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_multiplechoice set referdoc='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}
				evaluationUpdate($evid);
				$_SESSION['exam']['tot_marks']=$_SESSION['exam']['tot_marks']+$multiplemarks;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;
				$_SESSION['exam']['questionsforsection'][]=$secid;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);	
			}
		}
	}	
}
if($type=='freetext'){
	$freetextquestion=addslashes(trim($freetextquestion));
	$txtanswer=addslashes(trim($txtanswer));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$marks = $freetextmarks;
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{
			$sql = "INSERT INTO tbl_freetext(evid,section,question,answer,marks,uploadflag) VALUES ('$evid','$secid','$freetextquestion','$txtanswer','$freetextmarks','$uploadflag')";
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_free_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_freetext set document='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}
				evaluationUpdate($evid);
				$_SESSION['exam']['tot_marks']=$_SESSION['exam']['tot_marks']+$freetextmarks;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;	
				$_SESSION['exam']['questionsforsection'][]=$secid;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);
			}
		}
	}	
}

if($type=='uploadimage'){		
	$uoloadimagequestion=addslashes(trim($uoloadimagequestion));
	$discription=addslashes(trim($discription));
	if(isset($uploadflag))$uploadflag='1';else $uploadflag='0';
	$marks = $picsmarks;
	if($secid!=''){
		$key = array_search ($marks, $marr['marks']);
		if(!isset($_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']))		
		{
			$totq = 1;
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=1;
		}
		else { 
			$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']=$_SESSION['exam']['marks'.$marks.'-'.$secid]['totq']+1;
			$totq = $_SESSION['exam']['marks'.$marks.'-'.$secid]['totq'];
		}
		//echo $totq,'::',$qarr['questions'][$key];//exit;
		if($totq>$qarr['questions'][$key]) {echo 'invalid';exit;}
		else{
			$sql = "INSERT INTO tbl_questiondoc(evid,section,question,marks,answer,uploadflag) VALUES ('$evid','$secid','$uoloadimagequestion','$picsmarks','$discription','1')";
			if(mysqli_query($conn,$sql)){
				$lid = mysqli_insert_id($conn);
				if($_FILES['refdoc']['name']!=""){
					$fname=$evid.'_'.$lid.'_doc_'.$_FILES['refdoc']['name'];	
					$target = '../uploads/evaluation/referdoc/'.$fname;
					if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$target)){
						$sqlu="update tbl_questiondoc set document='$fname' where id='$lid'";
						mysqli_query($conn,$sqlu);
					}
				}
				evaluationUpdate($evid);
				$_SESSION['exam']['tot_marks']=$_SESSION['exam']['tot_marks']+$picsmarks;
				$_SESSION['exam']['qcount']=$_SESSION['exam']['qcount']+1;	
				$_SESSION['exam'][$secid]['qcount']=$_SESSION['exam'][$secid]['qcount']+1;
				$_SESSION['exam']['totq'.$marks.'-'.$secid]=$totq;
				$_SESSION['exam']['questionsforsection'][]=$secid;
				$ret[]=$totq;
				$ret[]=$marks;
				$ret[]=$_SESSION['exam']['qcount'];				
				echo json_encode($ret);
			}
		}
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

function evaluationUpdate($id){
	global $conn;
	$sql="update tbl_evolution set totquestions=totquestions+1 where id='$id'";
	mysqli_query($conn,$sql);
}
?>