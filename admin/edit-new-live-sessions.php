<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><?=getClassName($_GET['class'])?></li>
		<li class="breadcrumb-item"><a href="index.php?action=live-sessions&class=<?=$_GET['class']?>&subject=<?=$video['vid_sub']?>" title='Live Session'>Live Session</a></li>
		<li class="breadcrumb-item active" aria-current="page">Edit</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div id='result'></div>
				<form class="forms-sample" id="edit-video" autocomplete="off" enctype= "multipart/form-data">
					<input type='hidden' name='vid_id' id='vid_id' value="<?=$video['vid_id']?>">
					<input type='hidden' name='vid_class' id='vid_class' value="<?=$video['vid_class']?>">
					<input type='hidden' name='vid_teacher' id='vid_teacher' value="<?=$video['vid_teacher']?>">
					<input type='hidden' name='vid_sub' id='vid_sub' value="<?=$video['vid_sub']?>">
					<div class="form-group">
						<label>Title</label>
						<input type="text" class="form-control" placeholder="Title" id='vtitle' name='vtitle' value="<?=$video['vtitle']?>">
					</div>
					<div class="form-group">
						<label>Discription</label>
						<textarea class="form-control" placeholder="Discription"  id='description'  name='description' rows="5"><?=$video['vdesc']?></textarea>
					</div>
					<div class="form-group">
						<label>Upload Document</label>
						<input type="file" name="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
							<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
							</span>
						</div><?php if($video['ref_doc']!=''){ ?><a href="../uploads/videos/refdoc/<?=$video['ref_doc']?>" target='_blank'><?=$video['ref_doc']?></a><?php } ?>
					</div>
					<div class="form-group">
						<label>Date </label>
						<div class="input-group date datepicker" id="sub_start_at">
							<input type="text" class="form-control" id='sub_start_date' name='sub_start_date' value="<?=date('Y-m-d',strtotime($video['sub_start_at']))?>"><span class="input-group-addon"><i data-feather="calendar"></i></span>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								<label>Start Session </label>
								<div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample" id='start_time' name='start_time' value="<?=date('H:i',strtotime($video['sub_start_at']))?>"/>
									<div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
										<div class="input-group-text"><i data-feather="clock"></i></div>
									</div>
								</div>
							</div>
							<div class="col-6">
								<label>End Session</label>
								<div class="input-group date timepicker" id="datetimepickerExample2" data-target-input="nearest">
									<input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample2" id='end_time' name='end_time' value="<?=date('H:i',strtotime($video['sub_end_at']))?>"/>
									<div class="input-group-append" data-target="#datetimepickerExample2" data-toggle="datetimepicker">
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
								<input type="radio" class="form-check-input" name="livevideo" id="optionsRadios7" value="webcam" <?php if($vid_format=='webcam') echo 'checked'?> onclick='hideVformat(this.value)'>
								Live with webcam
							</label>
						</div>
					</div>
					<div class="form-group"  id='linkid'>
						<input type="text" class="form-control" id='vlink' name='vlink' placeholder="Paste Video link Here" value="<?=$video['aws_link']?>">
					</div>
					<div class="form-group" id='fileid'>
						<input type="file" id="myDropify" class="border"/>
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
	if(vidformath=='link') $('#fileid').hide();
	if(vidformath=='video') $('#linkid').hide();
	if(vidformath=='webcam') { $('#fileid').hide();$('#linkid').hide();}


	$("#edit-video").on('submit', function(e) {
		e.preventDefault();
		if(videoValidation()){
		let vid_class=$('#vid_class').val();
		let vid_sub=$('#vid_sub').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=video-edit',
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
	$("#result").html("Please enter title");
	document.getElementById('vtitle').focus();
	return false;
}
if(document.getElementById('description').value.trim()==''){
	$("#result").html("Please enter description");
	document.getElementById('description').focus();
	return false;
}
if(document.getElementById('sub_start_date').value.trim()==''){
	$("#result").html("Please select date");
	document.getElementById('sub_start_date').focus();
	return false;
}
if(document.getElementById('start_time').value.trim()==''){
	$("#result").html("Please select start time");
	document.getElementById('start_time').focus();
	return false;
}
if(document.getElementById('end_time').value.trim()==''){
	$("#result").html("Please select end time");
	document.getElementById('end_time').focus();
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
	if(f=='link') { $('#fileid').hide();$('#linkid').show();}
	if(f=='video') {$('#linkid').hide();$('#fileid').show();}
	if(f=='webcam') { $('#fileid').hide();$('#linkid').hide();}
}
</script>			