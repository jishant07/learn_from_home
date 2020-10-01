<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
include 'model/config.php';
include 'model/functions.php';
session_start();
error_reporting(0);
$action=$_GET['action'];
$ans = file_get_contents("php://input");
$jdata = $_GET;//json_decode($ans);

//print_r($_GET);

$url=$filelocation='https://www.flowrow.com/lfh/';
if($action=='login'){
	$code = trim($jdata['username']);
	$pass = trim($jdata['password']);
    $sql="SELECT std_id FROM students WHERE ecode='$code'";
	$sq_user = $conn -> query($sql); 
	$rw = $sq_user->num_rows;
	if($rw>0) echo 'success'; else echo 'failure';
	//$row= $sq_user->fetch_assoc();
	/*$hash = $row['pwd'];
	if (password_verify($pass, $hash)) {	
		echo 'success';
	} else echo 'failure';*/
	
}

if($action=='forgetpassword'){
	extract($_GET);
	$code = trim($username);
	$oldpass = trim($old_password);
	$newpass = trim($new_password);
	
    $sql="SELECT std_id FROM students WHERE ecode='$code'";
	$sq4= $conn->query("select pwd FROM students where std_id='$code'");
	$rw = $sq4->num_rows;
	$rowmain = $sq4->fetch_assoc();
	$hash = $rowmain['pwd'];
	if (password_verify($oldpass, $hash)) {		
		$newpwd = password_hash($newpass, PASSWORD_DEFAULT);	
		$conn->query("update students set pwd='$newpwd' where std_id='$std_id'");
		$message = "Password changes successfuly";
	}
	else{
		$message = "Please enter correct password";
	}
	$arr['message'] = $message;
	echo json_encode($arr);	
}
if($action=='register'){
	$ecode = trim($jdata['stdcode']);
	$pwd = password_hash(trim($jdata['password']), PASSWORD_DEFAULT); 
	$name = trim($jdata['name']);
	$email = trim($jdata['email']);
	$mobile = trim($jdata['mobile']);
	$dob = trim($jdata['dob']);
	$gender = trim($jdata['gender']);
	$doj = trim($jdata['doj']);
	$class = trim($jdata['class']);
	$designation = trim($jdata['division']);
	$branch = $_POST['branch'];	
	$state = trim($jdata['state']);
	$reportingto = trim($jdata['reportingto']);
	//$pic = trim($jdata['pic']);
	$status = trim($jdata['status']);
	
	$query = "SELECT ecode FROM students WHERE ecode='$ecode' ";
	$row =$conn->query($query) ;
	$rw = $row->num_rows;
	if ($rw >= 1){
		echo 'Student code '.$code.' is already present. Please enter another code.';
	}
	else {
		$sql_users = "INSERT INTO students (ecode, pwd, student_name, email, mobile, date_birth, gender, date_join, dept_id, branch, state, designation, status) VALUES ('$ecode', '$pwd', '$name', '$email' , '$mobile', '$dob', '$gender', '$doj','$class', '$branch', '$state', '$designation','$status' )";                                         
		if ($conn->query($sql_users) == true) {    
			$insert_id = $conn -> insert_id;
			$sql="INSERT INTO users(id,code,password,email,usertype,status) VALUES('$insert_id','$ecode', '$pwd', '$email','S','$enb')";
			$conn->query($sql); 		
		
			$tmpname=$_FILES['pic']['tmp_name'];							
			$ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
			$imgname=$ecode.'.'.$ext;
			$path=$url."uploads/images/students/$imgname";
			if(move_uploaded_file($tmpname, $path)){
				$sql="update students set image='$imgname' where std_id='$insert_id'";
				$conn->query($sql);
			}
			echo 'success';
		} else echo 'failure';	
	}		
}


