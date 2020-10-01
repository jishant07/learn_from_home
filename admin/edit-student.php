<?php
if($student['image']!='') {
	$pic='../uploads/images/students/'.$student['image'];
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
//print_r($student);
?>               
<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#"><?=getClassName($student['dept_id'])?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=students">Students</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?=$student['student_name']?> <?=$student['student_lastname']?></li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-3">
		<div class="card"><form class="forms-sample" id="frmstudent" autocomplete='off' enctype= "multipart/form-data">
			<div class="card-body">
				<div class="mb-2">
					<img src="<?=$pic?>" class="w-100" alt="profile">
					<?php if($_SESSION['u_type']=='admin') {?><input type="file" id="myDropify" name="myDropify" class="border"/>	<?php } ?>				
				</div>				
			</div>
		</div>
	</div>

	<div class="col-md-9 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>
				<form class="forms-sample">
				<input type='hidden' name='std_id' value="<?=$student['std_id']?>">
					<div class="form-group d-flex justify-content-end">
						<a href="index.php?action=students-stats&sid=<?=$student['std_id']?>" class="btn btn-dark mr-0">View Stats</a>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label class="d-inline-block mt-2 mr-2">Student ID</label>
								<input type="text" value="<?=$student['ecode']?>" class="form-control d-inline-block w-100" Readonly>
							</div>
							<div class="col-12 col-md-6">
								<label class="d-inline-block mt-2 mr-2">Created Date</label>
								<input type="text" value="<?=date('d M Y',strtotime($student['date_join']))?>" class="form-control d-inline-block w-100" Readonly>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Assign Class</label>
								<select class="form-control mb-3" id='class' name='class' <?=$disable?>>
									<option value=''>Select Class</option>
									<?php 
									$clax = explode(',',$student['dept_id']);
									for($c=0; $c<count($classes); $c++){
										$cid = $classes[$c]['class_id'];
										?>
										<option value="<?=$cid?>" <?php if(in_array($cid,$clax)) echo'selected'?>><?=$classes[$c]['class_name']?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-12 col-md-6">
								<label>Roll No.</label>
								<input type="text" class="form-control" placeholder="Roll no." value="<?=$student['roll_no']?>" readonly>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Fisrt Name*</label>
								<input type="text" class="form-control" placeholder="Fisrt Name" id='student_name' name='student_name' value="<?=$student['student_name']?>" <?=$readonly?>>
							</div>
							<div class="col-12 col-md-6">
								<label>Last Name*</label>
								<input type="text" class="form-control" placeholder="Last Name"  id='student_lastname' name='student_lastname' value="<?=$student['student_lastname']?>" <?=$readonly?>>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Date of Birth*</label>
								<?php if($_SESSION['u_type']=='admin') {?>
								<div class="input-group date datepicker" id="datePickerExample">
									
									<input type="text" class="form-control" id='date_birth' name='date_birth' value="<?=$student['date_birth']?>" <?=$readonly?> ><span class="input-group-addon"><i data-feather="calendar"></i></span>
									</div><?php } else echo $student['date_birth'] ?>
								
							</div>
							<div class="col-12 col-md-6">
								<label>Gender*</label><br>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" value="Male" name='gender' <?php if($student['gender']=='Male') echo 'checked';?> <?=$disable?>>
										Male
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" value="Female" name='gender' <?php if($student['gender']=='Female') echo 'checked';?> <?=$disable?>>
										Female
									</label>
								</div>								
							</div>
						</div>
						
					</div>
					
					
					
					<div class="form-group">
						<label>Address*</label>
						<textarea class="form-control" placeholder="Address" rows="5" id='address' name='address' <?=$readonly?>><?=$student['address']?></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Father Name*</label>
								<input type="text" class="form-control" placeholder="Father Name"  id='father_name' name='father_name' value="<?=$student['father_name']?>" <?=$readonly?>>
							</div>
							<div class="col-12 col-md-6">
								<label>Father Contact No*</label>
								<input type="text" class="form-control" placeholder="Father Contact No" id='father_contact' name='father_contact' value="<?=$student['father_contact']?>" <?=$readonly?>>
							</div>
							
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Mother Name</label>
								<input type="text" class="form-control" placeholder="Mother Name" id='mother_name' name='mother_name' value="<?=$student['mother_name']?>" <?=$readonly?>>
							</div>
							<div class="col-12 col-md-6">
								<label>Mother Contact No</label>
								<input type="text" class="form-control" placeholder="Mother Contact No" id='mother_contact' name='mother_contact' value="<?=$student['mother_contact']?>" <?=$readonly?>>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<label>Email ID*</label>
						<input type="text" class="form-control" placeholder="Email ID" id='email' name='email' value="<?=$student['email']?>" <?=$readonly?>>								
					</div>                                   
					<hr>
					<?php if($_SESSION['u_type']=='admin') {?>
					<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
					<button class="btn btn-light mt-2">Cancel</button>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>
 <?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
	$("#frmstudent").on('submit', function(e) {
		e.preventDefault();
		//let tid=$('#tid').val();
		if(studentValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=update-student',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=students'
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
});
function studentValidation(){
if(document.getElementById('class').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select class</div>");
	document.getElementById('class').focus();
	return false;
}
if(document.getElementById('student_name').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter student first name</div>");
	document.getElementById('student_name').focus();
	return false;
}
if(document.getElementById('student_lastname').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter last name</div>");
	document.getElementById('student_lastname').focus();
	return false;
}

if(document.getElementById('date_birth').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select birth date</div>");
	document.getElementById('date_birth').focus();
	return false;
}
var option=document.getElementsByName('gender');

if (!(option[0].checked || option[1].checked)) {
    $("#result").html("<div class='alert alert-warning'>Please Select Gender</div>");
    return false;
}

if(document.getElementById('address').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter address</div>");
	document.getElementById('address').focus();
	return false;
}

if(document.getElementById('father_name').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter father name</div>");
	document.getElementById('father_name').focus();
	return false;
}
if(document.getElementById('father_contact').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter father mobile number</div>");
	document.getElementById('father_contact').focus();
	return false;
}

if(document.getElementById('email').value==''){
	$("#result").html("<div class='alert alert-warning'>Please enter email id</div>");
	document.getElementById('email').focus();
	return false;
}
return true
}
</script>

			