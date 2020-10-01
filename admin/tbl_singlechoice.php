<script src="js/validation.js"></script>
<?php
$options = explode('-',$data['options']);
?>        
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#"><?=getClassName($exam['class'])?></a></li>
	  <li class="breadcrumb-item"><a href="index.php?action=edit-new-exam&id=<?=$_GET['evid']?>">Exam</a></li>
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
			<form class="forms-sample" method='post' id='frmsinglechoice'  autocomplete=off> 
				<input type='hidden' name='evid' id='evid' value="<?=$data['evid']?>">
				<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
				<input type='hidden' id='oldmarks' name='oldmarks' value='<?=$data['marks']?>'>
				<input type='hidden' id='noofsinglechoice' name='noofsinglechoice' value="<?=count($options)?>">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<label class="d-inline-block mt-2 mr-2">Marks</label>
							<input type="text" class="form-control d-inline-block wd-80" name='singlemarks' id='singlemarks' maxlength=2 size=2 value="<?=$data['marks']?>" readonly>
						</div>		
					</div>
				</div>
			  <div class="form-group">
				<label>Question</label>
				<input type="text" class="form-control" placeholder="Title" name='singlechoicequestion' id='singlechoicequestion' value="<?=$data['question']?>">
			  </div>
			  <div class="form-group">
				<label>Discription</label>
				<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=stripslashes($data['description'])?></textarea>
			  </div>
			  <div id='singleanswerchoice'>
				<div class="row mt-3">
					<div class="col-12">
						<label>Select Right Answer</label>
					</div>
				</div>
				<?php for($i=0; $i<count($options); $i++){
				$k=$i+1;				
				?>
				<div class="form-group" id='singleans<?=$k?>'>
					<div class="row">
						<div class="col-1"></div>
						<div class="col-1 form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" id="sinans<?=$k?>" name="singleanswer" value="<?=$k?>" <?php if($k==$data['answer']) echo'checked';?>>
							</label>
						</div>
						<div class="col-10">
							<input type='text' class="form-control" name='singlechoiceans[]' id='singlechoiceans<?=$k?>' value="<?=$options[$i]?>">
						</div>
					</div>
				</div>
				<?php } ?>
			  </div>
			  <div class='form-group'>
				<div class='matchcol'>
					<input type='button' id='addsinglechoice' class='btn btn-success mr-2 mt-2' value='+ Add More Rows'>
					<input type='button' id='removesinglerow' class='btn btn-success mr-2 mt-2' style='display:none' value='- Remove More Rows'>
				</div>				
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
			  </div>
			  <?php if($data['referdoc']!=''){
				$file ="../uploads/evaluation/referdoc/".$data['referdoc'];
				?>
				<a href="javascript:window.open('<?=$file?>')"><?=$data['referdoc']?></a>	
			  <?php } ?>
			  <hr>
			  <div class="form-check form-check-flat form-check-primary">
				<label class="form-check-label">
				  <input type="checkbox" class="form-check-input" name='uploadflag' id='uploadflag' value='1' <?php if($data['uploadflag']==1) echo'checked'?>>
				  Student can upload document
				</label>
			  </div>		  
			  
			  <button type="submit" class="btn btn-success mr-2 mt-2" name='createfillbankquestion' id='createfillbankquestion'>Update</button>					  
			</form>
		</div> 
      </div> 
	</div>
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
	
	$("#frmsinglechoice").on('submit', function(e) {
		e.preventDefault();
		if(singleobjectivevalid()){
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/editoperations.php?type=singlechoice&id='+id,
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
	
	
$( "#addsinglechoice" ).click(function() {
	  let rval=parseInt($('#noofsinglechoice').val())+parseInt(1);
	  if(rval>=4) $('#removesinglerow').show();
	  let id = 'singleans'+rval
	  let rowmatch =`<div class="form-group" id="${id}">
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" id="sinans${rval}" name="singleanswer" value="${rval}" />
					<i class="input-frame"></i>
				</label>
			</div>
			<div class="col-10">
				<input type="text" class="form-control" name="singlechoiceans[]" id="singlechoiceans${rval}">
			</div>
		</div>	
	</div>`			
	
  $( "#singleanswerchoice" ).append( rowmatch );
  $('#noofsinglechoice').val(rval)
});

$( "#removesinglerow" ).click(function() {
	let cval=parseInt($('#noofsinglechoice').val())
	  let rval=parseInt($('#noofsinglechoice').val())-parseInt(1);
	  if(rval<4) $('#removesinglerow').hide();
	  $( '#singleans'+cval ).remove();
	  $('#noofsinglechoice').val(rval)
});

});

</script>