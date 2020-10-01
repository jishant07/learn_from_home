<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#">Class <?=$_GET['class']?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=live-sessions&class=<?=$_GET['class']?>&subject=<?=$_GET['subject']?>">Live Session</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add New</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				
				<form class="forms-sample" id="add-video" autocomplete="off" enctype= "multipart/form-data">
				<div id='result'> </div>
					<input type='hidden' name='vid_class' id='vid_class' value="<?=$_GET['class']?>">
					<input type='hidden' name='vid_teacher' id='vid_teacher' value="<?=$_SESSION['tid']?>">
					<input type='hidden' name='vid_sub' id='vid_sub' value="<?=$_GET['subject']?>">
					<div class="form-group">
						<label>Title*</label>
						<input type="text" class="form-control" placeholder="Title" id='vtitle' name='vtitle'>
					</div>
					<div class="form-group">
						<label>Discription*</label>
						<textarea class="form-control" placeholder="Discription"  id='description'  name='description' rows="5"></textarea>
					</div>
					<div class="form-group">
						<label>Upload Document</label>
						<input type="file" name="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
							<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label>Date </label>
						<div class="input-group date datepicker" id="sub_start_at">
							<input type="text" class="form-control" id='sub_start_date' name='sub_start_date'><span class="input-group-addon"><i data-feather="calendar"></i></span>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								<label>Start Session* </label>
								<div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample" id='start_time' name='start_time'/>
									<div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
										<div class="input-group-text"><i data-feather="clock"></i></div>
									</div>
								</div>
							</div>
							<div class="col-6">
								<label>End Session*</label>
								<div class="input-group date timepicker" id="datetimepickerExample2" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample2" id='end_time' name='end_time'/>
									<div class="input-group-append" data-target="#datetimepickerExample2" data-toggle="datetimepicker">
										<div class="input-group-text"><i data-feather="clock"></i></div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-group">
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="Videolink" value="link" onclick='hideVformat(this.value)'>
								Add Video Link
							</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="UploadVideo" value="video" onclick='hideVformat(this.value)'>
								Upload Video
							</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="optionsRadios7" value="webcam" onclick='hideVformat(this.value)'>
								Live with webcam
							</label>
						</div>
					</div>
					<div class="form-group"  id='linkid'>
						<input type="text" class="form-control" id='vlink' name='vlink' placeholder="Paste Video link Here" value="">
					</div>
					<div class="form-group" id='fileid'>
						<input type="file" id="myDropify" name="myDropify" class="border"/>
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
$( document ).ready(function() {
	$('#Videolink').attr('checked','checked')
$('#fileid').hide();
   
	
	$("#add-video").on('submit', function(e) {
		e.preventDefault();
		if(videoValidation()){
		let vid_class=$('#vid_class').val();
		let vid_sub=$('#vid_sub').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=video-add',
			data: formData,
			type: 'POST',
			beforeSend: function() {
				//$('#bigloading').show();
			},
			complete: function() {
				//$('#bigloading').fadeOut(1000);
			},
			success: function(data) {
				if(data=='') window.location.href='index.php?action=live-sessions&class='+vid_class+'&subject='+vid_sub
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
	
});


function videoValidation(){
//let livevideo = $('.form-check-input').val();
 let livevideo = $("input[name='livevideo']:checked").val()

//alert(livevideo)
if(document.getElementById('vtitle').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</div>");
	document.getElementById('vtitle').focus();
	return false;
}
if(document.getElementById('description').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter description</div>");
	document.getElementById('description').focus();
	return false;
}
if(document.getElementById('sub_start_date').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select date</div>");
	document.getElementById('sub_start_date').focus();
	return false;
}
if(document.getElementById('start_time').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select start time</div>");
	document.getElementById('start_time').focus();
	return false;
}
if(document.getElementById('end_time').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select end time</div>");
	document.getElementById('end_time').focus();
	return false;
}
if(livevideo=='link'){
	if(document.getElementById('vlink').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter video link</div>");
	document.getElementById('vlink').focus();
	return false;
	}
}
if(livevideo=='video'){
	if(document.getElementById('myDropify').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select video</div>");
	document.getElementById('myDropify').focus();
	return false;
	}
}
return true;
}
function hideVformat(f){
	if(f=='link') { $('#fileid').hide();$('#linkid').show();}
	if(f=='video') {$('#linkid').hide();$('#fileid').show();}
	if(f=='webcam') { $('#fileid').hide();$('#linkid').hide();}
}
</script>			