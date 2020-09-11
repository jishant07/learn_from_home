<?php
include '../model/config.php';
include 'functions/functions.php';

$query = $conn->query("SELECT * FROM students");

if($query->num_rows > 0){
    $delimiter = ",";
    $filename = "Student_" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('Student code', 'Roll No', 'Student Name', 'Father Name', 'Father Contact', 'Mother Name', 'Mother Contact', 'Address', 'Email Id', 'Mobile No', 'Student Pic', 'Birthdate', 'Gender', 'Admission Date', 'Class');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
       // $status = ($row['status'] == '1')?'Active':'Inactive';
        $lineData = array($row['ecode'],$row['roll_no'],$row['student_name'].' '.$row['student_lastname'],$row['father_name'],$row['father_contact'],$row['mother_name'],$row['mother_contact'],$row['address'],$row['email'],$row['mobile'],$row['image'],$row['date_birth'],$row['gender'],$row['date_join'],getClassName($row['dept_id']));
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
