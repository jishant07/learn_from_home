<?php
function student_login(){
	global $conn;
	//print_r($_POST);exit;
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST['ecode']))
		{
			$ecode= trim($_POST['ecode']);
			$password= trim($_POST['password']);
			$sq4= $conn->query("select * FROM students where ecode='" . $ecode . "' and pwd='" . $password . "' ");
			$rw = $sq4->num_rows;
			$rowmain = $sq4->fetch_assoc();
			if ($rw == 1){
				$_SESSION["eid"] = $rowmain['ecode'];
				$_SESSION["u_type"] = "student";
				echo "<script>window.location.href='index.php?action=home';</script>";
				header('Location: index.php?action=home');exit;
			}
			else
			{
				$_SESSION['error_log']='Please enter correct login details';	
				echo "<script>window.location.href='index.php?action=login';</script>";
				header('Location: index.php?action=login');exit;
			
			}
		}
	}
}

function changePassword(){
	global $conn;
	extract($_POST);
	$newpass = trim($newpass);
	$oldpass = trim($oldpass);
	$sq4= $conn->query("select pwd FROM students where std_id='$std_id'");
	$rw = $sq4->num_rows;
	$rowmain = $sq4->fetch_assoc();
	$hash = $rowmain['pwd'];
	if (password_verify($oldpass, $hash)) {		
		$newpwd = password_hash($newpass, PASSWORD_DEFAULT);	
		$conn->query("update students set pwd='$newpwd' where std_id='$std_id'");
		echo "Password changes successfuly";
	}
	else{
		echo "Please enter correct password";
	}		
}
function teacher_login(){
	global $conn;
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['email'])) {
			$email = trim($_POST['email']);
			$password = trim($_POST['password']);
			$sq4 = $conn->query("select * FROM teachers where t_contact='" . $email . "' and t_pass='" . $password . "'");
			$rw = $sq4->num_rows;
			$rowmain = $sq4->fetch_assoc();
			if($rw == 1){
				$_SESSION["uid"] = $rowmain['t_id'];
				$_SESSION["u_type"] = "teacher";
				$_SESSION['u_email'] = $email;
				$sql_onlineSts= "UPDATE teachers SET t_isOnline=1 WHERE t_contact='".$rowmain['t_contact']."'  ";
				if ($conn->query($sql_onlineSts) == true){
					header('Location: index.php?action=tech-dashboard');exit; 
				}
				else{
					$_SESSION['errmsg']='Error Occured';
					header('Location: index.php?action=teacher-login');exit;   		
				}                                     
			}
			else {
				$_SESSION['errmsg']='Invalid user email id or password. please login again';
				header('Location: index.php?action=teacher-login');exit;  
			}
		}
	}	
}

function logout(){
//echo 'logout';exit;
	session_unset();
	// destroy the session
 	session_destroy();
	echo "<script> location.href='login.php'; </script>";	
}

function video_list($type){
	global $conn,$filelocation;			
	$sql = "SELECT * FROM video INNER JOIN video_category ON video.vc_id = video_category.vid where vid_type='$type' && video.enb='1'";
	$sql_vid= $conn -> query($sql);
	if ($sql_vid->num_rows > 0){
		$i=0;$arr=array();	
		while($row = $sql_vid->fetch_assoc()){
			$baseurl = $filelocation.'uploads/images/videothumb/';
			if($row['vthumb']  === NULL ):
			  $arr[$i]['vthumb'] = $filelocation."uploads/images/videothumb/dummy.png";
			else:
			  $imgname = $row['vthumb'];
			  $arr[$i]['vthumb'] =  $baseurl . '' . $imgname;
			endif;
			$arr[$i]['vid_id'] = $row['vid_id'];
			$arr[$i]['vtitle'] = $row['vtitle'];
			$i++;
		}
		return $arr;
	}
}

function videoSmallDetails(& $id){
	global $conn;
	$base='uploads/images/videothumb/';
	 $sql = "select vid_type,vthumb,vtitle,subject_name from video v,subjects s where s.subject_id=v.vid_sub AND vid_id=$id";
	 $res= mysqli_query($conn,$sql);
	 $row = mysqli_fetch_array($res);
	//$sqlt = $conn->query($sql);
	//$rowt=$sqlt->fetch_assoc();
	$arr = array();
	$vthumb = $base.$row_vid['vthumb'];
	if(!file_exists($vthumb))$vthumb=$base.'default.jpg';
	//$vthumb;
	$arr['vid_type']=$row['vid_type'];
	$arr['vthumb']=$vthumb;
	$arr['vtitle']=stripslashes(trim($row['vtitle']));
	$arr['subbject_name']=trim($row['subbject_name']);
	
	return $arr;
}

	
function video(){
	global $conn,$filelocation;
	$id = &$_GET['id'];
	$sql_vid = $conn->query("select * from video where vid_id=$id");
	$row_vid=$sql_vid->fetch_assoc();
	$vid_fmrt = $row_vid['vid_format'];
	if($vid_fmrt=='link'){
	  $arr['videolink'] =  $row_vid['aws_link'] ;
	}
	else{
	  $arr['videolink'] =  $filelocation."uploads/videos/ondemand/".$row_vid['vid_path'] ;
	}
	$arr['vid_id']=$row_vid['vid_id'] ;
	$arr['vc_id']=$row_vid['vc_id'] ;
	$arr['vid_type']=$row_vid['vid_type'] ;
	$arr['vid_div']=$row_vid['vid_div'] ;
	$arr['vid_sub']=$row_vid['vid_sub'] ;
	$arr['vid_class']=$row_vid['vid_class'] ;
	$arr['vid_classtype']=$row_vid['vid_classtype'] ;
	$arr['vid_format']=$row_vid['vid_format'] ;
	$arr['vtitle']=trim($row_vid['vtitle']) ;
	$arr['vid_teacher']=$row_vid['vid_teacher'] ;
	$arr['vdesc']=stripslashes(trim($row_vid['vdesc']));
	$arr['aws_link']=$row_vid['aws_link'] ;
	$arr['vthumb']=$filelocation.'uploads/images/videothumb/'.$row_vid['vthumb'];
	$arr['sub_start_at']=$row_vid['sub_start_at'] ;
	$arr['sub_end_at']=$row_vid['sub_end_at'] ;
	$arr['scheduled_date']=$row_vid['scheduled_date'] ;
	$arr['enb']=$row_vid['enb'] ;
	return $arr;	
}


function course_video(& $id){
	global $conn,$filelocation;	
	$sql_vid = $conn->query("select * from courses_videos where id=$id");
	$row_vid=$sql_vid->fetch_assoc();
	$vid_fmrt = $row_vid['vid_format'];
	  $arr['vlink'] =  $row_vid['vlink'] ;
	
	$arr['id']=$row_vid['id'] ;
	$arr['title']=$row_vid['title'] ;
	$arr['subject']=$row_vid['subject'] ;
	$arr['document']=$row_vid['document'];
	$arr['vtitle']=trim($row_vid['vtitle']) ;
	$arr['sheduledate']=$row_vid['sheduledate'] ;
	$arr['teacher']=$row_vid['teacher'] ;
	$arr['description']=stripslashes(trim($row_vid['description']));
	$arr['vthumb']=$filelocation.'uploads/images/coursevideos/'.$row_vid['vthumb'];
	$arr['status']=$row_vid['status'] ;
	return $arr;	
}


function getMyWatchList($emp_ecode){
	global $conn;
	$sql = "Select vid,title,vthumb,subject,course from videowatchlist w, courses_videos v where v.id=w.vid and stdid='$emp_ecode' order by w.id desc";
	$sql = $conn->query($sql);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['vid']= $row['vid'];
		$arr[$i]['cid']= $row['course'];
		$arr[$i]['subject']= $row['subject'];
		if($row['vthumb']!='')	
		$arr[$i]['vthumb']= 'uploads/images/coursevideos/'.$row['vthumb'];	
		else $arr[$i]['vthumb']= 'uploads/avtar.png';
		$arr[$i]['title']= stripslashes($row['title']);
		$i++;
	}
	return $arr;
}	
function my_classroom($emp_ecode){
	global $conn;
	$sql_dept = $conn->query("SELECT * FROM students 
	INNER JOIN classrooms ON students.dept_id = classrooms.class_id and students.ecode = '$emp_ecode' ");
	$row_dept = $sql_dept->fetch_assoc();
	$arr['class_id']= $classid = $row_dept['dept_id'];
	$arr['class_name']=$row_dept['class_name'];
	$arr['designation']= $row_dept['designation'];
	$sql= $conn->query("select count(gender) as gencount,gender from students where dept_id='$classid' GROUP by gender");
	while($row = $sql->fetch_assoc()){
		if($row['gender']=='Male')
		$arr['boys']= $row['gencount'];
		else 
		$arr['girls']= $row['gencount'];	
	}
	
	return $arr;
}

function getClassTeachers(& $clid){
	global $conn,$filelocation;
	$sq="SELECT t.t_name,t_pic,t_code,s.subject_name,a.subject FROM teacher_assign a ,teachers t,subjects s WHERE a.teacher_id=t.t_id AND a.subject=s.subject_id AND a.classroom='$clid' ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['t_name']= $row['t_name'];
		$arr[$i]['t_code']= $row['t_code'];
		if($row['t_pic']!='')	
		$arr[$i]['t_pic']= $filelocation.'uploads/teacher/'.$row['t_pic'];	
		else $arr[$i]['t_pic']= 'uploads/avtar.png';
		$arr[$i]['subject_name']= $row['subject_name'];
		$arr[$i]['subjidectid']= $row['subject'];
		$i++;
	}
	
	return $arr;
}

function getClassTeachersNew(& $clid){
	global $conn,$filelocation;
	$sq="SELECT t_name,t_lastname,t_pic,t_code FROM teacher_assign a ,teachers t WHERE a.teacher_id=t.t_id AND  a.classroom='$clid' ";
	$sql = $conn->query($sq);
	$arr=array();
	$arr1=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		if(in_array($row['t_code'],$arr1)) continue;
		$arr[$i]['t_name']= $row['t_name'].' '.$row['t_lastname'];
		$arr[$i]['t_code']= $row['t_code'];
		$arr1[]= $row['t_code'];
		if($row['t_pic']!='')	
		$arr[$i]['t_pic']= $filelocation.'uploads/teacher/'.$row['t_pic'];	
		else $arr[$i]['t_pic']= 'uploads/avtar.png';
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
		$arr[$i]['sthumb']= $row['sthumb'];
		$i++;
	}
	return $arr;
}

