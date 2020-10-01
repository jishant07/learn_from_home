<?php
$totassignment = count($freeassignemnt)+count($docassignemnt);
$totlive = count($sessions);
$totexams = count($exam);
$assignment = $stats['assignment'];
$live_session = $stats['live_session'];
$ab_totassignment=$totassignment-$assignment;
$ab_totlive=$totlive-$stats['live_session'];


$submiteedexam = $stats['exams'];
$notsubmittedexam= $totexams-$submiteedexam;

?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="index.php?action=classroom&class=<?=$sinfo['dept_id']?>"><?=getClassName($sinfo['dept_id'])?></a></li>
	  <li class="breadcrumb-item"><a href="index.php?action=edit-student&id=<?=$_GET['sid']?>"><?php echo $sinfo['student_name'],' ',$sinfo['student_lastname']?></a></li>
	  <li class="breadcrumb-item active" aria-current="page">Stats</li>
	</ol>
  </nav>          
</div>
<input type=hidden id='assignment' value="<?=$assignment?>">
<input type=hidden id='ab_totassignment' value="<?=$ab_totassignment?>">
<input type=hidden id='live_session' value="<?=$live_session?>">
<input type=hidden id='ab_totlive' value="<?=$ab_totlive?>">
<input type=hidden id='submiteedexam' value="<?=$submiteedexam?>">
<input type=hidden id='notsubmittedexam' value="<?=$notsubmittedexam?>">
<div class="row">
  <div class="col-xl-4 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h6 class="card-title">LIVE SESSION ATTENDANCE<br><span class="badge badge-warning">Total Session : <?=$stats['live_session']?></span></h6>
		<div id="live"></div>
		<div class="form-group d-flex justify-content-center">
		  <a href="index.php?action=students-live-sessions&sid=<?=$_GET['sid']?>" class="btn btn-dark mr-0">Details</a>
		</div>
					</div>
				</div>
			</div>
  <div class="col-xl-4 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h6 class="card-title">ASSIGNMENT <br><span class="badge badge-warning">Total Assignments  : <?=$stats['assignment']?></span></h6>
		<div id="assignmentchart"></div>
		
		<div class="form-group d-flex justify-content-center">
		  <a href="index.php?action=students-assignments&sid=<?=$_GET['sid']?>" class="btn btn-dark mr-0">Details</a>
		</div>
					</div>
				</div>
			</div>
  <div class="col-xl-4 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<h6 class="card-title">EXAMS<br><span class="badge badge-warning">Total Exams  : <?=$stats['exams']?></span></h6>
		<div id="exam"></div>
		<div class="form-group d-flex justify-content-center">
		  <a href="index.php?action=students-exam&sid=<?=$_GET['sid']?>" class="btn btn-dark mr-0">Details</a>
		</div>
					</div>
				</div>
			</div>
</div> <!-- row -->
<?php include('javascript.php') ?>