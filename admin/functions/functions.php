<?php

function logout(){
	unset($_SESSION);
	session_destroy();
	header("location:login.php");exit;	
}

function saveNotification($vals){
	global $conn;
	//print_r($vals);
	$key=implode(',',array_keys($vals));
	$val=implode("','",array_values($vals));
	$sql="insert into notifications($key,created) values('$val',NOW())";
	$res = mysqli_query($conn,$sql);
}


function getTeacherClasses($tid=''){
	global $conn;
	if($tid=='')
	$tid = $_SESSION['tid'];
	$sql="SELECT DISTINCT(classroom) FROM teacher_assign WHERE teacher_id='$tid'";
	$res = mysqli_query($conn,$sql);
	$arr = array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['classroom'] = $row['classroom'];	
		$i++;	
	}
	//print_r($arr);
	return $arr;
}

function getTeacherSubjectByClasses($tid){
	global $conn;
	//$tid = $_SESSION['tid'];
	$sql="SELECT subject,subject_name FROM subjects s, teacher_assign t WHERE teacher_id='$tid' AND t.subject=s.subject_id";
	$res = mysqli_query($conn,$sql);
	$arr = array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['subject'] = $row['subject'];	
		$arr[$i]['subject_name'] = $row['subject_name'];	
		$i++;	
	}
	//print_r($arr);
	return $arr;
}
function getClassName(& $id){
	global $conn;
	$sql="select class_name from classrooms where class_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['class_name'];
}

function getAllClasses(){
	global $conn;
	$sql="select * from classrooms";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);	
}

function getClassTeacher(& $cid){
	global $conn;
	$sql="select t_name,t_id,t_pic from teachers t,classrooms c where class_id='$cid' and class_teacher=t_id";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	$arr['t_name']=$row['t_name'];
	$arr['t_id']=$row['t_id'];
	if($row['t_pic'])
		$arr['t_pic']='../uploads/teacher/'.$row['t_pic'];
	else $arr['t_pic']='../uploads/avtar.png';
	return $arr;
}
function getClassTeachers(& $clid){
	global $conn;
	//$sq="SELECT assign_id,t_id,t.t_name,t_pic,s.subject_name,a.subject FROM teacher_assign a ,teachers t,subjects s WHERE a.teacher_id=t.t_id AND a.subject=s.subject_id AND a.classroom='$clid' ";
	$sq="SELECT t_id,t_name,t_pic FROM teachers WHERE FIND_IN_SET('$clid' , t_classname) ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		//$arr[$i]['assign_id']= $row['assign_id'];
		$arr[$i]['t_id']= $row['t_id'];
		$arr[$i]['t_name']= $row['t_name'];
		if($row['t_pic']!='')	
		$arr[$i]['t_pic']= '../uploads/teacher/'.$row['t_pic'];	
		else $arr[$i]['t_pic']= '../uploads/avtar.png';
		//$arr[$i]['subject_name']= $row['subject_name'];
		//$arr[$i]['subjidectid']= $row['subject'];
		$i++;
	}
	
	return $arr;
}

function getAssignSubjectByTeacher(& $tid,& $cid){
	global $conn;
	$sql="select subject,subject_name from teacher_assign a,subjects s where teacher_id='$tid' and classroom='$cid' and a.subject=subject_id";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);
	
}
function getAllStudents(& $class){
	global $conn;
	$sql="select std_id,ecode,roll_no,student_name,image,gender from students where dept_id='$class' and status=1";
	$res=mysqli_query($conn,$sql);
	$i=0;
	$f=0;
	$m=0;
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['std_id']=$row['std_id'];
	$arr[$i]['ecode']=$row['ecode'];
	$arr[$i]['gender']=$row['gender'];
	$arr[$i]['roll_no']=$row['roll_no'];
	$arr[$i]['student_name']=$row['student_name'];
	if($row['image']!='')
	$arr[$i]['image']='../uploads/images/students/'.$row['image'];
	else $arr[$i]['image']='../uploads/avtar.png';
	if($row['gender']=='Female') $f++; else $m++; 
	$i++;
	}
	$arr[$i]['Female']=	$f;
	$arr[$i]['Male']=	$m;
	return $arr;
}

function getSubject(& $id){
	global $conn;
	$sql="select subject_name from subjects where subject_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['subject_name'];
}

function getStudentInfo(& $id){
	global $conn;
	$sql="select student_name,student_lastname,image from students where ecode='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}

function getAllSubjectsByClass($class=''){
	global $conn;
	if(isset($_GET['class'])) $class=$_GET['class'];
	$sq="SELECT * FROM subjects where subject_isactive ='1' and subject_class='$class'";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['subject_id']= $row['subject_id'];	
		$arr[$i]['subject_name']= $row['subject_name'];	
		$arr[$i]['subject_createdat']= $row['subject_createdat'];
		$arr[$i]['sthumb']= $row['sthumb'];
		$i++;
	}
	return $arr;
}

function getAllSubjects(){
	global $conn;
	$sq="SELECT * FROM subjects where subject_isactive ='1' ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['subject_id']= $row['subject_id'];	
		$arr[$i]['subject_name']= $row['subject_name'];	
		$arr[$i]['subject_createdat']= $row['subject_createdat'];
		$arr[$i]['sthumb']= $row['sthumb'];
		$i++;
	}
	return $arr;
}

function getClassSubjects(& $clid){
	global $conn;
	$sq="SELECT s.* FROM subjects s , classrooms c where s.subject_class=c.class_id AND s.subject_class='$clid' AND subject_isactive ='1' ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['subject_id']= $row['subject_id'];	
		$arr[$i]['subject_name']= $row['subject_name'];	
		$arr[$i]['chapters']= $row['chapters'];
		$arr[$i]['sections']= $row['sections'];
		$arr[$i]['subject_createdat']= $row['subject_createdat'];
		$arr[$i]['sthumb']= $row['sthumb'];
		$i++;
	}
	return $arr;
}

function getClassSubjectsNotAssigned(& $clid){
	global $conn;
	$sq="SELECT s.* FROM subjects s , classrooms c where s.subject_class=c.class_id AND s.subject_class='$clid' AND subject_isactive ='1' and subject_id not in(select subject from teacher_assign where classroom='$clid') ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['subject_id']= $row['subject_id'];	
		$arr[$i]['subject_name']= $row['subject_name'];	
		$arr[$i]['chapters']= $row['chapters'];
		$arr[$i]['sections']= $row['sections'];
		$arr[$i]['subject_createdat']= $row['subject_createdat'];
		$arr[$i]['sthumb']= $row['sthumb'];
		$i++;
	}
	return $arr;
}

