<script src="js/validation.js"></script>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($exam['class'])?></a></li>
              <li class="breadcrumb-item"><a href="index.php?action=exams&class=<?=$exam['class']?>&subject=<?=$exam['subject']?>">Exams</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Exam</li>
            </ol>
          </nav>          
        </div>
		
        <div class="row alt-success success" id='result'></div>
        <div class="row">
          <div class="col-lg-8 mb-3">
            <div class="card">
              <div class="card-body">
               <div id=''>
					<form class="forms-sample" method='post' id='frmuploadmedia'  autocomplete=off> 
					<input type='hidden' name='evid' id='evid' value="<?=$data['evid']?>">
					<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
					<input type='hidden' id='oldmarks' name='oldmarks' value='<?=$data['marks']?>'>
										
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='picsmarks' id='picsmarks' maxlength=2 size=2  onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?=$data['marks']?>">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question</label>
	<input type="text" class="form-control" placeholder="Title" name='uoloadimagequestion' id='uoloadimagequestion' value="<?=$data['question']?>">
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=stripslashes($data['answer'])?></textarea>
  </div>

  

 <div class="form-group">
						<label>Upload Referance Document</label>
						<input type="file" name="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
						  <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Document">
						  <span class="input-group-append">
							<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
						  </span>
						</div>
					  </div><?php if($data['document']!=''){
							$file ="../uploads/evaluation/referdoc/".$data['document'];
							?>
							<a href="javascript:window.open('<?=$file?>')"><?=$data['document']?></a>	
						<?php } ?>
					  <hr>
					 				  
					  <button type="submit" class="btn btn-success mr-2 mt-2" name='createfillbankquestion' id='createfillbankquestion'>Update</button>
					  
					</form>
					</div> 
              </div> 
            </div>
          </div>
          <div class="col-lg-7">
            <!--question box-->
            
		  </div>
		</div>
      <script src="assets/Sortable.js"></script>

	
	<script src="assets/app.js"></script>  

	<!-- endinject -->
  <!-- plugin js for this page -->
<?php include('javascript.php') ?>
 
<script>
 $( document ).ready(function() {
	let id = $('#id').val();
	let evid = $('#evid').val();
	$("#frmuploadmedia").on('submit', function(e) {
		e.preventDefault();
		if(uploadimagevalid()){
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/editoperations.php?type=uploadimage&id='+id,
			data: formData,
			type: 'POST',
			success: function(data) {
				//$('#result').html(data)	
				window.location.href='index.php?action=edit-new-exam&id='+evid
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})

});

</script>