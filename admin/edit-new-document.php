                <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#">Class 1</a></li>
						<li class="breadcrumb-item"><a href="index.php?action=documents&class=<?=$doc['class']?>&subject=<?=$doc['subject']?>">Documents</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Document</li>
					</ol>
                </nav>
                <div class="row">
					<div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
								<div id='result'></div>
									<form class="forms-sample" id="edit-doc" autocomplete="off" enctype= "multipart/form-data">
										<input type='hidden' name='id' id='id' value="<?=$doc['id']?>">
										<input type='hidden' name='class' id='class' value="<?=$doc['class']?>">
										<input type='hidden' name='subject' id='subject' value="<?=$doc['subject']?>">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" placeholder="Title" id='name' name='name' value="<?=stripslashes($doc['name'])?>">
                                    </div>
                                   
									
                                    
                                    <div class="form-group">
										<label>Upload Document</label>
                                        <input type="file" id="myDropify" name="myDropify" class="border"/>
                                    </div>
									<a href="../uploads/study_material/<?php echo $doc['studydoc']?>" target='_blank'><?php echo $doc['studydoc']?><BR>
									
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
    $("#edit-doc").on('submit', function(e) {
		e.preventDefault();
		let name=$('#name').val();
		if(name!=''){
		
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=document-edit',
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
		} else{
			$("#result").html("Please enter document title");
		}
	})
	
});
</script>