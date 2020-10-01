<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><?=getClassName($_GET['class'])?></li>
		<li class="breadcrumb-item"><a href="index.php?action=courses&class=<?=$_GET['class']?>&subject=<?=$_GET['subject']?>">Courses</a></li>
		<li class="breadcrumb-item active" aria-current="page">Add Course</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body">
				<div id='result'></div>
				<form class="forms-sample" id="add-course" method='post' autocomplete="off" enctype= "multipart/form-data">
					<input type='hidden' name='classid' id='classid' value="<?=$_GET['class']?>">
					<input type='hidden' name='subject' id='subject' value="<?=$_GET['subject']?>">
						
					<div class="form-group">
						<label>Title*</label>
						<input type="text" class="form-control" placeholder="Title" id='title' name='title'>
					</div>
				   
					
					
					<div class="form-group">
						<label>Upload Thumbnail*</label>
						<input type="file" id="myDropify" name="myDropify" class="border"/>
					</div>
					<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
					<button class="btn btn-light mt-2">Cancel</button>
					
					
		
				</form>
			</div>
		</div>
	</div>
	<!--div class="col-md-6 grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="list-group">
					<a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
					<a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
					<a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
					<a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Vestibulum at eros</a>
				</div>
			</div>
		</div>
	</div-->
</div>

    <!-- Modal -->
  <div class="modal fade" id="viewlive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
<?php include('javascript.php') ?>
<script>
$( document ).ready(function() {

	$("#add-course").on('submit', function(e) {
		e.preventDefault();
		let classid=$('#classid').val();
		if(courseValidation()){
		
		let subject=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=course-add',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=courses&class='+classid+'&subject='+subject
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
});
function courseValidation(){
if(document.getElementById('title').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</div>");
	document.getElementById('title').focus();
	return false;
}
if(document.getElementById('myDropify').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please select image</div>");
	document.getElementById('myDropify').focus();
	return false;
}
return true
}
</script>