function tmp_topBreadCrumb($breadcrumb){
	//print_r($_SESSION);exit;
	if($_SESSION['u_type']=='teacher'){
		$tid = $_SESSION['tid'];
		$subjects =getTeacherSubjectByClasses($tid);
	}
	else $subjects =getAllSubjectsByClass();
	//print_r($subjects);
	if(isset($_GET['subject']) && $_GET['subject']!='') $selsubject=$_GET['subject']; else $selsubject='';
	?>	
	<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
		<nav class="page-breadcrumb">
		<ol class="breadcrumb">
		  <!--li class="breadcrumb-item"><a href="#">Class 1</a></li-->
		  <?php foreach($breadcrumb as $k=>$v){?>
		  <li class="breadcrumb-item active" aria-current="page"><?=$k?></li>
		  <?php } ?>
		</ol>
		</nav>
		<div class="d-flex align-items-center flex-wrap text-nowrap">
			<select class="form-control" id="selsubject" name="selsubject">
				<?php for($s=0; $s<count($subjects); $s++){
					if($_SESSION['u_type']=='teacher') $subid=$subjects[$s]['subject'];
					else $subid=$subjects[$s]['subject_id'];
					?>
				<option value="<?=$subid?>"><?=$subjects[$s]['subject_name']?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	<input type='hidden' name='hselsubjectid' id='hselsubjectid' value="<?=$selsubject?>"> 
	
	<?php
	
}
/*
function getLiveSession(& $classid,& $subjectid){
	global $conn;
	
	echo $sql="select vid_id,ref_doc,vtitle,vdesc,sub_start_at,sub_end_at from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_class='$classid' AND vid_sub='$subjectid' AND (sub_start_at<=NOW() and sub_end_at>=NOW()) order by sub_start_at asc limit 1 ";
	//exit;
	$res = mysqli_query($conn,$sql);
	
	$arr=array();
	if(mysqli_num_rows($res)>0){
	$row = mysqli_fetch_array($res);
	$arr['id']=$row['vid_id'];
	$arr['ref_doc']=$row['ref_doc'];
	$arr['sub_start_at']=$row['sub_start_at'];
	$arr['sub_end_at']=$row['sub_end_at'];
	$arr['vtitle']=stripslashes(trim($row['vtitle']));
	}
	print_r($arr);	
	return $arr;
}
*/

function getVideoInfo(& $vid){
	global $conn;
	$sql = "select * from video where vid_id='$vid'";
	$res= mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row;	
}
function getLiveSessions(& $classid,& $subjectid){
	global $conn;
	if($_SESSION['u_type']=='teacher'){
	$tid = $_SESSION['tid'];
	$sql="select v.* from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_teacher='$tid' and vid_class='$classid' AND vid_sub='$subjectid' order by sub_start_at asc";
	}
	else{
		$sql="select v.* from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_class='$classid' AND vid_sub='$subjectid' order by sub_start_at asc";
	}
	$res = mysqli_query($conn,$sql);
	$arr['live']=array();
	$arr['upcomming']=array();
	$arr['past']=array();
	$i=0;
	$j=0;
	while($row = mysqli_fetch_array($res)){
		if($row['sub_start_at']<=date('Y-m-d H:i:s') && $row['sub_end_at']>=date('Y-m-d H:i:s')){
			$arr['live']['id']=$row['vid_id'];
			$arr['live']['sub_start_at']=$row['sub_start_at'];
			$arr['live']['sub_end_at']=$row['sub_end_at'];
			$arr['live']['ref_doc']=$row['ref_doc'];
			$arr['live']['vtitle']=stripslashes(trim($row['vtitle']));
		}
		if($row['sub_start_at']>date('Y-m-d H:i:s')){
			$arr['upcomming'][$i]['id']=$row['vid_id'];
			$arr['upcomming'][$i]['sub_start_at']=$row['sub_start_at'];
			$arr['upcomming'][$i]['sub_end_at']=$row['sub_end_at'];
			$arr['upcomming'][$i]['ref_doc']=$row['ref_doc'];
			$arr['upcomming'][$i]['vtitle']=stripslashes(trim($row['vtitle']));
			$i++;
		}
		if($row['sub_end_at']<date('Y-m-d H:i:s')){
			$arr['past'][$j]['id']=$row['vid_id'];
			$arr['past'][$j]['sub_start_at']=$row['sub_start_at'];
			$arr['past'][$j]['sub_end_at']=$row['sub_end_at'];
			$arr['past'][$j]['ref_doc']=$row['ref_doc'];
			$arr['past'][$j]['totalwatched']=$row['totalwatched'];
			$arr['past'][$j]['vtitle']=stripslashes(trim($row['vtitle']));
			$j++;
		}		
	}
	return $arr;
}


function checkTimeSlot($vid=''){
	global $conn;
	$sdate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['start_time']));
	$edate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['end_time']));
	if($vid!='') $cond=" and vid_id!='$vid'"; else $cond="";
	$sql="select vid_id from video where ('$sdate' between sub_start_at and sub_end_at ) and ('$edate' between sub_start_at and sub_end_at ) $cond";
	$res = mysqli_query($conn,$sql);
	//echo 'mysqli_num_rows ',mysqli_num_rows($res);
	if(mysqli_num_rows($res)>0) return true; else return false;
}
function addVideo(){
	global $conn;
	if(checkTimeSlot()) {echo 'This time slot is already assigned to another session. Please select another time slot'; exit;}
	
	$vid_class = $_POST['vid_class'];
	$vtitle = addslashes(trim($_POST['vtitle']));
	$description = addslashes(trim($_POST['description']));
	$vid_teacher = $_POST['vid_teacher'];
	$vlink = '';
	$sdate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['start_time']));
	$edate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['end_time']));
	$vid_sub = $_POST['vid_sub'];
	$vid_format = $_POST['livevideo'];
	if($vid_format=='link')$vlink = trim($_POST['vlink']);
	$sql_vac = "INSERT INTO video SET vid_type='live', vid_class='$vid_class', vtitle='$vtitle', vdesc='$description',vid_format='$vid_format', aws_link='$vlink', sub_start_at='$sdate', sub_end_at='$edate', vid_teacher='$vid_teacher' , vid_sub='$vid_sub'";
	//echo $sql_vac;exit;

	if ($conn->query($sql_vac) == true) {
		$vid_id = $conn->insert_id;
		$comments=$_POST['vtitle']." video is updated. ";		
		$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'live-video&id='.$vid_id,'tableid'=>$vid_id,'tablename'=>'video','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
		$refdoc=$_FILES['refdoc']['name'];
		if($refdoc!=''){
			$fileType = strtolower(pathinfo($refdoc,PATHINFO_EXTENSION));
			$fname= "doc_".$vid_id.".$fileType";
			$ref_dir = "../uploads/videos/refdoc/$fname";
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE video SET `ref_doc`='$fname' WHERE `vid_id`='$vid_id'");
			}
		}
		if($vid_format=='video'){
			$maxsize_vid = 52428800; // 50MB
			$vid_name = $_FILES['myDropify']['name'];
			$target_vid_dir = "../uploads/videos/ondemand/";
			$target_vid_file = $target_vid_dir . $_FILES["myDropify"]["name"];
			// Select file type
			$videoFileType = strtolower(pathinfo($target_vid_file,PATHINFO_EXTENSION));
			if(($_FILES['myDropify']['size'] >= $maxsize_vid) || ($_FILES['myDropify']["size"] == 0)){
				echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>File too large. File must be less than 50MB.</div>";
			}
			else
			{
				// Upload
				if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target_vid_file)){
				$update_record1 = "UPDATE video SET `vid_path`='$vid_name' WHERE `vid_id`='$vid_id'";
				$conn->query($update_record1);
				// echo $update_record1;
					//echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Video Updated Successfully</div>";
				}
			}
		}
		
	}
}
function courseAddVideo(){
	global $conn;
	//print_r($_FILES);exit;
	$vid_class = $_POST['vid_class'];
	$vtitle = addslashes(trim($_POST['title']));
	$description = addslashes(trim($_POST['description']));
	$vid_teacher = $_SESSION['tid'];
	$vlink = '';
	
	
	$vid_sub = $_POST['vid_sub'];
	$vid_format = $_POST['livevideo'];
	if($vid_format=='link')$vlink = trim($_POST['vlink']);
	if($vid_format=='live'){
		$vlink = trim($_POST['sessionvideo']);
		$vid_format='link';
	}
	$sql_vac ="INSERT INTO courses_videos SET title='$vtitle',description='$description',vid_format='$vid_format', vlink='$vlink', teacher='$vid_teacher' , class='$vid_class',subject='$vid_sub'";
	//echo $sql_vac;exit;

	if ($conn->query($sql_vac) == true) {
		$lastid=$conn->insert_id;
		$cvideos = getVideoInCourse($vid_class);
		if($cvideos==''){
			$conn->query("UPDATE courses SET videos='$lastid' WHERE id='$vid_class'");	
		} else{
			$cvideos = $cvideos.','.$lastid;
			$conn->query("UPDATE courses SET videos='$cvideos' WHERE id='$vid_class'");	
		}
		$comments=$vtitle." course video is uploaded.";		
		$arr=array('from_id'=>$vid_teacher,'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'history-videos&id='.$lastid,'tableid'=>$lastid,'tablename'=>'courses_videos','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
		$myDropify2=$_FILES['myDropify2']['name'];
		if($myDropify2!=''){
			$fileType = strtolower(pathinfo($myDropify2,PATHINFO_EXTENSION));
			$fname= $lastid."-".$myDropify2;
			$ref_dir = "../uploads/images/coursevideos/$fname";
			if(move_uploaded_file($_FILES['myDropify2']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE courses_videos SET `vthumb`='$fname' WHERE `id`='$lastid'");
			}
		}
		$refdoc=$_FILES['refdoc']['name'];
		if($refdoc!=''){
			$fileType = strtolower(pathinfo($refdoc,PATHINFO_EXTENSION));
			$fname= "doc_".$lastid.".$fileType";
			$ref_dir = "../uploads/coursedocuments/$fname";
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE courses_videos SET `document`='$fname' WHERE `id`='$lastid'");
			}
		}
		if($vid_format=='video'){
			$maxsize_vid = 52428800; // 50MB
			$vid_name = $_FILES['myDropify']['name'];
			$target_vid_dir = "../uploads/videos/course/";
			$target_vid_file = $target_vid_dir . $_FILES["myDropify"]["name"];
			// Select file type
			$videoFileType = strtolower(pathinfo($target_vid_file,PATHINFO_EXTENSION));
			if(($_FILES['myDropify']['size'] >= $maxsize_vid) || ($_FILES['myDropify']["size"] == 0)){
				echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>File too large. File must be less than 50MB.</div>";
			}
			else
			{
				// Upload
				if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target_vid_file)){
				$update_record1 = "UPDATE courses_videos SET `videofile`='$vid_name' WHERE `id`='$lastid'";
				$conn->query($update_record1);
				// echo $update_record1;
					//echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Video Updated Successfully</div>";
				}
			}
		}		
	}
}

function getVideoInCourse(& $cid){
	global $conn;
	$sql="select videos from courses where id='$cid'";
	$rs = mysqli_query($conn,$sql);
	if(mysqli_num_rows($rs)){
		$row=mysqli_fetch_array($rs);
		return $row['videos'];
	}
}