function getClassTimeTable(& $clid){
	global $conn;
	$sq="SELECT tt_desc, p.period_slot FROM timetable t , periods p, days d where tt_day=day_id AND day_name=DAYName(CURDATE()) and t.tt_class='$clid' AND tt_prd=p.period_id AND tt_isactive ='1' ";
	$sql = $conn->query($sq);
	$arr=array();
	$i=0;
	while($row = $sql->fetch_assoc()){
		$arr[$i]['tt_desc']= $row['tt_desc'];	
		$arr[$i]['period_slot']= $row['period_slot'];		
		$i++;
	}
	return $arr;
}

function getHomework(& $id){
	global $conn;
	$sql = $conn -> query("SELECT * FROM homeworks WHERE hw_id='$id'");
	$arr=array();
	$row = $sql->fetch_assoc();
	return $row;
}

function my_books(){
	global $conn,$filelocation;
	 $sql = $conn -> query("SELECT book_thumb,book_link FROM my_books WHERE enb='1' ");
	 if ($sql->num_rows > 0){
	  $baseurl = $filelocation.'uploads/my_books/thumb/';
	  $i=0;$arr=array();
	  while($row = $sql->fetch_assoc()){
		$arr[$i]['book_thumb'] = $baseurl.$row['book_thumb'];
		$arr[$i]['book_link'] = $row['book_link'];
		$i++;
	  }
		return $arr;
	 }
	 
}

function teacher_classroom_options(&$id,$class=''){
	global $conn;
	$sql= "SELECT DISTINCT d.* FROM classrooms d ,teacher_assign a WHERE a.classroom=d.class_id and a.teacher_id =  $id";
	$result1 = $conn->query($sql);
	if ($result1->num_rows > 0)
	{
		echo "<select name='hw_class' id='class'  class='form-control classroom' required='required' >";
		echo"<option value=''>Select class Room</option>" ;
		while($row1 = $result1->fetch_assoc())
	  {
		  $dept_name = $row1['class_name'];
		  $class_id =  $row1['class_id'];
		  if($class!='' && $class==$class_id) $sel='selected'; else $sel='';
		  echo"<option value='$class_id' $sel>$dept_name</option>" ;
	  }
	echo "</select>";
	}
}

function getClassName($id){
	global $conn;
	$sql="select class_name from classrooms where class_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['class_name'];
}
function getDivision(&$id){
	global $conn;
	$sql="select div_name from divisions where div_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return stripslashes(trim($row['div_name']));
}

function getSubject(& $id){
	global $conn;
	$sql="select subject_name from subjects where subject_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['subject_name'];
}

function getEvaluationSubject(& $eid){
	global $conn;
	$sql="select subject_name from tbl_evolution e,subjects s where e.subject=s.subject_id AND e.id='$eid'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['subject_name'];
}
function getTeacher($id){
	global $conn;
	$sql="select concat(t_name,' ',t_lastname) as tname from teachers where t_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['tname'];
}

function getVideo($id){
	global $conn;
	$sql="select vtitle from video where vid_id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['vtitle'];
}

function getCourseVideo(& $id){
	global $conn;
	$sql="select title from courses_videos where id='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['title'];
}

function getStudentName($id){
	global $conn;
	$sql="select concat(student_name,' ',student_lastname) as sname from students where ecode='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['sname'];
}

function getStudentImage($id){
	global $conn;
	$sql="select image from students where ecode='$id'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $row['image'];
}

function getTeacherVideo($id){
	global $conn;
	$sql="select vid_teacher,concat(t_name,' ',t_lastname) as tname from video v , teachers t where vid_id='$id' and vid_teacher=t.t_id";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	$arr['teacherid']= $row['vid_teacher'];
	$arr['teachername']= $row['tname'];
	return $arr;
}

function getPromotedVideos(){
	global $conn,$filelocation;
	$sql="select vid_id,vtitle,vthumb from video where enb='1' and promoted='1' order by vid_id desc limit 10";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['vid_id'];
		$arr[$i]['vtitle']=stripslashes(trim($row['vtitle']));
		$arr[$i]['vthumb']=$filelocation.'uploads/images/videothumb/'.$row['vthumb'];
		$i++;
	}
	return $arr;
}

function getWatchingVideos(& $emp_ecode){
	global $conn,$filelocation;
	$sql="select v.id,title,vthumb,duration,watchtime,vlink from courses_videos v,videotrack t where t.video=v.id AND t.student='$emp_ecode' order by t.id desc limit 10";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['duration']=$row['duration'];
		$arr[$i]['watchtime']=$row['watchtime'];
		$arr[$i]['vlink']=$row['vlink'];
		$arr[$i]['vtitle']=stripslashes(trim($row['title']));
		$arr[$i]['vthumb']=$filelocation.'uploads/images/coursevideos/'.$row['vthumb'];
		$i++;
	}
	return $arr;
}


function getLiveSessions(){
	global $conn,$filelocation;
	$classid=$_SESSION['class'];
	$sql="select vid_id,vtitle,vdesc,vthumb,t_pic,vid_teacher,subject_name,concat(t_name,' ',t_lastname) as tname,sub_start_at,sub_end_at from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_class='$classid' order by sub_start_at asc limit 30 ";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$tdate = date('Y-m-d',strtotime($row['sub_start_at']));
		//echo'<br>',$tdate,'>=',date('Y-m-d');
		if($row['sub_end_at']>=date('Y-m-d H:i:s')){
		$arr[$i]['id']=$row['vid_id'];
		$arr[$i]['sub_start']=$tdate;
		$arr[$i]['sub_start_at']=$row['sub_start_at'];
		$arr[$i]['sub_end_at']=$row['sub_end_at'];
		$arr[$i]['t_name']=$row['tname'];
		$arr[$i]['vdesc']=stripslashes(trim($row['vdesc']));
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
		$arr[$i]['vtitle']=stripslashes(trim($row['vtitle']));
		$arr[$i]['vthumb']=$filelocation.'uploads/images/videothumb/'.$row['vthumb'];
		//if(file_exists($filelocation.'uploads/teacher/'.$row['t_pic']))
		if($row['t_pic']!=''){
			$pic = 'uploads/teacher/'.$row['t_pic'];
			if(!file_exists($pic))$pic = 'uploads/avtar.png';
		}	else $pic = 'uploads/avtar.png';
		$arr[$i]['tpic']=$pic;
		//else $arr[$i]['tpic']='uploads/avtar.png';
	
		$i++;
		}
	}
	return $arr;
}

function getTimeTable(){
	global $conn,$filelocation;
	$classid=$_SESSION['class'];
	
	if(isset($_GET['date']) && $_GET['date']!='') {
		$weekstart =$_GET['date'];
		///$weekstart =  date('Y-m-d',strtotime("+1 day"));    //from today
		$weekend = date('Y-m-d',strtotime("+6 day", strtotime($weekstart)));

	}
	else {
	$weekstart = date('Y-m-d',strtotime('monday this week'));
    $weekend = date('Y-m-d',strtotime('sunday this week 23:59:59'));
	}
	
	
	$sql="select vid_id,subject_name,sub_start_at,sub_end_at from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_class='$classid' 
	AND (DATE_FORMAT(sub_start_at,'%Y-%m-%d')>='$weekstart' and DATE_FORMAT(sub_start_at,'%Y-%m-%d')<='$weekend') 
	and (DATE_FORMAT(sub_end_at,'%Y-%m-%d')<='$weekend')
	order by sub_start_at asc limit 30 ";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['vid_id'];
		$arr[$i]['start_date']=date('Y-m-d',strtotime($row['sub_start_at']));
		$arr[$i]['day_name']=date('l',strtotime($row['sub_start_at']));
		$arr[$i]['period_slot']=date('H:iA',strtotime($row['sub_start_at']));
		$arr[$i]['time1']=date('H:i',strtotime($row['sub_start_at']));
		$arr[$i]['time2']=date('H:i',strtotime($row['sub_end_at']));
		$arr[$i]['tpye']='livesession';		
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));		
		//$arr[$i]=date('H:i',strtotime($row['sub_end_at']));
		$i++;
		
		
	}
	
	
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_freetext f,subjects s WHERE  s.subject_id=f.subject AND class='$classid' AND closedate<='$weekend' and evid='0' order by opendate asc limit 30";
	
	$res = mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['start_date']=$row['opendate'];
		$arr[$i]['day_name']=date('l',strtotime($row['opendate']));
		//$arr[$i]['period_slot']=date('H:iA',strtotime($row['sub_start_at']));
		$arr[$i]['tpye']='assignment';		
		$arr[$i]['qtpye']='freetext';		
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));		
		$i++;
		
	}
	
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_questiondoc f,subjects s WHERE  s.subject_id=f.subject AND class='$classid' AND closedate<='$weekend' and evid='0' order by opendate asc limit 30";
	
	$res = mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['start_date']=$row['opendate'];
		$arr[$i]['day_name']=date('l',strtotime($row['opendate']));
		//$arr[$i]['period_slot']=date('H:iA',strtotime($row['sub_start_at']));
		$arr[$i]['tpye']='assignment';
		$arr[$i]['qtpye']='doc';		
		
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));		
		$i++;
		
	}
	
	$sql="select v.*,subject_name from tbl_evolution v,subjects s where s.subject_id=v.subject and v.status='1' and classroom='$classid' 
	AND (opendate >='$weekstart' and opendate <='$weekend' )
	and closedate<='$weekend'
	order by opendate asc limit 30 ";
	$res = mysqli_query($conn,$sql);
	
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['start_date']=$row['opendate'];
		$arr[$i]['day_name']=date('l',strtotime($row['opendate']));
		//$arr[$i]['period_slot']=date('H:iA',strtotime($row['sub_start_at']));
		$arr[$i]['tpye']='exam';		
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));		
		$i++;
		
	}
	return $arr;
}




function getTimeTableBak(){
	global $conn,$filelocation;
	//print_r($_SESSION);
	$classid=$_SESSION['class'];
	//$sql="SELECT tt_id,subject_name,period_slot,t_name,t_pic,day_name from timetable m, teachers t,days d,periods p,subjects s where m.tt_teacher=t.t_id and tt_day=day_id and tt_prd=period_id and tt_sub=s.subject_id and m.tt_isactive=1 and tt_class='$classid' and day_name in(DAYName(CURDATE()),DAYName(CURDATE()+1),DAYName(CURDATE()+2),DAYName(CURDATE()+3),DAYName(CURDATE()+4),DAYName(CURDATE()+5),DAYName(CURDATE()+6)) order by day_name,period_slot asc";
	
	$sql="SELECT tt_id,subject_name,period_slot,concat(t_name,' ',t_lastname) as tname,t_pic,day_name as Day from timetable m, teachers t,days d,periods p,subjects s where m.tt_teacher=t.t_id and tt_day=day_id and tt_prd=period_id and tt_sub=s.subject_id and m.tt_isactive=1 and tt_class='$classid' and day_name in('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') order by 
	CASE
          WHEN Day = 'Sunday' THEN 1
          WHEN Day = 'Monday' THEN 2
          WHEN Day = 'Tuesday' THEN 3
          WHEN Day = 'Wednesday' THEN 4
          WHEN Day = 'Thursday' THEN 5
          WHEN Day = 'Friday' THEN 6
          WHEN Day = 'Saturday' THEN 7
     END 
	,period_slot asc";
	
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['tt_id']=$row['tt_id'];
		$slot = explode('-',$row['period_slot']);
		$arr[$i]['period_slot']=$slot[0];
		$arr[$i]['time1']=date('H:i',strtotime($slot[0]));
		$arr[$i]['time2']=date('H:i',strtotime($slot[1]));
		if($row['t_pic']!='' ){
			$pic= $filelocation."uploads/teacher/".$row['t_pic'];
			//if(file_exists($pic))
			$arr[$i]['t_pic']=$pic;
			//else $arr[$i]['t_pic']="uploads/avtar.png";
		}
		else $arr[$i]['t_pic']="uploads/avtar.png";
		$arr[$i]['t_name']=$row['tname'];
		$arr[$i]['day_name']=$row['Day'];
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
		$i++;
	}
	//print_r($arr);
	return $arr;
}

