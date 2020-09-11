<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#"><?=getClassName($video['class'])?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=videos&class=<?=$video['class']?>&subject=<?=$video['subject']?>">Videos</a></li>
		<li class="breadcrumb-item active" aria-current="page">Edit</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>
				<form class="forms-sample" id="edit-video" autocomplete="off" enctype= "multipart/form-data">
					<input type='hidden' name='vid_id' id='vid_id' value="<?=$video['id']?>">
					<input type='hidden' name='vid_class' id='vid_class' value="<?=$video['class']?>">
					<input type='hidden' name='vid_teacher' id='vid_teacher' value="<?=$video['teacher']?>">
					<input type='hidden' name='vid_sub' id='vid_sub' value="<?=$video['subject']?>">
						
					<div class="form-group">
						<label>Title</label>
						<input type="text" class="form-control" placeholder="Title" name='title' id='title' value="<?=$video['title']?>">
					</div>
					<div class="form-group">
						<label>Discription</label>
						<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=stripslashes($video['description'])?></textarea>
					</div>
					<div class="form-group">
						<label>Upload Document</label>
						<input type="file" name="refdoc" id="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
							<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Document">							
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
							</span>
						</div><?php if($video['document']!=''){ ?><a href="../uploads/coursedocuments/<?=$video['document']?>" target='_blank'><?=$video['document']?></a><BR>
							<?php } ?>	
					</div>
					<div class="form-group">
						<label>Upload Thumbnail</label>
						<div class="form-group">
							<input type="file" id="myDropify2" name="myDropify2" class="border"/>
						</div><?php if($video['vthumb']!=''){ ?><a href="../uploads/images/coursevideos/<?=$video['vthumb']?>"  target='_blank'><?=$video['vthumb']?></a><BR>
						<?php } ?>	
					</div>
					<div class="form-group">
						<label>Assign Under Course</label>
						<?php echo sel_courses($video['class'],$video['subject'],$video['id'])?>						
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								<label>Schedule Date</label>
								<div class="input-group date datepicker" id="datePickerExample">
									<input type="text" class="form-control"><span class="input-group-addon"><i data-feather="calendar"></i></span>
								</div>
							</div>
							<div class="col-6">
								<label>Schedule Time</label>
								<div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample"/>
									<div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
										<div class="input-group-text"><i data-feather="clock"></i></div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<?php echo $vid_format = $video['vid_format'];?>
					<input type='hidden' id='vidformath' value="<?=$vid_format?>">
					<div class="form-group">
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="Videolink" value="link" <?php if($vid_format=='link') echo 'checked'?> onclick='hideVformat(this.value)'>
								Add Video Link
							</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="UploadVideo" value="video" <?php if($vid_format=='video') echo 'checked'?> onclick='hideVformat(this.value)'>
								Upload Video
							</label>
						</div>
						<div class="form-check form-check-inline">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="livevideo" id="optionsRadios7" value="live" onclick='hideVformat(this.value)'>
								Add From Live Session
							</label>
						</div>						
					</div>
	
					<div class="form-group"  id='linkid'>
						<input type="text" class="form-control" id='vlink' name='vlink' placeholder="Paste Video link Here" value="<?=$video['vlink']?>">
					</div>
					<div class="form-group" id='fileid'>
						<input type="file" id="myDropify" class="border"/>
					</div>
					<div class="form-group" id='livesess'>
						<?php echo sel_livesession($video['class'],$video['subject'])?>
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
    let vidformath=$('#vidformath').val();
	if(vidformath=='link') { $('#fileid').hide();$('#livesess').hide();}
	if(vidformath=='video') {$('#linkid').hide();$('#livesess').hide();}
	if(vidformath=='live') { $('#fileid').hide();$('#linkid').hide();}


	$("#edit-video").on('submit', function(e) {
		e.preventDefault();
		if(videoValidation()){
		let vid_class=$('#vid_class').val();
		let vid_sub=$('#vid_sub').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=course-video-edit',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=videos&class='+vid_class+'&subject='+vid_sub
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
if(document.getElementById('title').value.trim()==''){
	$("#result").html("Please enter title");
	document.getElementById('title').focus();
	return false;
}
if(document.getElementById('description').value.trim()==''){
	$("#result").html("Please enter description");
	document.getElementById('description').focus();
	return false;
}
if(livevideo=='link'){
	if(document.getElementById('vlink').value.trim()==''){
	$("#result").html("Please enter video link");
	document.getElementById('vlink').focus();
	return false;
	}
}
if(livevideo=='video'){
	if(document.getElementById('myDropify').value.trim()==''){
	$("#result").html("Please select video");
	document.getElementById('myDropify').focus();
	return false;
	}
}
return true;
}
function hideVformat(f){
	if(f=='link') { $('#fileid').hide();$('#linkid').show();$('#livesess').hide();}
	if(f=='video') {$('#linkid').hide();$('#fileid').show();$('#livesess').hide();}
	if(f=='live') { $('#fileid').hide();$('#linkid').hide();$('#livesess').show();}
}
</script>			

amim mata building
near kamal driving school
mahajan layout
