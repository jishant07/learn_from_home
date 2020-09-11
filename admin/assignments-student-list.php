<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#"><?=getClassName($data['class'])?></a></li>
	  <li class="breadcrumb-item" aria-current="page"><a href="index.php?action=assignments&class=<?=$data['class']?>&subject=<?=$data['subject']?>">Assignments</a></li>
	  <li class="breadcrumb-item active" aria-current="page">Assignment</li>
	</ol>
  </nav>          
</div>        

<div class="row">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">
		<div class="d-flex justify-content-between align-items-baseline mb-2">
		  <h6 class="card-title mb-0"><?=getSubject($data['subject'])?> <span class="badge badge-warning"><?=date('d M Y',strtotime($data['closedate']))?></span></h6> 
		  
		</div>
		<div class="row mt-4 mb-4">
		  <div class="col-12">
			  <h4 id="default"><?=stripslashes($data['question'])?></h4>
				<p class="mb-3"><?=stripslashes($data['answer'])?></p>
				<?php if($data['document']!=''){
					
					$doc = '../uploads/evaluation/referdoc/'.$data['document'];
					?>	
			<a href="javascript:window.open('<?=$doc?>')" class="btn btn-danger btn-xs"><span class="wd-20 mr-2" data-feather="file-text"></span>Referance Document</a><?php } ?>
		  </div>
		</div>
		<div class="table-responsive">
		<?php //echo'<pre>',print_r($stud)?>
		  <table id="dataTabl$arr[$i]['created']=$arr[$i]['created']=eExample" class="table">
			<thead>
			  <tr>
				<th>Student Name</th>
				<th>Submitted Date</th>
				<th>Status</th>
				<th>Action</th>
			  </tr>
			</thead>
			<tbody>
			<?php for($i=0; $i<count($stud); $i++){
				$s = & $stud[$i];
				$status='';
				//if($s['status']=='0') { $status='Not Submitted';$cls='badge-light';}
				if(isset($s['feedbackstatus'])){
				if($s['feedbackstatus']=='2') {  $status='Checked';$cls='badge-success';}
				else if($s['feedbackstatus']=='1'){ $status='Not Checked';$cls='badge-info';}
				}
				else { $status='Not Submitted';$cls='badge-light';}
				if(file_exists($s['image'])) $simg = $s['image']; else $simg = '../uploads/avtar.png';
				?>
			  <tr>
				<td>
				  <div class="mr-3">
					<img src="<?=$simg?>" class="rounded-circle" alt="user"> <?=$s['student_name']?>
				  </div>
				 
				</td>
				<td><?php if($s['created']!='') echo date('d M Y',strtotime($s['created']))?></td>
				<td><span class="badge <?=$cls?>"><?=$status?></span></td>
				<td><?php if($s['id']!=''){?><a href="index.php?action=assignments-single-student&id=<?=$s['id']?>" class="btn btn-danger">OPEN</a><?php } ?></td>
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