function getLatestVideos($limit=''){
	global $conn,$filelocation;
	$classid=$_SESSION['class'];
	$sql="SELECT c.*,subject_name FROM courses_videos c,subjects s  WHERE c.subject=s.subject_id AND c.class='$classid' ORDER BY id DESC";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['title']=trim($row['title']);
		$arr[$i]['id']=$row['id'];
		$arr[$i]['vthumb']=$filelocation."uploads/images/coursevideos/".$row['vthumb'];
		$arr[$i]['subject_name']=$row['subject_name'];
		$i++;
	}
	return $arr;
}

function getLatestVideosbak($limit=''){
	global $conn,$filelocation;
	if($limit!='') $limits=" limit $limit";else $limits='';
	/*$sql="select vid_id,vid_type,vtitle,vthumb,vdesc,subject_name from video v, subjects s where vid_sub=subject_id and v.enb='1' $limits";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['vid_id']=$row['vid_id'];
		$arr[$i]['vid_type']=$row['vid_type'];
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
		$arr[$i]['vtitle']=stripslashes(trim($row['vtitle']));
		$arr[$i]['vdesc']=stripslashes(trim($row['vdesc']));
		$arr[$i]['vthumb']=$filelocation.'uploads/images/videothumb/'.$row['vthumb'];
		$i++;
	}*/
	$classid=$_SESSION['class'];
	$sql="SELECT videos FROM courses WHERE class='$classid' ORDER BY id DESC LIMIT 10";
	$res = mysqli_query($conn,$sql);
	$varr=array();
	$varr1=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$varrex = explode(',',$row['videos']);
		for($c=0; $c<count($varrex); $c++){
		$varr[]=	$varrex[$c];
		}
	}
	$varr= array_unique($varr);
	rsort($varr);
	//echo'<pre>',print_r($varr1),'</pre>';
	//echo'<pre>',print_r($varr),'</pre>';
	return $varr;
}
function getSubjects($limit=''){
	global $conn,$filelocation;
	$classid=$_SESSION['class'];

	if($limit!='') $limits=" limit $limit";else $limits='';
	$sql="select subject_name,chapters,sthumb,subject_id from subjects where subject_class='$classid' AND subject_isactive='1' $limits";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['subject_id']=$row['subject_id'];
		$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
		$arr[$i]['chapters']=stripslashes(trim($row['chapters']));
		$arr[$i]['sthumb']=$filelocation.'uploads/subjects/'.$row['sthumb'];
		$i++;
	}
	return $arr;
}

function getAchivements(){
	global $conn;
	$sql="select title,winner_name,rank from awards where status='1' order by awards_id desc limit 10 ";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['title']=$row['title'];
		$arr[$i]['rank']=$row['rank'];
		$arr[$i]['winner_name']=stripslashes(trim($row['winner_name']));
		$i++;
	}
	return $arr;
}
function getTalks(){
	global $conn;
	$ecode = $_SESSION["eid"];
	$sql="select q_details,concat(student_name,' ',student_lastname) as sname,qdate from ask_management a , students s where a.ecode=s.ecode and a.ecode='$ecode' order by ask_id desc limit 20 ";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['qdate']=date('d-m-Y',strtotime($row['qdate']));
		$arr[$i]['student_name']=$row['sname'];
		$arr[$i]['q_details']=stripslashes(trim($row['q_details']));
		$i++;
	}
	return $arr;
}

function saveNotification($vals){
	global $conn;
	//print_r($vals);
	$key=implode(',',array_keys($vals));
	$val=implode("','",array_values($vals));
	$sql="insert into notifications($key,created) values('$val',NOW())";
	$res = mysqli_query($conn,$sql);
}

function getCountNotification($type){
	global $conn;
	$sql="SELECT count(id) as cnt FROM notifications WHERE to_type='$type' AND status='1'";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	return $row['cnt'];
}

function getNotifications($type){
	global $conn;
	$pdate = date('Y-m-d', strtotime(' -1 day'));
	$sql="SELECT * FROM notifications WHERE to_type='$type' AND status='1' and created>='$pdate' order by id desc  LIMIT 10";
	$res=mysqli_query($conn,$sql);
	$i=0; $arr=array();
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['from_id']=$row['from_id'];
		$arr[$i]['from_type']=$row['from_type'];
		$arr[$i]['to_id']=$row['to_id'];
		$arr[$i]['to_type']=$row['to_type'];
		$arr[$i]['page']=$row['page'];
		$arr[$i]['tableid']=$row['tableid'];
		$arr[$i]['tablename']=$row['tablename'];
		$arr[$i]['created']=$row['created'];
		$arr[$i]['comments']=$row['comments'];
	$i++;	
	}
	return $arr;
}

function getLatestTest(){
	global $conn;
	$class=$_SESSION["class"];
	$sql="SELECT * FROM tbl_evolution WHERE class='$class' AND status=1 ORDER BY id DESC LIMIT 1;";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	$arr['id']=$row['id'];
	$arr['created']=$row['created'];
	return $arr;
}

function getStudentInfo(& $emp_ecode){
	global $conn,$filelocation;
	$sql_dept = $conn->query("SELECT * FROM students 
	INNER JOIN classrooms ON students.dept_id = classrooms.class_id and students.ecode = '$emp_ecode' ");
	$row_dept = $sql_dept->fetch_assoc();
	$arr['class_id']= $classid = $row_dept['dept_id'];
	$arr['std_id']=$row_dept['std_id'];
	$arr['class_name']=$row_dept['class_name'];
	$arr['student_name']= $row_dept['student_name'];
	$arr['student_lastname']= $row_dept['student_lastname'];
	$arr['father_name']= $row_dept['father_name'];
	$arr['mother_name']= $row_dept['mother_name'];
	$arr['father_contact']= $row_dept['father_contact'];
	$arr['mother_contact']= $row_dept['mother_contact'];
	$arr['roll_no']= $row_dept['roll_no'];
	$arr['email']= $row_dept['email'];
	$arr['mobile']= $row_dept['mobile'];
	$arr['ecode']= $row_dept['ecode'];
	if($row_dept['image']!=''){
	 $image = $filelocation.'uploads/images/students/'.$row_dept['image'];
		if(!file_exists($image)) $image=$filelocation.'uploads/avtar.png';	
	}
	else $image=$filelocation.'uploads/avtar.png';
	 
	 
	//if(!file_exists($arr['image']))	$arr['image']='uploads/images/students/dummy-image.jpg';	
	
	$arr['image']=$image;
	$arr['date_birth']= date('d M Y',strtotime($row_dept['date_birth']));
	$arr['date_join']= date('d M Y',strtotime($row_dept['date_join']));
	$arr['gender']= $row_dept['gender'];
	$arr['designation']= $row_dept['designation'];
	$arr['branch']= $row_dept['branch'];
	$arr['state']= $row_dept['state'];
	$arr['reporting_manager']= $row_dept['reporting_manager'];
	return $arr;
}

function getClassTeacher(& $cid){
	global $conn,$filelocation;
	//$sql="select t_name,t_id,t_pic from teachers t,students s where ecode='$ecode' and t_code=teacher_code";
	$sql="select concat(t_name,' ',t_lastname) as tname,t_id,t_pic from teachers t,classrooms s where class_id='$cid' and class_teacher=t_id";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	$arr['t_name']=$row['tname'];
	$arr['t_id']=$row['t_id'];
	if($row['t_pic'])
		$arr['t_pic']=$filelocation.'uploads/teacher/'.$row['t_pic'];
	else $arr['t_pic']='uploads/avtar.png';
	return $arr;
}
function getAllStudents(){
	global $conn,$filelocation;
	$class=$_SESSION["class"];
	$sql="select std_id,ecode,roll_no,concat(student_name,' ',student_lastname) as sname,image from students where dept_id='$class' and status=1";
	$res=mysqli_query($conn,$sql);
	$i=0;
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['std_id']=$row['std_id'];
	$arr[$i]['ecode']=$row['ecode'];
	$arr[$i]['roll_no']=$row['roll_no'];
	$arr[$i]['student_name']=$row['sname'];
	if($row['image']!='')
	$arr[$i]['image']=$filelocation.'uploads/images/students/'.$row['image'];
	else $arr[$i]['image']='uploads/avtar.png';
	$i++;
	}	
	return $arr;
}

function getAdminMessages(& $std){
	global $conn;
	$sql="SELECT * FROM `message` WHERE to_empid= '$std' order by msg_id DESC";
	$res=mysqli_query($conn,$sql);
	$i=0;
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['send_date']=date('d M Y H:i A',strtotime($row['send_date']));
	$arr[$i]['from_empid']=$row['from_empid'];
	$arr[$i]['sub']=stripslashes(trim($row['sub']));
	$arr[$i]['msg']=stripslashes(trim($row['msg']));
	$i++;
	}	
	return $arr;
}
function getConversation(& $std){
	global $conn;
	$sql="SELECT * FROM `ask_questions` WHERE ecode= '$std' AND `forteacher` = 1 order by ask_id desc";
	$res=mysqli_query($conn,$sql);
	$i=0;
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['qdate']=date('d M Y H:i A',strtotime($row['qdate']));
	$arr[$i]['vtime']=gmdate('H:i:s A',$row['vtime']);
	$arr[$i]['ask_id']=$row['ask_id'];
	$arr[$i]['q_title']=$row['q_title'];
	$arr[$i]['q_details']=stripslashes(trim($row['q_details']));
	$i++;
	}	
	return $arr;
}


