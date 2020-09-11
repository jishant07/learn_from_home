<?php
session_start();
include '../model/config.php';

if(isset($_SESSION['tid'])) { header("location:index.php");exit;}
$message = '';
//print_r($_POST);
if(isset($_POST['user']) && $_POST['user']!=''){
	$user = trim($_POST['user']);
	$pass = trim($_POST['pass']);
	$sql=" SELECT * FROM teachers where t_code='$user'";
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		$row = mysqli_fetch_array($res);
		if($row['usertype']=='admin'){
			$_SESSION['u_type']='admin';			
		}
		else{
			$_SESSION['u_type']='teacher';			
		}
		$_SESSION['tid']=$row['t_id'];
		$_SESSION['tcode']=$row['t_code'];
		$_SESSION['username']=$row['t_name'].' '.$row['t_lastname'];
		$_SESSION['email']=$row['t_contact'];
		$_SESSION['pic']=$row['t_pic'];
		$_SESSION['class']=$row['t_classname'];
		header("location:index.php");exit;
	}
	else {		
		$message = 'Please enter correct credentials';
	}
	
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Lean From Home</title>
	<!-- core:css -->
	<link rel="stylesheet" href="assets/vendors/core/core.css">
	<!-- endinject -->
  <!-- plugin css for this page -->
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="assets/fonts/feather-font/css/iconfont.css">
	<link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
	<!-- endinject -->
  <!-- Layout styles -->  
	<link rel="stylesheet" href="assets/css/demo_1/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-6 col-xl-4 mx-auto">
						<div class="card">
							<div class="row">
                
                <div class="col-md-12"><?=$message?>
                  <div class="auth-form-wrapper px-4 py-5">
                    <img src="assets/images/logo.svg" />
                    <h5 class="text-muted font-weight-normal mb-4 mt-3">Welcome back! Log in to your account.</h5>
                    <form class="forms-sample" method='post' autocomplete='off'>
                      <div class="form-group">
                        <label >Login ID</label>
                        <input type="text" class="form-control"  placeholder="Login ID" name='user' id='user'>
                      </div>
                      <div class="form-group">
                        <label >Password</label>
                        <input type="password" class="form-control" placeholder="Password" name='pass' id='pass'>
                      </div>
                      
                      <div class="mt-3">
                        <button type='submit' class="btn btn-primary mr-2 mb-2 mb-md-0 text-white">Login</button>
                        
                      </div>
                    </form>
                  </div>
                </div>
              </div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- core:js -->
	<script src="assets/vendors/core/core.js"></script>
	<!-- endinject -->
  <!-- plugin js for this page -->
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="assets/vendors/feather-icons/feather.min.js"></script>
	<script src="assets/js/template.js"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
	<!-- end custom js for this page -->
</body>
</html>