<?php 
if(isset($_POST['sectionbtn'])){
	//echo'<pre>',print_r($_POST),'</pre>';
	$evid=$_SESSION['exam']['evid'];
	$seclastid=array();
	for($i=0;$i<$_POST['totsections'];$i++){
		$section = $_POST['section'][$i];	
		$marks = $_POST['marks'][$i];	
		$sql="insert into tbl_section(evid,name,marks) values('$evid','$section','$marks')";
		mysqli_query($conn,$sql);
		$seclastid[]=mysqli_insert_id($conn);
	}
	$_SESSION['exam']['seclastid']= $seclastid;
	$_SESSION['exam']['post']= $_POST;
	$_SESSION['exam']['current']= 0;
	
	echo"<script>location.href='index.php?action=planmarksquestions'</script>";die();
}
//include_once('header.php');
?>
   <script src="js/validationnew.js"></script>


<div class="row">
  <div class="col-lg-5 mb-3">
  
	<div class="card">
	  <div class="card-body">
		
		  <div class="form-group">

			<form action='' method=post autocomplete=off onsubmit="return sectionvalid()"> 
				<div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label>Total  Sections</label>
                            <input type="text" class="form-control" name='totsections' id='totsections' value="<?=$_SESSION['exam']['totsections']?>" readonly>
                        </div>  
						<div class="col-6">
                            <label>Total Marks</label>
                            <input type="text" class="form-control" name='totmarks' id='totmarks' value="<?=$_SESSION['exam']['totmarks']?>" readonly>
                        </div>                          
                    </div>  
                </div>    
			
				
				<?php for($i=0;$i<$_SESSION['exam']['totsections'];$i++){ ?>
				<div class="row">
					<div class="col-sm-9"><h6 class="card-title mt-4 mb-2">Section <?=$i+1?></h6></div>
					
					<div class="col-sm-9">
						<div class="form-group">
							<label class="control-label">Section Name*</label>
							<input type="text" class="form-control" name='section[]' id='section<?=$i+1?>' size=3>
						</div>
					</div><!-- Col -->
					<div class="col-sm-3">
						<div class="form-group">
							<label class="control-label">Marks*</label>
							<input type="text" class="form-control" name='marks[]' id='marks<?=$i+1?>' size=3 onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
						</div>
					</div><!-- Col -->
					<hr/>
					
				</div>
				<?php } ?>
				<input type='submit' name='sectionbtn' id='sectionbtn' class='btn btn-primary mr-2 mt-2' value='Submit'/>
			</form>
		  </div>
		</div>
	</div>
	</div>
</div>
<?php include 'javascript.php'?>