 <section class="container-fluid mainwrapper">
	<div class="row account-details justify-content-center">
		<div class="col-lg-5 col-md-5 col-12">
				<h2><?=$student['student_name']?> <?=$student['student_lastname']?></h2>			
				<ul>
				  <li>
					<div class="row">
					  <div class="col-4 title">ID  :</div><div class="col-8"><?=$student['ecode']?></div>
					</div>
				  </li>
				  <li>
					<div class="row">
					  <div class="col-4 title">Password  :</div><div class="col-8"><a href="" data-toggle="modal" data-target="#resetpassword">Change password</a></div>
					</div>
				  </li>
				  
				</ul>
		</div>
		
		
		
	</div>
  </section>

<div class="modal fade" id="resetpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog resetpassword-popup" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<h1>Change Password</h1>
				<div class="row alert alert-notice" id='message'></div>
				<form class="mt-4" id='frmchangepassword' name='frmchangepassword' method='post'>
				<input type='hidden' name='stdid' value="<?=$student['std_id']?>">
				  <div class="row">
					<div class="col-6">
						<div class="form-group">
						  <input class="form-control" placeholder="Enter old Password" id='oldpass' name='oldpass' type="text"/>
						  <span id='oldid'></span>
						</div>
					</div>
						<div class="col-12">
						  <hr>
						</div>
					  
				  </div>
				  <div class="row">
					<div class="col-6">
						<div class="form-group">
						  <input class="form-control" placeholder="Enter New Password" id='newpass' name='newpass' type="text"/>
						  <span id='newid'></span>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
						  <input class="form-control" placeholder="Re-Enter New Password" id='newpass1' name='newpass1' type="text"/>
						  <span id='newid1'></span>
						</div>
					</div>                          
				  </div>
				  
				  <div class="d-flex align-items-center justify-content-center mt-3">
					<input type='submit' class="button2 btn-red" 1d='frmsubmit' value='SAVE PASSWORD'>
				</div>
				</form>
				
			</div>
		
		</div>
	</div>
</div>
<?php include('javascript.php') ?>
 <script>
	$(document).ready(function() {  
	
	$("#frmchangepassword").on('submit', function(e) {
		e.preventDefault();						
		if(passValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=changepassword',
			data: formData,
			type: 'POST',
			beforeSend: function() {
				//$('#bigloading').show();
			},
			complete: function() {
				//$('#bigloading').fadeOut(1000);
			},
			success: function(data) {
				$('#message').html(data)
			 //submission		
			},
			cache: false,
			contentType: false,
			processData: false
		})
		}
	});
		
})

function passValidation(){
	if(document.getElementById('oldpass').value.trim()==''){
		$('#message').html('Please enter old password');
		document.getElementById('oldpass').focus();
		return false;
	}
	if(document.getElementById('newpass').value.trim()==''){
		$('#message').html('Please enter new password password');
		document.getElementById('newpass').focus();
		return false;
	}
	if(document.getElementById('newpass1').value.trim()==''){
		$('#message').html('Please enter confirm password');
		document.getElementById('newpass1').focus();
		return false;
	}
	if(document.getElementById('newpass').value.trim() != document.getElementById('newpass1').value.trim()){
		$('#message').html('confirm Password not match');
		document.getElementById('newpass1').focus();
		return false;
	}
	return true;
}
</script>