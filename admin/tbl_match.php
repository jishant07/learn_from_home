<?php
$cols1x = explode('-',$data['cols1']);
$cols2x = explode('-',$data['cols2']);
$answerx = explode('-',$data['answer']);
?> 
<script src="js/validation.js"></script>       
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
			<form class="forms-sample" method='post' id='frmmatch'  autocomplete=off>     
				<input type='hidden' id='noofmatchrows' name='noofmatchrows' value="<?=count($cols1x)?>">
				<input type='hidden' name='evid' id='evid' value="<?=$data['evid']?>">
				<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
				<input type='hidden' id='oldmarks' name='oldmarks' value='<?=$data['marks']?>'>				
			  <div class="form-group">
				<div class="row">
					<div class="col-6">
						<label class="d-inline-block mt-2 mr-2">Marks</label>
						<input type="text" class="form-control d-inline-block wd-80" name='matchmarks' id='matchmarks' maxlength=2 size=2 value="<?=$data['marks']?>" readonly>
					</div>		
				</div>
			  </div>
			  <div class="form-group">
				<label>Match the following</label>
				<input type="text" class="form-control" placeholder="Title" name='qmatch' id='qmatch' value="<?=$data['question']?>">
			  </div>
			  <div class="form-group">
					<label>Discription</label>
					<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=$data['description']?></textarea>
			  </div>
			<div id='matchrows'>	
			  <?php 
				for($i=0; $i<count($cols1x); $i++){
					$q=$cols1x[$i];
					$a=$cols2x[$i];
					$k=$i+1;	
			  ?>
			 <div class="form-group" id='matchrows'>	
				<div class='rowmatch' id='rowpair<?=$k?>'>
					<div class="row">
						<div class="col-6">
							<div class="matchcol">
								<?php if($k==1){?>
								<label class="control-label">Column One</label>
								<?php } ?>
								<input type="text" class="form-control" name='matchrowq[]' id='matchrowq<?=$k?>' onkeyup="setQuestion(this.value,<?=$k?>)" value='<?=$q?>'>
							</div>
						</div><!-- Col -->
						<div class="col-6">
							<div class="matchcol">
								<?php if($k==1){?><label class="control-label">Column Two</label><?php } ?>
								<input type="text" class="form-control" name='matchrowopt[]' id='matchrowopt<?=$k?>' onkeyup="setAnswer(this.value,<?=$k?>)" value='<?=$a?>'>
							</div>
						</div><!-- Col -->		
						
					</div>
					
				</div>
			</div>		
				
			 <?php } ?>	
			</div>	
				
			 
			<div class="form-group">
				<input type='button' id='addmatchrow' class='btn btn-primary mt-2' value='+ Add More Rows'>
				<input type='button' id='removematchrow' class='btn btn-primary mt-2' style='display:none' value='- Remove More Rows'>			
			</div>		
			<div class="form-group mt-4">
				<div id='matchrowans'>
					<div class="row">
					<div class="col-sm-12"><h6 class="card-title mt-4 mb-2">Answer</h6></div>
					
					<div class='col-2 matchcol'>
					<ul id="sortno" class="list-group">
						<?php
						for($i=0; $i<count($cols1x); $i++){
							$k=$i+1;
						?>
						<li class="list-group-item" id="liq<?=$k?>"><?=$k?></li>       
						<?php } ?>
					</ul>
					</div>
					<div class='col-5 matchcol'>
					<!--ul id="sortable3" class='droptruex' style='margin:auto'-->
					<div id="sortable3" class="list-group col">
						<?php
						for($i=0; $i<count($cols1x); $i++){
							$k=$i+1;
						?>
						<div class="list-group-item" id='matchq<?=$k?>'><?=$cols1x[$i]?></div>
						
						<?php } ?>
					</div>
					</div>

					<div id="simple-list" class="col-5 matchcol">			
						<div id="example1" class="list-group col">
						<?php
						for($i=0; $i<count($answerx); $i++){
							$k=$i+1;
						?>
						<div class="list-group-item" id='mans<?=$k?>'><span id='matchrowans_span<?=$k?>'><?=$answerx[$i]?></span><input type=hidden name=matchrowans[] id=matchrowans<?=$k?> value="<?=$answerx[$i]?>"></div>
							<?php } ?>
						</div>			
					</div>
				</div>	
				</div>	
				
			 </div>		
			  <div class="form-group">
				<label>Discription</label>
				<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'><?=stripslashes($data['description'])?></textarea>
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
	$("#frmmatch").on('submit', function(e) {
		e.preventDefault();
		if(fillmatchvalid()){
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/editoperations.php?type=matchans&id='+id,
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
	
	$( "#addmatchrow" ).click(function() {
	  let rval=parseInt($('#noofmatchrows').val())+parseInt(1);
	  if(rval==4) $('#removematchrow').show();
	  let id = 'rowpair'+rval
	   let matrowq = 'matchrowq'+rval; 
	   let matrowans = 'matchrowans'+rval; 
	  let matrowopt = 'matchrowopt'+rval;
	  let matchq = 'matchq'+rval;
	  
	  let rowmatch11 = `<div class="form-group"><div class='rowmatch' id=${id}><div class='matchcol'> Row${rval} <input type='text' name='matchrowq[]' id=${matrowq} onkeyup="setQuestion(this.value,${rval})"></div><div class='matchcol'> Row${rval}   <input type='text' name=matchrowopt[] id=${matrowopt} onkeyup="setAnswer(this.value,${rval})"></div></div></div>`;
	  
	  let rowmatch = `<div class="form-group"><div class='rowmatch' id=${id}>
	  				<div class="row">
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowq[]' id='${matrowq}' onkeyup="setQuestion(this.value,${rval})">
							</div>
						</div><!-- Col -->
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowopt[]' id='${matrowopt}' onkeyup="setAnswer(this.value,${rval})" >
							</div>
						</div><!-- Col -->
						
						
					</div>
	  
	  </div></div>`;
	  
	  let mid = 'rowmatch'+rval;	  
		//let rowans3 =`<li id="${matchq}"></li>`
		let rowans3 =`<div class="list-group-item" id='${matchq}'></div>`
		
		let rxxxowans2 =`<li id="que"><span id='matchrowans_span${rval}'></span><input type='hidden' name="matchrowans[]" id=${matrowans}></li>`
		let rowans2 =`<div class="list-group-item" id='mans${rval}'><span id='matchrowans_span${rval}'></span><input type=hidden name="matchrowans[]" id=${matrowans}></div>`
				
		
		let sortno=`<li class="list-group-item" id='liq${rval}'>${rval}</li>`
  $( "#matchrows" ).append( rowmatch );
  $( "#example1" ).append( rowans2 );
  $( "#sortable3" ).append( rowans3 );
  $( "#sortno" ).append( sortno );
  $('#noofmatchrows').val(rval)
});

$( "#removematchrow" ).click(function() {
	let cval=parseInt($('#noofmatchrows').val())
	  let rval=parseInt($('#noofmatchrows').val())-parseInt(1);
	  if(rval<4) $('#removematchrow').hide();
	  let cc = 'rowpair'+cval
	  $( '#rowpair'+cval ).remove();
	//  $( '#rowmatch'+cval ).remove();
	  $( '#liq'+cval ).remove();
	  $( '#matchq'+cval ).remove();
	  $( '#mans'+cval ).remove();
	  $('#noofmatchrows').val(rval)
});	
});

function setQuestion(val,i){
	// $('#matchq'+i).val(val)
	$('#matchq'+i).html(val)	
 }
function setAnswer(val, i){
 $('#matchrowans'+i).val(val)
 $('#matchrowans_span'+i).html(val)
}

</script>