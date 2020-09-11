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
									 <a href="index.php?action=edit-question&id=<?=$e['id']?>&type=<?=$e['qtype']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$e['id']?>,'<?=$qtype?>')">
                            <i data-feather="x"></i>
                          </button>
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
		  
		  
		  <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
<div id='fillblanksection'>
					<form class="forms-sample" method='post' id='frmfillblank'  autocomplete=off>  
					  <div class="form-group">
						<div class="row">
							<div class="col-6">
								<label class="d-inline-block mt-2 mr-2">Marks</label>
								<input type="text" class="form-control d-inline-block wd-80" name='fillblankanswermarks' id='fillblankanswermarks' maxlength=2 size=2 onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false; ">
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

<div id='matchsection'>
<form class="forms-sample" method='post' id='frmmatch'  autocomplete=off>     
	<input type='hidden' id='noofmatchrows' name='noofmatchrows' value='3'>
				
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='matchmarks' id='matchmarks' maxlength=2 size=2 onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false; ">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Match the following</label>
	<input type="text" class="form-control" placeholder="Title" name='qmatch' id='qmatch'>
  </div>
<div id='matchrows'>	
  <div class="form-group" id='matchrows'>	
	<div class='rowmatch' id='rowpair1'>
		<div class='matchcol'>Row1 <input type='text' name='matchrowq[]' id='matchrowq1' onkeyup="setQuestion(this.value,1)"></div>
		<div class='matchcol'>Row1 <input type='text' name='matchrowopt[]' id='matchrowopt1' onkeyup="setAnswer(this.value,1)" ></div>
	</div>
 </div>	
	<div class="form-group" id='matchrows'>			
	<div class='rowmatch' id='rowpair2'>
		<div class='matchcol'>Row2 <input type='text' name='matchrowq[]' id='matchrowq2' onkeyup="setQuestion(this.value,2)" > </div>
		<div class='matchcol'>Row2 <input type='text' name='matchrowopt[]' id='matchrowopt2' onkeyup="setAnswer(this.value,2)" ></div>
	</div>
	</div>
<div class="form-group" id='matchrows'>	
	<div class='rowmatch'  id='rowpair3'>
		<div class='matchcol'>Row3 <input type='text' name='matchrowq[]' id='matchrowq3' onkeyup="setQuestion(this.value,3)"></div>
		<div class='matchcol'>Row3 <input type='text' name='matchrowopt[]' id='matchrowopt3' onkeyup="setAnswer(this.value,3)" ></div>
	</div>			
  </div>	
  </div>	
	
  <div class="form-group">
				<input type='button' id='addmatchrow' class='btn btn-primary btn-icon mt-2' value='+ Add More Rows'>
				<input type='button' id='removematchrow' class='btn btn-primary btn-icon mt-2' style='display:none' value='- Remove More Rows'>
		
   </div>		
  <div class="form-group">
	<div id='matchrowans'>
		<div class='rowmatch' style="clear:left;padding-top:30px;"> 
			Answer
		</div>
		<div class='matchcol' style='width:20px;float:left'>
		<ul id="sortno">
			<li id="liq1">1</li>       
			<li id="liq2">2</li>       
			<li id="liq3">3</li>
		</ul>
		</div>
		<div class='matchcol'>
		<ul id="sortable3" class='droptruex' style='margin:auto'>
			<li id="matchq1"></li>       
			<li id="matchq2"></li>       
			<li id="matchq3"></li>				
		</ul>
		</div>

		<div id="simple-list" class="row">			
			<div id="example1" class="list-group col">
				<div class="list-group-item" id='mans1'><span id='matchrowans_span1'></span><input type=hidden name=matchrowans[] id=matchrowans1></div>
				<div class="list-group-item" id='mans2'><span id='matchrowans_span2'></span><input type=hidden name=matchrowans[] id=matchrowans2></div>
				<div class="list-group-item" id='mans3'><span id='matchrowans_span3'></span><input type=hidden name=matchrowans[] id=matchrowans3></div>
			</div>			
		</div>
	</div>	
	
 </div>		
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
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
	
<div id='singlechoicesection'>
<form class="forms-sample" method='post' id='frmsinglechoice'  autocomplete=off> 
<input type='hidden' id='noofsinglechoice' name='noofsinglechoice' value='3'>                     
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='singlemarks' id='singlemarks' maxlength=2 size=2 onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question</label>
	<input type="text" class="form-control" placeholder="Title" name='singlechoicequestion' id='singlechoicequestion'>
  </div>
	
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
  </div>
	<div id='singleanswerchoice'>
	<div class="form-group" id='singleans1'>
		<div class='left anspadd'>ans1</div> <div class='left'><input type='text' name='singlechoiceans[]' id='singlechoiceans1'> </div><div class='left'><input type="radio" id="sinans1" name="singleanswer" value="1" checked></div>		
	</div>
	<div class="form-group" id='singleans2'>
		<div class='left anspadd'>ans2</div> <div class='left'><input type='text' name='singlechoiceans[]' id='singlechoiceans1'> </div><div class='left'><input type="radio" id="sinans2" name="singleanswer" value="2" checked></div>		
	</div>
	<div class="form-group" id='singleans3'>
		<div class='left anspadd'>ans3</div> <div class='left'><input type='text' name='singlechoiceans[]' id='singlechoiceans3'> </div><div class='left'><input type="radio" id="sinans3" name="singleanswer" value="3" checked></div>		
	</div>	
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
  <hr>
  <div class="form-check form-check-flat form-check-primary">
	<label class="form-check-label">
	  <input type="checkbox" class="form-check-input" id='uploadflag' name='uploadflag' value='1'>
	  Student can upload document
	</label>
  </div>
  
  
  <button type="submit" class="btn btn-success mr-2 mt-2">Save</button>
  <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
	  <i data-feather="x"></i>
  </button>
