<?php 
$classn = getClassName($_GET['class']);

$arr= array($classn=>'','Courses'=>'');
tmp_topBreadCrumb($arr);
$subjectid=$_GET['subject'];$subjectname = getSubject($_GET['subject']);
$gclass=$_GET['class'];
?>
<div class="row">
	<div class="col-lg-12 stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-baseline mb-2">
				<h6 class="card-title mb-0"><?=$subjectname?></h6>
				<button type="button" onclick="window.location.href='index.php?action=add-new-course&class=<?=$gclass?>&subject=<?=$subjectid?>'" class="btn btn-success btn-icon-text">
					<i class="btn-icon-prepend" data-feather="plus-square"></i>
					Add New Course
				</button>
				</div>
				<div class="table-responsive mt-5">
				<table id="dataTableExample" class="table table-hover mb-0">
					<thead>
					<tr>
						<th class="pt-0">Thumbnail</th>
						<th class="pt-0">Created Date</th>
						<th class="pt-0">Created By</th>
						<th class="pt-0">Title</th>
						<th class="pt-0">Total Videos</th>
						<th class="pt-0">Action</th>
					</tr>
					</thead>
					<tbody>
					<?php for($c=0; $c<count($courses); $c++){

						$s= & $courses[$c];
					?>
					<tr>
						<td>
						<?php if($s['cthumb']!=''){?>	
						<img src="../uploads/images/courses/<?=$s['cthumb']?>" class="tableimg" />
						<?php } else { ?>
						<img src="assets/images/placeholder.jpg" class="tableimg" />
						<?php } ?>
						</td>
						<td><?=date('d M Y',strtotime($s['created']))?></td>
						<td><span class="badge badge-success"><?php echo ($s['createdby']=='teacher')?'you':'Admin'?></span></td>
						<td><?=$s['name']?></td>
						<td><span class="badge badge-info"><?=$s['videocount']?></span></td>
						<td>
							<a href="index.php?action=edit-new-course&id=<?=$s['id']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
								<i data-feather="edit-2" class="mt-2"></i>
							</a>
							
							
							<button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$s['id']?>)">
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
</div> <!-- row -->
<?php include('javascript.php') ?>

<script>
$( document ).ready(function() {
    $( "#selsubject" ).change(function(e) {
		//alert(e.target.value)
		window.location.href='index.php?action=courses&class=<?=$_GET['class']?>&subject='+e.target.value
	});
	let hselsubjectid = $( "#hselsubjectid" ).val();
	if(hselsubjectid==''){
		window.location.href='index.php?action=courses&class=<?=$_GET['class']?>&subject='+$( "#selsubject" ).val()
	}
	$( "#selsubject" ).val(hselsubjectid);
});
function deleteRecord(id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=course-delete&id='+id,
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

		