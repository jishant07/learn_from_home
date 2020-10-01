<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><?=getClassName($data['class'])?></li>
		<li class="breadcrumb-item"><a href="index.php?action=assignments&class=<?=$data['class']?>&subject=<?=$data['subject']?>">Assignments</a></li>
		<li class="breadcrumb-item active" aria-current="page">Edit Assignments</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div id='result'></div>
				<form class="forms-sample" id="add-assign" method='post' autocomplete="off" enctype= "multipart/form-data">
					<input type='hidden' name='id' id='id' value="<?=$data['id']?>">					
					<input type='hidden' id='classid' value="<?=$data['class']?>">					
					<input type='hidden' id='subject' value="<?=$data['subject']?>">					
					<input type='hidden' id='type' name='type' value="<?=$_GET['type']?>">					
					<div class="form-group">
						<?php echo $_GET['type']=='freetext'?'Free Text Answer':'Upload Image or Doc'?>
					</div>
					<div class="form-group">
						<label>Title*</label>
						<input type="text" class="form-control" placeholder="Title" name='title' id='title' value="<?=stripslashes($data['question'])?>">
					</div>
					<div class="form-group">
						<label>Discription*</label>
						<textarea class="form-control" placeholder="Discription" rows="5" name='description' id='description'><?=stripslashes($data['answer'])?></textarea>
					</div>
					<div class="form-group">
						<label>Referance Document</label>
						<input type="file" name="refdoc" id="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
							<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image">
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
							</span>
						</div>
						<?php if($data['document']!=''){ ?><a href="../uploads/evaluation/referdoc/<?=$data['document']?>"  target='_blank'><?=$data['document']?></a><BR>
						<?php } ?>
					</div>
					<div class="form-group">
						<label>Submission Last Date*</label>
						<div class="input-group date datepicker" id="datePickerExample">
							<input type="text" class="form-control" name='submitdate' id='submitdate' value="<?=$data['closedate']?>"><span class="input-group-addon"><i data-feather="calendar"></i></span>
						</div>
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

	$("#add-assign").on('submit', function(e) {
		e.preventDefault();
		let classid=$('#classid').val();
		if(assignValidation()){
		
		let subject=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=assignment-edit',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=assignments&class='+classid+'&subject='+subject
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
});
function assignValidation(){
if(document.getElementById('title').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</div>");
	document.getElementById('title').focus();
	return false;
}
if(document.getElementById('description').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter description</div>");
	document.getElementById('description').focus();
	return false;
}
if(document.getElementById('submitdate').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select submission date</div>");
	document.getElementById('submitdate').focus();
	return false;
}
return true
}
</script>        

	