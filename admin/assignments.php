<?php 
$classn = getClassName($_GET['class']);
$arr= array($classn=>'','Assignments'=>'');
tmp_topBreadCrumb($arr);
$subjectid=$_GET['subject'];$subjectname = getSubject($_GET['subject']);
$gclass=$_GET['class'];
$upcom = $assign['upcomming'];
$past = $assign['past'];
?>
        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"><?=$subjectname?> <span class="badge badge-warning">Upcoming</span></h6>
                  <button type="button" onclick="window.location.href='index.php?action=add-new-assignment&class=<?=$gclass?>&subject=<?=$subjectid?>'" class="btn btn-success btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Add New
                  </button>
                </div>
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Created Date</th>
                        <th class="pt-0">Submission Date</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Document</th>
                        <!--th class="pt-0">Total Submission</th-->
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
						<?php for($i=0; $i<count($upcom); $i++) {
							$u = & $upcom[$i];
							$doc='';	
							if($u['document']!='')
							$doc='../uploads/evaluation/referdoc/'.$u['document'];
							$type = $u['type'];
						?>
                      <tr>
                        <td><?=date('d M Y',strtotime($u['opendate']))?></td>
                        <td><span class="badge badge-success"><?=date('d M Y',strtotime($u['closedate']))?></span></td>
                        <td><?=$u['question']?></td>
                        <td><?php if(file_exists($doc)){?><button type="button" class="btn btn-outline-info" onclick="window.open('<?=$doc?>')">View</button><?php } ?></td>
                        <!--td><a href="index.php?action=assignments-student-list" class="btn btn-info"></a></td-->
                        <td>
                          
                          <a href="index.php?action=edit-new-assignment&id=<?=$u['id']?>&type=<?=$type?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord('<?=$type?>',<?=$u['id']?>)">
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
        <div class="row mt-5">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
              <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"><?=$subjectname?> <span class="badge badge-warning">Previous</span></h6>
                  
                </div>
                <div class="table-responsive mt-5">
                  <table id="dataTableExample2" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Created Date</th>
                        <th class="pt-0">Submission Date</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Document</th>
                        <th class="pt-0">Total Submission</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php for($i=0; $i<count($past); $i++) {
							$u = & $past[$i];
							$doc='';	
							if($u['document']!='')
							$doc='../uploads/evaluation/referdoc/'.$u['document'];
							$type = $u['type'];
						?>

                      <tr>
                        <td><?=date('d M Y',strtotime($u['opendate']))?></td>
                        <td><span class="badge badge-success"><?=date('d M Y',strtotime($u['closedate']))?></span></td>
                        <td><?=$u['question']?></td>
                        <td><?php if(file_exists($doc)){?><button type="button" class="btn btn-outline-info" onclick="window.open('<?=$doc?>')">View</button><?php } ?></td>
                        <td><a href="index.php?action=assignments-student-list&id=<?=$u['id']?>&type=<?=$type?>" class="btn btn-info"></a></td>
                        <td>
                          
                            <a href="index.php?action=assignments-student-list&id=<?=$u['id']?>&type=<?=$type?>" class="btn btn-danger">VIEW</a>
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
		window.location.href="index.php?action=assignments&class=<?=$_GET['class']?>&subject="+e.target.value
	});
	let hselsubjectid = $( "#hselsubjectid" ).val();
	if(hselsubjectid==''){
		window.location.href="index.php?action=assignments&class=<?=$_GET['class']?>&subject="+$( "#selsubject" ).val()
	}
	$( "#selsubject" ).val(hselsubjectid);
});
function deleteRecord(atype,id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=assignment-delete&id='+id+'&type='+atype,
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

		