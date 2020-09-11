<?php
include '../model/config.php';
include 'functions/functions.php';

 $query = $conn->query("SELECT * FROM teachers");

if($query->num_rows > 0){
    $delimiter = ",";
    $filename = "Teacher_" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('Teacher Code', 'Teacher Name', 'Subjects', 'Class', 'BirthDate', 'Join Date', 'Gender', 'Pic', 'Address', 'Email Id', 'Mobile No.','Created At');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
       // $status = ($row['status'] == '1')?'Active':'Inactive';
	   $class = explode(',',$row['t_classname']);
	   $clar=array();
	   for($c=0; $c<count($class);$c++){
		$clar[]=  getClassName($class[$c]); 
	   }
        $lineData = array($row['t_code'],$row['t_name'].' '.$row['t_lastname'],getSubject($row['t_sub']),implode(',',$clar),date('d-M-Y',strtotime($row['t_dob'])),date('d-M-Y',strtotime($row['t_doj'])),$row['t_gender'],$row['t_pic'],$row['t_address'],$row['t_contact'],$row['t_phone'],date('d-M-Y',strtotime($row['t_createdat'])));
        fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;

?>
