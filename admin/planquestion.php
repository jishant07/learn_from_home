<?php
//echo'<pre>',print_r($_SESSION);
$secid=isset($_GET['section'])?$_GET['section']:'';
$_SESSION['exam']['getsecid']=$secid;

if($secid!='') {
	$sql = "select q.*,s.name,s.marks as totmarks from tbl_questionsections q,tbl_section s where section='$secid' and s.id=q.section";
	$res = mysqli_query($conn,$sql);
	$arr=array();
	$mqarr=array();
	$i=0;
	while($row=mysqli_fetch_array($res)){
		$marr['marks'][$i]=$row['marks'];
		$qarr['questions'][$i]=$row['questions'];
		$arr[$i]['marks']=$row['marks'];
		$arr[$i]['questions']=$row['questions'];
		$arr[$i]['name']=$row['name'];
		$arr[$i]['totmarks']=$row['totmarks'];
		$i++;		
	}
} else {
	$marr['marks']=array();
	$qarr['questions']=array();
}
//echo'<pre>',print_r($_SESSION);
//$evid=$_GET['evid'];
$evid=$_SESSION['exam']['evid']; 

$sqlev="select class,subject,totsections from tbl_evolution where id='$evid'";
$resev = mysqli_query($conn,$sqlev);
$rowev=mysqli_fetch_array($resev);
$evclass = $rowev['class'];
$evsubject = $rowev['subject'];

?>
<script src="js/validationnew.js"></script>

 <?php if($secid!=''){?>
<div class='alertMsg'>
<div> Section <?=$arr[0]['name']?></div>
<div> Total Marks <?=$arr[0]['totmarks']?></div>
<?php 
	$tot_ques_persec=0;
	for($i=0;$i<count($arr);$i++){
		$tot_ques_persec=$tot_ques_persec+$arr[$i]['questions'];
	?>
	<div>Questions type <?=$arr[$i]['marks']?> marks <span id="span<?=$arr[$i]['marks']?>"><?php if(isset($_SESSION['exam']['totq'.$arr[$i]['marks'].'-'.$secid])) echo $_SESSION['exam']['totq'.$arr[$i]['marks'].'-'.$secid]?></span>/<?=$arr[$i]['questions']?></div>
<?php } ?>
</div>
<?php
if(!in_array($secid,$_SESSION['exam']['section_complete'])){
	if($tot_ques_persec==$_SESSION['exam'][$secid]['qcount'])
	$_SESSION['exam']['section_complete'][]=$secid;
}
$totseccomplete = count($_SESSION['exam']['section_complete']);

} 


?>
<input type='hidden' id='evid' value='<?=$evid?>'>
<input type='hidden' id='chksecid' value='<?=$secid?>'>
<input type='hidden' id='tot_ques_persec' value='<?=$tot_ques_persec?>'>
<input type='hidden' id='tot_ques_persec_created' value="<?php if(isset($_SESSION['exam']['qcount'])) echo $_SESSION['exam']['qcount']; else echo '0'?>">
<!--div class='dtop'><?php if($secid!=''){?><div class='left'>Section : <?=$arr[0]['name']?></div><?php } ?>
<div class='left' style='padding-left:30px;'>Total Questions : <div id='totquestion' class='right'><?php if(isset($_SESSION['exam'][$secid]['qcount'])) echo $_SESSION['exam'][$secid]['qcount']; else echo '0'?></div></div>
</div-->