function getConversationVideo(& $std,& $video_id){
	global $conn;
	$sql="SELECT a.*,t.t_name,r.flag FROM `ask_questions` a, teachers t,raise_question r,video v WHERE ecode= '$std' AND  t.t_id=a.teachervid and r.id=a.raiseid and v.vid_id=r.vid and v.vid_id='$video_id' order by ask_id desc";
	$res=mysqli_query($conn,$sql);
	$i=0;
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['qdate']=date('d M Y H:i A',strtotime($row['qdate']));
	$arr[$i]['vtime']=gmdate('H:i:s A',$row['vtime']);
	$arr[$i]['flag']=$row['flag'];
	$arr[$i]['ask_id']=$row['ask_id'];
	$arr[$i]['q_title']=$row['q_title'];
	$arr[$i]['q_details']=stripslashes(trim($row['q_details']));
	$i++;
	}	
	return $arr;
}

/*function getStudyMaterials(){
	global $conn;
	$class=$_SESSION["class"];
	$arr=array();
	//echo $sql="SELECT count(vid_id) as cnt,vid_id,vtitle,vthumb,vid_sub FROM study_material s, video v WHERE s.mat_vid=v.vid_id and vid_class='$class' group by vid_id";
	$sql="SELECT count(vid_sub) as cnt, vid_sub FROM study_material s, video v WHERE s.mat_vid=v.vid_id and vid_class='$class' GROUP BY vid_sub";
	$res = $conn -> query($sql);
	if($res->num_rows){
		$i=0;
		while($row = $res->fetch_assoc()){
		  $arr[$i]['cnt']=$row['cnt'];
		  $arr[$i]['vid_sub']=$row['vid_sub'];
		  $i++;		  
		}
	}
	//print_r($arr);
	return $arr;	
}*/

function getStudyMaterials(){
	global $conn;
	$class=$_SESSION["class"];
	$arr=array();	
	$sql="SELECT COUNT(`subject`) as cnt,subject,subject_name from study_documents d,subjects s WHERE d.subject=s.subject_id and d.class='$class' and d.class=s.subject_class GROUP by subject";
	$res = $conn -> query($sql);
	if($res->num_rows>0){
		$i=0;
		while($row = $res->fetch_assoc()){
		  $arr[$i]['cnt']=$row['cnt'];
		  $arr[$i]['subject_name']=$row['subject_name'];
		  $arr[$i]['subjectid']=$row['subject'];
		  $i++;		  
		}
	}
	//print_r($arr);
	return $arr;	
}

function getStudyMaterialsListBySubgect(& $sid){
	global $conn,$filelocation;
	$arr=array();
	$sql="SELECT d.*,subject_name from study_documents d,subjects s WHERE d.subject=s.subject_id and d.subject='$sid' and status='1'";
	$res = $conn -> query($sql);
	if($res->num_rows>0){
		$i=0;
		while($row = $res->fetch_assoc()){
		  $arr[$i]['name']=$row['name'];
		  $arr[$i]['studydoc']=$row['studydoc'];
		  $arr[$i]['subject_name']=$row['subject_name'];
		  $arr[$i]['subjectid']=$row['subject'];
		  $i++;		  
		}
	}
	//print_r($arr);
	return $arr;
}	
function getStudyMaterialsListByVideo(& $vid){
	global $conn,$filelocation;
	$arr=array();
	$sql="SELECT s.*,vtitle FROM study_material s, video v WHERE v.vid_id=s.mat_vid AND mat_vid='$vid'";
	$res = $conn -> query($sql);
	if($res->num_rows){
		$i=0;
		while($row = $res->fetch_assoc()){
		  $arr[$i]['mat_id']=$row['mat_id'];
		  $arr[$i]['mat_vid']=$row['mat_vid'];
		  $arr[$i]['vtitle']=stripslashes(trim($row['vtitle']));
		  $arr[$i]['mat_desc']=stripslashes(trim($row['mat_desc']));
		  $arr[$i]['mat_sub_ref']=$row['mat_sub_ref'];
		  $arr[$i]['mat_ref']=$row['mat_ref'];
		  $arr[$i]['mat_link']=$row['mat_link'];
		  $arr[$i]['enb']=$row['enb'];
		  $i++;		  
		}
	}
	return $arr;	
}


function checkStudyMaterial(& $video_id){
	global $conn;
	$vid_mat_sql = $conn -> query("SELECT * FROM study_material  WHERE mat_vid='$video_id'");
	if ($vid_mat_sql->num_rows > 0){
	  $vid_mat_row = $vid_mat_sql->fetch_assoc();
	}
	if(isset($vid_mat_row)){
	$disable_smaterial = '';
	}
	else{
	$disable_smaterial = 'disabled'; 
	}
	return $disable_smaterial;
}

function checkTeacherOnline(&$teacherid){
	global $conn;
	$adminEmail =  "jiten003@gmail.com"; 
	$admin_sql = $conn -> query("SELECT * FROM admin where email='$adminEmail'");
	if ($admin_sql->num_rows > 0){
		while($admin_row = $admin_sql->fetch_assoc()){
		  $isOnline = $admin_row['isOnline'];
		}  
	}                  
	$teacher_sql = $conn -> query("SELECT * FROM teachers where t_id=$teacherid");
	if ($teacher_sql->num_rows > 0){
		while($teacher_row = $teacher_sql->fetch_assoc()){
		  if(isset($isOnline)){
			if($isOnline=='1'){
			  echo $teacher_row['t_name'].' '.$teacher_row['t_lastname']." <span class='ol_stats'></span>";
			}
			else{
			  echo $teacher_row['t_name'].' '.$teacher_row['t_lastname']." <span class='of_stats'></span>";
			}
		  }
		  else{
			  echo $teacher_row['t_name'].' '.$teacher_row['t_lastname']." <span class='of_stats'></span>";
		  }
		}  
	}
}

function getCourses(&$subject_id,& $emp_ecode){
	global $conn,$filelocation;
	$arr = array();
	$get_records = $conn -> query("SELECT * from courses WHERE subject='$subject_id' order by id desc");
	if ($get_records->num_rows> 0){
		$i=0;
		while($row = $get_records->fetch_assoc()){
			$arr[$i]['id']=$cid= $row['id'];
			$arr[$i]['name']= stripslashes(trim($row['name']));
			//$arr[$i]['subbject_name']= stripslashes(trim($row['subbject_name']));
			$arr[$i]['chapter']= $row['chapter'];
			$arr[$i]['cthumb']= $filelocation.'uploads/images/courses/'.$row['cthumb'];
			$listvideos=explode(',',$row['videos']);
			$listprefs=explode(',',$row['preferences']);
			if(count($listvideos)==count($listprefs))
			$newarr=array_combine($listprefs,$listvideos);
			else $newarr=$listvideos;
			ksort($newarr);	
			$arr[$i]['videoarr']=array_values($newarr);
			$sl="SELECT cid from course_likes WHERE cid='$cid' and stdid='$emp_ecode'";
			$get_like = $conn -> query($sl);						
			
			if ($get_like->num_rows==0){
				$arr[$i]['st']=0; 
				$arr[$i]['cls']='fa fa-heart-o';
			}
			else {
				$arr[$i]['st']=1;
				$arr[$i]['cls']='fa fa-heart';
			}
			
		$i++;	
		}
	}
		return $arr;
}

function getVideoThumb(& $vid){
	global $conn,$filelocation;
	$sql="select vthumb from video where vid_id='$vid'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $filelocation."uploads/images/videothumb/".$row['vthumb'];
}
function getCourseVideoThumb(& $vid){
	global $conn,$filelocation;
	$sql="select vthumb from courses_videos where id='$vid'";
	$res = mysqli_query($conn,$sql);
	$row = mysqli_fetch_array($res);
	return $filelocation."uploads/images/coursevideos/".$row['vthumb'];
}
function getTotalCourseBySubject(&$subject_id){
	global $conn;
	$count=0;
	$get_records = $conn -> query("SELECT videos from courses WHERE subject='$subject_id'");
	if ($get_records->num_rows> 0){		
		while($row = $get_records->fetch_assoc()){
			$count++;//=$count+ count(explode(',',$row['videos']));
		}
	}	
	return $count;		
}
function getLatestCourseBySubject(&$subject_id,& $emp_ecode){
	global $conn;
	$arr = array();
	$get_records = $conn -> query("SELECT name,id,videos from courses WHERE subject='$subject_id' and videos!='' order by id DESC limit 1");
	if ($get_records->num_rows> 0){
		$row = $get_records->fetch_assoc();
		$arr['id']=$cid= $row['id'];
		$arr['name']= stripslashes(trim($row['name']));
		$arr['videos']= $row['videos'];
		$sl="SELECT cid from course_likes WHERE cid='$cid' and stdid='$emp_ecode'";
		$get_like = $conn -> query($sl);						
		
		if ($get_like->num_rows==0){
			$arr['st']=0; 
			$arr['cls']='fa fa-heart-o';
		}
		else {
			$arr['st']=1;
			$arr['cls']='fa fa-heart';
		}		
	}
	return $arr;
}

/*function getVideosByCourse(& $cid){
	global $conn;
	$arr = array();
	$sql = "SELECT name,id from courses WHERE subject='$subject_id' order by id DESC limit 1"
	$get_records = $conn -> query();
	if ($get_records->num_rows> 0){
		$row = $get_records->fetch_assoc();
		$arr['id']=$cid= $row['id'];
		$arr['name']= stripslashes(trim($row['name']));
		$sl="SELECT cid from course_likes WHERE cid='$cid' and stdid='$emp_ecode'";
		$get_like = $conn -> query($sl);						
		
		if ($get_like->num_rows==0){
			$arr['st']=0; 
			$arr['cls']='fa fa-heart-o';
		}
		else {
			$arr['st']=1;
			$arr['cls']='fa fa-heart';
		}
			
		
	}
	return $arr;

}*/

function isTeacherOnline(& $teacherid){
global $conn; 
 $adminEmail =  "jiten003@gmail.com"; 
  $admin_sql = $conn -> query("SELECT * FROM admin where email='$adminEmail'");
  if ($admin_sql->num_rows > 0){
	while($admin_row = $admin_sql->fetch_assoc()){
	 $isOnline = $admin_row['isOnline'];
	}  
  }                  
  $teacher_sql = $conn -> query("SELECT * FROM teachers where t_id=$teacherid");
  if ($teacher_sql->num_rows > 0){
	while($teacher_row = $teacher_sql->fetch_assoc()){
	  if(isset($isOnline)){
		if($isOnline=='1'){
		  echo $teacher_row['t_name']." <span class='active'></span>";
		}
		else{
		  echo $teacher_row['t_name']." <span class='of_stats'></span>";
		}
	  }
	  else{
		  echo $teacher_row['t_name']." <span class='of_stats'></span>";
	  }
	}  
  }

}




