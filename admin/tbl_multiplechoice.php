<script src="js/validation.js"></script>
<?php
$options = explode('-',$data['options']);
$answer = explode('-',$data['answer']);
?>        
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
					<form class="forms-sample" method='post' id='frmmultiplechoice'  autocomplete=off>  
					<input type='hidden' id='noofmultiplechoice' name='noofmultiplechoice' value="<?=count($options)?>">
					<input type='hidden' name='evid' id='evid' value="<?=$data['evid']?>">
					<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
					<input type='hidden' id='oldmarks' name='oldmarks' value='<?=$data['marks']?>'>	
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='multiplemarks' id='multiplemarks' maxlength=2 size=2  onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?=$data['marks']?>">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question</label>
	<input type="text" class="form-control" placeholder="Title" name='multiplechoicequestion' id='multiplechoicequestion' value="<?=$data['question']?>">
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=stripslashes($data['description'])?></textarea>
  </div>

<div id='multipleanswerchoice'>
	<?php
	for($i=0;$i<count($options); $i++){
		$k=$i+1;
		if(in_array($k,$answer)) $chk='checked'; else $chk='';
	?>
	<div class='form-group' id='multipleans<?=$k?>'> 
		<div class='left anspadd'>ans<?=$k?></div> <div class='left'><input type='text' name='muloption[]' id='muloption<?=$k?>' value="<?=$options[$i]?>"> </div><div class='left'><input type="checkbox" id="ans<?=$k?>" name="ans[]" value="<?=$k?>" <?=$chk?>></div>
	</div>
	<?php } ?>
</div>
<div class='form-group'>
	<div class='matchcol'><input type='button' id='addmultiplechoice' class='btn btn-success' value='+ Add More Rows'>
	<input type='button' id='removemultiplerow' class='btn btn-success' style='display:none' value='- Remove More Rows'>
	</div>				
</div>  <div class="form-group">
						<label>Upload Referance Document</label>
						<input type="file" name="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
						  <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Document">
						  <span class="input-group-append">
							<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
						  </span>
						</div>
					  </div><?php if($data['referdoc']!=''){
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
	$("#frmmultiplechoice").on('submit', function(e) {
		e.preventDefault();
		if(multipleobjectivevalid()){
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/editoperations.php?type=multiplechoice&id='+id,
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
	
	$( "#addmultiplechoice" ).click(function() {
	  let rval=parseInt($('#noofmultiplechoice').val())+parseInt(1);
	  if(rval>=4) $('#removemultiplerow').show();
	  let id = 'multipleans'+rval
	  let rowmatch = `<div class='clrboth m10' id='${id}'> 
					<div class='left anspadd'>ans${rval}</div> <div class='left'><input type='text' name='muloption[]' id='muloption${rval}'> </div><div class='left'><input type="checkbox" id="ans${rval}" name="ans[]" value="${rval}"></div>				
				</div>`;
	  
  $( "#multipleanswerchoice" ).append( rowmatch );
  $('#noofmultiplechoice').val(rval)
});

$( "#removemultiplerow" ).click(function() {
	let cval=parseInt($('#noofmultiplechoice').val())
	  let rval=parseInt($('#noofmultiplechoice').val())-parseInt(1);
	  if(rval<4) $('#removemultiplerow').hide();
	  $( '#multipleans'+cval ).remove();
	  $('#noofmultiplechoice').val(rval)
});

});

</script>