<div class="row">
	<div class="col-lg-5 mb-3">
	<div class="card">
	  <div class="card-body">
		
		  <div class="form-group">
			<label>Section</label>
			 <div class="input-group ">
			  <?=$arr[0]['name']?>
			</div>
		  </div>
		  <div class="form-group">
			  <div class="row">
				  <div class="col-6">
					  <label>Total Questions</label>
					  <div class="input-group" data-target-input="nearest" id='totquestion'>
						  <?php if(isset($_SESSION['exam']['qcount'])) echo $_SESSION['exam']['qcount']; else echo '0'?>
						  
					  </div>
				  </div>
				 
			  </div>

		  </div>
		 
		      
		
	  </div> 
	</div>
	</div>
	<div class="col-lg-7">
            <!--question box-->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
					<div id='fillblanksection'>
					<form class="forms-sample" method='post' id='frmfillblank'  autocomplete=off>  
					<input type='hidden' id='noofblanks' name='noofblanks' value='0'>
					  <div class="form-group">
						<div class="row">
							<div class="col-6">
								<label class="d-inline-block mt-2 mr-2">Marks*</label>
								<input type="text" class="form-control d-inline-block wd-80" name='fillblankanswermarks' id='fillblankanswermarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false; ">
							</div>		
						</div>
					  </div>
					  <div class="form-group">
						<label>Fill in the blanks*</label>
						<input type="text" class="form-control" placeholder="Title" name='fillblankque' id='fillblankque'>[please type ... (3 dots) for each blank]
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
					<label class="d-inline-block mt-2 mr-2">Marks*</label>
					<input type="text" class="form-control d-inline-block wd-80" name='matchmarks' id='matchmarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false; ">
				</div>		
			</div>
		</div>
		<div class="form-group">
			<label>Match the following*</label>
			<input type="text" class="form-control" placeholder="Title" name='qmatch' id='qmatch'>
		</div>
				
		<div class="form-group">
			<label>Discription</label>
			<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
		</div>
		<div id='matchrows'>	
			<div class="form-group" id='matchrows'>	
				<div class='rowmatch' id='rowpair1'>
					<div class="row">
						<div class="col-6">
							<div class="matchcol">
								<label class="control-label">Column One*</label>
								<input type="text" class="form-control" name='matchrowq[]' id='matchrowq1' onkeyup="setQuestion(this.value,1)">
							</div>
						</div><!-- Col -->
						<div class="col-6">
							<div class="matchcol">
								<label class="control-label">Column Two*</label>
								<input type="text" class="form-control" name='matchrowopt[]' id='matchrowopt1' onkeyup="setAnswer(this.value,1)" >
							</div>
						</div><!-- Col -->
						
						
					</div>
					
				</div>
			</div>	
			<div class="form-group" id='matchrows'>			
				<div class='rowmatch' id='rowpair2'>
					<div class="row">
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowq[]' id='matchrowq2' onkeyup="setQuestion(this.value,2)">
							</div>
						</div><!-- Col -->
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowopt[]' id='matchrowopt2' onkeyup="setAnswer(this.value,2)" >
							</div>
						</div><!-- Col -->
						
						
					</div>
				</div>
			</div>
			<div class="form-group" id='matchrows'>	
				<div class='rowmatch'  id='rowpair3'>
					<div class="row">
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowq[]' id='matchrowq3' onkeyup="setQuestion(this.value,3)">
							</div>
						</div><!-- Col -->
						<div class="col-6">
							<div class="matchcol">
								<input type="text" class="form-control" name='matchrowopt[]' id='matchrowopt3' onkeyup="setAnswer(this.value,3)" >
							</div>
						</div><!-- Col -->
						
						
					</div>
				</div>			
			</div>	
		</div>	
		
		<div class="form-group">
					<input type='button' id='addmatchrow' class='btn btn-primary mt-2' value='+ Add More Rows'>
					<input type='button' id='removematchrow' class='btn btn-primary mt-2' style='display:none' value='- Remove More Rows'>
			
		</div>		
		<div class="form-group mt-4">
			<div id='matchrowans'>
				
				
				<div class="row">
					<div class="col-sm-12"><h6 class="card-title mt-4 mb-2">Answer</h6></div>
					<div class="col-2 matchcol">
						<ul id="sortno" class="list-group">
							<li class="list-group-item" id="liq1">1</li>       
							<li class="list-group-item" id="liq2">2</li>       
							<li class="list-group-item" id="liq3">3</li>
						</ul>
					</div>
					<div class='col-5 matchcol'>
						<!--ul id="sortable3" class='droptruex' style='margin:auto'-->
						<div id="sortable3" class="list-group col">
						<div class="list-group-item" id='matchq1'></div>
						<div class="list-group-item" id='matchq2'></div>
						<div class="list-group-item" id='matchq3'></div>
							
							<!--li id="msatchq1"></li>       
							<li id="matchq2"></li>       
							<li id="matchq3"></li-->				
						</div>
					</div>
					<div id="simple-list" class="col-5 matchcol">
						<div id="example1" class="list-group col">
							<div class="list-group-item" id='mans1'><span id='matchrowans_span1'></span><input type=hidden name=matchrowans[] id=matchrowans1></div>
							<div class="list-group-item" id='mans2'><span id='matchrowans_span2'></span><input type=hidden name=matchrowans[] id=matchrowans2></div>
							<div class="list-group-item" id='mans3'><span id='matchrowans_span3'></span><input type=hidden name=matchrowans[] id=matchrowans3></div>
						</div>	
					</div>					
				</div>
				
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
			<input type="checkbox" class="form-check-input" name='uploadflag' id='uploadflag' value='1'>
			Student can upload document
			</label>
		</div>
	
	
		<button type="submit" class="btn btn-success mr-2 mt-2" name='createfillbankquestion' id='createfillbankquestion'>Save</button>
		
	</form>