//teachers function
function getTeacherClassrooms(&$id){
	global $conn;
	$sql= "SELECT DISTINCT d.*,class_id,subject_name  FROM classrooms d ,teacher_assign a,subjects s WHERE a.classroom=d.class_id and a.  subject=s.subject_id and a.teacher_id ='$id'";
	$result = $conn->query($sql);
	$arr=array();
	if ($result->num_rows > 0)	{
		$i=0;		
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['class_name'] = $row['class_name'];
		  $arr[$i]['class_id'] = $row['class_id'];
		  $arr[$i]['assign_id'] = $row['assign_id'];
		 // $arr['t_branch'] = $row['t_branch'];
		  $arr[$i]['subject_name'] = $row['subject_name'];
		 $i++;
	  }
	}
	return $arr;
}

//homeworks given by teaqcher
function getTeacherHomeworks(&$id){
	global $conn;
	$sql="SELECT DISTINCT h.*,class_name,subject_name FROM homeworks h , teacher_assign a,classrooms c, subjects s WHERE h.hw_class = a.classroom and h.hw_class = c.class_id and s.subject_id=hw_sub and h.hw_sub = a.subject and a.teacher_id =  $id";
	
	$result = $conn->query($sql);
	$arr=array();
	if ($result->num_rows > 0)	{
		$i=0;		
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['hw_id'] = $row['hw_id'];
		  $arr[$i]['hw_code'] = $row['hw_code'];
		  $arr[$i]['hw_req'] = $row['hw_req'];
		  $arr[$i]['subject_name'] = $row['subject_name'];
		  $arr[$i]['class_name'] = $row['class_name'];
		  $arr[$i]['hw_start_date'] = date("d-m-Y", strtotime($row['hw_start_date']));
		  $arr[$i]['hw_end_date'] = date("d-m-Y", strtotime($row['hw_end_date']));
		  $arr[$i]['hw_isActive'] = $row['hw_isActive']== 0 ? 'No' : 'Yes';
		  $arr[$i]['hw_end_date'] = $row['hw_end_date'];
		 $i++;
	  }
	}
	return $arr;
}


//homeworks submission by student
function getHomeworkSubmission(&$techid){
	global $conn;
	$sql="SELECT DISTINCT j.* FROM homeworks h , teacher_assign a , job_application j WHERE h.hw_class = a.classroom and h.hw_sub = a.subject and j.ecode = h.hw_code and a.teacher_id = $techid";
	
	$result = $conn->query($sql);
	$arr=array();
	if ($result->num_rows > 0)	{
		$i=0;		
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['jid'] = $row['jid'];
		  $arr[$i]['ecode'] = $row['ecode'];
		  $arr[$i]['jobid'] = $row['jobid'];
		  $arr[$i]['name'] = $row['name'];
		  $arr[$i]['email'] = $row['email'];
		  $arr[$i]['date'] = date("d-m-Y", strtotime($row['date']));
		  $arr[$i]['mobile'] = $row['mobile'];
		  $arr[$i]['resume'] = "uploads/homeworks/".$row['resume'];
		 $i++;
	  }
	}
	return $arr;
}


function tmp_getSubjectOptionsFromClass(&$id,$sub=''){
	global $conn;
	$sql="SELECT subject_id,subject_name from subjects WHERE subject_class='$id' AND subject_isactive=1";
	$result1 = $conn->query($sql);
	if ($result1->num_rows > 0)
	{
		echo "<select name='hw_sub' id='hw_sub'  class='form-control classroom' required='required' >";
		echo"<option value=''>Select Subject</option>" ;
		while($row1 = $result1->fetch_assoc())
	  {
		  $subject_name = $row1['subject_name'];
		  $subject_id =  $row1['subject_id'];
		  if($sub!='' && $sub==$subject_id) $sel='selected';else $sel='';
		  echo"<option value='$subject_id' $sel>$subject_name</option>" ;
	  }
	echo "</select>";
	}
}

//dropdown for videos class type
function sel_video_classtype($selclass=''){
	global $conn;
	$classtype_sql= "SELECT * FROM class_type where classtype_sts='1'";
	$classtype_result = $conn->query($classtype_sql);
	if ($classtype_result->num_rows > 0)
	{
	echo "<select name='vid_classtype'  class='form-control' required='required' >";
	  echo"<option value=''>Select Class Type</option>" ;
	  while($classtype_row = $classtype_result->fetch_assoc())
	  {
	  $classtype_name = $classtype_row['classtype_name'];
	  $classtype_id =  $classtype_row['classtype_id'];
	  if($selclass!='' && $selclass==$classtype_id){
		echo"<option value='$classtype_id' selected>$classtype_name</option>" ;
	  }
	  else{
		echo"<option value='$classtype_id'>$classtype_name</option>" ;
	  }
	  }
	echo "</select>";
	}
}


//dropdown for teachers
function sel_teachers($selclass=''){
	global $conn;
	$sql= "SELECT t_name,t_lastname,t_id FROM teachers where t_isActive='1'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)
	{
	echo "<select name='vid_teacher' class='form-control' required='required' >";
	  echo"<option value=''>Select Class Type</option>" ;
	  while($row = $result->fetch_assoc()) {
		$t_name = $row['t_name'].' '.$row['t_lastname'];
		$t_id =  $row['t_id'];
	  if($selclass!='' && $selclass==$t_id){
		echo"<option value='$t_id' selected>$t_name</option>" ;
	  }
	  else{
		echo"<option value='$t_id'>$t_name</option>" ;
	  }
	  }
	echo "</select>";
	}
}

//dropdown for video categories
function sel_video_categories($selclass=''){
	global $conn;
	$sql= "SELECT vcategory,vid FROM video_category";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{
		echo "<select name='vcid' class='form-control' required='required' >";
		echo"<option value=''>Select Category</option>" ;
		while($row = $result->fetch_assoc()) {
			$vcategory = $row['vcategory'];
			$vid =  $row['vid'];
			if($selclass!='' && $selclass==$vid){
				echo"<option value='$vid' selected>$vcategory</option>" ;
			}
			else{
				echo"<option value='$vid'>$selclass, $vcategory</option>" ;
			}
		}
		echo "</select>";
	}
}
//dropdown for classrooms
function sel_classrooms($selclass=''){
	global $conn;
	$sql= "SELECT class_name,class_id from classrooms";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{
		echo "<select name='vid_class' id='vid_class' class='form-control classroom' required='required' required='required' >";
		echo"<option value=''>Select Classroom</option>" ;
		while($row = $result->fetch_assoc()) {
			$class_name = $row['class_name'];
			$class_id =  $row['class_id'];
			if($selclass!='' && $selclass==$class_id){
				echo"<option value='$class_id' selected>$class_name</option>" ;
			}
			else{
				echo"<option value='$class_id'>$class_name</option>" ;
			}
		}
		echo "</select>";
	}
}


//teacher's video list
function getTeacherVideosList(&$techid){
	global $conn;
	$sql="SELECT * FROM video INNER JOIN video_category ON video.vc_id = video_category.vid where vid_teacher = $techid";	
	$result = $conn->query($sql);
	$arr=array();
	if ($result->num_rows > 0)	{
		$i=0;		
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['vid_id'] = $row['vid_id'];
		  $arr[$i]['vthumb'] = $row['vthumb']=== NULL ?"uploads/images/videothumb/dummy.png":"uploads/images/videothumb/".$row['vthumb'];
		  $arr[$i]['vtitle'] = $row['vtitle'];
		  $arr[$i]['vcategory'] = $row['vcategory'];		  
		 $i++;
	  }
	}
	return $arr;
}

//GET ALL COURSES LIST IN ADMIN PAGE
function getAllcourses(){
	global $conn;
	$sql="select c.*,class_name,subject_name,b.name as sybname from  courses c, subjects s, classrooms r,syllabus b where c.subject=s.subject_id and c.class=r.class_id and c.syllabus=b.id";	
	$result = $conn->query($sql);
	$arr=array();
	if ($result->num_rows > 0)	{
		$i=0;		
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id'] = $row['id'];
		  $arr[$i]['name'] = $row['name'];
		  $arr[$i]['subject_name'] = $row['subject_name'];
		  $arr[$i]['chapter'] = $row['chapter'];
		  $arr[$i]['class_name'] = $row['class_name'];
		  $arr[$i]['sybname'] = $row['sybname'];		  
		  $arr[$i]['section'] = $row['section'];		  
		 $i++;
	  }
	}
	return $arr;
}
function getCourse(& $cid){
	global $conn;	
	$sql="SELECT * FROM courses WHERE id='$cid'";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	$arr['id']=$row['id'];
	$arr['name']=$row['name'];
	$arr['description']=stripslashes(trim($row['description']));
	$arr['class']=$row['class'];
	$arr['subject']=$row['subject'];
	$arr['section']=$row['section'];
	$arr['chapter']=$row['chapter'];
	$arr['syllabus']=$row['syllabus'];
	$arr['subject']=$row['subject'];
	$arr['videos']=$row['videos'];
	$arr['preferences']=$row['preferences'];
	return $arr;
}

function watchingVideos($student,$video){
	global $conn;
	$sqlchk="SELECT * FROM session_watching WHERE student='$student' AND video='$video'";
	$vidchk = $conn -> query($sqlchk);
	if($vidchk->num_rows ==0){		
		$sql = "INSERT INTO session_watching(student,video) VALUES('$student','$video')";
		$res = mysqli_query($conn,$sql);
	}
}
function getStudentsWatchingVideos($video){
	global $conn,$filelocation;
	$sql="SELECT concat(student_name,' ',student_lastname) as sname,image FROM session_watching w,students s WHERE student=ecode AND video='$video'";
	$res=mysqli_query($conn,$sql);
	$i=0;$arr=array();
	while($row=mysqli_fetch_array($res)){
	$arr[$i]['student_name']=$row['sname'];
	if($row['image']!='')
	$arr[$i]['image']=$filelocation.'uploads/images/students/'.$row['image'];
	else
	$arr[$i]['image']=$filelocation.'uploads/avtar.png';
	$i++;
	}
	return $arr;
}
function deleteWatchingVideos(& $ecode){
	global $conn;
	$sql="DELETE FROM session_watching WHERE student='$ecode'";
	$res=mysqli_query($conn,$sql);	
}


function getStudentTasks(& $stdcode){
	global $conn;
	if(isset($_GET['date'])) $today = $_GET['date'];
	else
	$today = date('Y-m-d');
	$sql="SELECT * FROM student_task WHERE stdid='$stdcode' AND (taskdate='$today' OR allday='1') ORDER BY time ASC";
	$res=mysqli_query($conn,$sql);
	$i=0;$arr=array();
	$furnish=0;
	$unfurnish=0;
	while($row=mysqli_fetch_array($res)){		
	$arr[$i]['id']=$row['id'];
	$arr[$i]['taskname']=$row['taskname'];
	$arr[$i]['allday']=$row['allday'];
	$arr[$i]['alldatstatus']=$row['alldatstatus'];
	$arr[$i]['taskdate']=$row['taskdate'];
	$arr[$i]['time']=$row['time'];
	$arr[$i]['color']=$row['color'];
	$arr[$i]['status']=$row['status'];
	$arr[$i]['furnish']=$row['status']==0?$furnish=$furnish+1 :$furnish;
	$arr[$i]['unfurnish']=$row['status']==1?$unfurnish=$unfurnish+1: $unfurnish;
	
	$i++;
	}
	return $arr;	
}

