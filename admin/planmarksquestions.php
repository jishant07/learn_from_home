<?php
$post = $_SESSION['exam']['post'];

//print_r($_SESSION['seclastid']);
$totsections=$_SESSION['exam']['totsections'];
$current=$_SESSION['exam']['current'];
if(isset($_POST['submit'])){
	//print_r($_POST);
	$sec = $_SESSION['exam']['seclastid'][$current];
	for($i=0;$i<count($_POST['mperq']); $i++){		
		$marks=$_POST['mperq'][$i];
		$questions=$_POST['noque'][$i];
		if($marks!=''){
		$sql="insert into tbl_questionsections(section,marks,questions) values('$sec','$marks','$questions')";
		mysqli_query($conn,$sql);
		}
	}
	$_SESSION['exam']['current']=$_SESSION['exam']['current']+1;
	$current=$_SESSION['exam']['current'];
	unset($_POST);
	if($current==$totsections){ echo "<script>location.href='index.php?action=plansectionstab'</script>"; die();}
	else{
		echo "<script>location.href='index.php?action=planmarksquestions'</script>";}
}
?>
   <script src="js/validationnew.js"></script>

<div class="row">
  <div class="col-lg-5 mb-3">
	<div class="card">
	  <div class="card-body">
	  		<div class="form-group mb-4">
                    <div class="row">
                        <div class="col-6">
                            <label>Sections Name</label>
                            <input type="text" class="form-control" value="<?=$post['section'][$current]?>" readonly>
                        </div>  
						<div class="col-6">
                            <label>Total Marks</label>
                            <input type="text" class="form-control" value="<?=$post['marks'][$current]?>" readonly>
                        </div>                          
                    </div>  
            </div>
				<hr>
			<form acton='' method='post' onsubmit="return checkMarks()" autocomplete=off>
				<input type='hidden' id='totmarks' value="<?=$post['marks'][$current]?>">
				<input type='hidden' id='noofrows' name='noofrows' value='1'>

				<div class='mdiv2' id='addrow'>
					<div id='rowq'>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Marks per question*</label>
										<input type="text" class="form-control" name='mperq[]' id='mperq1' size=3 onkeyup="changeMarks(1)" onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
									</div>
								</div><!-- Col -->
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">No of questions*</label>
										<input type="text" class="form-control" name='noque[]' id='noque1' size=3 onkeyup="changeMarks(1)" onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
									</div>
								</div><!-- Col -->
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label">Total</label>
										<input type="text" class="form-control"  name='total[]' id='total1' size='3' readonly>
									</div>
								</div><!-- Col -->
								
							</div>
						</div>
					</div>
							
					<div class='rowmatch'>			
							<div class='matchcol'>
							<input type='button' id='addmatchrow' class='btn btn-primary mr-2 mt-2' value='+ Add More Rows'>
							<input type='button' id='removematchrow' class='btn btn-primary mr-2 mt-2' style='display:none' value='- Remove More Rows'>
							</div>				
					</div>
					<div class="form-group mt-5 mb-4">
						<div class="row">
							<div class="col-6">
								<label>Total questions</label>
								<input type="text" class="form-control"  name='totalques' id='totalques' size=3 value='0' readonly>
							</div>  
							<div class="col-6">
								<label style="display:block;">Sum Total</label>
								<input type="text" style="width:70px; display:inline" class="form-control" name='totalsum' id='totalsum' size=3 value='0' readonly> /<?=$post['marks'][$current]?>
							</div>                          
						</div>  
					</div>
					
				
				</div>
				<div class='mdiv2  right'>
					<?php if($current==$totsections-1){?>
					<input type='submit' name='submit' value='Submit' class='btn btn-primary mr-2 mt-2'>
					<?php } else { ?>
					<input type='submit' name='submit' value='Finish and Go to Next Section' class='btn btn-primary mr-2 mt-2'>
					<?php } ?>
				</div>

			</form>
	  </div>
	</div>
  </div>
</div>
<?php include 'javascript.php';?>

<script>
function checkMarks(){
	let totmarks=$('#totmarks').val();
	let totalsum=$('#totalsum').val();
	if(totalsum!=totmarks) {
		alert('Sum total is not matching with total marks');return false;
	}
	return true;
}
 $( "#addmatchrow" ).click(function() {
	 let html='';
	  let rval=parseInt($('#noofrows').val())+parseInt(1);
	  //alert(rval)
	  if(rval==2) $('#removematchrow').show();	 
	  html=`<div style='clear:both' id="mqr${rval}">
		<div class="form-group">
			<hr>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Marks per question</label>
						<input type="text" class="form-control" name='mperq[]' id='mperq${rval}' size=3 onkeyup="(${rval})" onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
					</div>
				</div><!-- Col -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">No of questions</label>
						<input type="text" class="form-control" name='noque[]' id='noque${rval}' size=3 onkeyup="changeMarks(${rval})" onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
					</div>
				</div><!-- Col -->
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Total</label>
						<input type="text" class="form-control" name='total[]' id='total${rval}' size='3' readonly>
					</div>
				</div><!-- Col -->
				
			</div>
		</div>
					
		</div>`;
	    $( "#rowq" ).append( html );
	$('#noofrows').val(rval)	

});	

$( "#removematchrow" ).click(function() {
	let cval=parseInt($('#noofrows').val())
	  let rval=parseInt($('#noofrows').val())-parseInt(1);
	  if(rval<3) $('#removematchrow').hide();
	  $( '#mqr'+cval ).remove();
	  $('#noofrows').val(rval)
});


function changeMarks(i){
	//console.log(i)
	let m = parseInt($('#mperq'+i).val());
	let n = parseInt($('#noque'+i).val());
	let t=m*n
	if(!isNaN(t))
	$('#total'+i).val(t)
	//console.log(t)
	let questiontype=$('#noofrows').val();
	//alert(questiontype)
	let totq=0;
	let totm=0;
	for(let i=1;i<=questiontype;i++){
		if($('#noque'+i).val()!='')
		totq=parseInt(totq) + parseInt($('#noque'+i).val());
		if($('#total'+i).val()!='')
		totm=parseInt(totm) + parseInt($('#total'+i).val());
	}
	
	if(!isNaN(totm)){
		if(totm>$('#totmarks').val()) {
			alert('Total marks exceeds limit');
			$('#noque'+i).val('');
			$('#total'+i).val('')
		}	
		$('#totalsum').val(totm)
	}
	if(!isNaN(totq)) $('#totalques').val(totq)
	
	
}
</script>