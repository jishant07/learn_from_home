        <link href="css/cropper.min.css" rel="stylesheet" type="text/css"/>
<style>
            #change-profile .preview {

            }

            .priview-wraper{
                width: 100px;
                height:100px;
                position: absolute;
                top: 25%;
                right: 10%;
                overflow: hidden;
                border-radius: 100%;


            }

            .priview-wraper-origal{
                width: 100px;
                height:100px;
                overflow: hidden;
                border-radius: 100%;
                background-position: center;
                background-repeat: no-repeat;
                background-size: 100%;
            }
        </style>
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
					<input type="file" id="myDropify" name="myDropify"  accept="image/*" class="border" onchange="loadFile(event)"/>					
				</div>
			</div>
			<div class="preview"></div>
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
						<div class="row">
							<div class="col-12 col-md-6">
								<label>Date of Birth</label>								
								<div class="input-group date datepicker" id="datePickerExample">
									<input type="text" class="form-control" id='date_birth' name='date_birth'><span class="input-group-addon"><i data-feather="calendar"></i></span>
								</div>								
							</div>
							<div class="col-12 col-md-6">
								<label>Gender</label><br>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" class="form-check-input" value="Male" name='gender'>
										Male
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input type="radio" value="Female" name='gender'>
										Female
									</label>
								</div>								
							</div>
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
					<input type='hidden' id='canvascrp' name='canvascrp'>
					<button type="submit" class="btn btn-primary mr-2 mt-2" id='submitp'>Submit</button>
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
   // alert("");
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

if(document.getElementById('myDropify').value==''){
	$("#result").html("<div class='alert alert-warning'>Please select student pic</div>");
	document.getElementById('myDropify').focus();
	return false;
}

return true
}
</script>

			