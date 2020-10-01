<?php 
$classn = getClassName($_GET['class']);
//unset($_SESSION['evid']);
$arr= array($classn=>'','Exams'=>'');
tmp_topBreadCrumb($arr);
$subjectid=$_GET['subject'];$subjectname = getSubject($_GET['subject']);
$gclass=$_GET['class'];
?>
        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"><?=getSubject($_GET['subject'])?> <span class="badge badge-warning">Upcoming</span></h6>
                  <button type="button" onclick="window.location.href='index.php?action=add-new-exam&class=<?=$gclass?>&subject=<?=$subjectid?>'" class="btn btn-success btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Add New
                  </button>
                </div>
               
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Time</th>
                        <!--th class="pt-0">Created by</th-->
                        <th class="pt-0">Total Questions </th>
                        <th class="pt-0">Total Marks</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php for($i=0; $i<count($exams); $i++) { 
							$e=& $exams[$i];
							?>
                      <tr>
                        <td><?=date('d M Y',strtotime($e['opendate']))?></td>
                        <td><span class="badge badge-info"><?=date('H:i A',strtotime($e['opendate']))?> to <?=date('H:i A',strtotime($e['closedate']))?></span></td>
                        <!--td><span class="badge badge-success">You</span></td-->
                        <td><?=$e['totquestions']?> Questions</td>
                        <td><?=$e['totmarks']?> Marks</td>
                        <td>
                          
                          <a href="index.php?action=edit-new-exam&id=<?=$e['id']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$e['id']?>)">
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
                  <h6 class="card-title mb-0"><?=getSubject($_GET['subject'])?> <span class="badge badge-warning">Previous</span></h6>
                  
                </div>
                
                <div class="table-responsive mt-5">
                  <table id="dataTableExample2" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Time</th>
                        <!--th class="pt-0">Created by</th-->
                        <th class="pt-0">Total Questions </th>
                        <th class="pt-0">Total Marks</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      
					   <?php
						$classid=$_GET['class'];
					   for($i=0; $i<count($pexams); $i++) { 
							$e=& $pexams[$i];
							$id = $e['id'];
							?>
                    
                      <tr>
                        <td><?=date('d M Y',strtotime($e['opendate']))?></td>
                        <td><span class="badge badge-info"><?=date('H:i A',strtotime($e['opendate']))?> to <?=date('H:i A',strtotime($e['closedate']))?></span></td>
                        <!--td><span class="badge badge-success">Admin</span></td-->
                        <td><?=$e['totquestions']?> Questions</td>
                        <td><?=$e['totmarks']?> Marks</td>
                        <td>
                          
                          <button onclick="window.location.href='index.php?action=exam-student-list&id=<?=$id?>&class=<?=$classid?>'" type="button" class="btn btn-primary btn-sm">VIEW</button>
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
		window.location.href="index.php?action=exams&class=<?=$_GET['class']?>&subject="+e.target.value
	});
	let hselsubjectid = $( "#hselsubjectid" ).val();
	if(hselsubjectid==''){
		window.location.href="index.php?action=exams&class=<?=$_GET['class']?>&subject="+$( "#selsubject" ).val()
	}
	$( "#selsubject" ).val(hselsubjectid);
});
function deleteRecord(id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=exam-delete&id='+id,
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

