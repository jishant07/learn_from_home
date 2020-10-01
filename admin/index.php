<?php if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start(); ?>
<?php
session_start();
error_reporting(E_ALL);
error_reporting(0);
include '../model/config.php';
include 'functions/functions.php';
if(isset($_GET['action']))
$action=$_GET['action'];
else $action='home';
if(!isset($_SESSION['tid'])) {header("location:login.php");exit;}
//$_SESSION['tid']='1';
//$_SESSION['tcode']='t001';
//$_SESSION['u_type']='teacher';

if($action!='add-new-exam'){
	unset($_SESSION['tot_marks']);
	unset($_SESSION['qcount']);
	unset($_SESSION['evid']);	
}
$rclass=explode(',',$_SESSION['class']);
$rclassn = $rclass[0];

$folder='';
switch($action){
	case 'home':
		$live = getAlLiveSessions();
		$exams = getAllExams();		
		//echo'<pre>',print_r($live);exit;
	break;
	case 'logout':
		logout();exit;
	break;	
	case 'live-sessions':		
		$live = getLiveSessions($_GET['class'],$_GET['subject']);
		
	break;
	case 'edit-new-live-sessions':		
		$video = getVideoInfo($_GET['id']);		
		//print_r($video);
	break;
	case 'video-edit':		
		updateVideo();exit;
	break;
	case 'video-add':		
		addVideo();exit;
	break;
	case 'video-delete':		
		deleteVideo($_GET['id']);exit;
	break;
	case 'live-video':		
		$video = getVideo($_GET['id']);		
	break;
	case 'courses':		
		$courses = getCourses($_GET['class'],$_GET['subject']);		
	break;
	case 'course-delete':		
		deleteCourse($_GET['id']);exit;
	break;
	case 'edit-new-course':		
		$course = getCourse($_GET['id']);
		//print_r($course);	exit;	
	break;
	case 'course-edit':		
		updateCourse();exit;
	break;
	case 'course-add':		
		addCourse();exit;
	break;
	case 'course-edit-preference':		
		courseEditPreference();exit;
	break;
	
	case 'videos':	
		$videos = getCourseVideos($_GET['class'],$_GET['subject']);
		//print_r($videos);//exit;	
	break;		
	case 'course-video-delete':		
		//getCourseVideo($_GET['id']);exit;
	break;
	case 'edit-new-video':	
		$video = getCourseVideo($_GET['id']);
		//print_r($video);//exit;	
	break;
	case 'course-video-edit':		
		courseUpdateVideo();exit;
	break;

	case 'course-video-add':		
		courseAddVideo();exit;
	break;
	
	case 'documents':
		$docs = getDocuments($_GET['class'],$_GET['subject']);
	break;
	case 'edit-new-document':
		$doc = getDocument($_GET['id']);
	break;
	
	case 'document-delete':
		deleteDocument($_GET['id']);exit;
	break;
	case 'document-edit':		
		updateDocument();exit;
	break;
	case 'document-add':		
		addDocument();exit;
	break;
		
	case 'assignments':
		$assign = getAssignments($_GET['class'],$_GET['subject']);
	break;

	case 'assignment-add':		
		addAssignment();exit;
	break;
	case 'edit-new-assignment':
		$data = getAssignment($_GET['id'],$_GET['type']);
		//echo'<pre>',print_r($data);exit;
	break;
	case 'assignment-delete':
		deleteAssignment($_GET['id'],$_GET['type']);exit;
	break;		
	case 'assignment-edit':		
		updateAssignment();exit;
	break;

	case 'assignments-student-list':
		$data = getAssignment($_GET['id'],$_GET['type']);
		$stud = getStudentsAssignment($_GET['id'],$_GET['type'],$data['class']);
	break;

	case 'assignments-single-student':
		$data = getAssignmentAnswerByStudent($_GET['id']);		
		$assign = getAssignment($data['question'],str_replace('tbl_','',$data['question_type']));
	break;

	case 'changeStatusAssignment':
	changeStatusAssignment();exit;
	break;
	
	case 'discussion-delete':
		discussionDelete($_GET['id']);
	break;
	case 'classroom-discussion':
		$data = getClassroomDiscussions($_GET['class']);
		
	break;

	case 'single-discussion':
		$data = getClassroomDiscussionById($_GET['id']);
		$comments = getCommentsByDisId($_GET['id']);
		//print_r($data);exit;
	break;

	case 'discussion-feedback':
		discussionFeedback();exit;
	break;
	case 'books':
		$books = my_books();
		//print_r($books);exit;
	break;
	case 'add-new-book':		
	break;
	case 'book-add':
		addBook();exit;
	break;
	case 'edit-book':
		$book = getBook($_GET['id']);
	break;
	case 'books-delete':
		deleteBook($_GET['id']); exit;
	break;
	case 'book-update':
		updateBook();exit;
	break;
	case 'subjects':
		$subjects = getClassSubjects($_GET['class']);	
		//sprint_r($subjects);exit;
	break;
	
	case 'get-subject':
		echo getSubject($_GET['id']);exit;
	break;
	case 'subject-add':
		addSubject();exit;
	break;
	case 'subject-delete':
		deleteSubject($_GET['id']);
	break;
	case 'actionsubject':
		actionsubject();exit;
	break;
	
	//new section wise exam
	case 'examsnew':
		unset($_SESSION['exam']);
		$exams = getClassExams($_GET['class'],$_GET['subject']);		
		$pexams = getPrevClassExams($_GET['class'],$_GET['subject']);		
	break;
	
	//end new section wise exam
	
	
	case 'exams':
		$exams = getClassExams($_GET['class'],$_GET['subject']);		
		$pexams = getPrevClassExams($_GET['class'],$_GET['subject']);		
	break;
	case 'edit-new-exam':
		$exam = getExam($_GET['id']);	
		$examdetails = getExamDetails($_GET['id']);	
		//echo'<pre>',print_r($examdetails);	exit;
	break;
	
	case 'exam-student-list':
		$exam = getExam($_GET['id']);
		$ans = getStudentSubmittedExam($_GET['id']);
		$notans = getStudentNotSubmittedExam($_GET['id'],$_GET['class']);
		//echo'<pre>',print_r($notans);	exit;	
	break;
	
	case 'exam-student-page':
			$exam = getExam($_GET['id']);
			$examdetails = getExamDetails($_GET['id']);
			//$answers = getAnswersByStudentAndExam($_GET['id'],$_GET['student']);
			//$ans = getStudentSubmittedExam($_GET['id']);
			$student_info = getStudentInfo($_GET['student']);
			//echo'<pre>',print_r($exam);	exit;	
	break;
	
	case 'exam-delete':
		examDelete($_GET['id']);
	break;
	case 'changeStatusExam':
	changeStatusExam($_GET['i']);exit;
	break;
	
	case 'edit-question':
		$type = $_GET['type']; 
		$data = getQuestion($_GET['id'],$_GET['type']);
		$exam = getExam($_GET['evid']);
		$action=$type;	
		//print_r($data);exit;
	break;
	
	case 'delete-exam-question':
		deleteExamQuestion(_GET[$id],$_GET['type']);	
	break;
	
	case 'classroom':
		$classteacher = getClassTeacher($_GET['class']);
		$classteachers = getClassTeachers($_GET['class']);
		$subjects = getClassSubjects($_GET['class']);
		$subjects_na = getClassSubjectsNotAssigned($_GET['class']);
		$students = getAllStudents($_GET['class']);
		$subjectsall = getAllSubjects();	
		//echo'<pre>',print_r($subjectsall);
		$subject_ids = array_column($subjects, 'subject_id');
		//print_r($subject_ids);
		//echo'<pre>',print_r($subjects_na);exit;
	break;
	
	case 'assign-teacher-delete':
		assignTeacherDelete($_GET['assignid']);exit;
	break;
	case 'assign-subject-teacher':
		assignSubjectTeacher();exit;
	break;
	
	case 'teachers':
		$teachers = getAllTeachers();
		//echo'<pre>',print_r($teachers);exit;
	break;
	
	case 'edit-teacher':
		$teacher = getTeacher($_GET['id']);
		$classes = getAllClasses();
		$tclass = getTeacherClasses($_GET['id']);
		$tclass = array_column($tclass,'classroom');
		//echo'<pre>',print_r($tclass);exit;
	break;
	
	case 'add-new-teacher':
		$classes = getAllClasses();		
	break;
	case 'update-teacher':
		updateTeacher();exit;
	break;
	case 'add-teacher':
		addTeacher();exit;
	break;
	
	case 'teacher-delete':
		deleteTeacher($_GET['id']);exit;
	break;
	
	case 'students':
		$students = getAllStudentsNew();
		//echo'<pre>',print_r($students);exit;
	break;
	case 'edit-student':
		$student = getStudent($_GET['id']);
		$classes = getAllClasses();		
	break;
	case 'update-student':
		updateStudent();exit;
	break;
	case 'add-new-student':
		$classes = getAllClasses();		
	break;
	
	case 'add-student':
		addStudent();exit;
	break;
	
	case 'student-delete':
		deleteStudent($_GET['id']);exit;
	break;
	
	case 'changepassword':
		changePassword();exit;
	break;	
	case 'update-profile':
		updateProfile();exit;
	break;
	case 'edit-profile':
		$teacher = getTeacher($_SESSION['tid']);
		$tclass = getTeacherClasses($_SESSION['tid']);
		$tclass = array_column($tclass,'classroom');
		//echo'<pre>',print_r($tclass);exit;
	break;
	
	case 'uploadcsv-student':
		uploadCSV();exit;	
	break;
	case 'uploadcsv-teacher':
		uploadTeacherCSV();exit;	
	break;
	
	
	case 'createevaluation':		
	$folder='newexam/';
	break;
	
	case 'students-stats':
		$sinfo=getStudentInfo($_GET['sid']);
		//print_r($sinfo);
		$sessions=getStatsLiveSession($sinfo['dept_id']);
		
		
		$freeassignemnt=getStatsAssignmentFreeText($sinfo['dept_id']);
		$docassignemnt=getStatsAssignmentDoc($sinfo['dept_id']);
		
		$exam= getStatsExams($sinfo['dept_id']);
		
		$stats = getStudentStats($sinfo['ecode']);
		//print_r($stats);exit;
	break;
	case 'students-live-sessions':
		$sinfo=getStudentInfo($_GET['sid']);
		$sessions=getStatsLiveSession($sinfo['dept_id']);
		$stats = getStudentStats($sinfo['ecode']);
	break;
	case 'students-assignments':
		$sinfo=getStudentInfo($_GET['sid']);
		$freeassignemnt=getStatsAssignmentFreeText($sinfo['dept_id']);
		$docassignemnt=getStatsAssignmentDoc($sinfo['dept_id']);
		$stats = getStudentStats($sinfo['ecode']);
	break;
	case 'students-exam':
		$sinfo=getStudentInfo($_GET['sid']);
		$exam= getStatsExams($sinfo['dept_id']);
		
		//$stats = getStudentStats($sinfo['ecode']);
	break;
}

$file="$folder$action.php";	
if($_SESSION['u_type']=='teacher'){
$tclasses = getTeacherClasses();
$tsubjects = getTeacherSubjectByClasses($_SESSION['tid']);
}
else{
$tclasses = getAllClasses();
$tsubjects = getAllSubjectsByClass();	
}
//print_r($tclasses);exit;
?>

    <?php include('header.php') ?>
    <?php include($file) ?>    
    <?php include('footer.php') ?>
