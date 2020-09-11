<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
session_start();
error_reporting(0);
$filelocation="http://www.flowrow.com/lfh/";
$filelocation="";//http://flowrow.com/new/";
include 'model/config.php';
include 'model/functions.php';

$action = isset($_GET['action'])?$_GET['action']:'home'; 

if($action=='login' && isset($_SESSION["eid"])){ header('location:index.php?action=home');die();}

if(isset($_SESSION["eid"])){
	$emp_ecode = $_SESSION["eid"];
} 
else{ 
	echo "<script> location.href='login.php'; </script>";
}

$countnoti=getCountNotification('student');

$notifications = getNotifications('student');

//if($action!='live-video' && $action !='student_watching') deleteWatchingVideos($emp_ecode);
if(in_array($action,['live-video','student_watching'])){} else deleteWatchingVideos($emp_ecode);
$studentname = getStudentName($emp_ecode);

switch($action){
	case 'home':
		$pvideos =  getWatchingVideos($emp_ecode);
		//$timetbl =  getTimeTable();
		//$subjects =  getSubjects(10);
		$latestvideos = getLatestVideos(10);
		//print_r($latestvideos);
		//$achivements =  getAchivements();
		//$talks =  getTalks();
		$books =  my_books();
		//print_r($books);
		$taskstatus = getFurnishStudentTasks($emp_ecode);
		$assign = getAssignmentsHome($emp_ecode);
		//$exams = getExams();
		//$details = getTypeExamDetails($exams[0]['evid']);
		$lastexam = getLastExam();
		$nextexam = upCommingExam();
		$ans = getAnswersList($exams[0]['evid']);
		$subject = getEvaluationSubject($exams[0]['evid']);
		$livesession =  getLiveSessions();
		//echo'<pre>',print_r($livesession),'</pre>';
		$file='home.php';			
	break;
	case 'pdf':
			$file='pdfreader.php';		
	break;
	case 'login':
			$file='student-login.html';		
	break;
	
	case 'dologin':
		student_login();		
	break;
	
	case 'logout':
		logout();		
	break;
	
	
	case 'live-session':		
		$videos =  getLiveSessions();
		$file='live-session.php';	
	break;
	
	case 'video-list':
		$file='videos.php';	
		$subjects =  getSubjects();
	break;
	
	case 'history-videos':		
		$file='history-videos.php';			
	break;
	
	case 'live-video-list':
		$file='live-video-list.php';	
		$videos =  video_list('live');
	break;
	
	case 'scheduled_video_list':
		 $file='scheduled_video_list.php';	
		$videos =  video_list('scheduled');
	break;
	
	case 'live-video':
		$file='live-video.php';	
		$video =  video();
	break;
	
	case 'student_watching':		
		$students =  getStudentsWatchingVideos($_GET['videoid']);
		if(!empty($students))
		echo json_encode($students);
		
		exit;
	break;
	
	
	case 'my_books':
		$file='books.php';	
		$books = my_books();
	break;
	
	case 'timetable':
		$file='time-table.php';	
		//$timetbl =  getTimeTableBak();
		$timetbl =  getTimeTable();
	break;
	
	case 'classroom-discussion-single':
	$file='classroom-discussion-single.php';	
	
	break;
	case 'classroom_discussion':
		//$conversation = & getConversation($emp_ecode);
		$file='classroom-discussion.php';	
	break;
	
	case 'submit_discussion':
		submit_discussion();
		header('location:index.php?action=classroom_discussion');die();
	break;
	
	case 'my_classroom':
		$file='my-classroom.php';	
		$classteacher =  getClassTeacher($emp_ecode);
		$room =  my_classroom($emp_ecode);
		$teachers =  getClassTeachers($room['class_id']);
		$subjects =  getClassSubjects($room['class_id']);
		//$time =  getClassTimeTable($room['class_id']);
		$students =  getAllStudents();
		$subjects =  getSubjects();
		//print_r($time);
	break;
	
	case 'getstudentinfo':
		$student=getStudentInfo($_GET['stid']);
		echo json_encode($student);
	exit;
	break;
	
	case 'documents':
		$file='study-materials.php';
		$study = getStudyMaterials();
		//print_r($study);
	break;
	
	case 'study-materials-single':
		$study = getStudyMaterialsListBySubgect($_GET['sid']);
		$file='study-materials-single.php';
	break;
	
	case 'my-account':
		$file='account-details.php';
		$student=getStudentInfo($emp_ecode);	
		///print_r($student);	
	break;
	
	case 'changepassword':
		changePassword();
		exit;
	break;
	
	case 'task':
		$tasks = getStudentTasks($emp_ecode);
		$file = 'task.php';		
	break;
	
	case 'savetask':
		saveTask();
		exit;	
	break;
	
	case 'updatetask':
		updateTask();
		header('location:index.php?action=task');die();
	break;
	
	case 'deletetask':
		deleteTask($_GET['id']);
		header('location:index.php?action=task');die();
	break;
	
	case 'gettask':
		$taskview = getTask($_GET['id']);	
		echo json_encode($taskview);
		exit;
	break;
	
	case 'taskdone':
		$taskview = statusTask($_GET['id'],$_GET['status'],$_GET['gdate']);	
		echo json_encode($taskview);
		exit;
	break;
	
	case 'taskedit':
		$taskview = getTask($_GET['id']);	
		echo json_encode($taskview);
		exit;
	break;
	
	case 'assignments':
		$tasks = getAssignments($emp_ecode);
		//echo'<pre>',print_r($tasks),'</pre>';
		$file = 'assignments.php';		
	break;
	
	case 'assignment-single':
		$tasks = getAssignment($_GET['id'],$_GET['type']);
		///echo'<pre>',print_r($tasks),'</pre>';
		$file = 'assignment-single.php';		
	break;
	
	case 'submit_assignment':
		$ansid = submit_assignment();
		header('location:index.php?action=assignment-single-submited&id='.$ansid);die();
	break;
	
	case 'assignment-single-submited':
		$ans = getAnswerInformation($_GET['id']);
		$tasks = getAssignment($ans['question'],$ans['question_type']);
		//echo'<pre>',print_r($tasks),'</pre>';
		
		$file = 'assignment-single-submited.php';		
	break;
	
	
	case 'exams':		
		$examdetails = getExams();
		//print_r($examdetails);
		$exams = $examdetails['qdetails'];
		if(count($exams)>0) {
			$details = getTypeExamDetails($exams[0]['evid']);
			$ans = getAnswersList($exams[0]['evid']);		
			$subject = getEvaluationSubject($exams[0]['evid']);
		}
		$lastexam = getLastExam();	
		//echo'<pre>',print_r($exams),'</pre>';
		$file = 'exams.php';		
	break;
	
	case 'exam-single':
		$exam = getExamsQuestion($_GET['id'],$_GET['type']);
		//echo'<pre>',print_r($exam),'</pre>';
		$file = 'exam-single.php';		
	break;
	
	case 'submitexam':
	$id = submit_exam();
	header('location:index.php?action=exam-single-checked&id='.$id);die();
	exit;
	break;
	
	case 'exam-single-checked':
		$ans = getAnswerInformation($_GET['id']);
		$tasks = getExamsQuestion($ans['question'],$ans['question_type']);
		//print_r($ans);
		$file = 'exam-single-checked.php';		
	break;
	
	case 'exam-last':
		$details = getTypeExamDetails($_GET['eid']);
		$ans = getAnswersList($_GET['eid']);
		$subject = getEvaluationSubject($_GET['eid']);
		$lastexam = getLastExam();	
		//$tasks = getExamsQuestion($ans['question'],$ans['question_type']);
	//	echo'<pre>',print_r($ans),'</pre>';	
		$file = 'exam-last.php';		
	break;
	
	case 'savechat':
		saveChat();
		exit;
	break;
	
	
	case 'my-profile':
		$student=  getStudentInfo($_SESSION['eid']);		
		$file='profile.php';	
	break;
	
	case 'my_watchlist':
		$wlist =  getMyWatchList($emp_ecode);		
		$file='watchlist.php';	
	break;
	
	//old functions
	
	
	
	
	case 'my_scheduled_video':
		$file='my_scheduled_video.php';	
		//$video = video();
	break;
	
	case 'homework':
		$file='homework.php';	
	break;
	
	case 'homework_view':
		$file='homework_view.php';	
	break;
	
	
	
	
	
	
	case 'study_material_list':
		$file='study_material_list.php';	
	break;
	case 'my_submissions':
		$file='my_submissions.php';	
	break;
	case 'myclass_discussion':
		$file='myclass_discussion.php';	
	break;
	case 'start_discussion':
		$file='start_discussion.php';	
	break;
	
	case 'evaluation':
		$file='evaluation.php';	
	break;
	
	case 'student':
		$file='studenttest.php';	
	break;
	case 'result':
		$file='result.php';	
	break;	
	case 'courses':
		$subjects = & getSubjects();
		$file='courses.php';	
	break;
	case 'courses_videos':
		$file='courses_videos.php';	
	break;
	case 'courses_subjects':
		$file='courses_subjects.php';	
	break;
	case 'notifications':
		$notifications = getNotifications('student');
		$file='notifications.php';	
	break;	
	
}

if($action=='pdf'){
	include "$file";
} else{ 
/*
else {
if($action!='login') {
	include "../templates/header.php";
	
	include "../templates/sub_header.php";
}
include "templates/$file";
if($action!='login') {
include '../templates2/jscript1.php';
include '../templates2/footer.php';
}  

}*/
?>

    <?php include('header.php') ?>
    <?php include($file) ?>    
    <?php include('footer.php') ?>
<?php } ?>
