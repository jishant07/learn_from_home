<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
include 'model/config.php';

$action=$_GET['action'];
$ans = file_get_contents("php://input");
$jdata = $_GET;//json_decode($ans);


if($action=='login'){
	$code = trim($jdata['username']);
	$pass = trim($jdata['password']);
    $sql="SELECT pwd FROM students WHERE ecode='$code'";
	$sq_user = $conn -> query($sql);                  
	$row= $sq_user->fetch_assoc();
	$hash = $row['pwd'];
	if (password_verify($pass, $hash)) {	
		echo 'success';
	} else echo 'failure';	
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
			$path="uploads/images/students/$imgname";
			if(move_uploaded_file($tmpname, $path)){
				$sql="update students set image='$imgname' where std_id='$insert_id'";$conn->query($sql);	
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
		$arr['books'][$i]['book_link'] = 'http://flowrow.com/uploads/my_books/'.$row['book_link'];
		$arr['books'][$i]['book_thumb'] = 'http://flowrow.com/uploads/my_books/thumb/'.$row['book_thumb'];
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
		$arr[$i]['vthumb'] = $row['vthumb'];
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
		$arr[$i]['vthumb'] = $row['vthumb'];
		$arr[$i]['vid_type'] = $row['vid_type'];
		$arr[$i]['subject_name'] = $row['subject_name'];
		$arr[$i]['status'] = $row['vstatus'];
		$i++;
	}	
	echo json_encode($arr);
}	