function getFurnishStudentTasks(& $stdcode){
	global $conn;
	if(!isset($_GET['date']))
	$today = date('Y-m-d');
	else $today=$_GET['date'];
	$sql="SELECT id,status,allday FROM student_task WHERE stdid='$stdcode' AND (taskdate='$today' or allday='1') ORDER BY time ASC";
	$res=mysqli_query($conn,$sql);
	$arr=array();
	$furnish=0;
	$unfurnish=0;
	
	while($row=mysqli_fetch_array($res)){
		$id = $row['id'];
		$allday = $row['allday'];
		if($allday=='1'){
			$sqlc="SELECT * FROM `tbl_allday` where stdid='$stdcode' and taskid='$id' and cdate='$today'";
			$resc = mysqli_query($conn,$sqlc);
			if(mysqli_num_rows($resc)>0) {
				$furnish=$furnish+1;
				$arr['furnish']=$furnish;
			} else{
				$unfurnish=$unfurnish+1;
				$arr['unfurnish']=$unfurnish;
			}	
		} else{
			$arr['furnish']=$row['status']==0?$furnish=$furnish+1:$furnish;
			$arr['unfurnish']=$row['status']==1?$unfurnish=$unfurnish+1:$unfurnish;	
		}	
	}
	if(!empty($arr)){
	$per= 100*$arr['furnish']/array_sum($arr);
	$arr['per']=$per;
	} else{
		$arr['furnish']=0;
		$arr['unfurnish']=0;
	}
	return $arr;	
}

function saveTask(){
	global $conn;
	$stdid = $_SESSION["eid"];
	$taskname= trim(addslashes($_POST['task_name']));
	$description = trim(addslashes($_POST['description']));
	$allday=$_POST['alldeaycheck'];
	$taskdate=$_POST['date'];
	$time=$_POST['time'];
	$color=$_POST['colorselector_1'];
	$sql="INSERT INTO student_task(stdid,taskname,description,allday,taskdate,time,color,created,status) VALUES('$stdid','$taskname','$description','$allday','$taskdate','$time','$color',NOW(),'1')";
	$res = mysqli_query($conn,$sql);
	
}

function deleteTask(& $id){
	global $conn;
	$sql="DELETE FROM student_task WHERE id='$id'";
	$res = mysqli_query($conn,$sql);
}	
function updateTask(){
	global $conn;
	$taskid = $_POST["taskid"];
	$taskname= trim(addslashes($_POST['task_name']));
	$description = trim(addslashes($_POST['description']));
	$allday=$_POST['alldeaycheck'];
	$taskdate=$_POST['date'];
	$time=$_POST['time'];
	$color=$_POST['colorselector_3'];
	$sql="UPDATE student_task set taskname='$taskname',description='$description',allday='$allday',taskdate='$taskdate',time='$time',color='$color' WHERE id='$taskid'";
	$res = mysqli_query($conn,$sql);
}


function getTask(& $id){
	global $conn;
	$sql="SELECT * FROM student_task WHERE id='$id'";
	$res=mysqli_query($conn,$sql);
	$arr=array();
	$row=mysqli_fetch_array($res);
	$arr['id']=$row['id'];
	$arr['taskname']=stripslashes($row['taskname']);
	$arr['description']=stripslashes($row['description']);
	$arr['allday']=$row['allday'];
	$arr['taskdate']=date('d M Y',strtotime($row['taskdate']));
	$arr['created']=date('d M Y',strtotime($row['created']));
	$arr['time']=$row['time'];
	$arr['color']=$row['color'];
	$arr['status']=$row['status'];	
	
	return $arr;	
}

function statusTask(& $id, & $status, & $gdate){
	global $conn;
	$eid = $_SESSION["eid"];
	if($status==1) $st='0'; else $st='1';
	
	$sqla="SELECT allday FROM student_task WHERE id='$id'";
	$resa=mysqli_query($conn,$sqla);
	$rowa=mysqli_fetch_array($resa);
	$cday=date('Y-m-d');
	//echo 'allday',$rowa['allday'];
	if($rowa['allday']=='1') {
		echo 'alldaydate ',$gdate,':',$alldaydate=getAllDayStatus($id,$eid,$gdate);
		if($alldaydate==$gdate) 
		{
			$sqldel="delete from tbl_allday where stdid='$eid' and taskid='$id' and cdate='$gdate'";
			mysqli_query($conn,$sqldel);
		}	
		else		
		$sql="INSERT INTO tbl_allday SET stdid='$eid',taskid='$id',cdate='$gdate'";
	}
	else
	$sql="UPDATE student_task SET status='$st' WHERE id='$id'";
	$res=mysqli_query($conn,$sql);
}

function getAllDayStatus(& $id,& $emp_ecode, & $gdate){
	global $conn;
	$sql="SELECT cdate FROM tbl_allday WHERE stdid='$emp_ecode' AND taskid='$id' AND cdate='$gdate'";
	$res=mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		$rows= mysqli_fetch_array($res);
		return $rows['cdate'];	
	}	
}
function getAssignments(& $emp_ecode){
	global $conn;
	$class = $_SESSION["class"];
	if(isset($_GET['adate']) && $_GET['adate']!='') $today =$_GET['adate'];
	else $today=date('Y-m-d');
	//$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_freetext f,tbl_evolution e,subjects s WHERE evid=e.id AND s.subject_id=f.subject AND e.evolutiontype='assignment' AND classroom='$class' AND (opendate='$today') ORDER BY created DESC";
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_freetext f,subjects s WHERE  s.subject_id=f.subject AND class='$class' AND closedate>='$today' ORDER BY id DESC";
	$result = $conn->query($sql);
	$arr=array();
	$i=0;
	$notsolved=0;
	$solved=0;
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id'] = $row['id'];
		  $arr[$i]['question'] = stripslashes($row['question']);
		  $arr[$i]['subject_name'] = $row['subject_name'];
		  $arr[$i]['freestatus'] ='1';// $row['freestatus'];
		  $arr[$i]['opendate'] = $row['opendate'];		 	  
		  $arr[$i]['closedate'] = $row['closedate'];
		  
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_freetext');
		  if($ansid!='') {
			  $arr[$i]['submitted'] = 'yes';
		  $solved++; 
		  }
		  
		  else {
			  $arr[$i]['submitted'] = 'no';
			  $notsolved++;	
		  }	  
		 $i++;
	  }
	}
	
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_questiondoc f,subjects s WHERE  s.subject_id=f.subject AND class='$class' AND closedate>='$today' ORDER BY id DESC";
	$result = $conn->query($sql);
	
	
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id'] = $row['id'];
		  $arr[$i]['question'] = $row['question'];
		  $arr[$i]['subject_name'] = $row['subject_name'];
		  $arr[$i]['docstatus'] = '1';//$row['docstatus'];
		  $arr[$i]['opendate'] = $row['opendate'];		 	  
		  $arr[$i]['closedate'] = $row['closedate'];
			
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_questiondoc');
		  if($ansid!='') {
			  $arr[$i]['submitted'] = 'yes';
		  $solved++; 
		  }
		  
		  else {
			  $arr[$i]['submitted'] = 'no';
			  $notsolved++;	
		  }	  
		  $i++;
	    }
	}
	if($i>0){
		$arr[$i]['solved']=$solved;
		$arr[$i]['notsolved']=$notsolved;
	}
	return $arr;
}

function getAssignmentsApi(& $emp_ecode){
	global $conn;
	$class = $_SESSION["class"];
	if(isset($_GET['adate']) && $_GET['adate']!='') $today =$_GET['adate'];
	else $today=date('Y-m-d');
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_freetext f,subjects s WHERE  s.subject_id=f.subject AND class='$class' AND closedate>='$today' ORDER BY id DESC";
	$result = $conn->query($sql);
	$arr=array();
	$i=0;
	$notsolved=0;
	$solved=0;
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $arr['data'][$i]['id'] = $row['id'];
		  $arr['data'][$i]['question'] = stripslashes($row['question']);
		  $arr['data'][$i]['subject_name'] = $row['subject_name'];
		  $arr['data'][$i]['freestatus'] ='1';// $row['freestatus'];
		  $arr['data'][$i]['opendate'] = $row['opendate'];		 	  
		  $arr['data'][$i]['closedate'] = $row['closedate'];
		  $arr['data'][$i]['type'] = 'freetext';
		  
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_freetext');
		  if($ansid!='') $solved++; else $notsolved++;	
		 $i++;
	  }
	}
	
	$sql="SELECT f.question,f.id,opendate,closedate,subject_name FROM tbl_questiondoc f,subjects s WHERE  s.subject_id=f.subject AND class='$class' AND (opendate<='$today' and closedate>='$today') ORDER BY id DESC";
	$result = $conn->query($sql);	
	
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $arr['data'][$i]['id'] = $row['id'];
		  $arr['data'][$i]['question'] = $row['question'];
		  $arr['data'][$i]['subject_name'] = $row['subject_name'];
		  $arr['data'][$i]['docstatus'] = '1';//$row['docstatus'];
		  $arr['data'][$i]['opendate'] = $row['opendate'];		 	  
		  $arr['data'][$i]['closedate'] = $row['closedate'];
		  $arr['data'][$i]['type'] = 'doc';
			
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_questiondoc');
		  if($ansid!='') $solved++; else $notsolved++;			
		  $i++;
	    }
	}
	if($i>0){
		$arr['solved'][$i]['solved']=$solved;
		$arr['solved'][$i]['notsolved']=$notsolved;
	}
	return $arr;
}


function getAssignmentsHome(& $emp_ecode){
	global $conn;
	$class = $_SESSION["class"];
	$today=date('Y-m-d');
	//$sql="SELECT f.id FROM tbl_freetext f,tbl_evolution e,subjects s WHERE evid=e.id AND s.subject_id=f.subject AND e.evolutiontype='assignment' AND classroom='$class' AND (opendate='0000-00-00' OR opendate='$today')";
	$sql="SELECT f.id FROM tbl_freetext f,subjects s WHERE s.subject_id=f.subject AND class='$class' AND   closedate>='$today'";
	
	
	$result = $conn->query($sql);
	$arr=array();
	$i=0;
	$notsolved=0;
	$solved=0;
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_freetext');
		  if($ansid!='') $solved++; else $notsolved++;	
		 $i++;
	  }
	}
	
	$sql="SELECT f.id FROM tbl_questiondoc f,subjects s WHERE s.subject_id=f.subject AND  class='$class' AND  closedate>='$today'";
	$result = $conn->query($sql);	
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		 $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_questiondoc');
		 if($ansid!='') $solved++; else $notsolved++;			
		 $i++;
	  }
	}
	if($i>0){
	$arr['solved']=$solved;
	$arr['notsolved']=$notsolved;
	$arr['total']=$solved+$notsolved;
	$arr['per']=100*$solved/$arr['total'];
	}
	
	
	if(empty($arr)){	
		$arr['solved']=0;
		$arr['notsolved']=0;
		$arr['total']=0;
		$arr['per']=0;
	}
	return $arr;
}