</form>
</div>

<div id='multiplechoicesection'>
<form class="forms-sample" method='post' id='frmmultiplechoice'  autocomplete=off>  
  <input type='hidden' id='noofmultiplechoice' name='noofmultiplechoice' value='3'>					
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='multiplemarks' id='multiplemarks' maxlength=2 size=2  onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question</label>
	<input type="text" class="form-control" placeholder="Title" name='multiplechoicequestion' id='multiplechoicequestion'>
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
  </div>

<div id='multipleanswerchoice'>
	<div class='form-group' id='multipleans1'> 
		<div class='left anspadd'>ans1</div> <div class='left'><input type='text' name='muloption[]' id='muloption1'> </div><div class='left'><input type="checkbox" id="ans1" name="ans[]" value="1"></div>				
	</div>
	<div class='form-group' id='multipleans2'> 
		<div class='left anspadd'>ans2</div> <div class='left'><input type='text' name='muloption[]' id='muloption2'> </div><div class='left'><input type="checkbox" id="ans2" name="ans[]" value="2"></div>				
	</div>
	<div class='form-group' id='multipleans3'> 
		<div class='left anspadd'>ans3</div> <div class='left'><input type='text' name='muloption[]' id='muloption3'> </div><div class='left'><input type="checkbox" id="ans3" name="ans[]" value="3"></div>				
	</div>
</div>
<div class='form-group'>
	<div class='matchcol'><input type='button' id='addmultiplechoice' class='btn btn-success' value='+ Add More Rows'>
	<input type='button' id='removemultiplerow' class='btn btn-success' style='display:none' value='- Remove More Rows'>
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
  <hr>
  <div class="form-check form-check-flat form-check-primary">
	<label class="form-check-label">
	  <input type="checkbox" class="form-check-input" id='uploadflag' name='uploadflag' value='1'>
	  Student can upload document
	</label>
  </div>
  
  
  <button type="submit" class="btn btn-success mr-2 mt-2" id='createmultiplechoicequestion'>Save</button>
  <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
	  <i data-feather="x"></i>
  </button>
</form>
</div>
	
<div id='freetextsection'>
<form class="forms-sample" method='post' id='frmfreetext'  autocomplete=off>
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='freetextmarks' id='freetextmarks' maxlength=2 size=2 onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question</label>
	<input type="text" class="form-control" placeholder="Title" name='freetextquestion' id='freetextquestion'>
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" name='txtanswer' id='txtanswer'></textarea>
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
  <hr>
  <div class="form-check form-check-flat form-check-primary">
	<label class="form-check-label">
	  <input type="checkbox" class="form-check-input" id='uploadflag' name='uploadflag' value='1'>
	  Student can upload document
	</label>
  </div>
  
  
  <button type="submit" class="btn btn-success mr-2 mt-2" id='createfreetextquestion'>Save</button>
  <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
	  <i data-feather="x"></i>
  </button>
</form>	
</div>
	
<div id='uploadimagesection'>		
<form class="forms-sample" method='post' id='frmuploadmedia'  autocomplete=off>  
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks</label>
			<input type="text" class="form-control d-inline-block wd-80" name='picsmarks' id='picsmarks' maxlength=2 size=2 onkeypress="if( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Submit pic or doc</label>
	<input type="text" class="form-control" placeholder="Title" name='uoloadimagequestion' id='uoloadimagequestion'>
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='discription' name='discription'></textarea>
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
  <hr>
  
  <button type="submit" class="btn btn-success mr-2 mt-2" id='createpicquestion'>Save</button>
  <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
	  <i data-feather="x"></i>
  </button>
</form>
</div>	
                      
                  </div> 
                </div>
              </div>
            </div>
            
		  <div class="row">
              <div class="col-md-4"></div>
             <div class="col-md-8 col-12">
                <div class="form-group  d-inline-block float-right " >                 
					<select name='selectarea' id='selectarea' class='form-control mb-3 '>
						<option value='0' >Select Question Type</option>
						<option value='fillblanksection' selected='selected'>fill in the blanks</option>
						<option value='matchsection'>match the following</option>
						<option value='singlechoicesection'>select objective single</option>
						<option value='multiplechoicesection'>select objective multiple</option>					
						<option value='freetextsection'>free text answer</option>
						<option value='uploadimagesection'>upload image or doc</option>						
					</select>	
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
	  
	  let rowmatch = `<div class="form-group"><div class='rowmatch' id=${id}><div class='matchcol'> Row${rval} <input type='text' name='matchrowq[]' id=${matrowq} onkeyup="setQuestion(this.value,${rval})"></div><div class='matchcol'> Row${rval}   <input type='text' name=${matrowopt} id=${matrowopt} onkeyup="setAnswer(this.value,${rval})"></div></div></div>`;
	  let mid = 'rowmatch'+rval;	  
		let rowans3 =`<li id="${matchq}"></li>`
		let rxxxowans2 =`<li id="que"><span id='matchrowans_span${rval}'></span><input type='hidden' name="matchrowans[]" id=${matrowans}></li>`
		let rowans2 =`<div class="list-group-item" id='mans${rval}'><span id='matchrowans_span${rval}'></span><input type=hidden name="matchrowans[]" id=${matrowans}></div>`
				
		let sortno=`<li id='liq${rval}'>${rval}</li>`
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
	  let rowmatch = `<div class='clrboth m10' id='${id}'> 
					<div class='left anspadd'>ans${rval}</div> <div class='left'><input type='text' name='singlechoiceans[]' id='singlechoiceans${rval}'> </div><div class='left'><input type="radio" id="sinans${rval}" name="singleanswer" value="${rval}"></div>				
				</div>`;
	  
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