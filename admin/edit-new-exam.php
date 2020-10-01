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
                <form class="forms-sample" id='evaluationfrm' name='evaluationfrm' method='post'  autocomplete=off>
				  <div class="form-group">
					<label>Schedule Date for Exam</label>
					 <div class="input-group date datepicker" id="datePickerExample">
                      <input type="text" class="form-control" name='sdate' id='sdate' value="<?=date('Y-m-d',strtotime($exam['opendate']))?>"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="row">
                          <div class="col-6">
                              <label>Start Time</label>
                              <div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
                                  <input type="text" id='starttime' name='starttime' class="form-control datetimepicker-input" data-target="#datetimepickerExample" value="<?=date('H:i A',strtotime($exam['opendate']))?>"/>
                                  <div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i data-feather="clock"></i></div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-6">
                              <label>End Time</label>
                              <div class="input-group date timepicker" id="datetimepickerExample2" data-target-input="nearest">
                                  <input type="text" id='endtime' name='endtime' class="form-control datetimepicker-input" data-target="#datetimepickerExample2" value="<?=date('Y-m-d',strtotime($exam['closedate']))?>"/>
                                  <div class="input-group-append" data-target="#datetimepickerExample2" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i data-feather="clock"></i></div>
                                  </div>
                              </div>
                          </div>
                      </div>
  
                  </div>
                  <div class="form-group">
                      <div class="row">
                          <div class="col-6">
                              <label>Total Marks</label>
                              <input type="text" class="form-control" readonly id='tot_marks' name='tot_marks' value="<?=$exam['totmarks']?>">
                          </div>                          
                      </div>  
                  </div>        
					<?php ///if(isset($_SESSION['evid'])) $qtcls='hide'; else $qtcls='show'?>			
					<button type="submit" class="btn btn-primary mr-2 mt-2" id='evsave'>Save</button>
				</form>          
                
              </div> 
            </div>
          </div>
          <div class="col-lg-7">
            <!--question box-->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
					<div class="table-responsive mt-5">
						<table id="dataTableExample" class="table table-hover mb-0">
							<thead>
							  <tr>
								<th class="pt-0">Date</th>
								<th class="pt-0">Section</th>
								<th class="pt-0">Marks</th>
								<th class="pt-0">Action</th>
								
							  </tr>
							</thead>
							<tbody>
								<?php for($i=0; $i<count($examdetails); $i++){
									$e = & $examdetails[$i];
									$qtype=$e['qtype'];
									?>
								<tr>
									<td>
									<?=$e['question']?>
									</td>
									<td>
									<?=getSectionName($e['section'])?>
									</td>
									<td>
									<?=$e['marks']?>
									</td>
									<td>
									 <a href="index.php?action=edit-question&id=<?=$e['id']?>&type=<?=$e['qtype']?>&evid=<?=$_GET['id']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>
                          
                          <!--button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$e['id']?>,'<?=$qtype?>')">
                            <i data-feather="x"></i>
                          </button-->
									</td>
								</tr>
								<?php } ?>
							</tbody>                
						</table>                
					</div>
				  </div>
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
function reset(){ 
	hideall();
	$('#selectarea').val('0');
	$('#noofsinglechoice').val('3');
	$('#noofmatchrows').val('3');
	$('#noofmultiplechoice').val('3');
 }