</div>
	
<div id='singlechoicesection'>
<form class="forms-sample" method='post' id='frmsinglechoice'  autocomplete=off> 
<input type='hidden' id='noofsinglechoice' name='noofsinglechoice' value='3'>                     
  <div class="form-group">
	<div class="row">
		<div class="col-6">
			<label class="d-inline-block mt-2 mr-2">Marks*</label>
			<input type="text" class="form-control d-inline-block wd-80" name='singlemarks' id='singlemarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question*</label>
	<input type="text" class="form-control" placeholder="Title" name='singlechoicequestion' id='singlechoicequestion'>
  </div>
	
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
  </div>
	<div id='singleanswerchoice'>
	<div class="row mt-3">
		<div class="col-12">
			<label>Select Right Answer*</label>
		</div>
	</div>
	<div class="form-group" id='singleans1'>
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" id="sinans1" name="singleanswer" value="1">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='singlechoiceans[]' id='singlechoiceans1'>
			</div>
		</div>
	</div>
	<div class="form-group" id='singleans2'>
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" id="sinans2" name="singleanswer" value="2">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='singlechoiceans[]' id='singlechoiceans2'>
			</div>
		</div>	
	</div>
	<div class="form-group" id='singleans3'>
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" id="sinans3" name="singleanswer" value="3">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='singlechoiceans[]' id='singlechoiceans3'>
			</div>
		</div>	
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
			<label class="d-inline-block mt-2 mr-2">Marks*</label>
			<input type="text" class="form-control d-inline-block wd-80" name='multiplemarks' id='multiplemarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question*</label>
	<input type="text" class="form-control" placeholder="Title" name='multiplechoicequestion' id='multiplechoicequestion'>
  </div>
  <div class="form-group">
	<label>Discription</label>
	<textarea class="form-control" placeholder="Discription" rows="5" id='description' name='description'></textarea>
  </div>