if($action=='home'){
	$sql="SELECT COUNT(`vid_id`) as cnt,subject_name FROM `video` v,subjects s WHERE enb=1 and v.vid_sub=s.subject_id GROUP BY `vid_sub` ";
	$sq_user = $conn -> query($sql);                  
	$arr=array();	
	$i=0;
	while($row= $sq_user->fetch_assoc()){	
		$arr['videos'][$i]['subject_id'] = $row['cnt'];
		$arr['videos'][$i]['subject_name'] = $row['subject_name'];
		$i++;
	}
		
	$sql="select subject_id,subject_name  from subjects where subject_isactive='1' order by subject_name asc";
	$sq_user = $conn -> query($sql);  		
	$i=0;
	while($row= $sq_user->fetch_assoc()){	
		$arr['subjects'][$i]['subject_id'] = $row['subject_id'];
		$arr['subjects'][$i]['subject_name'] = $row['subject_name'];
		$i++;
	}
		
	$sql="select book_id,book_name,book_thumb,book_link from my_books where enb='1' order by book_name asc";
	$sq_user = $conn -> query($sql); 
	$i=0;
	while($row= $sq_user->fetch_assoc()){	
		$arr['books'][$i]['book_id'] = $row['book_id'];
		$arr['books'][$i]['book_name'] = $row['book_name'];
		$arr['books'][$i]['book_link'] = $url.'uploads/my_books/'.$row['book_link'];
		$arr['books'][$i]['book_thumb'] = $url.'uploads/my_books/thumb/'.$row['book_thumb'];
		$i++;
	}	
	echo json_encode($arr);
}

if($action=='task'){	
	$sql="SELECT h.*,s.subject_name as subject FROM `homeworks` h,subjects s where hw_isActive=1 and h.hw_sub=s.subject_id and (hw_start_date<= ( CURDATE() + INTERVAL 3 DAY ) and hw_start_date>=CURDATE())";
	$sq_user = $conn -> query($sql);                  
	$arr=array();
    $i=0;
	while($row= $sq_user->fetch_assoc()){	
		$arr[$i]['id'] = $row['hw_id'];
		$arr[$i]['code'] = $row['hw_code'];
		$arr[$i]['req'] = $row['hw_req'];
		$arr[$i]['start_date'] = $row[' hw_start_date'];
		$arr[$i]['end_date'] = $row['hw_end_date'];
		$arr[$i]['subject'] = $row['subject'];
		$i++;
	}		
	echo json_encode($arr);
}

if($action=='live'){	
	$sql="SELECT vid_id,vid_type,vthumb,vdesc,sub_start_at,t.t_name from video v, teachers t where v.vid_teacher=t.t_id and v.enb=1 and (sub_start_at<= ( CURDATE() + INTERVAL 3 DAY ) and sub_start_at>=CURDATE())";
	$sq_user = $conn -> query($sql);                  
	$arr=array();
    $i=0;
	while($row= $sq_user->fetch_assoc()){	
		$arr[$i]['id'] = $row['vid_id'];
		$arr[$i]['vid_type'] = $row['vid_type'];
		$arr[$i]['vthumb'] = $url.'uploads/images/videothumb/'.$row['vthumb'];
		$arr[$i]['vdesc'] = $row['vdesc'];
		$arr[$i]['sub_start_at'] = $row['sub_start_at'];
		$arr[$i]['t_name'] = $row['t_name'];
		$i++;
	}	
	echo json_encode($arr);
}

if($action=='document'){
	$sql="select subject_id,subject_name from subjects where subject_isactive='1' order by subject_name asc";
	$sq_user = $conn -> query($sql);                  
	$arr=array();	
	$i=0;
	while($row = $sq_user->fetch_assoc()){	
		$arr[$i]['subject_id'] = $row['subject_id'];
		$arr[$i]['subject_name'] = $row['subject_name'];
		$i++;
	}	
	echo json_encode($arr);
}	

if($action=='t_timetable'){
	$teacherid='1';
	$currday = date('l');
	$sql="select count(tt_id) as cnt,d.day_name from timetable t, days d where tt_teacher='$teacherid' and d.day_id=tt_day group by tt_day order by tt_day asc";
	$sq_user = $conn -> query($sql);                  
	$arr=array();	
	$i=0;
	while($row = $sq_user->fetch_assoc()){	
		$arr[$i]['tot_sessions'] = $row['cnt'];
		$arr[$i]['day_name'] = $row['day_name'];
		$i++;
	}	
	echo json_encode($arr);
}

