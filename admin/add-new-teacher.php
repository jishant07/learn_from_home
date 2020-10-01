<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="index.php?action=teachers">Teachers</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add New</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-3">
		<div class="card"><form class="forms-sample" id="frmteacher" autocomplete='off' enctype= "multipart/form-data">


			<div class="card-body">
				<input type="file" id="myDropify" name="myDropify" class="border"/>
								
				
			</div>
		</div>
	</div>
	<div class="col-md-9 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Fisrt Name*</label>
								<input type="text" class="form-control" placeholder="Fisrt Name" id='t_name' name='t_name'>
							</div>
							<div class="col-12 col-md-6">
								<label>Last Name*</label>
								<input type="text" class="form-control" placeholder="Last Name" id='t_lastname' name='t_lastname' >
							</div>
						</div>						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Date of Birth*</label>
								<div class="input-group date datepicker" id="datePickerExample">
									<input type="text" class="form-control" id='t_dob' name='t_dob'><span class="input-group-addon"><i data-feather="calendar"></i></span>
								</div>
							</div>
							
							<div class="col-12 col-md-6">
								<label>Gender*</label><br>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" value="Male" name='t_gender'>
										Male
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" value="Female" name='t_gender'>
										Female
									</label>
								</div>
							</div>
						</div>						
					</div>
					<div class="form-group">
						<label>Address*</label>
						<textarea class="form-control" placeholder="Address" rows="5" id='t_address' name='t_address'></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Email Id*</label>
								<input type="text" class="form-control" id='t_contact' name='t_contact' placeholder="Email ID">
							</div>
							<div class="col-12 col-md-6">
								<label>Contact No.*</label>
								<input type="text" class="form-control" placeholder="Contact No." id='t_phone' name='t_phone'>
							</div>
						</div>
						
					</div>
					
					<hr>
					<div class="form-group">
						<div class="row">
							<div class="col-6 col-md-6">
								<label>Assign Class</label>
								<div id='class'>
									<?php 
									for($c=0; $c<count($classes); $c++){
										$cid = $classes[$c]['class_id'];
										?>										
										<div class="form-check form-check-inline">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input" name='class[]'>
											<?=$classes[$c]['class_name']?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
							
						</div>
						
					</div>
					
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
			url: 'index.php?action=add-teacher',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=teachers'
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
if(document.getElementById('t_name').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter name</div>");
	document.getElementById('t_name').focus();
	return false;
}
if(document.getElementById('t_lastname').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter last name</div>");
	document.getElementById('t_lastname').focus();
	return false;
}
if(document.getElementById('t_dob').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select date of birth</div>");
	document.getElementById('t_dob').focus();
	return false;
}

var option=document.getElementsByName('t_gender');

if (!(option[0].checked || option[1].checked)) {
   	$("#result").html("<div class='alert alert-warning'>Please Select Your Gender</div>");
    return false;
}

if(document.getElementById('t_address').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter address</div>");
	document.getElementById('t_address').focus();
	return false;
}

if(document.getElementById('t_contact').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter email id</div>");
	document.getElementById('t_contact').focus();
	return false;
}
if(document.getElementById('t_phone').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter mobile number</div>");
	document.getElementById('t_phone').focus();
	return false;
}

if(document.getElementById('class').value==''){
	$("#result").html("<div class='alert alert-warning'>Please select class</div>");
	document.getElementById('class').focus();
	return false;
}

if(document.getElementById('myDropify').value==''){
	$("#result").html("<div class='alert alert-warning'>Please select image</div>");
	document.getElementById('myDropify').focus();
	return false;
}


return true
}
</script>