function getAssignment(& $id, & $type){
	global $conn;
	if($type=='freetext' || $type=='tbl_freetext'){
		//$sql="SELECT f.question,minwords,maxwords,f.id,opendate,closedate,subject_name,e.id as evid  FROM tbl_freetext f,tbl_evolution e,subjects s WHERE evid=e.id AND s.subject_id=f.subject AND e.evolutiontype='assignment' AND f.id='$id'";
		
		$sql="SELECT f.question,document,uploadflag,f.id,opendate,closedate,subject_name FROM tbl_freetext f,subjects s WHERE s.subject_id=f.subject AND f.id='$id'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$arr['id'] = $row['id'];
		$arr['question'] = $row['question'];
		$arr['subject_name'] = $row['subject_name'];
		$arr['freestatus'] = '1';//$row['freestatus'];
		$arr['opendate'] = $row['opendate'];		 	  
		$arr['closedate'] = $row['closedate'];		 
		$arr['document'] = $row['document'];		 
		$arr['uploadflag'] = $row['uploadflag'];		 
		//$arr['evid'] = $row['evid'];		 
	}
		
	if($type=='doc' || $type=='tbl_questiondoc'){
		//$sql="SELECT f.question,f.id,opendate,closedate,subject_name,e.id as evid FROM tbl_questiondoc f,tbl_evolution e ,subjects s WHERE evid=e.id AND s.subject_id=f.subject AND e.evolutiontype='assignment'  AND f.id='$id'";
		
		$sql="SELECT f.question,document,uploadflag,f.id,opendate,closedate,subject_name FROM tbl_questiondoc f,subjects s WHERE s.subject_id=f.subject AND f.id='$id'";
		$result = $conn->query($sql);	
		$row = $result->fetch_assoc();
	 	$arr['id'] = $row['id'];
		$arr['question'] = $row['question'];
		$arr['subject_name'] = $row['subject_name'];
		$arr['docstatus'] = '1';//$row['docstatus'];
		$arr['opendate'] = $row['opendate'];		 	  
		$arr['closedate'] = $row['closedate'];
		$arr['evid'] = $row['evid'];
	}
	return $arr;
	
}

function submit_assignment(){
	global $conn;
	$type=$_POST['qtype'];
	$qid=$_POST['qid'];
	$evid=$_POST['evid'];
	$answer=addslashes(trim($_POST['answer']));
	$studid = $_SESSION["eid"];
	if($type=='doc') $questype='tbl_questiondoc';
	if($type=='freetext') $questype='tbl_freetext';
	$sql = "INSERT INTO tbl_answer(studid,evid,question,question_type,answer,created) 
	VALUES('$studid','$evid','$qid','$questype','$answer',NOW())";
	if ($conn->query($sql) == true) {    
		$insert_id = $conn -> insert_id;	
		if($type=='doc'){		
			$fileType = strtolower(pathinfo($_FILES['uploaddoc']['name'],PATHINFO_EXTENSION));
			$filename=$type.'-'.$evid.'-'.$insert_id.'.'.$fileType;
			$target='uploads/evaluation/'.$filename;
			if(move_uploaded_file($_FILES['uploaddoc']['tmp_name'],$target)){
				$sqlu="UPDATE tbl_answer SET document='$filename' WHERE id='$insert_id'";
				$conn->query($sqlu);
			}			
		}		
	}
	return $insert_id;	
}

function getAnswerInformation(& $id){
	global $conn;
	$sql="SELECT * FROM tbl_answer WHERE id='$id'";
	$res=mysqli_query($conn,$sql);
	$arr=array();
	$row=mysqli_fetch_array($res);
	$arr['id']=$row['id'];
	$arr['answer']=stripslashes($row['answer']);
	$arr['studid']=$row['studid'];
	$arr['evid']=$row['evid'];
	$arr['section']=$row['section'];
	$arr['question']=$row['question'];
	$arr['question_type']=$row['question_type'];
	$arr['document']=$row['document'];
	$arr['marks']=$row['marks'];
	$arr['teacher_feedback']=stripslashes($row['teacher_feedback']);
	$arr['created']=date('d M Y',strtotime($row['created']));
	return $arr;	
}

function getAnswersList(& $eid){
	global $conn;
	$stid = $_SESSION["eid"];
	$sql="SELECT * FROM tbl_answer WHERE evid='$eid' and studid='$stid'";
	$res=mysqli_query($conn,$sql);
	$arr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$arr[$i]['id']=$row['id'];
		$arr[$i]['answer']=stripslashes($row['answer']);
		$arr[$i]['studid']=$row['studid'];
		$arr[$i]['evid']=$row['evid'];
		$arr[$i]['section']=$row['section'];
		$arr[$i]['question']=$row['question'];
		$arr[$i]['question_type']=$row['question_type'];
		$arr[$i]['document']=$row['document'];
		$arr[$i]['marks']=$row['marks'];
		$arr[$i]['teacher_feedback']=stripslashes($row['teacher_feedback']);
		$arr[$i]['created']=date('d M Y',strtotime($row['created']));
		$i++;
	}
	return $arr;	
}

function checkAnswer($stdid,$quesid,$table){
	global $conn;
	$sql="SELECT id FROM tbl_answer WHERE studid='$stdid' AND question='$quesid' AND question_type='$table'";
	$res=mysqli_query($conn,$sql);
	$row=mysqli_fetch_array($res);
	return $row['id'];
}


function getCountExam(& $eid){
	global $conn;
	$sql="SELE";
}
function getExamsHome(& $emp_ecode){
	global $conn;
	$class = $_SESSION["class"];
	if(isset($_GET['adate']) && $_GET['adate']!='') $today =$_GET['adate'];
	else $today=date('Y-m-d');
	//SELECT e.id FROM tbl_evolution e,subjects s,tbl_answer a WHERE s.subject_id=e.subject AND a.evid=e.id AND e.evolutiontype='exam' AND e.classroom='1' AND e.opendate='2020-08-11'
	$sql = "SELECT e.id,subject_name FROM tbl_evolution e,subjects s,tbl_answer a WHERE s.subject_id=e.subject AND a.evid=e.id AND e.evolutiontype='exam' AND e.classroom='$class' AND e.opendate='$today' LIMIT 1";
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{
		$row = $result->fetch_assoc();
		$eid = $row['eid'];
		$qdetails = getCountExam($row['eid']);
		$arr['subject_name']=$row['subject_name'];
	}
	else{
		
	}
	$arr=array();
	$i=0;
	$notsolved=0;
	$solved=0;
	if ($result->num_rows > 0)	{				
		while($row = $result->fetch_assoc())  {
		  $ansid = checkAnswer($emp_ecode,$row['id'],'tbl_freetext');
		  if($ansid!='') $solved++; else $notsolved++;	
		 $i++;
	  }
	}
	
	
	return $arr;
}

function getTypeExamDetails(& $eid){
	global $conn;
	$sql="SELECT * FROM `tbl_fillblank` WHERE evid='$eid' ORDER BY `id` DESC";
	$i=0;$arr=array();
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['qtype']='tbl_fillblank';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_match` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['cols1']=$row['cols1'];
		  $arr[$i]['cols2']=$row['cols2'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['qtype']='tbl_match';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_multiplechoice` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['options']=$row['options'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['qtype']='tbl_multiplechoice';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_singlechoice` WHERE evid='$eid' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['options']=$row['options'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['referdoc'];
		  $arr[$i]['qtype']='tbl_singlechoice';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_questiondoc` WHERE evid='$eid' AND evid!='' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['document'];
		  $arr[$i]['qtype']='tbl_questiondoc';
		 $i++;
	  }
	}
	
	$sql="SELECT * FROM `tbl_freetext` WHERE evid='$eid' AND evid!='' ORDER BY `id` DESC";	
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{			
		while($row = $result->fetch_assoc())  {
		  $arr[$i]['id']=$row['id'];
		  $arr[$i]['evid']=$row['evid'];
		  $arr[$i]['question']=$row['question'];
		  $arr[$i]['minwords']=$row['minwords'];
		  $arr[$i]['maxwords']=$row['maxwords'];
		  $arr[$i]['answer']=$row['answer'];
		  $arr[$i]['section']=$row['section'];
		  $arr[$i]['marks']=$row['marks'];
		  $arr[$i]['uploadflag']=$row['uploadflag'];
		  $arr[$i]['referdoc']=$row['document'];
		  $arr[$i]['qtype']='tbl_freetext';
		 $i++;
	  }
	}
	
	//print_r($arr);
	return $arr;
}

function upCommingExam(){
	global $conn;
	$eid = $_SESSION["eid"];
	$class = $_SESSION["class"];

	$sql = "select max(id) as evid,opendate,subject from tbl_evolution where evolutiontype='exam' and classroom='$class' and id not in (select id from tbl_answer where studid='$eid')";
	$result = $conn->query($sql);
	if($result->num_rows>0){
		$row = $result->fetch_assoc();
		if($row['evid']!=''){
		$arr['id']=$row['evid'];
		$arr['opendate']=$row['opendate'];
		$arr['subject']=$row['subject'];
		}
	}
	//print_r($arr);
	return $arr;
}
function getLastAssignment(){
	global $conn;
	$eid = $_SESSION["eid"];	
	$sql = "SELECT evid,marks,e.subject FROM tbl_answer a,tbl_evolution e WHERE a.evid=e.id and e.evolutiontype='assignment' and studid='$eid' and evid=(select max(evid) FROM tbl_answer a,tbl_evolution e WHERE a.evid=e.id and e.evolutiontype='assignment' and studid='$eid' )";
	$res = mysqli_query($conn,$sql);
	$wrong=0;
	$correct=0;
	$marks=0;
	while($row = mysqli_fetch_array($res)){
		if($row['marks']==0) $wrong=$wrong+1;
		else $correct=$correct+1;
		$marks=$marks+$row['marks'];
		$evid=$row['evid'];	
		$subject=$row['subject'];	
	}
	
	$totques = getTotAssignemntQues($evid);
	$arr['marks']= $marks;
	$arr['subject']= $subject;
	$arr['wrong']= $wrong;
	$arr['correct']= $correct;
	$arr['notsolved']= $totques-($wrong+$correct);
	//print_r($arr);
	return $arr;
}	
	