if($action=='t_myvideos'){
	$teacherid='1';
	$sql="SELECT vid_id,vtitle,vid_type,vthumb,v.enb as vstatus,class_name,s.subject_name FROM `video` v, classrooms c , subjects s where vid_teacher='$teacherid' and c.class_id=v.vid_class and s.subject_id=v.vid_sub";
	$sq_user = $conn -> query($sql);                  
	$arr=array();	
	$i=0;
	while($row = $sq_user->fetch_assoc()){	
		$arr[$i]['vid_id'] = $row['vid_id'];
		$arr[$i]['vtitle'] = stripslashes(trim($row['vtitle']));
		$arr[$i]['class_name'] = $row['class_name'];
		$arr[$i]['vthumb'] = $url.'uploads/images/videothumb/'.$row['vthumb'];
		$arr[$i]['vid_type'] = $row['vid_type'];
		$arr[$i]['subject_name'] = $row['subject_name'];
		$arr[$i]['status'] = $row['vstatus'];
		$i++;
	}	
	echo json_encode($arr);
}
if($action == 'list-gen') {
	$category = $_GET['category'];
	$classid=$_GET['classid'];
	$emp_ecode=$_GET['emp_code'];
	$_SESSION["class"]=$_GET['classid'];
	$_SESSION["eid"]=$emp_ecode;	
	/*if($category == 'videos')
	{
		$select_sql = "select * from video";
		$result = $conn -> query($select_sql);
		$outp = $result->fetch_all(MYSQLI_ASSOC);
		echo json_encode($outp);
	}
	else*/
	if($category == 'ebooks')
	{
		$sql = $conn -> query("SELECT book_thumb,book_link FROM my_books WHERE enb='1' ");
		if ($sql->num_rows > 0){
		  $baseurl = $url.'uploads/my_books/thumb/';
		  $i=0;$arr=array();
		  while($row = $sql->fetch_assoc()){
			$arr[$i]['book_id'] = $row['book_id'];
			$arr[$i]['book_thumb'] = $baseurl.$row['book_thumb'];
			$arr[$i]['book_link'] = $url.'uploads/my_books/'.$row['book_link'];
			$i++;
		  }
		  echo json_encode($arr);
		}
	}
	else if($category == 'documents')
	{
		//$select_sql = "select * from documents";
		//$select_sql="SELECT count(vid_id) as cnt,vid_id,vtitle,vthumb FROM study_material s, video v WHERE s.mat_vid=v.vid_id and vid_class='$classid' group by vid_id";
		$select_sql="SELECT COUNT(`subject`) as cnt,subject,subject_name from study_documents d,subjects s WHERE d.subject=s.subject_id and d.class='$classid' GROUP by subject";
	
		
		$sql = $conn -> query($select_sql);
		$i=0;$arr=array();
		  while($row = $sql->fetch_assoc()){
			$arr[$i]['cnt'] = $row['cnt'];
			$arr[$i]['subjectid'] = $row['subject'];
			$arr[$i]['subject_name'] = $row['subject_name'];
			$i++;
		  }
			
		
		echo json_encode($arr);
	}
	else if($category == 'study_material')
	{
		//$select_sql = "select * from documents";
		//$select_sql="SELECT count(vid_id) as cnt,vid_id,vtitle,vthumb FROM study_material s, video v WHERE s.mat_vid=v.vid_id and vid_class='$classid' group by vid_id";
		extract($_GET);
		$dir=$filelocation.'/uploads/study_material/';
		$select_sql="SELECT *,concat('$dir',studydoc) as studydocfile from study_documents WHERE subject='$sid'";
		$res = mysqli_query($conn,$select_sql);
		$data = mysqli_fetch_all($res,MYSQLI_ASSOC);	
		
		echo json_encode($data);
	}
	
	else if($category == 'session')
	{
		//$select_sql = "select * from my_books";
		$select_sql="select vid_id,vtitle,vdesc,vthumb,t_pic,vid_teacher,subject_name,t_name,sub_start_at,sub_end_at from video v,subjects s,teachers t where vid_type='live' and s.subject_id=vid_sub and vid_teacher=t.t_id and v.enb='1' and vid_class='$classid' order by sub_start_at asc limit 30 ";
			
		$sql = $conn -> query($select_sql);
		$i=0;$arr=array();
		  while($row = $sql->fetch_assoc()){
			$tdate = date('Y-m-d',strtotime($row['sub_start_at']));
			if($row['sub_end_at']>=date('Y-m-d H:i:s')){		  
				$arr[$i]['vdesc'] = $row['vdesc'];
				$arr[$i]['vid_teacher'] = $row['vid_teacher'];
				$arr[$i]['subject_name'] = $row['subject_name'];
				$arr[$i]['t_name'] = $row['t_name'];
				$arr[$i]['vtitle'] = $row['vtitle'];
				$arr[$i]['sub_start_at'] = $row['sub_start_at'];
				$arr[$i]['sub_end_at'] = $row['sub_end_at'];
				$arr[$i]['vid_id'] = $baseurl.$row['vid_id'];
				if($row['vthumb']!='')
				$arr[$i]['vthumb'] = $url.'uploads/images/videothumb/'.$row['vthumb'];
				else $arr[$i]['vthumb'] = '';				
				$i++;
			}
		  }
		
		echo json_encode($arr);
	}
	else if($category == 'tasks')
	{
		$outp = getStudentTasks($emp_ecode);
		echo json_encode($outp);
	}
	else if($category == 'assignment')
	{		
		$assign = getAssignmentsApi($emp_ecode);
		$arr['assignment']=$assign;
		//echo'<pre>',print_r($arr);
		echo json_encode($arr);
	}
	else if($category == 'assignment-single')
	{		
		$tasks = getAssignment($_GET['id'],$_GET['type']);
		echo json_encode($tasks);
		//print_r($tasks);
	}
	else if($category == 'assignment-submit')
	{		
		extract($_REQUEST);
		$answer=addslashes(trim($answer));
		if($type=='doc') $questype='tbl_questiondoc';
		if($type=='freetext') $questype='tbl_freetext';
		$sql = "INSERT INTO tbl_answer(studid,question,question_type,answer,created) 
		VALUES('$emp_ecode','$id','$questype','$answer',NOW())";
		if(mysqli_query($conn,$sql)) {
			if(isset($_FILES['file'])){
				$lastid = mysqli_insert_id($conn);
				$fileType = strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));
				$filename='doc-'.$lastid.'.'.$fileType;
				$target='uploads/evaluation/'.$filename;
				if(move_uploaded_file($_FILES['file']['tmp_name'],$target)){
					$sqlu="UPDATE tbl_answer SET document='$filename' WHERE id='$lastid'";
					$conn->query($sqlu);
				}
			}
			$arr['message']='success'; 	
		}	
		else $arr['message']='fail';
		echo json_encode($arr);
	}
	else if($category == 'prevassign')
	{
		$assign = getLastAssignment($emp_ecode);
		echo json_encode($assign);
	}
	else if($category == 'videos')
	{
		$sql="select subject_name,chapters,sthumb,subject_id from subjects where subject_class='$classid' AND subject_isactive='1'";
		$res = mysqli_query($conn,$sql);
		$arr=array();
		$i=0;
		while($row = mysqli_fetch_array($res)){
			$arr[$i]['subject_id']=$row['subject_id'];
			$arr[$i]['subject_name']=stripslashes(trim($row['subject_name']));
			$arr[$i]['chapters']=stripslashes(trim($row['chapters']));
			$arr[$i]['sthumb']=$url.'uploads/subjects/'.$row['sthumb'];
			$course = getLatestCourseBySubject($row['subject_id'],$emp_ecode);
			if(!empty($course))
			$arr[$i]['course']=$course;
			$arr[$i]['total_courses']= getTotalCourseBySubject($row['subject_id']);
			$i++;
		}
		
		//echo'<pre>',print_r($arr);;
		echo json_encode($arr);
	}
	else if($category == 'course')
	{		
		if(isset($_GET['cid'])){
			$cid=$_GET['cid'];	
			$row= getCourse($cid);
		
			$listvideos=explode(',',$row['videos']);
			$listprefs=explode(',',$row['preferences']);
			$chapter = $row['chapter'];
			if(count($listvideos)==count($listprefs))
			$newarr=array_combine($listprefs,$listvideos);
			else $newarr=$listvideos;
			
			
			
			ksort($newarr);	
			if(!isset($_GET['id'])){
				$varray=$newarr;
				$varr = array_values($varray);
				$_GET['id']=$varr[0];
			}
		}
		else{
			$vid=$_GET['id'];
			$sql= "SELECT * FROM `courses` WHERE FIND_IN_SET('$vid',videos) order by id desc limit 1";
			$sqlr= $conn -> query($sql);
			if ($sqlr->num_rows > 0){
				$row = $sqlr->fetch_assoc();
				$cid=$row['id'];
				//$row= getCourse($cid);
				//print_r($row);;
				$listvideos=explode(',',$row['videos']);
				$listprefs=explode(',',$row['preferences']);
				$chapter = $row['chapter'];
				if(count($listvideos)==count($listprefs))
				$newarr=array_combine($listprefs,$listvideos);
				else $newarr=$listvideos;
				//$newarr = $row['videoarr'];
				ksort($newarr);	
				//print_r($newarr);
			}
		}
		
		$video = course_video($_GET['id']);
		$video['document'] = $url.'uploads/coursedocuments/'.$video['document'];
		$subject_name = getSubject($video['subject']);
		$courses =  getCourses($video['subject'],$emp_ecode);
		$arr['videoinfo']=$video;
		$arr['subject']=$subject_name;
		$subid=$video['subject'];
		$i=0;
		foreach($newarr as $k=>$v){
            $arr['course'][$i]['video_name'] = getCourseVideo($v);
            $arr['course'][$i]['video_id'] = $v;
			$i++;
		}
		$arr['othercourse']=$courses;
		//echo'<pre>',print_r($arr);
		echo json_encode($arr);
	
	}
	else if($category == 'classdiscuss')
	{
		$sql = "select q.*,student_name from ask_questions q, students s where q.ecode=s.ecode and class='$classid' order by qdate asc";
		$result = $conn->query($sql);
		if($result -> num_rows > 0) {
			$i = 0;
			$class_count = 1;
			$arr = array();
			while($row = $result->fetch_assoc())
			{
				$arr[$i]=$row;
				$int_ask_id = $row['ask_id'];
				$comment_count = "select id from comment_section where `ask_id`='$int_ask_id'";
				$inner_result = $conn->query($comment_count);
				$arr[$i]['noofcomments']=$inner_result->num_rows;
				$i++;
			}
		}
		//echo'<pre>',print_r($arr),'</pre>';
		echo json_encode($arr);
	}
	else if($category == 'exams')
	{		
		$examdetails = getExams();
		$qdetails = $examdetails['qdetails'];
		//echo'<pre>',	print_r($examdetails);
		for($i=0; $i<count($qdetails); $i++){
			$ex[$i] = & $qdetails[$i];
			$ex[$i]['ansid'] = checkAnswer($emp_ecode,$examdetails[$i]['id'],$examdetails[$i]['qtype']);
		}				
		echo json_encode($ex);
	}
	else if($category == 'exam-single')
	{		
		$examdetails = getExamsQuestion($_GET['id'],$_GET['type']);
		echo json_encode($examdetails);
	}
	
	else if($category == 'exam-submit')
	{		
		submit_exam_api();
		echo json_encode($examdetails);
	}
	
	else if($category == 'prevexams')
	{		
		$lastexam = getLastExam();	
		echo json_encode($lastexam);
	}
	else if($category == 'timetable')
	{		
		$timetbl =  getTimeTable();
		echo json_encode($timetbl);
	}
	else if($category == 'classroom')
	{				
		$arr['room'] = $room= my_classroom($emp_ecode);
		$arr['classteacher'] =  getClassTeacher($room['class_id']);
		$arr['teachers'] =  getClassTeachers($room['class_id']);
		$arr['subjects'] =  getClassSubjects($room['class_id']);
		$arr['students'] =  getAllStudents();
		$arr['subjects'] =  getSubjects();
		echo json_encode($arr);
	}
	else if($category == 'deletetask')
	{		
		extract($_GET);		
		$sql="delete from student_task where id='$taskid'";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';		
	}
	else if($category == 'updatetask')
	{		
		extract($_GET);
		$title=addslashes(trim($title));
		$description=addslashes(trim($description));		
		$sql="update student_task set taskname='$title',description='$description',allday='$all_day',taskdate='$date',time='$time',color='$color' where id='$taskid'";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';		
	}
	else if($category == 'addtask')
	{		
		extract($_GET);
		$title=addslashes(trim($title));
		$description=addslashes(trim($description));
		$color='#'.$color;
		$sql="INSERT INTO student_task(stdid,taskname,description,allday,taskdate,time,color,created,status) VALUES('$emp_ecode','$title','$description','$all_day','$date','$time','$color',NOW(),'1')";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';		
	}
	else if($category == 'adddiscuss')
	{		
		extract($_GET);
		$text=addslashes(trim($text));
		$sql="INSERT INTO ask_questions(ecode,qdate,q_details,class) VALUES('$emp_ecode',NOW(),'$text','$classid')";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';		
	}
	else if($category == 'notifications')
	{		
		$notifications = getNotifications('student');
		echo json_encode($notifications);
	}	
	else if($category == 'addwatchlist')
	{		
		extract($_GET);
		$sqlc="select id from videowatchlist where stdid='$emp_ecode' and vid='$id' and course='$course'";
		$resc = mysqli_query($conn,$sqlc);
		if(mysqli_num_rows($resc)==0){
		$sql="insert into videowatchlist(stdid,vid,course,dateadded) values('$emp_ecode','$id','$course',NOW())";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';
		} else echo 'You have already added';
	}
	else if($category == 'watchlist')
	{		
		extract($_GET);
		$dir=$filelocation.'/uploads/images/coursevideos/';
		$sql="select v.*,cv.title as videotitle,c.name as coursename,concat('$dir',cv.vthumb) as cvthumb from videowatchlist v, courses c, courses_videos cv where stdid='$emp_ecode' and v.vid=cv.id and v.course=c.id  order by id desc";
		$res = mysqli_query($conn,$sql);
		$data = mysqli_fetch_all($res,MYSQLI_ASSOC);
		//echo '<pre>',print_r($data);	
		echo json_encode($data);
	}
	else if($category == 'removewatchlist')
	{		
		extract($_GET);	
		$sql="delete from videowatchlist where stdid='$emp_ecode' and vid='$id'";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';
	}
	else if($category == 'profile')
	{		
		extract($_GET);
		$data = getStudentInfo($emp_ecode);
		echo json_encode($data);
	}
	else if($category == 'latestvideos')
	{		
		extract($_GET);
		$_SESSION['class']=$classid;
		$data = getLatestVideos();
		echo json_encode($data);
	}
	else if($category == 'discusscomment'){
		extract($_GET);
		$dir=$filelocation.'/uploads/images/students/';
		$sql = "select c.*,student_name,concat('$dir',s.image) as studentpic from comment_section c,students s where c.ecode=s.ecode and `ask_id`= '$ask_id'";
		$res = mysqli_query($conn,$sql);
		$data = mysqli_fetch_all($res,MYSQLI_ASSOC);
		//echo '<pre>',print_r($data);	
		echo json_encode($data);	
	}
	else if($category == 'adddiscusscomment')
	{		
		extract($_GET);
		$text = addslashes($text);
		$sql="insert into comment_section(timestamp,ecode,ask_id,comment) values(NOW(),'$emp_ecode','$ask_id','$text')";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';
	}
	else if($category == 'subject'){
		extract($_GET);
		$dir=$filelocation.'uploads/subjects/';
		$sql = "select *,concat('$dir',sthumb) as subjectpic from subjects where subject_class='$classid'";
		$res = mysqli_query($conn,$sql);
		$data = mysqli_fetch_all($res,MYSQLI_ASSOC);
		//echo '<pre>',print_r($data);	
		echo json_encode($data);	
	}
	else if($category == 'sendchat'){
		//echo '<pre>',print_r($_GET);	
		extract($_GET);
		$group=getClassName($classid);	
		$chat_message = addslashes(trim($chat_message));
		$sql="insert into tbl_chat(userid,usertype,usertext,groupname,created) values('$emp_code','student','$chat_message','$group',NOW())";
		if(mysqli_query($conn,$sql)) echo 'success';else echo 'failure';	
	}
	else if($category == 'getchat'){
		//echo '<pre>',print_r($_GET);	
		extract($_GET);
		$group=getClassName($classid);	
		$dir=$filelocation.'uploads/images/students/';
		//$sql = "select *,concat('$dir',sthumb) 
		$sql="select c.*,concat('$dir',image) as studentpic from tbl_chat c,students s where groupname='$group' and usertype='student' and userid=ecode order by id desc limit 100";
		$res = mysqli_query($conn,$sql);
		$data = mysqli_fetch_all($res,MYSQLI_ASSOC);
		//echo '<pre>',print_r($data);	
		echo json_encode($data);	
	}
	else if($category == 'continuewatching'){
			$videos = getWatchingVideos($emp_ecode);
			echo json_encode($videos);
	}
	else if($category == 'submitcontinuewatching'){			
			$videoID=$_GET['videoid'];
			$pauseTime=$_GET['mark'];
			$sql = "select id from videotrack WHERE student='$emp_ecode' AND video='$videoID'";
			$res = mysqli_query($conn,$sql);
			if(mysqli_num_rows($res)>0){	
			$sql="UPDATE videotrack SET watchtime='$pauseTime',datewatched=NOW() WHERE student='$emp_ecode' AND video='$videoID'";
			}
			else{
				$sql="INSERT INTO videotrack(student,video,watchtime,datewatched) VALUES('$emp_ecode','$videoID','$pauseTime',NOW())";	
			}
			if($conn -> query($sql)) $arr['message']='Success';else $arr['message']='Fail'; 
			echo json_encode($arr);
	}
	
}
