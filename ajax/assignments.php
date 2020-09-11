<?php include '../model/config.php'; ?>
<?php session_start(); ?>
<?php include '../permission.php' ?>
<?php include '../model/functions.php' ?>

  <?php   
         if ($_SERVER["REQUEST_METHOD"] == "POST")
          {                     
             if(isset($_POST['name']))
              {
                  $title = addslashes(trim($_POST['name']));
                  $details = addslashes(trim($_POST['details']));
               //   $rank = $_POST['rank'];
                  $ecode = $_POST['ecode'];
                  $post_date = $_POST['award_date'];
                  $status = $_POST['enb'];
               
                  $winner_name =  $_POST['winner_name'];
                  $designation = $_POST['designation'];
                  $department = $_POST['department'];
                  $branch = $_POST['branch'];
                  $state = $_POST['state'];
				$sql_awd = "INSERT INTO awards (title, details, ecode, winner_name, designation, department, branch, state, post_date, status) VALUES ('$title', '$details', '$ecode', '$winner_name', '$designation', '$department', '$branch', '$state', '$post_date', '$status')";
                                         
                if ($conn->query($sql_awd) == true)
                 {    
					$comments="$title award is given to $winner_name.";
					$arr=array('from_id'=>$_SESSION['uid'],'from_type'=>$_SESSION['u_type'],'to_id'=>$ecode,'to_type'=>'student','page'=>'index.php','tableid'=>$conn->insert_id,'tablename'=>'awards','comments'=>$comments,'status'=>'1');
					saveNotification($arr);
                  echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #145101;'>Achievements Added Successfully</div>";     
                 } 
                 else
                 {
                    echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>Error Occur</div>";                       
                 }
               }
               else
               {
                      echo "<div align='center' style='font-size: 18px;font-weight: bold;color: #ff0303;'>Post input error</div>";

               }


           }
             $conn->close();
?>