function removeVideoFromCourse($prevcourseid,$vid_id){
	global $conn;
	$sq = "update courses set videos = REPLACE(videos,',$vid_id','') where id='$prevcourseid'";
	$sq1 = "update courses set videos = REPLACE(videos,'$vid_id','') where id='$prevcourseid'";
	//exit;
	$conn->query($sq);
	$conn->query($sq1);
	$conn->query("update courses set videos = REPLACE(videos,',,',',') where id='$prevcourseid'");
}
function courseUpdateVideo(){
	global $conn;
	//print_r($_FILES);exit;
	$vid_id = $_POST['vid_id'];
	
	$course = $_POST['course'];
	$vid_class = $_POST['vid_class'];
	$vtitle = addslashes(trim($_POST['title']));
	$description = addslashes(trim($_POST['description']));
	$vid_teacher = $_POST['vid_teacher'];
	$vlink = '';
	
	
	$vid_sub = $_POST['vid_sub'];
	$vid_format = $_POST['livevideo'];
	if($vid_format=='link')$vlink = trim($_POST['vlink']);
	if($vid_format=='live'){
		$vlink = trim($_POST['sessionvideo']);
		$vid_format='link';
	}
	$sql_vac = "UPDATE courses_videos SET title='$vtitle', description='$description',vid_format='$vid_format', vlink='$vlink', teacher='$vid_teacher' , subject='$vid_sub' WHERE id='$vid_id'  ";
	//echo $sql_vac;exit;

	if ($conn->query($sql_vac) == true) {	
		$prevcourseid = getCourseIdByVideo($vid_id);
		if($prevcourseid!=$course){
			$cvideos = getVideoInCourse($course);
			if($cvideos==''){
				$conn->query("UPDATE courses SET videos='$vid_id' WHERE id='$course'");	
			} else{
				$cvideos = $cvideos.','.$vid_id;
				$conn->query("UPDATE courses SET videos='$cvideos' WHERE id='$course'");					
			}
			removeVideoFromCourse($prevcourseid,$vid_id);
		}
		$comments=$vtitle." course video is updated. ";		//$arr=array('from_id'=>$_SESSION['uid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'live-video&id='.$_POST['vid_id'],'tableid'=>$_POST['vid_id'],'tablename'=>'video','comments'=>$comments,'status'=>'1');
		
		$myDropify2=$_FILES['myDropify2']['name'];
		if($myDropify2!=''){
			$fileType = strtolower(pathinfo($myDropify2,PATHINFO_EXTENSION));
			$fname= $vid_id."-".$myDropify2;
			$ref_dir = "../uploads/coursedocuments/$fname";
			if(move_uploaded_file($_FILES['myDropify2']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE courses_videos SET `vthumb`='$fname' WHERE `id`='$vid_id'");
			}
		}
		$refdoc=$_FILES['refdoc']['name'];
		if($refdoc!=''){
			$fileType = strtolower(pathinfo($refdoc,PATHINFO_EXTENSION));
			$fname= "doc_".$vid_id.".$fileType";
			$ref_dir = "../uploads/coursedocuments/$fname";
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE courses_videos SET `document`='$fname' WHERE `id`='$vid_id'");
			}
		}
		if($vid_format=='video'){
			$maxsize_vid = 52428800; // 50MB
			$vid_name = $_FILES['myDropify']['name'];
			$target_vid_dir = "../uploads/videos/course/";
			$target_vid_file = $target_vid_dir . $_FILES["myDropify"]["name"];
			// Select file type
			$videoFileType = strtolower(pathinfo($target_vid_file,PATHINFO_EXTENSION));
			if(($_FILES['myDropify']['size'] >= $maxsize_vid) || ($_FILES['myDropify']["size"] == 0)){
				echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>File too large. File must be less than 50MB.</div>";
			}
			else
			{
				// Upload
				if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target_vid_file)){
				$update_record1 = "UPDATE courses_videos SET `videofile`='$vid_name' WHERE `id`='$vid_id'";
				$conn->query($update_record1);
				// echo $update_record1;
					//echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Video Updated Successfully</div>";
				}
			}
		}		
	}
}
function updateVideo(){
	global $conn;
	//print_r($_FILES);exit;
	$vid_id = $_POST['vid_id'];
	if(checkTimeSlot($vid_id)) {echo 'This time slot is already assigned to another session. Please select another time slot'; exit;}
	
	
	$vid_class = $_POST['vid_class'];
	$vtitle = addslashes(trim($_POST['vtitle']));
	$description = addslashes(trim($_POST['description']));
	$vid_teacher = $_POST['vid_teacher'];
	$vlink = '';
	$sdate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['start_time']));
	$edate = $_POST['sub_start_date'].' '.date('H:i:s',strtotime($_POST['end_time']));
	
	
	$vid_sub = $_POST['vid_sub'];
	$vid_format = $_POST['livevideo'];
	if($vid_format=='link')$vlink = trim($_POST['vlink']);
	$sql_vac = "UPDATE video SET vid_class='$vid_class', vtitle='$vtitle', vdesc='$description',vid_format='$vid_format', aws_link='$vlink', sub_start_at='$sdate', sub_end_at='$edate', vid_teacher='$vid_teacher' , vid_sub='$vid_sub' WHERE vid_id='$vid_id'  ";
	//echo $sql_vac;exit;

	if ($conn->query($sql_vac) == true) {	
		$comments=$_POST['vtitle']." video is updated. ";		//$arr=array('from_id'=>$_SESSION['uid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'live-video&id='.$_POST['vid_id'],'tableid'=>$_POST['vid_id'],'tablename'=>'video','comments'=>$comments,'status'=>'1');
		
		$refdoc=$_FILES['refdoc']['name'];
		if($refdoc!=''){
			$fileType = strtolower(pathinfo($refdoc,PATHINFO_EXTENSION));
			$fname= "doc_".$vid_id.".$fileType";
			$ref_dir = "../uploads/videos/refdoc/$fname";
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ref_dir)){
				$conn->query("UPDATE video SET `ref_doc`='$fname' WHERE `vid_id`='$vid_id'");
			}
		}
		if($vid_format=='video'){
			$maxsize_vid = 52428800; // 50MB
			$vid_name = $_FILES['myDropify']['name'];
			$target_vid_dir = "../uploads/videos/ondemand/";
			$target_vid_file = $target_vid_dir . $_FILES["myDropify"]["name"];
			// Select file type
			$videoFileType = strtolower(pathinfo($target_vid_file,PATHINFO_EXTENSION));
			if(($_FILES['myDropify']['size'] >= $maxsize_vid) || ($_FILES['myDropify']["size"] == 0)){
				echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>File too large. File must be less than 50MB.</div>";
			}
			else
			{
				// Upload
				if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target_vid_file)){
				$update_record1 = "UPDATE video SET `vid_path`='$vid_name' WHERE `vid_id`='$vid_id'";
				$conn->query($update_record1);
				// echo $update_record1;
					//echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Video Updated Successfully</div>";
				}
			}
		}		
	}
}

function deleteCourse(& $id){
	global $conn;
	
	$conn->query("DELETE FROM courses WHERE `id`='$id'");
}
function deleteVideo(& $id){
	global $conn;
	$conn->query("DELETE FROM video WHERE `vid_id`='$id'");
}

function getVideo(& $id){
	global $conn;
	
	$sql_vid = $conn->query("select * from video where vid_id=$id");
	$row_vid=$sql_vid->fetch_assoc();
	$vid_fmrt = $row_vid['vid_format'];
	if($vid_fmrt=='link'){
	  $arr['videolink'] =  $row_vid['aws_link'] ;
	}
	else{
	  $arr['videolink'] = "../uploads/videos/ondemand/".$row_vid['vid_path'] ;
	}
	$arr['vid_id']=$row_vid['vid_id'] ;
	$arr['vc_id']=$row_vid['vc_id'] ;
	$arr['vid_type']=$row_vid['vid_type'] ;
	//$arr['vid_div']=$row_vid['vid_div'] ;
	$arr['vid_sub']=$row_vid['vid_sub'] ;
	$arr['vid_class']=$row_vid['vid_class'] ;
	//$arr['vid_classtype']=$row_vid['vid_classtype'] ;
	$arr['vid_format']=$row_vid['vid_format'] ;
	$arr['vtitle']=trim($row_vid['vtitle']) ;
	$arr['vid_teacher']=$row_vid['vid_teacher'] ;
	$arr['vdesc']=stripslashes(trim($row_vid['vdesc']));
	$arr['aws_link']=$row_vid['aws_link'] ;
	$arr['ref_doc']="../uploads/videos/refdoc/".$row_vid['ref_doc'] ;
	//$arr['vthumb']=$filelocation.'uploads/images/videothumb/'.$row_vid['vthumb'];
	$arr['sub_start_at']=$row_vid['sub_start_at'] ;
	$arr['sub_end_at']=$row_vid['sub_end_at'] ;
	//$arr['scheduled_date']=$row_vid['scheduled_date'] ;
	$arr['enb']=$row_vid['enb'] ;
	return $arr;	
}
function watchingVideos(& $video){
	global $conn;
	$sql = "SELECT student_name,ecode,image FROM session_watching w, students s WHERE video='$video' and w.student=s.ecode";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['student_name']=$row['student_name'];
		$arr[$i]['ecode']=$row['ecode'];
		if($row['image']!='')
		$arr[$i]['image']='../uploads/images/students/'.$row['image'];
		$arr[$i]['image']='../uploads/avtar.png';
	}
	return $arr;
}