$( "#selectarea" ).change(function() {
	hideall()
	let divsec = $('#selectarea').val();
	$('#'+divsec).show();
});

 $( document ).ready(function() {
	let evid = $('#evid').val();
	if(evid=='') {$('#selectarea').hide();$('#evsave').show();}
	else{ $('#selectarea').show();$('#evsave').hide();
	}	
		//fill blank
	$("#frmfillblank").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=fillblank&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				location.reload();
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					//location.reload();
					//let marks=parseInt($('#tot_marks').val());
					//$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
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

	//fill blank
	$("#frmmatch").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=matchans&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					let marks=parseInt($('#tot_marks').val());
					$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
				}
				$("#frmmatch").trigger("reset");
				$("#selectarea").val(0);
				$("#matchsection").hide();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})
	$("#frmsinglechoice").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=singlechoice&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					let marks=parseInt($('#tot_marks').val());
					$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
				}
				$("#frmsinglechoice").trigger("reset");
				$("#selectarea").val(0);
				$("#singlechoicesection").hide();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})

	$("#frmmultiplechoice").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=multiplechoice&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					let marks=parseInt($('#tot_marks').val());
					$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
				}
				$("#frmmultiplechoice").trigger("reset");
				$("#selectarea").val(0);
				$("#multiplechoicesection").hide();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})

	$("#frmfreetext").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=freetext&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					let marks=parseInt($('#tot_marks').val());
					$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
				}
				$("#frmfreetext").trigger("reset");
				$("#selectarea").val(0);
				$("#freetextsection").hide();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})
	$("#frmuploadmedia").on('submit', function(e) {
		e.preventDefault();
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'ajax/operations.php?type=uploadimage&class='+dclass+'&subject='+sub+'&evid='+evid,
			data: formData,
			type: 'POST',
			success: function(data) {
				var obj = JSON.parse(data);
				if(obj.tot_marks!==''){
					let marks=parseInt($('#tot_marks').val());
					$('#tot_marks').val(marks+parseInt(obj.tot_marks))	
				}
				$("#frmuploadmedia").trigger("reset");
				$("#selectarea").val(0);
				$("#uploadimagesection").hide();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		///} 
	})

	reset();
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


$( "#addmatchrow" ).click(function() {
	  let rval=parseInt($('#noofmatchrows').val())+parseInt(1);
	  if(rval==4) $('#removematchrow').show();
	  let id = 'rowpair'+rval
	   let matrowq = 'matchrowq'+rval; 
	   let matrowans = 'matchrowans'+rval; 
	  let matrowopt = 'matchrowopt'+rval;
	  let matchq = 'matchq'+rval;
	  
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
		/*let rxxxowans2 =`<li id="que" ><span id='matchrowans_span${rval}'></span><input type='hidden' name="matchrowans[]" id=${matrowans}></li>`*/
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

 $( "#addsinglechoice" ).click(function() {
	  let rval=parseInt($('#noofsinglechoice').val())+parseInt(1);
	  if(rval==4) $('#removesinglerow').show();
	  let id = 'singleans'+rval
	
	let rowmatch =`<div class="form-group" id="${id}">
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" id="sinans${rval}" name="singleanswer" value="${rval}" />
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


//multiple choice
 $( "#addmultiplechoice" ).click(function() {
	  let rval=parseInt($('#noofmultiplechoice').val())+parseInt(1);
	  if(rval==4) $('#removemultiplerow').show();
	  let id = 'multipleans'+rval
	  let rowmatch = `<div class='form-group m10' id='${id}'> 
					<div class="row">
						<div class="col-1"></div>
						<div class="col-1 form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" id="ans${rval}" name="ans[]" value="${rval}">
							</label>
						</div>
						<div class="col-10">
							<input type='text' class="form-control" name='muloption[]' id='muloption${rval}'>
						</div>
					</div>				
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
 
function hideall(){	
	$('#fillblanksection').hide();
	$('#matchsection').hide();
	$('#singlechoicesection').hide();
	$('#multiplechoicesection').hide();
	$('#freetextsection').hide();
	$('#uploadimagesection').hide();
	$('#noofmatchrows').val(3);
 }
 function setQuestion(val,i){
	// $('#matchq'+i).val(val)
	$('#matchq'+i).html(val)	
 }
function setAnswer(val, i){
 $('#matchrowans'+i).val(val)
 $('#matchrowans_span'+i).html(val)
}

function deleteRecord(id,qtype){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=delete-exam-question&id='+id+'&type='+qtype,
		success: function(data) {
			location.reload();			
		},
		cache: false,
		contentType: false,
		processData: false
	});
	}
}

</script>