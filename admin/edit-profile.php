<?php
if($teacher['t_pic']!='') {
	$pic='../uploads/teacher/'.$teacher['t_pic'];
	if(!file_exists($pic)) $pic='../uploads/avtar.png';
}
else $pic='../uploads/avtar.png';

if($_SESSION['u_type']=='admin') {
	$readonly=''; 
	$disable=''; 
}
else {
	$readonly='readonly';
	$disable='disabled';
}
?>
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php?action=teachers">Teachers</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?=$teacher['t_name']?> <?=$teacher['t_lastname']?></li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-3">
		<div class="card">
		<form class="forms-sample" id="frmteacher" autocomplete='off' enctype= "multipart/form-data">
		<input type='hidden' id='usertype' name='usertype' value="<?=$teacher['usertype']?>">
			<div class="card-body">
				<div class="mb-2">
					<img src="<?=$pic?>" class="w-100" alt="profile">
					<input type="file" id="myDropify" name="myDropify" class="border" />

				</div>
				
			</div>
		</div>
	</div>
	<div class="col-md-9 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>
				
					<input type='hidden' name='tid' id='tid' value="<?=$teacher['t_id']?>">
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="d-inline-block mt-2 mr-2">Teacher ID</label>
								<input type="text" value="<?=$teacher['t_code']?>" class="form-control d-inline-block w-100" Readonly>
							</div>
							<div class="col-12 col-md-6">
								<label class="d-inline-block mt-2 mr-2">Created Date</label>
								<input type="text" value="<?=date('d M Y',strtotime($teacher['t_createdat']))?>" class="form-control d-inline-block w-100" Readonly>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Fisrt Name</label>
								<input type="text" class="form-control" placeholder="Fisrt Name" id='t_name' name='t_name' value="<?=$teacher['t_name']?>" <?=$readonly?>>
							</div>
							<div class="col-12 col-md-6">
								<label>Last Name</label>
								<input type="text" class="form-control" placeholder="Last Name" id='t_lastname' name='t_lastname' value="<?=$teacher['t_lastname']?>" <?=$readonly?>>
							</div>
						</div>						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6"><label>Date of Birth</label>
								<?php if($_SESSION['u_type']=='admin') {?>
								<div class="input-group date datepicker" id="datePickerExample">
									<input type="text" class="form-control" id='t_dob' name='t_dob' value="<?=$teacher['t_dob']?>"><span class="input-group-addon"><i data-feather="calendar"></i></span>
								</div>
								<?php } else echo $teacher['t_dob']?>
							</div>
							<div class="col-12 col-md-6">
								<label>Gender</label>
								Male <input type="radio" class="form-control" value="Male" name='t_gender' <?php if($teacher['t_gender']=='Male') echo 'checked';?> <?=$disable?>><BR>
								Femail <input type="radio" class="form-control" value="Female" name='t_gender' <?php if($teacher['t_gender']=='Female') echo 'checked';?>   <?=$disable?>>
							</div>
						</div>						
					</div>
					<div class="form-group">
						<label>Address</label>
						<textarea class="form-control" placeholder="Address" rows="5" id='t_address' name='t_address' <?=$readonly?>><?=$teacher['t_address']?></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Email ID</label>
								<input type="text" class="form-control" id='t_contact' name='t_contact' placeholder="Email ID" value="<?=$teacher['t_contact']?>">
							</div>
							<div class="col-12 col-md-6">
								<label>Contact No.</label>
								<input type="text" class="form-control" placeholder="Contact No." id='t_phone' name='t_phone' value="<?=$teacher['t_phone']?>" >
							</div>
						</div>
						
					</div>
					
					<hr>
					<div class="clearfix"></div>
					<hr>
					
					<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
					<button class="btn btn-light mt-2">Cancel</button>
					
				</form>
			</div>
		</div>
	</div>
</div>
<?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
	$("#frmteacher").on('submit', function(e) {
		e.preventDefault();
		let tid=$('#tid').val();
		if(teacherValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=update-profile',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') location.reload();
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
});
function teacherValidation(){
if(document.getElementById('t_contact').value.trim()==''){
	$("#result").html("Please enter email id");
	document.getElementById('t_contact').focus();
	return false;
}
if(document.getElementById('t_phone').value.trim()==''){
	$("#result").html("Please enter mobile number");
	document.getElementById('t_phone').focus();
	return false;
}
return true
}
</script>

