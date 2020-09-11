                <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.php?action=edit-profile">Profile</a></li>
						<li class="breadcrumb-item active" aria-current="page">Change Password</li>
					</ol>
                </nav>
                <div class="row">
					<div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body"><div class="row alert alert-notice" id='message'></div>
								<form class="forms-sample" id='frmchangepassword' name='frmchangepassword' method='post'>
									<div class="form-group">
										<label>Enter Old Password</label>
										<input type="password" class="form-control" placeholder="Enter Old Password" id='oldpass' name='oldpass'>
									</div>
									<hr>
									<div class="form-group">
										<label>Enter New Password</label>
										<input type="password" class="form-control" placeholder="Enter New Password" id='newpass' name='newpass'>
									</div>
									<div class="form-group">
										<label>Re-Enter New Password</label>
										<input type="password" class="form-control" placeholder="Re-Enter New Password" id='newpass1' name='newpass1'>
                                    </div>
									<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
									<button class="btn btn-light mt-2">Cancel</button>
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