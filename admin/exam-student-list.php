<?php //print_r($exam)?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
	  <li class="breadcrumb-item" aria-current="page"><a href="index.php?action=exams&class=<?=$exam['class']?>&subject=<?=$exam['subject']?>">Exams</a></li>
	  <li class="breadcrumb-item active" aria-current="page"><?=date('d M Y',strtotime($exam['opendate']))?></li>
	</ol>
  </nav>
  
</div>


<div class="row mb-3">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">
		<div class="d-flex justify-content-between align-items-baseline mb-2">
		  <h6 class="card-title mb-0"><?=getSubject($exam['subject'])?> </h6>
		</div>
		<form class="forms-sample">
		  <div class="form-group">
			  <div class="row">
				  <div class="col-md-3 col-12">
					  <label>Date of Exam</label>
					  <input type="text" class="form-control" value="<?=date('d M Y',strtotime($exam['opendate']))?>" readonly>
				  </div>
				  <div class="col-md-3 col-6">
					  <label>Start Time</label>
					  <input type="text" class="form-control" value="<?=date('H:i A',strtotime($exam['opendate']))?>" readonly>
				  </div>
				  <div class="col-md-3 col-6">
					  <label>End Time</label>
					  <input type="text" class="form-control" value="<?=date('H:i A',strtotime($exam['closedate']))?>" readonly>
				  </div>
				  <div class="col-md-3 col-6">
					  <label>Total Marks</label>
					  <input type="text" class="form-control" value="<?=$exam['totmarks']?>" readonly>
				  </div>
			  </div>
		  </div>		  
		</form>
	  </div> 
	</div>
  </div>
</div> <!-- row -->

<div class="row">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">		
		<div class="table-responsive">
		  <table id="dataTableExample" class="table">
			<thead>
			  <tr>
				<th>Student Name</th>
				<th>Attendance</th>
				<th>Status</th>
				<th>Marks</th>
				<th>Action</th>
			  </tr>
			</thead>
			<tbody>			  
			  <?php
				for($s=0; $s<count($ans); $s++){					
					$a = & $ans[$s];
					if($a['image']!=''){
						$pic = '../uploads/images/students/'.$a['image'];
						if(!file_exists($pic)) $pic='../uploads/avtar.png';
					}
					else $pic='../uploads/avtar.png';
					$status = $a['status']=='1'?'Not Checked':'Checked';
			  ?>
			  <tr>
				<td>
				  <div class="mr-3">
					<img src="<?=$pic?>" class="rounded-circle" alt="user"> <?=$a['student_name']?>
				  </div>				 
				</td>
				<td><span class="badge badge-pill badge-success"><i data-feather="check" class="wd-10 ht-10"></i></span></td>
				<td><span class="badge badge-info"><?=$status?></span></td>
				<td><?=$a['totmarks']?></td>
				<td><a href="index.php?action=exam-student-page&id=<?=$_GET['id']?>&student=<?=$a['studid']?>" class="btn btn-danger"><?=$a['studid']?> OPEN</a></td>
			  </tr>
				<?php } ?>
				<?php
				for($s=0;$s<count($notans);$s++){							
					$a = & $notans[$s];
					if($a['image']!=''){
						$pic = '../uploads/images/students/'.$a['image'];
						if(!file_exists($pic)) $pic='../uploads/avtar.png';
					}
					else $pic='../uploads/avtar.png';					
				?>
			  <tr>
				<td>
				  <div class="mr-3">
					<img src="<?=$pic?>" class="rounded-circle" alt="user"> <?=$a['student_name']?>
				  </div>                         
				</td>
				<td><span class="badge badge-pill badge-success"><i data-feather="check" class="wd-10 ht-10"></i></span></td>
				<td><span class="badge badge-info">Not Submitted</span></td>
				<td></td>
				<td></td>
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