function checkRaise(& $std,& $vid){
	global $conn;
	$sql = "SELECT flag FROM raise_question WHERE vid='$vid' and stid='$std'";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		$row = mysqli_fetch_array($res);
		return $row['flag'];
	}
}

function getCourse(& $id){
	global $conn;
	$sql = "SELECT * FROM courses where id='$id'";
	$res = mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);		
	return $row;
}

function getCourses(& $class, & $subject){
	global $conn;
	
	$tid = $_SESSION['tid'];
	$sql = "SELECT * FROM courses where class='$class' and subject='$subject' order by id desc";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['name']=$row['name'];
		$arr[$i]['description']=$row['description'];
		$arr[$i]['class']=$row['class'];
		$arr[$i]['subject']=$row['subject'];
		$arr[$i]['chapter']=$row['chapter'];
		if($row['videos']!='')	
		$arr[$i]['videocount']=count(explode(',',$row['videos']));
		else $arr[$i]['videocount']=0;
		$arr[$i]['cthumb']=$row['cthumb'];
		$arr[$i]['created']=$row['created'];
		$arr[$i]['createdby']=$row['createdby'];
		$arr[$i]['status']=$row['status'];
		$i++;		
	}
	return $arr;
}
function addCourse(){
	global $conn;
	$u_type = $_SESSION['u_type'];
	$tid = $_SESSION['tid'];
	//print_r($_POST);exit;
	extract($_POST);
	$title = addslashes(trim($title));
	$sql = "INSERT INTO courses SET name='$title',class='$classid',subject='$subject',created=NOW(),createdby='$u_type',userid='$tid'";
	$res = mysqli_query($conn,$sql);
	$lastid = mysqli_insert_id($conn);
	$comments=$title." course is added. ";	
	$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'history-videos&cid'.$lastid,'tableid'=>$lastid,'tablename'=>'courses','comments'=>$comments,'status'=>'1');
	saveNotification($arr);

	
	if($_FILES['myDropify']['name']!=''){
		$thumb = $lastid."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/images/courses/'.$thumb;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE courses SET `cthumb`='$thumb' WHERE `id`='$lastid'";
			$conn->query($update_record);
		}		
	}
}
function updateCourse(){
	global $conn;
	//print_r($_FILES);exit;
	extract($_POST);
	$title = addslashes(trim($title));
	$sql = "UPDATE courses SET name='$title' where id='$id'";
	$res = mysqli_query($conn,$sql);
	if($_FILES['myDropify']['name']!=''){
		$thumb = $id."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/images/courses/'.$thumb;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE courses SET `cthumb`='$thumb' WHERE `id`='$id'";
			$conn->query($update_record);
		}		
	}
}

function getCourseVideo(& $id){
	global $conn;
	$tid = $_SESSION['tid'];
	$sql = "SELECT * FROM courses_videos where id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_array($res);
}

function getCourseVideos(& $class, & $subject){
	global $conn;
	if($_SESSION['u_type']=='teacher'){
	$tid = $_SESSION['tid'];
	$sql = "SELECT * FROM courses_videos where class='$class' and subject='$subject' and teacher='$tid' order by id desc";
	}
	else{
		$sql = "SELECT * FROM courses_videos where class='$class' and subject='$subject' order by id desc";
	}
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['title']=$row['title'];
		$arr[$i]['description']=$row['description'];
		$arr[$i]['class']=$row['class'];
		$arr[$i]['subject']=$row['subject'];
		$arr[$i]['vlink']=$row['vlink'];
		$arr[$i]['vthumb']=$row['vthumb'];
		$arr[$i]['created']=$row['created'];
		$arr[$i]['createdby']=$row['createdby'];
		$arr[$i]['document']=$row['document'];
		$arr[$i]['status']=$row['status'];
		$arr[$i]['course']=getCourseByVideo($row['id']);
		$i++;		
	}
	return $arr;
}

function getCourseByVideo(& $vid){
	global $conn;
	$sql = "SELECT name FROM courses where FIND_IN_SET('$vid',videos)";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		$row = mysqli_fetch_array($res);
		return stripslashes($row['name']);
	}
	return '';	
}

function getCourseIdByVideo(& $vid){
	global $conn;
	$sql = "SELECT id FROM courses where FIND_IN_SET('$vid',videos)";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		$row = mysqli_fetch_array($res);
		return stripslashes($row['id']);
	}
	return '';	
}

function sel_courses($classid,$subject,$cvid=''){
	global $conn;
	$selcid ='';
	if($cvid!='') $selcid = getCourseIdByVideo($cvid);
	//echo 'selcid:',$cvid;
	$sql= "SELECT name,id FROM courses where class='$classid' and subject='$subject'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
	echo "<select name='course' id='course' class='form-control mb-3'>";
	  echo"<option value=''>Select course</option>" ;
	  while($row = $result->fetch_assoc()) {
		$t_name = $row['name'];
		$t_id =  $row['id'];
		
	  if($selcid!='' && $selcid==$t_id){
		echo"<option value='$t_id' selected>$t_name</option>" ;
	  }
	  else{
		echo"<option value='$t_id'>$t_name</option>" ;
	  }
	  }
	echo "</select>";
	}
}

function sel_subjects($classid,$subject=''){
	global $conn;
	$sql= "SELECT subject_name,subject_id FROM subjects where subject_isactive='1'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
	echo "<select name='subject' id='subject' class='form-control mb-3'>";
	  echo"<option value=''>Select subject</option>" ;
	  while($row = $result->fetch_assoc()) {
		$sumbject_name = $row['subject_name'];
		$subject_id =  $row['subject_id'];
		
	  if($subject!='' && $subject==$subject_id){
		echo"<option value='$subject_id' selected>$sumbject_name</option>" ;
	  }
	  else{
		echo"<option value='$subject_id'>$sumbject_name</option>" ;
	  }
	  }
	echo "</select>";
	}
}
function sel_livesession($classid,$subject,$liveid=''){
	global $conn;
	$sql= "SELECT vtitle,aws_link FROM video where vid_class='$classid' and vid_sub='$subject' and aws_link!=''";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
	echo "<select name='sessionvideo' id='sessionvideo' class='form-control mb-3'>";
	  echo"<option value=''>Select Live Session</option>" ;
	  while($row = $result->fetch_assoc()) {
		$vtitle = $row['vtitle'];
		$aws_link =  $row['aws_link'];
		
	  if($selcid!='' && $selcid==$t_id){
		echo"<option value='$aws_link' selected>$vtitle</option>" ;
	  }
	  else{
		echo"<option value='$aws_link'>$vtitle</option>" ;
	  }
	  }
	echo "</select>";
	}
}

function getDocument(& $id){
	global $conn;
	$sql = "SELECT * FROM study_documents where id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_array($res);
}	
function getDocuments(& $class, & $subject){
	global $conn;
	$sql = "SELECT * FROM study_documents where class='$class' and subject='$subject' order by id desc";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['name']=$row['name'];
		$arr[$i]['studydoc']=$row['studydoc'];
		$arr[$i]['class']=$row['class'];
		$arr[$i]['subject']=$row['subject'];
		$arr[$i]['created']=$row['created'];
		$arr[$i]['createdby']=$row['createdby'];
		$arr[$i]['status']=$row['status'];
		$i++;		
	}
	return $arr;	
}

function addDocument(){
	global $conn;
	extract($_POST);
	//print_r($_FILES);exit;
	$name = addslashes(trim($name));
	$sql ="insert into study_documents set name='$name',class='$class',subject='$subject',createdby='teacher',created=NOW(),status='1'";
	$res = mysqli_query($conn,$sql);
	$id=mysqli_insert_id($conn);
	$comments=$name." document is uploaded. ";	

	if($_FILES['myDropify']['name']!=''){		
		$filename = $id."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/study_material/'.$filename;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE study_documents SET `studydoc`='$filename' WHERE `id`='$id'";
			$conn->query($update_record);
		}
	}
	$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'pdf&type=study&file='.$filename,'tableid'=>$id,'tablename'=>'study_documents','comments'=>$comments,'status'=>'1');
	saveNotification($arr);	
}

function updateDocument(){
	global $conn;
	extract($_POST);
	//print_r($_FILES);exit;
	$name = addslashes(trim($name));
	$sql ="update study_documents set name='$name' where id='$id'";
	$res = mysqli_query($conn,$sql);
	if($_FILES['myDropify']['name']!=''){
		$filename = $id."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/study_material/'.$filename;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE study_documents SET `studydoc`='$filename' WHERE `id`='$id'";
			$conn->query($update_record);
		}
	}
}
function deleteDocument(& $id){
	global $conn;
	$conn->query("delete from study_documents where id='$id'");
}