<div id='multipleanswerchoice'>

	<div class="row mt-3">
		<div class="col-12">
			<label>Check only Right Answers*</label>
		</div>
	</div>
	<div class='form-group' id='multipleans1'> 
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input" id="ans1" name="ans[]" value="1">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='muloption[]' id='muloption1'>
			</div>
		</div>				
	</div>
	<div class='form-group' id='multipleans2'> 
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input" id="ans2" name="ans[]" value="2">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='muloption[]' id='muloption2'>
			</div>
		</div>			
	</div>
	<div class='form-group' id='multipleans3'> 
		<div class="row">
			<div class="col-1"></div>
			<div class="col-1 form-check">
				<label class="form-check-label">
					<input type="checkbox" class="form-check-input" id="ans3" name="ans[]" value="3">
				</label>
			</div>
			<div class="col-10">
				<input type='text' class="form-control" name='muloption[]' id='muloption3'>
			</div>
		</div>				
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
			<label class="d-inline-block mt-2 mr-2">Marks*</label>
			<input type="text" class="form-control d-inline-block wd-80" name='freetextmarks' id='freetextmarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question*</label>
	<input type="text" class="form-control" placeholder="Title" name='freetextquestion' id='freetextquestion'>
  </div>
  <div class="form-group">
	<label>Discription*</label>
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
			<label class="d-inline-block mt-2 mr-2">Marks*</label>
			<input type="text" class="form-control d-inline-block wd-80" name='picsmarks' id='picsmarks' maxlength=2 size=2 onkeyup="return checkMarks(this.value,this.id)" onkeypress="if( isNaN( String.fromCharCode(event.keyCode) )) return false;">
		</div>		
	</div>
  </div>
  <div class="form-group">
	<label>Question*</label>
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
            <!--end question box-->
            <div class="row" id='questiontypediv'>
              <div class="col-md-4"></div>
             <div class="col-md-8 col-12">
                <!--button type="submit" class="btn btn-warning ml-2 d-inline-block float-right">Add Question</button-->
                <?php if(!isset($_SESSION['evid'])) $qtcls='hide'; else $qtcls='show'?>
				<div class="form-group  d-inline-block float-right " >                 
					<select name='selectarea' id='selectarea' class='form-control mb-3 '>
						<option value='0'>Select Question Type</option>
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
			<?php 
			//echo $_SESSION['exam']['totsections'],'>',count($_SESSION['exam']['section_complete']);
			
			if($_SESSION['exam']['totsections']>count($_SESSION['exam']['section_complete']))$nextbtns='display:block'; else $nextbtns='display:none';
				?>
			<button type="submit" class="btn btn-success mr-2 mt-2" id='nextsecbtn' onclick="window.location='index.php?action=plansectionstab'" style="<?=$nextbtns?>">Next Section</button>
			<?php// } else {?>
			<button type="submit" class="btn btn-success mr-2 mt-2" id='nextexam' onclick="window.location='index.php?action=examsnew&class=<?=$evclass?>&subject=<?=$evsubject?>'">Go tO Exams</button>
			<?php //} ?>		  
				
          </div>
		  
	</div>	 
         <script src="assets/Sortable.js"></script>

	
	<script src="assets/app.js"></script>  

	<!-- endinject -->
  <!-- plugin js for this page -->
<?php include('javascript.php') ?>
<?php // echo'<pre>',print_r($_SESSION['exam']),'</pre>'?>
 <input type='hidden' name='class' id='class' value="<?=$rowev['class']?>">
		<input type='hidden' name='subject' id='subject' value="<?=$rowev['subject']?>">
		<input type='hidden' name='totsections' id='totsections' value="<?=$_SESSION['exam']['totsections']?>">
		<?php if(isset($_SESSION['exam']['section_complete'])) $currtotsections=count($_SESSION['exam']['section_complete']); else $currtotsections=0;?>
		<input type='hidden' name='currtotsections' id='currtotsections' value='<?=$currtotsections?>' >
<script>
function reset(){ 
	hideall();
	$('#selectarea').val('0');
	$('#noofsinglechoice').val('3');
	$('#noofmatchrows').val('3');
	$('#noofmultiplechoice').val('3');
 }
function checkMarks(m,id){
	let chksecid = $('#chksecid').val();
	if(chksecid!=''){
		var a = marr.indexOf(m);
		if(a=='-1' && m!='') {
			alert('please neter correct marks')
			$('#'+id).val('');
		}
	}
}

