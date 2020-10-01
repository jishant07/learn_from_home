                <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
						<li class="breadcrumb-item"><a href="index.php?action=documents&class=<?=$_GET['class']?>&subject=<?=$_GET['subject']?>">Documents</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add New Document</li>
					</ol>
                </nav>
                <div class="row">
					<div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
								<div id='result'></div>
									<form class="forms-sample" id="add-doc" autocomplete="off" enctype= "multipart/form-data">
										<input type='hidden' name='class' id='class' value="<?=$_GET['class']?>">
										<input type='hidden' name='subject' id='subject' value="<?=$_GET['subject']?>">
									<div class="form-group">
										<label>Title*</label>
										<input type="text" class="form-control" placeholder="Title" id='name' name='name'>
                                    </div>
                                   
									
                                    
                                    <div class="form-group">
										<label>Upload Document*</label>
                                        <input type="file" id="myDropify" name="myDropify" class="border"/>
                                    </div>
									
									<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
									<button class="btn btn-light mt-2">Cancel</button>
								</form>
                            </div>
                        </div>
					</div>
					
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
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

<?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
    $("#add-doc").on('submit', function(e) {
		e.preventDefault();
		//let name=$('#name').val();
		if(docValid()){
		
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=document-add',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=documents&class='+dclass+'&subject='+sub
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})	
});
function docValid(){
if(document.getElementById('name').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</div>");
	document.getElementById('name').focus();
	return false;
}
if(document.getElementById('myDropify').value==''){
	$("#result").html("<div class='alert alert-warning'>Please select doc file</div>");
	document.getElementById('myDropify').focus();
	return false;
}
return true;
}
</script>