function addAssignment(){
	global $conn;
	$tid = $_SESSION['tid'];
	extract($_POST);
	//print_r($_FILES);exit;
	$title = addslashes(trim($title));
	$description = addslashes(trim($description));
	if($qtype=='freetextsection'){
	$sql ="insert into tbl_freetext set question='$title',class='$classid',subject='$subject',answer='$description',opendate=NOW(),closedate='$submitdate',teacher='$tid'";
	$docpre='freetext_';	
	} else{
		$sql ="insert into tbl_questiondoc set question='$title',class='$classid',subject='$subject',answer='$description',opendate=NOW(),closedate='$submitdate',teacher='$tid'";
		$docpre='questiondoc_';	
	}
	//	echo $sql;exit;
	if(mysqli_query($conn,$sql)){
		$id=mysqli_insert_id($conn);
		if($_FILES['refdoc']['name']!=''){			
			$filename = $docpre.$id."_".$_FILES['refdoc']['name'];
			$ufile = '../uploads/evaluation/referdoc/'.$filename;
			if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ufile)){
				if($qtype=='freetextsection')$update_record = "UPDATE tbl_freetext SET `document`='$filename' WHERE `id`='$id'";
				else $update_record = "UPDATE tbl_questiondoc SET `document`='$filename' WHERE `id`='$id'";	
				$conn->query($update_record);
			}
		}
		$classn = getClassName($classid);
		$comments="New assignemnt is given for class $classn. ";		
		$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'assignments','tableid'=>$id,'tablename'=>'','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
	}

}
function updateAssignment(){
	global $conn;
	extract($_POST);
	$title = addslashes(trim($title));
	$description = addslashes(trim($description));
	if($type=='freetext'){
	$sql ="update tbl_freetext set question='$title',answer='$description',closedate='$submitdate' where id='$id'";
	$docpre='freetext_';	
	} else{
		$sql ="update tbl_questiondoc set question='$title',answer='$description',closedate='$submitdate' where id ='$id'";
		$docpre='questiondoc_';	
	}

	$res = mysqli_query($conn,$sql);
	if($_FILES['refdoc']['name']!=''){
		$filename = $docpre.$id."_".$_FILES['refdoc']['name'];
		$ufile = '../uploads/evaluation/referdoc/'.$filename;
		if(move_uploaded_file($_FILES['refdoc']['tmp_name'],$ufile)){
			if($type=='freetext')$update_record = "UPDATE tbl_freetext SET `document`='$filename' WHERE `id`='$id'";
			else $update_record = "UPDATE tbl_questiondoc SET `document`='$filename' WHERE `id`='$id'";	
			$conn->query($update_record);
		}
	}
}

function getAssignment(& $id,$type){
	global $conn;
	if($type=='freetext')
	$sql="SELECT * FROM tbl_freetext where id='$id'";
	else $sql="SELECT * FROM tbl_questiondoc where id='$id'";
	//echo $sql;
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}
function getAssignments(& $classid,& $subjectid){
	global $conn;
	$tid = $_SESSION['tid'];
	if($_SESSION['u_type']=='teacher'){
	$sql="SELECT * FROM tbl_freetext where teacher='$tid' and class='$classid' and subject='$subjectid' order by id asc";
	}
	else{
		$sql="SELECT * FROM tbl_freetext where class='$classid' and subject='$subjectid' order by id asc";
	}
	//exit;
	$res = mysqli_query($conn,$sql);
	$arr['upcomming']=array();
	$arr['past']=array();
	$i=0;
	$j=0;
	while($row = mysqli_fetch_array($res)){
		if($row['closedate']>=date('Y-m-d')){
			$arr['upcomming'][$i]['id']=$row['id'];
			$arr['upcomming'][$i]['question']=$row['question'];
			$arr['upcomming'][$i]['answer']=$row['answer'];
			$arr['upcomming'][$i]['opendate']=$row['opendate'];
			$arr['upcomming'][$i]['closedate']=$row['closedate'];
			$arr['upcomming'][$i]['document']=$row['document'];
			$arr['upcomming'][$i]['type']='freetext';
			$i++;
		}	
		if($row['closedate']<date('Y-m-d')){
			$arr['past'][$j]['id']=$row['id'];
			$arr['past'][$j]['question']=$row['question'];
			$arr['past'][$j]['answer']=$row['answer'];
			$arr['past'][$j]['opendate']=$row['opendate'];
			$arr['past'][$j]['closedate']=$row['closedate'];
			$arr['past'][$j]['document']=$row['document'];
			$arr['past'][$j]['type']='freetext';
			$j++;
		}		
	}
	$sql="SELECT * FROM tbl_questiondoc where teacher='$tid' and class='$classid' and subject='$subjectid' order by id asc";
	$res = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($res)){
		if($row['closedate']>=date('Y-m-d')){
			$arr['upcomming'][$i]['id']=$row['id'];
			$arr['upcomming'][$i]['question']=$row['question'];
			$arr['upcomming'][$i]['answer']=$row['answer'];
			$arr['upcomming'][$i]['opendate']=$row['opendate'];
			$arr['upcomming'][$i]['closedate']=$row['closedate'];
			$arr['upcomming'][$i]['document']=$row['document'];
			$arr['upcomming'][$i]['type']='questiondoc';
			$i++;
		}	
		if($row['closedate']<date('Y-m-d')){
			$arr['past'][$j]['id']=$row['id'];
			$arr['past'][$j]['question']=$row['question'];
			$arr['past'][$j]['answer']=$row['answer'];
			$arr['past'][$j]['opendate']=$row['opendate'];
			$arr['past'][$j]['closedate']=$row['closedate'];
			$arr['past'][$j]['document']=$row['document'];
			$arr['past'][$j]['type']='questiondoc';
			$j++;
		}		
	}
	return $arr;
}
function deleteAssignment(& $id, & $type){
	global $conn;
	if($type='freetext')
	$sql="delete from tbl_freetext where id='$id'";
	else $sql="delete from tbl_questiondoc where id='$id'";
	mysqli_query($conn,$sql);
}

function getStudentsAssignment(& $id, & $type, & $class){
	global $conn;
	$qtype='tbl_'.$type;
	
	$sql = "SELECT a.*,concat(student_name,' ',student_lastname) as sname,image from tbl_answer a, students s where s.ecode=a.studid and question='$id' and question_type='$qtype'";
	$i=0;
	$arr= array();
	$stdarr= array();
	$res = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($res)){		
		$arr[$i]['id']=$row['id'];
		$arr[$i]['studid']=$row['studid'];
		$arr[$i]['student_name']=$row['sname'];
		if($row['image']!='')
		$arr[$i]['image']='../uploads/images/students/'.$row['image'];
		else $arr[$i]['image']='../uploads/avtar.png';
		$arr[$i]['question']=$row['question'];
		$arr[$i]['question_type']=$row['question_type'];
		$arr[$i]['answer']=stripslashes($row['answer']);
		$arr[$i]['document']=$row['document'];
		$arr[$i]['teacher_feedback']=$row['teacher_feedback'];
		$arr[$i]['created']=$row['created'];
		$arr[$i]['feedbackstatus']=$row['status'];
		$stdarr[] = $row['studid'];
		$i++;
	}
	$starr = implode("','",$stdarr);
	$sql = "SELECT ecode,concat(student_name,' ',student_lastname) as sname,image from students where ecode not in ('$starr') and dept_id='$class' and status='1'"; //exit;
	//$i=0;
	$res = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_array($res)){		
		$arr[$i]['id']='';
		$arr[$i]['studid']=$row['ecode'];
		$arr[$i]['student_name']=$row['sname'];
		if($row['image']!='')
		$arr[$i]['image']='../uploads/images/students/'.$row['image'];
		else $arr[$i]['image']='../uploads/avtar.png';
		
		//$arr[$i]['image']='../uploads/images/students/'.$row['image'];	
		$arr[$i]['status']='0';
		$arr[$i]['created']='';	
		$i++;
	}	
	
	return $arr;
}

function getAssignmentAnswerByStudent(& $id){
	global $conn;
	$sql = "SELECT a.*,student_name,image from tbl_answer a, students s where s.ecode=a.studid and id='$id'";	
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);		
	$arr['id']=$row['id'];
	$arr['studid']=$row['studid'];
	$arr['student_name']=$row['student_name'];
	$arr['image']='../uploads/images/students/'.$row['image'];
	$arr['question']=$row['question'];
	$arr['question_type']=$row['question_type'];
	$arr['answer']=stripslashes($row['answer']);
	$arr['document']=$row['document'];
	$arr['teacher_feedback']=$row['teacher_feedback'];
	$arr['created']=$row['created'];
	$arr['status']=$row['status'];
	return $arr;
}

