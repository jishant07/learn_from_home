                <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
						<li class="breadcrumb-item"><a href="index.php?action=books&class=<?=$_GET['class']?>">Books</a></li>
						<li class="breadcrumb-item active" aria-current="page">Edit Book</li>
					</ol>
                </nav>
                <div class="row">
					<div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
								<div id='result'></div>
								<form class="forms-sample" id="frmbook" method='post' autocomplete="off" enctype= "multipart/form-data">
									<input type='hidden' name='classid' id='classid' value="<?=$book['class']?>">
									<input type='hidden' name='bookid' id='bookid' value="<?=$book['book_id']?>">
				
									<div class="form-group">
										<label>Book Title*</label>
										<input type="text" class="form-control" placeholder="Title" name='title' id='title' value="<?=$book['book_name']?>">
                                    </div>
									<div class="form-group">
										<label>Upload Book*</label>
										<input type="file" name="bookfile" id="bookfile" class="file-upload-default">
										<div class="input-group col-xs-12">
											<input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Book">
											<span class="input-group-append">
												<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
											</span>
										</div>
										<?php 
								$book_link = $book['book_link'];
								if($book['book_link']!='') {?><a href="javascript:window.open('../uploads/my_books<?=$book_link?>')"><?=$book['book_link']?></a>
										<?php } ?>
									</div>
                                    <div class="form-group">
										<label>Upload Book Cover*</label>
                                        <input type="file" id="myDropify" name='myDropify' class="border"/>
                                    </div>
									<?php 
								$book_thumb = $book['book_thumb'];
								if($book['book_thumb']!='') {?><a href="javascript:window.open('../uploads/my_books/thumb/<?=$book_thumb?>')"><?=$book['book_thumb']?></a><BR>
										<?php } ?>
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
	$("#frmbook").on('submit', function(e) {
		e.preventDefault();
		let classid=$('#classid').val();
		if(bookValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=book-update',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=books&class='+classid
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	})
	
});
function bookValidation(){
if(document.getElementById('title').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</dov>");
	document.getElementById('title').focus();
	return false;
}
return true
}
</script>

