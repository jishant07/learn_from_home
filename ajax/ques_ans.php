<?php session_start(); ?>
<?php include '../model/config.php'; ?>
<?php include '../model/functions.php'; ?>

<?php error_reporting(0); ?>
  <?php   
		
		$type=$_GET['type'];
		if($type=='raise'){ 
		$stid = $_SESSION["eid"];	
		 $videoid= $_REQUEST['videoid'];		 
		  $sql="insert into raise_question(stid,vid,flag,created) values('$stid','$videoid','0',NOW())";
		 if(mysqli_query($conn,$sql)){
			$lastid = mysqli_insert_id($conn);
			$teacher = getTeacherVideo($videoid);
			$studentname = getStudentName($stid);
			$comments="New question is raised by student $studentname.";
			$arr=array('from_id'=>$_SESSION['eid'],'from_type'=>$_SESSION['u_type'],'to_id'=>$teacher['teacherid'],'to_type'=>'teacher','page'=>'queans.php','tableid'=>$lastid,'tablename'=>'raise_question','comments'=>$comments,'status'=>'1');
			saveNotification($arr);
			echo '1';
		 }
		 else echo '2';
		} 
		if($type=='changestatus'){
         $status = $_REQUEST["status"];
		 $qsid= $_REQUEST['qsid'];		 
		 $vtitle= $_REQUEST['vtitle'];		 
		 $sql="update raise_question set flag='$status',replied=NOW() where id='$qsid'";
		 if(mysqli_query($conn,$sql)){
		 echo '1';
			if($status==0) $vstatus="Open";
			if($status==1) $vstatus="Approved";
			if($status==2) $vstatus="In Process";
			if($status==3) $vstatus="Solved";
			if($status==4) $vstatus="Banned";
			$sqls="select * from raise_question where id='$qsid'";
			$ress=mysqli_query($conn,$sqls);
			$rows =mysqli_fetch_array($ress);
			$vid=$rows['vid'];
			$comments="Your raise hand status is $vstatus for video $vtitle.";
			$arr=array('from_id'=>$_SESSION['eid'],'from_type'=>$_SESSION['u_type'],'to_id'=>$rows['stid'],'to_type'=>'student','page'=>'video&id='.$vid,'tableid'=>$qsid,'tablename'=>'raise_question','comments'=>$comments,'status'=>'1');
			saveNotification($arr);
		 }
		 else echo '2';
		}
		if($type=='subquestion'){
			$stid = $_SESSION["eid"];	
			$question = addslashes(trim($_REQUEST["question"]));
			$teacherid= $_REQUEST['teacherid'];	
			$videoid= $_REQUEST['videoid'];	
			$rid= $_REQUEST['rid'];	
			$studentname = getStudentName($stid);
			$sql="insert into ask_questions(ecode,qdate,q_vid,q_details,teachervid ,raiseid ) values('$stid',NOW(),'$videoid','$question','$teacherid','$rid')";
			 if(mysqli_query($conn,$sql)){
			 echo '1';
			 $lid=mysqli_insert_id($conn);
			 $comments="New question from student $studentname";
			$arr=array('from_id'=>$_SESSION['eid'],'from_type'=>$_SESSION['u_type'],'to_id'=>$teacherid,'to_type'=>'teacher','page'=>'student_questions.php','tableid'=>$lid,'tablename'=>'ask_questions','comments'=>$comments,'status'=>'1');
			saveNotification($arr);
			 }
			 else echo '2';
			
		}	
		
?>		 
		 