<div class="row">
	<div class="col-lg-6 stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-baseline mb-2">
					<h6 class="card-title mb-0">Upcoming Live</h6>					
				</div>
			  <div class="table-responsive mt-3">
				<table class="table table-hover mb-0">
					<thead>
					<tr>
						<th class="pt-0">Date/time</th>
						<th class="pt-0">Class</th>
						<th class="pt-0">Subject</th>						
						<th class="pt-0">Action</th>
						
					</tr>
					</thead>
					<tbody>
					<?php
						$today = date("Y-m-d H:i:s");
						for($l=0; $l<count($live); $l++){
							$v = & $live[$l];
							
							$sub_start_at = $v['sub_start_at'];
							$sub_end_at = $v['sub_end_at'];
							if($today>=$sub_start_at && $today<=$sub_end_at){
								$clivecss='active-live'; 
								$pslot = 'Live';
								//$href="index.php?action=live-video&id=".$vid;
							}
							else $pslot= date('d M Y H:i A',strtotime($sub_start_at));
					?>
						<tr>
							<td>
								<span class="badge badge-danger"><?=$pslot?></span>
							</td>
							<td><span class="badge badge-light"><?=getClassName($v['vid_class'])?></span></td>
							<td><?=$v['subject_name']?></td>							
							<td>							
							<a href="index.php?action=live-video&id=<?=$v['id']?>" class="btn btn-primary btn-sm">VISIT</a>							
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-baseline mb-2">
					<h6 class="card-title mb-0">Upcoming Exam</h6>
					
				</div>
			  <div class="table-responsive mt-3">
				<table class="table table-hover mb-0">
					<thead>
					<tr>
						<th class="pt-0">Date/time</th>
						<th class="pt-0">Class</th>
						<th class="pt-0">Subject</th>
						<?php if($_SESSION['u_type']=='admin') {?>
						<th class="pt-0">Action</th>
						<?php } ?>
					</tr>
					</thead>
					<tbody>
					<?php
					for($i=0; $i<count($exams); $i++){
						$e = & $exams[$i];
						
					?>
					<tr>
						<td>
							<?=date('d M Y',strtotime($e['opendate']))?> <br>
							<small class="text-muted"><?=date('H:i A',strtotime($e['opendate']))?> to <?=date('H:i A',strtotime($e['closedate']))?></small>
						</td>
						<td><span class="badge badge-light"><?=getClassName($e['class'])?></span></td>
						<td><?=getSubject($e['subject'])?></td>
						<?php if($_SESSION['u_type']=='admin') {?>
						<td>						
						<a href="index.php?action=edit-new-exam&id=<?=$e['id']?>" class="btn btn-warning btn-icon">
							<i data-feather="eye" class="mt-2"></i>
						</a>				
						
						</td><?php } ?>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'javascript.php'?>