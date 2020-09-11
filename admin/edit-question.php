        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($exam['class'])?></a></li>
              <li class="breadcrumb-item"><a href="index.php?action=exams&class=<?=$exam['class']?>&subject=<?=$exam['subject']?>">Exams</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Exam</li>
            </ol>
          </nav>          
        </div>
		<input type='hidden' name='evid' id='evid' value="<?=$exam['id']?>">
		<input type='hidden' name='class' id='class' value="<?=$exam['class']?>">
		<input type='hidden' name='subject' id='subject' value="<?=$exam['subject']?>">

        <div class="row alt-success success" id='result'></div>
        <div class="row">
          <div class="col-lg-5 mb-3">
            <div class="card">
              <div class="card-body">
               <div id=''>
					<form class="forms-sample" method='post' id='frmfillblank'  autocomplete=off>  
					  <div class="form-group">
						<div class="row">
							<div class="col-6">
								<label class="d-inline-block mt-2 mr-2">Marks</label>
								<input type="text" class="form-control d-inline-block wd-80" name='fillblankanswermarks' id='fillblankanswermarks' maxlength=2 size=2 onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false; " >
							</div>		
						</div>
					  </div>
					  <div class="form-group">
						<label>Fill in the blanks</label>
						<input type="text" class="form-control" placeholder="Title" name='fillblankque' id='fillblankque'>
					  </div>
						
					  <div class="form-group" id='fillblankansdiv'>
						
					  </div>		
					  <div class="form-group">
						<label>Discription</label>
						<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
					  </div>
					  <div class="form-group">
						<label>Upload Referance Document</label>
						<input type="file" name="refdoc" id="refdoc" class="file-upload-default">
						<div class="input-group col-xs-12">
						  <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Document">
						  <span class="input-group-append">
							<button class="file-upload-browse btn btn-primary" type="button">Upload</button>
						  </span>
						</div>
					  </div>
					  <hr>
					  <div class="form-check form-check-flat form-check-primary">
						<label class="form-check-label">
						  <input type="checkbox" class="form-check-input" name='uploadflag' id='uploadflag' value='1'>
						  Student can upload document
						</label>
					  </div>
					  
					  
					  <button type="submit" class="btn btn-success mr-2 mt-2" name='createfillbankquestion' id='createfillbankquestion'>Save</button>
					  <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
						  <i data-feather="x"></i>
					  </button>
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
	let evid = $('#evid').val();
	$("#frmfillblank").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=fillblank&class='+dclass+'&subject='+sub,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					$('#tot_marks').val(obj.tot_marks)	
				}
				$("#frmfillblank").trigger("reset");
				$("#selectarea").val(0);
				$("#fillblanksection").hide();
				
				//hideall();	
				
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})
	$("#fillblankque").focusout(function(){
	  $( "#fillblankansdiv" ).empty();
    let que = $("#fillblankque").val();
	let blanks = que.split("...");
	let blength = blanks.length-1
	for (let i=1; i<=blength;i++){
		let fillans =`Answer ${i}: <input type='text' name='fillblankanswer[]' id='fillblankanswer${i}' class="form-control"/>`
		$( "#fillblankansdiv" ).append( fillans );
	}
	$('#noofblanks').val(blength);
	if(blength>1){ $('#assignmdiv').show();} else{$('#assignmdiv').hide();}
  });
});
</script>