function changeStatusAssignment(){
	global $conn;
	//print_r($_POST);
	extract($_POST);
	$feedback = addslashes(trim($feedback));
	$sql="update tbl_answer set status='2',teacher_feedback='$feedback' where id='$ansid'";
	mysqli_query($conn,$sql);
}

function changeStatusExam($i){
	global $conn;
	
	extract($_POST);
	$feedback = addslashes(trim($_POST['feedback'.$i]));
	$result = $_POST['ques_result'.$i];
	$ansid = $_POST['ansid'.$i];
	$mark = $_POST['amarks'.$i];
	$sql="update tbl_answer set status='2',teacher_feedback='$feedback',answer_result='$result',marks='$mark' where id='$ansid'";//exit;
	mysqli_query($conn,$sql);
}


function getCommentsByDisId(& $ask_id){
	global $conn;
	$sql="select * from comment_section where ask_id='$ask_id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);
}
function getClassroomDiscussionById(& $id){
	global $conn;
	$sql = "select q.ecode,qdate,q_details,class,student_name,image from ask_questions q, students s where s.ecode=q.ecode and ask_id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);
	
}
function getClassroomDiscussions(& $class){
	global $conn;
	$sql = "select q.*,COUNT(q.ask_id) as cnt from ask_questions q left join comment_section c on q.ask_id=c.ask_id where class='1' GROUP by q.ask_id order BY q.ask_id desc";
	$res = mysqli_query($conn,$sql);
	$i=0;
	$arr = array();
	while($row = mysqli_fetch_array($res)){		
		$arr[$i]['cnt']=$row['cnt'];
		$arr[$i]['ask_id']=$row['ask_id'];
		$arr[$i]['ecode']=$row['ecode'];
		$arr[$i]['date']=$row['qdate'];
		$arr[$i]['details']=stripslashes(trim($row['q_details']));
		$i++;
	}
	return $arr;
}

function discussionDelete(& $id){
	global $conn;
	$sql="delete from ask_questions where ask_id='$id'";
	mysqli_query($conn,$sql);
	$sql="delete from comment_section where ask_id='$id'";
	mysqli_query($conn,$sql);
}

function discussionFeedback(){
	global $conn;
	extract($_POST);
	$tid = $_SESSION['tid'];
	$txtdiscussion=addslashes(trim($txtdiscussion));
	$sql = "INSERT INTO ask_questions(qdate,q_details,class,teachervid) VALUES(NOW(),'$txtdiscussion','$classid','$tid')";
	mysqli_query($conn,$sql);	
}

function getBook(& $id){
	global $conn;
	$sql = "SELECT * FROM my_books WHERE book_id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);
	
}
function my_books(){
	global $conn;
	$sql = "SELECT * FROM my_books WHERE enb='1'";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}		 
}

function addBook(){
	global $conn;
	//print_r($_POST);
	extract($_POST);
	$title=addslashes(trim($title));
	//book_thumb,book_link
	$sql = "INSERT INTO my_books(book_name,class,enb,created) VALUES('$title','$classid','1',NOW())";
	if(mysqli_query($conn,$sql)){
		$lastid = mysqli_insert_id($conn);
		if($_FILES['bookfile']['name']!=''){
			$name = $lastid.'_'.$_FILES['bookfile']['name'];
			$target='../uploads/my_books/'.$name;
			if(move_uploaded_file($_FILES['bookfile']['tmp_name'],$target)){
				$conn->query("UPDATE my_books SET `book_link`='$name' WHERE `book_id`='$lastid'");
			}
		}
		if($_FILES['myDropify']['name']!=''){
			$name = $lastid.'_'.$_FILES['myDropify']['name'];
			$target='../uploads/my_books/thumb/'.$name;
			if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target)){
				$conn->query("UPDATE my_books SET `book_thumb`='$name' WHERE `book_id`='$lastid'");
			}
		}
		$comments=$title." book is added.";		
		$arr=array('from_id'=>$_SESSION['tid'],'from_type'=>$_SESSION['u_type'],'to_id'=>'','to_type'=>'student','page'=>'index.php?action=pdf&type=book&file='.$name,'tableid'=>$lastid,'tablename'=>'my_books','comments'=>$comments,'status'=>'1');
		saveNotification($arr);
		
	}	
}

function deleteBook(& $id){
	global $conn;
	mysqli_query($conn,"delete from my_books where book_id='$id'");
}

function updateBook(){
	global $conn;
	//print_r($_POST);
	extract($_POST);
	$title=addslashes(trim($title));
	//book_thumb,book_link
	$sql = "update my_books set book_name='$title' where book_id='$bookid'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['bookfile']['name']!=''){
			$name = $bookid.'_'.$_FILES['bookfile']['name'];
			$target='../uploads/my_books/'.$name;
			if(move_uploaded_file($_FILES['bookfile']['tmp_name'],$target)){
				$conn->query("UPDATE my_books SET `book_link`='$name' WHERE `book_id`='$bookid'");
			}
		}
		if($_FILES['myDropify']['name']!=''){
			$name = $bookid.'_'.$_FILES['myDropify']['name'];
			$target='../uploads/my_books/thumb/'.$name;
			if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$target)){
				$conn->query("UPDATE my_books SET `book_thumb`='$name' WHERE `book_id`='$bookid'");
			}
		}	
	}	
}


function actionsubject(){
	global $conn;
	//print_r($_POST);exit;
	extract($_POST);
	$title=addslashes(trim($subname));
	if($subid=='')
	$sql = "INSERT INTO subjects(subject_name,subject_class,subject_isactive,subject_createdat) VALUES('$title','$classid','1',NOW())";// exit;
	else $sql = "UPDATE subjects set subject_name='$title' where subject_id='$subid'";
	//echo $sql; exit;
	mysqli_query($conn,$sql);
	header("location:index.php?action=subjects&class=$classid");exit;	
}

function deleteSubject(& $id){
	global $conn;
	$sql="delete from subjects where subject_id='$id'";
	mysqli_query($conn,$sql);
}

