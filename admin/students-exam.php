<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="index.php?action=classroom&class=<?=$sinfo['dept_id']?>"><?=getClassName($sinfo['dept_id'])?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=edit-student&id=<?=$_GET['sid']?>"><?php echo $sinfo['student_name'],' ',$sinfo['student_lastname']?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=students-stats&sid=<?=$_GET['sid']?>">Stats</a></li><li class="breadcrumb-item active" aria-current="page">Exams</li>
	</ol>
  </nav>          
</div>
<div class="row">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">
		<div class="d-flex justify-content-between align-items-baseline mb-2">
		  <h6 class="card-title mb-0">Exams Stats </h6>
		  <div class="d-flex align-items-center flex-wrap text-nowrap">
			<select class="form-control" id='select_month' name='select_month'>
				<option value=''>All Months</option>
				<option value='01'>January</option>
				<option value='02'>February</option>
				<option value='03'>march</option>
				<option value='04'>April</option>
				<option value='05'>May</option>
				<option value='06'>June</option>                        
				<option value='07'>July</option>
				<option value='08'>August</option>
				<option value='09'>September</option>
				<option value='10'>October</option>
				<option value='11'>November</option>
				<option value='12'>December</option>
			</select>
		  </div>
		</div>
		<div>
		  <span class="badge badge-warning">Total Exam : <?=count($exam)?></span>
		  <!--span class="badge badge-success">Pass : 30</span>
		  <span class="badge badge-danger">Fail : 6</span-->
		</div>
		<div class="table-responsive mt-5">
		  <table id="dataTableExample" class="table table-hover mb-0">
			<thead>
			  <tr>
				<th class="pt-0">Date</th>
				<th class="pt-0">Time</th>
				<th class="pt-0">Subject Name</th>
				<th class="pt-0">Marks</th>
				<th class="pt-0">Result</th>
			  </tr>
			</thead>
			<tbody>
			<?php for($e=0; $e<count($exam); $e++){
				$I=& $exam[$e];
				$chkpresent = chkSubmitPerExam($I[id],$sinfo['ecode']);
				
				if($chkpresent!=''){
						$present = 'badge-success'; 
						$tick='check';
					}
				else {
					$present = 'badge-primary'; 
					$tick='x';
				}

				?>
			  <tr>
				<td><?=date('d M',strtotime($I['opendate']))?></td>
				<td><?=date('h:i A',strtotime($I['opendate']))?> to <?=date('h:i A',strtotime($I['closedate']))?></td>
				<td><span class="badge badge-success"><?=getSubject($I['subject'])?></span></td>
				<td><span class="badge badge-info"><?=getMarksPerExam($I['id'])?>/<?=$I['totmarks']?></span></td>
				<td><span class="badge badge-pill <?=$present?>"><i data-feather="<?=$tick?>" class="wd-10 ht-10"></i></span></td>
			  </tr>
			<?php } ?>
			  
			</tbody>
		  </table>
		  
		</div>
	  </div> 
	</div>
  </div>
</div> <!-- row -->
 <?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
	var sid=<?=$_GET['sid']?>;
	var month="<?=$_GET['month']?>";
	$("#select_month").val(month)
	$("#select_month").change(function(e) {
	var url  = window.location.href;   
	var month = $("#select_month").val();	
	url='index.php?action=students-exam&sid='+sid+'&month='+month;
	$.ajax({
		url: url,
		type: 'POST',
		success: function(data) {

			window.location.href=url
		},
		cache: false,
		contentType: false,
		processData: false
	});	 
	});
});
</script>