function getTotAssignemntQues(& $eid){
	global $conn;
	$i=0;
	$sql="SELECT count(id) as cnt FROM `tbl_freetext` WHERE evid='$eid'";	
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT count(id) as cnt FROM `tbl_questiondoc` WHERE evid='$eid'";
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	return $i;
}
function getLastExam(){
	global $conn;
	$eid = $_SESSION["eid"];	
	$sql = "SELECT evid,marks,e.subject FROM tbl_answer a,tbl_evolution e WHERE a.evid=e.id and e.evolutiontype='exam' and studid='$eid' and evid=(select max(evid) FROM tbl_answer a,tbl_evolution e WHERE a.evid=e.id and e.evolutiontype='exam' and studid='$eid' )";
	$res = mysqli_query($conn,$sql);
	$wrong=0;
	$correct=0;
	$marks=0;
	while($row = mysqli_fetch_array($res)){
		if($row['marks']==0) $wrong=$wrong+1;
		else $correct=$correct+1;
		$marks=$marks+$row['marks'];
		$evid=$row['evid'];	
		$subject=$row['subject'];	
		$opendate=$row['opendate'];	
	}
	$qdetails = getTypeExamDetails($evid);
	$totques = getTotExamQues($evid);
	$arr['evid']= $evid;
	$arr['qdetails']= $qdetails;
	$arr['marks']= $marks;
	$arr['subject']= $subject;
	$arr['opendate']= $opendate;
	$arr['wrong']= $wrong;
	$arr['correct']= $correct;
	$arr['notsolved']= $totques-($wrong+$correct);
	//print_r($arr);
	return $arr;
}	

function getTotExamQues(& $eid){
	global $conn;
	$i=0;
	$sql="SELECT id FROM `tbl_fillblank` WHERE evid='$eid'";	
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT id FROM `tbl_match` WHERE evid='$eid'";
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT id FROM `tbl_multiplechoice` WHERE evid='$eid'";
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT id FROM `tbl_questiondoc` WHERE evid='$eid'";	
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT id FROM `tbl_freetext` WHERE evid='$eid' AND evid!=''";
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	$sql="SELECT id FROM `tbl_singlechoice` WHERE evid='$eid' AND evid!=''";	
	$result = $conn->query($sql);
	$i=$i+$result->num_rows;
	
	return $i;
	
}	
function getExams(){
	global $conn;
	$class = $_SESSION["class"];
	if(isset($_GET['adate']) && $_GET['adate']!='') $today =$_GET['adate'];
	else $today=date('Y-m-d');
	$sql="SELECT e.id,opendate,closedate,opendate,subject_name FROM tbl_evolution e,subjects s WHERE s.subject_id=e.subject AND e.evolutiontype='exam' AND class='$class' AND date_format(opendate,'%Y-%m-%d')='$today' ORDER BY e.id DESC LIMIT 1";
	$arr=array();
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{				
		$row = $result->fetch_assoc();
		$qdetails = getTypeExamDetails($row['id']);
		//print_r($row);
		$arr['evid']=$row['id'];
		$arr['opendate']=$row['opendate'];
		$arr['closedate']=$row['closedate'];
		$arr['subject_name']=$row['subject_name'];
		$arr['qdetails']=$qdetails;
		
	} //else { echo 'No records';}	
	//print_r($arr);	
	return $arr;
}

function addExamAttendance($evid){
	global $conn;
	$emp_ecode = $_SESSION["eid"];
	$sqlc = "select * from exam_attendance where examid='$evid' and student='$emp_ecode'";
	$resc = mysqli_query($conn,$sqlc);
	if(mysqli_num_rows($resc)==0){
		$sql="insert into exam_attendance(examid,student,attenddate) values('$evid','$emp_ecode',NOW())";
		mysqli_query($conn,$sql);
	}	
}

function getExamsQuestion(& $id,& $table){
	global $conn;
	$sql = "SELECT * FROM $table WHERE id='$id'";
	$arr = array();
	$result = $conn->query($sql);
	if ($result->num_rows > 0)	{				
		$row = $result->fetch_assoc();
		$arr['id']=$row['id'];
		$arr['evid']=$row['evid'];
		$arr['question']=$row['question'];
		$arr['answer']=$row['answer'];
		$arr['section']=$row['section'];
		$arr['cols1']=$row['cols1'];
		$arr['cols2']=$row['cols2'];
		$arr['options']=$row['options'];
		if($table=='tbl_freetext' || $table=='tbl_questiondoc')		
		$arr['referdoc']=$row['document'];
		else $arr['referdoc']=$row['referdoc'];
		$arr['uploadflag']=$row['uploadflag'];
		$arr['marks']=$row['marks'];
	}
	return $arr;	
}

function submit_exam(){
	global $conn;
	//print_r($_POST);
	$studid = $_SESSION["eid"];
	$type=$_POST['type'];
	$id=$_POST['id'];
	$marks=$_POST['marks'];
	$corranswer=trim($_POST['answer']);
	$evid=$_POST['evid'];
	$obtmarks=0;
	if($type=='tbl_fillblank'){
		$answer=implode('-',$_POST['fillblankans']);
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_match'){
		$answer=implode('-',$_POST['matchans']);
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_multiplechoice'){
		$answer=implode('-',$_POST['mulans']);
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_singlechoice'){
		$answer=$_POST['singleanswer'];
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_freetext'){
		$answer=addslashes(trim($_POST['freeanswer']));		
	}
	
	$sql="INSERT INTO tbl_answer(studid,evid,question,question_type,answer,marks,created)
	VALUES('$studid','$evid','$id','$type','$answer','$obtmarks',NOW());
	";
	$res = mysqli_query($conn,$sql);
	$lastid = mysqli_insert_id($conn);
	if($type=='tbl_questiondoc'){
		$fileType = strtolower(pathinfo($_FILES['uploaddoc']['name'],PATHINFO_EXTENSION));
		$filename='doc-'.$evid.'-'.$lastid.'.'.$fileType;
		$target='uploads/evaluation/'.$filename;
		if(move_uploaded_file($_FILES['uploaddoc']['tmp_name'],$target)){
			$sqlu="UPDATE tbl_answer SET document='$filename' WHERE id='$lastid'";
			$conn->query($sqlu);
		}			
	}
	return $lastid;
}


function submit_exam_api(){
	global $conn;
	extract($_GET);
	
	$exam = getExamsQuestion($id,$type);
	$corranswer=trim($exam['answer']);
	$evid=$exam['evid'];
	$marks=$exam['marks'];
	$obtmarks=0;
	if($type=='tbl_fillblank'){
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_match'){
		//$answer=implode('-',$_POST['matchans']);
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_multiplechoice'){
		//$answer=implode('-',$_POST['mulans']);
		if($answer==$corranswer) $obtmarks=$marks;
	}
	if($type=='tbl_singlechoice'){
		//$answer=$_POST['singleanswer'];
		if($answer==$corranswer) $obtmarks=$marks;
	}
	
	
	$sql="INSERT INTO tbl_answer(studid,evid,question,question_type,answer,marks,created)
	VALUES('$emp_code','$evid','$id','$type','$answer','$marks',NOW());
	";
	$res = mysqli_query($conn,$sql);
	$lastid = mysqli_insert_id($conn);
	if($type=='tbl_questiondoc'){
		$fileType = strtolower(pathinfo($_FILES['uploaddoc']['name'],PATHINFO_EXTENSION));
		$filename='doc-'.$evid.'-'.$lastid.'.'.$fileType;
		$target='uploads/evaluation/'.$filename;
		if(move_uploaded_file($_FILES['uploaddoc']['tmp_name'],$target)){
			$sqlu = "UPDATE tbl_answer SET document='$filename' WHERE id='$lastid'";
			$conn->query($sqlu);
		}			
	}
	return $lastid;
}

function saveChat(){
	global $conn;
	$eid = $_SESSION["eid"];
	$class = $_SESSION["class"];
	$u_type = $_SESSION["u_type"];
	$message = addslashes(trim($_POST['message']));
	$sql = "INSERT INTO tbl_chat(userid,usertype,usertext,created) VALUES('$eid','$u_type','$message',NOW())";
	mysqli_query($conn,$sql);	
}

function getLatestChatUsers(){
	global $conn;
	$sql = "SELECT userid,concat(student_name,' ',student_lastname) as sname FROM tbl_chat c, students s WHERE c.userid=s.std_id ORDER BY c.created DESC LIMIT 15";
	$res = mysqli_query($conn,$sql);	
	$arr= array();
	$i=0;
	while($row = mysqli_fetch_array($res)){
		$arr[$i]['userid']=$row['userid'];
		$arr[$i]['student_name']=$row['sname'];
	}
	return $arr;
}

function submit_discussion(){
	global $conn;
	$eid= $_SESSION["eid"];
	$class = $_SESSION["class"];

	$message = addslashes(trim($_POST['txtdiscussion']));
	//$txtsubject = addslashes(trim($_POST['txtsubject']));
	$sql = "INSERT INTO ask_questions(ecode,qdate,q_details,class) VALUES('$eid',NOW(),'$message','$class')";
	mysqli_query($conn,$sql);		
}

function addViews($vid_id,$stdid){
	global $conn;
	$sql = "update courses_videos set views = views+1 where id='$vid_id'";
	mysqli_query($conn,$sql);	
}

function getViews($vid_id){
	global $conn;
	$sql = "select views from courses_videos where id='$vid_id'";
	$res = mysqli_query($conn,$sql);	
	$row = mysqli_fetch_array($res);
	return $row['views'];
}

function addChatMessage(){
	global $conn;
	extract($_GET);
	$message=stripslashes(trim($message));
	$sql="insert into chat_message(message,sender,receiver,sendtime,chatwindow) 
	values('$message','$sender','$receiver',NOW(),'$chatwindow')";
	mysqli_query($conn,$sql);
}

function getChatMessage($sender,$receiver,$chatwindow){
	global $conn;
	$sql="select id from chat_message where sender='$sender' and receiver='$receiver' and chatwindow='$chatwindow' and status='0'";
	$res = mysqli_query($conn,$sql);
	return mysqli_num_rows($res);
} 

function removeChatMessage(){
	global $conn;
	extract($_GET);
	$sql="update chat_message set status='1' where sender='$sender' and receiver='$receiver' and chatwindow='$chatwindow'";
	$res = mysqli_query($conn,$sql);
	return mysqli_num_rows($res);
} 
function getOpenChatMessage($class){
	global $conn;
	$sql="select id from chat_message where receiver='$class'and chatwindow='2' and status='0'";
	$res = mysqli_query($conn,$sql);
	$ar=array();
	while($row=mysqli_fetch_array($res)){
		$ar[]=$row['id'];
	}
	return $ar;
} 