function getExamDetails(& $eid){
	global $conn;
	$sql="SELECT * FROM `tbl_fillblank` WHERE evid='$eid' ORDER BY `id` DESC";
	$i=0;$arr=array();
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['description']);
		  $arr[$i]['qtype']='tbl_fillblank';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_match` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];		  
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['cols1']=$row['cols1'];
		  $arr[$i]['cols2']=$row['cols2'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['description']);
		  $arr[$i]['qtype']='tbl_match';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_multiplechoice` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['options']=$row['options'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['description']);
		  $arr[$i]['qtype']='tbl_multiplechoice';
		 $i++;
	  }
	}
	$sql="SELECT * FROM `tbl_singlechoice` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['options']=$row['options'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['description']);
		  $arr[$i]['qtype']='tbl_singlechoice';
		 $i++;
	  }
	}
	$sql="SELECT * FROM `tbl_questiondoc` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['document'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['answer']);
		  $arr[$i]['qtype']='tbl_questiondoc';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_freetext` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['referdoc']=$row['document'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['description']=stripslashes($row['answer']);
		  $arr[$i]['qtype']='tbl_freetext';
		 $i++;
	  }
	}
	
	//print_r($arr);
	return $arr;
}


function getAllExams(){
	global $conn;
	$tid = $_SESSION['tid'];
	$sql="select * from tbl_evolution where opendate>=NOW() order by id desc";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}
	else return array();	
}	


function getPrevClassExams(& $class,& $subject){
	global $conn;
	$tid = $_SESSION['tid'];
	if($_SESSION['u_type']=='teacher'){
		$sql="select * from tbl_evolution where class='$class' and subject='$subject' and teacher='$tid' and closedate<NOW()";
	}
	else{
		$sql="select * from tbl_evolution where class='$class' and subject='$subject' and closedate<NOW()";
	}
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}
	else return array();	
}

function getClassExams(& $class,& $subject){
	global $conn;
	$tid = $_SESSION['tid'];
	if($_SESSION['u_type']=='teacher'){
		$sql="select * from tbl_evolution where class='$class' and subject='$subject' and teacher='$tid' and opendate>=NOW()";
	}
	else{
		$sql="select * from tbl_evolution where class='$class' and subject='$subject' and opendate>=NOW()";
		//echo $sql;exit;
	}
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}
	else return array();	
}


function getStudentSubmittedExam(& $evid){
	global $conn;
	$sql="select a.*,student_name,image,sum(marks) as totmarks from tbl_answer a,students s where a.studid=s.ecode and evid='$evid' group by `studid` order by `status` asc";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);		
}

function getStudentNotSubmittedExam(& $evid,$class){
	global $conn;
	$sql="select student_name,ecode,dept_id,image from students where dept_id='$class' and ecode not in (select studid from tbl_answer where evid='$evid')";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);		
}

function getAnswersByStudentAndExam(& $evid,& $student){
	global $conn;
	$sql="select * from tbl_answer where evid='$evid' and studid='$student' order by `question` asc";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);	
}

function getAnswerofQuestionByStudent(& $qid,& $type,& $student){
	global $conn;
	$sql="select * from tbl_answer where studid='$student' and question='$qid' and question_type='$type'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}



function getExam(& $id){
	global $conn;
	$sql="select * from tbl_evolution where id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}
function getQuestion(& $id, & $type){
	global $conn;
	$sql="select * from $type where id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}
function deleteExamQuestion($id,$type){
	global $conn;
	$sql="delete from $type where id='$id'";
	$res = mysqli_query($conn,$sql);	
}

function assignTeacherDelete(& $assignid){
	global $conn;
	extract($_GET);
	$sql="delete from teacher_assign where assign_id='$assignid'";
	mysqli_query($conn,$sql);
}
function assignSubjectTeacher(){
	global $conn;
	extract($_POST);
	print_r($_POST);
	if(isset($assignsubject)){
	$sql="insert into teacher_assign(teacher_id,classroom,subject) value('$assignteacherid','$assignteacherclass','$assignsubject')";
	mysqli_query($conn,$sql);
	}
	
	if(isset($makeclassteacher)){
		$sql = "update classrooms set class_teacher='$assignteacherid' where class_id='$assignteacherclass'";
		mysqli_query($conn,$sql);	
	}	
}

function getAllTeachers(){
	global $conn;
	$sql = "select * from teachers order by t_id desc";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);	
}
function getTeacher(& $id){
	global $conn;
	$sql = "select * from teachers where t_id ='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}
function updateProfile(){
	global $conn;
	$tid=$_SESSION['tid'];
	extract($_POST);
	if($usertype=='admin' && $_SESSION['u_type']=='admin')
	$sql = "update teachers set t_contact='$t_contact',t_phone='$t_phone',t_name='$t_name',t_lastname='$t_lastname',t_dob='$t_dob',t_gender='$t_gender',t_address='$t_address',t_contact='$t_contact',t_phone='$t_phone' where t_id ='$tid'";
	else $sql = "update teachers set t_contact='$t_contact',t_phone='$t_phone' where t_id ='$tid'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['myDropify']['name']!=''){
		$thumb = $tid."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/teacher/'.$thumb;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE teachers SET `t_pic`='$thumb' WHERE `t_id`='$tid'";
			$conn->query($update_record);
		}		
	}
		
	}	
}	
function changePassword(){
	global $conn;
	$tid=$_SESSION['tid'];
	extract($_POST);
	$newpass = trim($newpass);
	$oldpass = trim($oldpass);
	$sq4= $conn->query("select t_pass FROM teachers where t_id='$tid'");
	$rw = $sq4->num_rows;
	$rowmain = $sq4->fetch_assoc();
	$hash = $rowmain['pwd'];
	if (password_verify($oldpass, $hash)) {		
		$newpwd = password_hash($newpass, PASSWORD_DEFAULT);	
		$conn->query("update teachers set t_pass='$newpwd' where t_id='$tid'");
		echo "Password changes successfuly";
	}
	else{
		echo "Please enter correct password";
	}		
}

function updateTeacher(){
	global $conn;
	extract($_POST);
	//print_r($_POST);exit;
	$t_name=addslashes(trim($t_name));
	$t_lastname=addslashes(trim($t_lastname));
	$classn=implode(',',$class);
	$sql = "update teachers set t_name='$t_name',t_lastname='$t_lastname',t_classname='$classn',t_dob='$t_dob',t_gender='$t_gender',t_address='$t_address',t_contact='$t_contact',t_phone='$t_phone' where t_id ='$tid'";
	$res = mysqli_query($conn,$sql);
	
	$sqlcheck = "select classroom from teacher_assign where teacher_id='$tid'";
	$reschk= mysqli_query($conn,$sqlcheck);
	$t=0;
	$ta=array();
	while($rowa = mysqli_fetch_array($reschk)){	
	$ta[$t]=$rowa['classroom'];
	$t++;
	}	
	for($c=0; $c<count($class);$c++){
		$clid = $class[$c];
		if(in_array($clid,$ta)){}
		else{	
			$sqlt="insert into teacher_assign(teacher_id,classroom) values('$tid','$clid')";
			mysqli_query($conn,$sqlt);
		}
	}
	for($c=0; $c<count($ta);$c++){
		$clid = $ta[$c];
		if(in_array($clid,$class)){}
		else{	
			$sqlt="delete from teacher_assign where teacher_id='$tid' and classroom='$clid'";
			mysqli_query($conn,$sqlt);
		}
	}
	
	
	if($_FILES['myDropify']['name']!=''){
		$thumb = $tid."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/teacher/'.$thumb;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE teachers SET `t_pic`='$thumb' WHERE `t_id`='$tid'";
			$conn->query($update_record);
		}		
	}
}

function addTeacher(){
	global $conn;
	extract($_POST);
	//print_r($_POST);exit;
	$t_name=addslashes(trim($t_name));
	$t_lastname=addslashes(trim($t_lastname));
	$class=implode(',',$class);
	$sql = "insert into teachers set t_name='$t_name',t_lastname='$t_lastname',t_classname='$class',t_dob='$t_dob',t_gender='$t_gender',t_address='$t_address',t_contact='$t_contact',t_phone='$t_phone',t_createdat=NOW()";
	$res = mysqli_query($conn,$sql);
	$lastid = mysqli_insert_id($conn);
	for($c=0; $c<count($class);$c++){
		$clid = $class[$c];
		$sqlt="insert into teacher_assign(teacher_id,classroom) values('$lastid','$clid')";
		mysqli_query($conn,$sqlt);
	}
	$t_code='T'.$lastid;
	$update_record = "UPDATE teachers SET `t_code`='$t_code' WHERE `t_id`='$lastid'";
	$conn->query($update_record);
	if($_FILES['myDropify']['name']!=''){
		$thumb = $lastid."_".$_FILES['myDropify']['name'];
		$ufile = '../uploads/teacher/'.$thumb;
		if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
			$update_record = "UPDATE teachers SET `t_pic`='$thumb' WHERE `t_id`='$lastid'";
			$conn->query($update_record);
		}		
	}
}

function deleteTeacher(& $id){
	global $conn;
	$sqlp="select t_pic from teachers where t_id='$id'";
	$resp=mysqli_query($conn,$sqlp);
	$row=mysqli_fetch_array($resp);
	$img = '../uploads/teacher/'.$row['t_pic'];
	if(file_exists($img)) unlink($img);
	$sql="delete from teachers where t_id='$id'";
	if(mysqli_query($conn,$sql)){
		$sqla="delete from teacher_assign where teacher_id='$id'";
		mysqli_query($conn,$sqla);
		
		$sqlc="update classrooms set class_teacher='' where class_teacher='$id'";
		mysqli_query($conn,$sqlc);
	}
}

function deleteStudent(& $id){
	global $conn;
	$sqlp="select image from students where std_id='$id'";
	$resp=mysqli_query($conn,$sqlp);
	$row=mysqli_fetch_array($resp);
	$img = '../uploads/images/students/'.$row['image'];
	if(file_exists($img)) unlink($img);
	$sql="delete from students where std_id='$id'";
	mysqli_query($conn,$sql);
}

   

function getAllStudentsNew(){
	global $conn;
	$sql = "select * from students order by std_id desc limit 10";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_all($res,MYSQLI_ASSOC);	
}

function getStudent(& $id){
	global $conn;
	$sql="select * from students where std_id='$id'";
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_assoc($res);	
}

function addStudent(){
	global $conn;
	extract($_POST);
	$sql = "insert into students set student_name='$student_name',student_lastname='$student_lastname',father_name='$father_name',father_contact='$father_contact',mother_name='$mother_name',mother_contact='$mother_contact',address='$address',email='$email',date_birth='$date_birth',gender='$gender',dept_id='$class' ";
	if(mysqli_query($conn,$sql)){
		$lastid = mysqli_insert_id($conn);
		$ecode = "ST0".$lastid;
		$roolno = $lastid;
		$sqlu= "update students set roll_no='$roolno', ecode='$ecode' where std_id='$lastid'";
		mysqli_query($conn,$sqlu);		
		if($_FILES['myDropify']['name']!=''){
			$thumb = $lastid."_".$_FILES['myDropify']['name'];
			$ufile = '../uploads/images/students/'.$thumb;
			if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
				$update_record = "UPDATE students SET `image`='$thumb' WHERE `std_id`='$lastid'";
				$conn->query($update_record);
			}
		}		
	}	
}

function updateStudent(){
	global $conn;
	extract($_POST);
	$sql = "update students set student_name='$student_name',student_lastname='$student_lastname',father_name='$father_name',father_contact='$father_contact',mother_name='$mother_name',mother_contact='$mother_contact',address='$address',email='$email',date_birth='$date_birth',gender='$gender',dept_id='$class' where std_id='$std_id'";
	if(mysqli_query($conn,$sql)){
		if($_FILES['myDropify']['name']!=''){
			$thumb = $std_id."_".$_FILES['myDropify']['name'];
			$ufile = '../uploads/images/students/'.$thumb;
			if(move_uploaded_file($_FILES['myDropify']['tmp_name'],$ufile)){
				$update_record = "UPDATE students SET `image`='$thumb' WHERE `std_id`='$std_id'";
				$conn->query($update_record);
			}
		}		
	}	
}

function getAlLiveSessions(){
	global $conn,$filelocation;
	$sql="select vid_id,vtitle,vdesc,vid_class,vthumb,t_pic,vid_teacher,subject_name,t_name,sub_start_at,sub_end_at from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1'  and sub_end_at>=NOW() order by sub_start_at desc ";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$tdate = date('Y-m-d',strtotime($row['sub_start_at']));
		//echo'<br>',$tdatel,'>=',date('Y-m-d');
		//if($row['sub_end_at']>=date('Y-m-d H:i:s')){
		$arr[$i]['id']=$row['vid_id'];
		$arr[$i]['sub_start']=$tdate;
		$arr[$i]['sub_start_at']=$row['sub_start_at'];
		$arr[$i]['sub_end_at']=$row['sub_end_at'];
		$arr[$i]['t_name']=$row['t_name'];
		$arr[$i]['vid_class']=$row['vid_class'];
		$arr[$i]['vdesc']=stripslashes(trim($row['vdesc']));
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
		$arr[$i]['vtitle']=stripslashes(trim($row['vtitle']));
		$arr[$i]['vthumb']=$filelocation.'uploads/images/videothumb/'.$row['vthumb'];
		//if(file_exists($filelocation.'uploads/teacher/'.$row['t_pic']))
		$arr[$i]['tpic']='uploads/teacher/'.$row['t_pic'];
		//else $arr[$i]['tpic']='uploads/avtar.png';
	
		$i++;
		//}
	}
	return $arr;
}

function uploadCSV(){
	global $conn;
	
    $fileName = $_FILES["myDropify"]["tmp_name"];    
    if ($_FILES["myDropify"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $i=0;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if($i>0){
            $student_name = "";
            if (isset($column[0])) {
                $student_name = mysqli_real_escape_string($conn, $column[0]);
            }
            $student_lastname = "";
            if (isset($column[1])) {
                $student_lastname = mysqli_real_escape_string($conn, $column[1]);
            }
            $father_name = "";
            if (isset($column[2])) {
                $father_name = mysqli_real_escape_string($conn, $column[2]);
            }
            $father_contact = "";
            if (isset($column[3])) {
                $father_contact = mysqli_real_escape_string($conn, $column[3]);
            }
            $mother_name = "";
            if (isset($column[4])) {
                $mother_name = mysqli_real_escape_string($conn, $column[4]);
            }
			$mother_contact = "";
            if (isset($column[5])) {
                $mother_contact = mysqli_real_escape_string($conn, $column[5]);
            }
			$address = "";
            if (isset($column[6])) {
                $address = mysqli_real_escape_string($conn, $column[6]);
            }
			$email = "";
            if (isset($column[7])) {
                $email = mysqli_real_escape_string($conn, $column[7]);
            }
			$mobile = "";
            if (isset($column[8])) {
                $mobile = mysqli_real_escape_string($conn, $column[8]);
            }
			
			$date_birth = "";
            if (isset($column[9])) {
                $date_birth = mysqli_real_escape_string($conn, $column[9]);
				$date_birth = date('Y-m-d', strtotime($date_birth));
            }
			$gender = "";
            if (isset($column[10])) {
                $gender = mysqli_real_escape_string($conn, $column[10]);
            }
			$date_join = "";
            if (isset($column[11])) {
                $date_join = mysqli_real_escape_string($conn, $column[11]);
				$date_join = date('Y-m-d', strtotime($date_join));
            }
            $dept_name = "";
            if (isset($column[12])) {
                $dept_name = mysqli_real_escape_string($conn, $column[12]);
				$dept_id = getClassIdByName($dept_name);
            }
			
           if($student_name!=''){			
          $sqlInsert = "INSERT into students (student_name,student_lastname,father_name,father_contact,mother_name,mother_contact,address,email,mobile,date_birth,gender,date_join,dept_id,status)
                   values (
                '$student_name',
                '$student_lastname',
                '$father_name',
                '$father_contact',
                '$mother_name',
				'$mother_contact',
				'$address',
				'$email',
				'$mobile',
				'$date_birth',
				'$gender',
				'$date_join',
				'$dept_id',
				'1'
            )";
          
            //$conn->query()
            if (mysqli_query($conn,$sqlInsert)) {
                //$type = "success";
				$lastid = mysqli_insert_id($conn);
				$ecode = "ST0".$lastid;
				$roolno = $lastid;
				$pwd = password_hash($ecode, PASSWORD_DEFAULT);
				$sqlu= "update students set roll_no='$roolno',pwd='$pwd',ecode='$ecode' where std_id='$lastid'";
				mysqli_query($conn,$sqlu);		
                $message = '1';
            } else {
                //$type = "error";
                $message = "Problem in Importing CSV Data";
            }
			}
			}
			$i++;		
			
        }
    }
//}
echo $message;
}


function uploadTeacherCSV(){
	global $conn;
	
    $fileName = $_FILES["myDropify"]["tmp_name"];    
    if ($_FILES["myDropify"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $i=0;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if($i>0){
            $usertype = "";
            if (isset($column[0])) {
                $usertype = mysqli_real_escape_string($conn, $column[0]);
            }
            $t_name = "";
            if (isset($column[1])) {
                $t_name = mysqli_real_escape_string($conn, $column[1]);
            }
			

            $t_lastname = "";
            if (isset($column[2])) {
                $t_lastname = mysqli_real_escape_string($conn, $column[2]);
            }
            
            $t_classname = "";
            if (isset($column[3])) {
                $t_classname = mysqli_real_escape_string($conn, $column[3]);
				$class= explode(',',$t_classname);
				$clidar=array();
				for($c=0; $c<count($class);$c++){
				$clidar[] = getClassIdByName($class[$c]);
				}
				$t_classname = implode(',',$clidar);
            }
			
			$t_dob = "";
            if (isset($column[4])) {
                $t_dob = mysqli_real_escape_string($conn, $column[4]);
				$t_dob = date('Y-m-d', strtotime($t_dob));
            }
			
			$t_doj = "";
            if (isset($column[5])) {
                $t_doj = mysqli_real_escape_string($conn, $column[5]);
				$t_doj = date('Y-m-d', strtotime($t_doj));
            }
			$t_gender = "";
            if (isset($column[6])) {
                $t_gender= mysqli_real_escape_string($conn, $column[6]);
            }
			
			$t_pic = "";
            if (isset($column[7])) {
                $t_pic = mysqli_real_escape_string($conn, $column[7]);				
            }
			
			
			$t_address= "";
            if (isset($column[8])) {
                $t_address = mysqli_real_escape_string($conn, $column[8]);
            }
			$t_contact = "";
            if (isset($column[9])) {
                $t_contact= mysqli_real_escape_string($conn, $column[9]);				
            }
            $t_phone = "";
            if (isset($column[10])) {
                $t_phone = mysqli_real_escape_string($conn, $column[10]);
            }
			
         $sqlInsert = "INSERT into teachers (usertype,t_name,t_lastname,t_classname,t_dob,t_doj,t_gender,t_pic,t_address,t_contact,t_phone,t_createdat,t_isActive)
                   values (
                'teacher',
                '$t_name',
                '$t_lastname',
                '$t_classname',
				'$t_dob',
				'$t_doj',
				'$t_gender',
				'$t_pic',
				'$t_address',
				'$t_contact',
				'$t_phone',
				 NOW(),
				'1'
            )";
          
            //$conn->query()
            if (mysqli_query($conn,$sqlInsert)) {
				$lastid = mysqli_insert_id($conn);
                //$type = "success";
				$class = explode(',',$t_classname);
				for($c=0; $c<count($class);$c++){
				$clid = getClassIdByName($class[$c]);
				$sqlt="insert into teacher_assign(teacher_id,classroom) values('$lastid','$clid')";
				mysqli_query($conn,$sqlt);
				}
				$t_code='T'.$lastid;
				$pwd = password_hash($t_code, PASSWORD_DEFAULT);
				$update_record = "UPDATE teachers SET `t_code`='$t_code',t_pass='$pwd' WHERE `t_id`='$lastid'";
				$conn->query($update_record);
	
               // $message = '1';
            } else {
                //$type = "error";
               // $message = "Problem in Importing CSV Data";
            }
			}
			$i++;		
	
        }
    }
//}

}

function getClassIdByName(& $name){
	global $conn;
	$name=trim($name);
	$sql="select class_id from classrooms where class_name='$name'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['class_id'];
}
function getSubjectIdByName(& $name){
	global $conn;
	$sql="select subject_id from subjects where subject_name='$name'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['subject_id'];
}


function examDelete($id){
	global $conn;
	$sql="delete from tbl_evolution where id='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_fillblank where evid='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_match where evid='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_singlechoice where evid='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_multiplechoice where evid='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_freetext where evid='$id'";
	mysqli_query($conn,$sql);
	
	$sql="delete from tbl_questiondoc where evid='$id'";
	mysqli_query($conn,$sql);	
}