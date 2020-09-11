<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php?action=students">Students</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add New</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-3">
		<div class="card"><form class="forms-sample" id="frmstudent" autocomplete='off' enctype= "multipart/form-data">
			<div class="card-body">
				<div class="mb-2">
					<input type="file" id="myDropify" name="myDropify" class="border"/>					
				</div>				
			</div>
		</div>
	</div>
	<div class="col-md-9 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>					
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Assign Class</label>
								<select class="form-control mb-3" id='class' name='class'>
									<option value=''>Select Class</option>
									<?php 
									for($c=0; $c<count($classes); $c++){
										$cid = $classes[$c]['class_id'];
										?>
										<option value="<?=$cid?>"><?=$classes[$c]['class_name']?></option>
									<?php } ?>
								</select>
							</div>							
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Fisrt Name</label>
								<input type="text" class="form-control" placeholder="Fisrt Name" id='student_name' name='student_name'>
							</div>
							<div class="col-12 col-md-6">
								<label>Last Name</label>
								<input type="text" class="form-control" placeholder="Last Name"  id='student_lastname' name='student_lastname' >
							</div>
						</div>					
					</div>
					<div class="form-group">
						<div class="col-12 col-md-6">
							<label>Date of Birth</label>
							<div class="input-group date datepicker" id="datePickerExample">
								<input type="text" class="form-control" id='date_birth' name='date_birth'><span class="input-group-addon"><i data-feather="calendar"></i></span>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label>Gender</label>
							Male <input type="radio" class="form-control" value="Male" name='gender'><BR>
							Female <input type="radio" class="form-control" value="Female" name='gender'>
						</div>
					</div>
					
					
					
					<div class="form-group">
						<label>Address</label>
						<textarea class="form-control" placeholder="Address" rows="5" id='address' name='address'></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Father Name</label>
								<input type="text" class="form-control" placeholder="Father Name"  id='father_name' name='father_name'>
							</div>
							<div class="col-12 col-md-6">
								<label>Father Contact No</label>
								<input type="text" class="form-control" placeholder="Father Contact No" id='father_contact' name='father_contact'>
							</div>
							
						</div>
						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Mother Name</label>
								<input type="text" class="form-control" placeholder="Mother Name" id='mother_name' name='mother_name'>
							</div>
							<div class="col-12 col-md-6">
								<label>Mother Contact No</label>
								<input type="text" class="form-control" placeholder="Mother Contact No" id='mother_contact' name='mother_contact'>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<label>Email ID</label>
						<input type="text" class="form-control" placeholder="Email ID" id='email' name='email'>								
					</div>                                   
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
	$("#frmstudent").on('submit', function(e) {
		e.preventDefault();
		//let tid=$('#tid').val();
		if(studentValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=add-student',
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
	$("#result").html("Please select class");
	document.getElementById('class').focus();
	return false;
}
if(document.getElementById('student_name').value.trim()==''){
	$("#result").html("Please enter student first name");
	document.getElementById('student_name').focus();
	return false;
}
if(document.getElementById('student_lastname').value.trim()==''){
	$("#result").html("Please enter last name");
	document.getElementById('student_lastname').focus();
	return false;
}

if(document.getElementById('date_birth').value.trim()==''){
	$("#result").html("Please select birth date");
	document.getElementById('date_birth').focus();
	return false;
}
var option=document.getElementsByName('gender');

if (!(option[0].checked || option[1].checked)) {
    alert("Please Select Gender");
    return false;
}

if(document.getElementById('address').value.trim()==''){
	$("#result").html("Please enter address");
	document.getElementById('address').focus();
	return false;
}

if(document.getElementById('father_name').value.trim()==''){
	$("#result").html("Please enter father name");
	document.getElementById('father_name').focus();
	return false;
}
if(document.getElementById('father_contact').value.trim()==''){
	$("#result").html("Please enter father mobile number");
	document.getElementById('father_contact').focus();
	return false;
}

if(document.getElementById('email').value==''){
	$("#result").html("Please enter email id");
	document.getElementById('email').focus();
	return false;
}

if(document.getElementById('myDropify').value==''){
	$("#result").html("Please select student pic");
	document.getElementById('myDropify').focus();
	return false;
}

return true
}
</script>

			