$( "#selectarea" ).change(function() {
	hideall()
	let divsec = $('#selectarea').val();
	$('#'+divsec).show();
});

 $( document ).ready(function() {
	 let chksecid = $('#chksecid').val();
	 let totsections = parseInt($('#totsections').val());
	 let currtotsections1=parseInt($('#currtotsections').val());
	 if(totsections==currtotsections1) $('#nextexam').show();
	 else $('#nextexam').hide();
	 let tot_ques_persec = $('#tot_ques_persec').val();
	 let tot_ques_persec_created = $('#tot_ques_persec_created').val();
	// alert(tot_ques_persec+'::'+tot_ques_persec_created)
	 
	if(tot_ques_persec_created==tot_ques_persec)
	$('#nextsecbtn').show();
	else 
	$('#nextsecbtn').hide();	
	if(chksecid!=''){	
	 marr= <?php echo json_encode($marr['marks']); ?>;
	 qarr= <?php echo json_encode($qarr['questions']); ?>;
	}	
	 
	 
	let evid = $('#evid').val();
	if(evid=='') {$('#selectarea').hide();$('#evsave').show();}
	else{ $('#selectarea').show();$('#evsave').hide();
	}
	let curqueno=0;
	//fill blank
	
	$("#frmfillblank").on('submit', function(e) {
		e.preventDefault();
		if(fillblankvalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=fillblank',
			data: formData,
			type: 'POST',
			success: function(data) {
				//alert(chksecid)
				//var obj = JSON.parse(data);
				
				$("#frmfillblank").trigger("reset");
				$("#selectarea").val(0);
				$("#fillblanksection").hide();
				
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				let currtotsections=parseInt($('#currtotsections').val());
				/*alert(currtotsections)
				currtotsections=currtotsections+parseInt(1);
				$('#currtotsections').val(currtotsections);	*/
				
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				//alert(currtotsections+'=='+totsections)
				location.reload();
				if(currtotsections==totsections) $('#nextexam').show();
				else
				$('#nextsecbtn').show();
				}	
				
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})

	//fill blank
	$("#frmmatch").on('submit', function(e) {
		e.preventDefault();
		
		if(fillmatchvalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=matchans',
			data: formData,
			type: 'POST',
			success: function(data) {
				//alert(data)
				$("#frmmatch").trigger("reset");
				$("#selectarea").val(0);
				$("#matchsection").hide();
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				location.reload();
				$('#nextsecbtn').show();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})
	$("#frmsinglechoice").on('submit', function(e) {
		e.preventDefault();
		if(singleobjectivevalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=singlechoice',
			data: formData,
			type: 'POST',
			success: function(data) {
				
				$("#frmsinglechoice").trigger("reset");
				$("#selectarea").val(0);
				$("#singlechoicesection").hide();
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				$('#nextsecbtn').show();
				location.reload();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})

	$("#frmmultiplechoice").on('submit', function(e) {
		e.preventDefault();
		if(multipleobjectivevalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=multiplechoice',
			data: formData,
			type: 'POST',
			success: function(data) {
				
				$("#frmmultiplechoice").trigger("reset");
				$("#selectarea").val(0);
				$("#multiplechoicesection").hide();				
				
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				$('#nextsecbtn').show();
				location.reload();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})

	$("#frmfreetext").on('submit', function(e) {
		e.preventDefault();
		if(freetextvalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=freetext',
			data: formData,
			type: 'POST',
			success: function(data) {
				
				$("#frmfreetext").trigger("reset");
				$("#selectarea").val(0);
				$("#freetextsection").hide();
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				$('#nextsecbtn').show();
				location.reload();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})
	
	$("#frmuploadmedia").on('submit', function(e) {
		e.preventDefault();
		if(uploadimagevalid()){
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=uploadimage',
			data: formData,
			type: 'POST',
			success: function(data) {
				
				$("#frmuploadmedia").trigger("reset");
				$("#selectarea").val(0);
				$("#uploadimagesection").hide();
				if(chksecid==''){
					curqueno=parseInt(curqueno)+parseInt(1);
					//$('#btnrow').show();
					//$('#reviewbtnrow').show();
					alert('Question created successfully. Total questions are '+data )
				}
				else{	
					if(data=='invalid') alert("Your questions exceeds for this marks");
					else {
						
						let dd = eval(data);
						//alert(dd[1])
						$('#span'+dd[1]).html(dd[0]);		
						//showquestionno(dd[2]);
						$('#totquestion').html(dd[2]);
						curqueno=parseInt(curqueno)+parseInt(1);
						alert('Question created successfully. Total questions are '+dd[2] )			
					}
				}
				$('#tot_ques_persec_created').val(curqueno);
				if(tot_ques_persec==curqueno) { 
				alert("You have created all questions susscessfully");$('#questiontypediv').hide();
				$('#nextsecbtn').show();
				location.reload();
				}
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	})

	reset();
	$("#fillblankque").focusout(function(){
	  $( "#fillblankansdiv" ).empty();
    let que = $("#fillblankque").val();
	let blanks = que.split("...");
	let blength = blanks.length-1
	for (let i=1; i<=blength;i++){
		let fillans =`Answer ${i}*: <input type='text' name='fillblankanswer[]' id='fillblankanswer${i}' class="form-control"/>`
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
								<i class="input-frame"></i>
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

</script>