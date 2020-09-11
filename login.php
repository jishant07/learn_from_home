<?php include 'model/config.php';
session_start();
if(isset($_SESSION["eid"])){
header('Location: index.php');
}
$ecode='';
$message='';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	if(isset($_POST['ecode'])){
		$ecode= trim($_POST['ecode']);
		$password= trim($_POST['password']);
		$sq4= $conn->query("select * FROM students where ecode='$ecode'");
		$rw = $sq4->num_rows;
		$rowmain = $sq4->fetch_assoc();
		if($rw>0){			
			$_SESSION["class"] = $rowmain['dept_id'];
			$_SESSION["eid"] = $rowmain['ecode'];
			$_SESSION["u_type"] = "student";
			echo "<script>window.location.href='index.php';</script>";die();
		} else{
			$message="Invalid student ecode or password. please login again! ";
			//echo "<div style='color:#fff'>Invalid student ecode or password. please login again</div>";
		}
		/*
		$hash = $rowmain['pwd'];
		if (password_verify($password, $hash)) {		
			echo "<script>noteAction('Student Login','".$ecode."')</script>";
			$_SESSION["class"] = $rowmain['dept_id'];
			$_SESSION["eid"] = $rowmain['ecode'];
			$_SESSION["u_type"] = "student";
			echo "<script>window.location.href='students/index.php';</script>";
			header('Location: students/index.php');die();
		}
		else {
			echo "<div style='color:#fff'>Invalid student ecode or password. please login again</div>";
		}*/
	}
}

  
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Learn From Home</title>
    <link rel="icon" href="images/favicon.png" sizes="16x16" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css" crossorigin="anonymous">

  </head>
  <body>
    
    <div class="login-wrapper d-flex align-items-center justify-content-center">
        <div class="login-pannel row">
            <div class="col-md-6 first-half">
                <div class="logo">
                    <img src="images/logo.svg" />
                </div>
                <div class="amuze-1">
                    <img src="images/amuze_logo.svg" />
                </div>

            </div>
            <div class="col-md-6 login">
                <h1>Login</h2>
                <form action='' id='loginform' method='post' autocomplete='off'>
                    <div class="error" id='error-reg'><?=$message?></div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Enter ID" id='ecode' name='ecode'>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="*****" id='password' name='password'>
                    </div>
                    
                    <div class="form-group d-flex align-items-center justify-content-center">
                        <button type='submit' class="button3 btn-red" value='submit'>SUBMIT</button>
						
                    </div>
                </form>
                <div class="amuze-2">
                    <img src="images/amuze_logo.svg" />
                </div>
            </div>
        </div>
    </div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

<script>
$(document).ready(function(){
  //  $('#bigloading').fadeOut(1000);
$('#loginform').on('submit', function(e){
    e.preventDefault();
  var T=$(this);
    $('#error-reg').html('');
var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,10}\b$/i
if($('#ecode').val().trim()==='')
{
$('#error-reg').html('Please enter your ecode');
} 
/*else if($('#password').val().trim()===''){
$('#error-reg').html('Please enter password');
}*/ else {
$('#error-reg').html('');

this.submit();
//return true;
    }
  })
})
</script>  
  </body>
</html>
