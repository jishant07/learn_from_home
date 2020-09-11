<?php include 'model/config.php'; ?>

<?php session_start(); ?>
<?php
//print_r($_SESSION);
if(isset($_SESSION["eid"])){
header('Location: index.php');
}
?>
<?php
include 'header.php';
?>


<div class="">
  <div id="wrapper">
    <div id="login" class="animate form">
      <section class="login_content">
        <div>
          <div style="text-align:center; width:100%; display:block;">
            <img src="templates/assets/images/svg/student-login.svg" alt="img" width="70">
          </div>
        </div>
        <div style="clear: both"> </div>
        
        <form name="form1" id="loginform" action="student-login.php" method="post">
          <h1>Student Login</h1>
          <div>
            <input id="ecode" type="text" class="form-control" placeholder="Student Code" required="" name="ecode" />
          </div>
          <div>
            <input id="password" type="password" class="form-control" placeholder="Password" required="" name="password" />
            
          </div>
          <div id ="btn_clicked">
            <button id="btnsubmit" type="submit" class="btn  yellow-button btn-block submit" >Log in </button>
          </div>
          
          <div class="clearfix"></div>
          <div class="separator"></div>
        </form>
        <!-- form -->
        <div id="bigloading">Loading</div>
        <div id="error-reg"> </div>
      </section>
      
      <!-- post form php   -->
      <div>
        <?php
		$ecode='';
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
        if(isset($_POST['ecode']))
        {
        $ecode= trim($_POST['ecode']);
        $password= trim($_POST['password']);
		$sq4= $conn->query("select * FROM students where ecode='$ecode'");
        $rw = $sq4->num_rows;
        $rowmain = $sq4->fetch_assoc();
		$hash = $rowmain['pwd'];
		//print_r($rowmain);
		if (password_verify($password, $hash)) {		
		$_SESSION["class"] = $rowmain['dept_id'];
        $_SESSION["eid"] = $rowmain['ecode'];
        $_SESSION["u_type"] = "student";
		//print_r($_SESSION);
		echo "<script>window.location.href='index.php';</script>";
        //header('Location: index.php');die();
        }
        else
        {
        echo "<div style='color:#fff'>Invalid student ecode or password. please login again</div>";
        
        }
        }
        }
        
        ?>
      </div>
      <!-- post form php  end -->
      
      <!-- content -->
    </div>
  </div>
</div>


<script>
$(document).ready(function(){
    $('#bigloading').fadeOut(1000);
$('#loginform').on('submit', function(e){
    e.preventDefault();
  var T=$(this);
    $('#error-reg').html('');
var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,10}\b$/i
if($('#ecode').val().trim()==='')
{
$('#error-reg').html('Please enter your ecode');
} else if($('#password').val().trim()===''){
$('#error-reg').html('Please enter password');
} else {
$('#error-reg').html('');

this.submit();
return true;
    }
  })
})
</script>

<?php include 'footer.php' ?>