<?php 
$classn = getClassName($_GET['class']);
$arr= array($classn=>'','Live Session'=>'');
tmp_topBreadCrumb($arr);

if(!isset($_GET['subject']) && $_GET['subject']=='') {
//$subjectid=$subjects[0]['subject'];$subjectname = $subjects[0]['subject_name'];
} else{
$subjectid=$_GET['subject'];$subjectname = getSubject($_GET['subject']);
}
$clive = & $live['live'];
$past = & $live['past'];
$upcomming = & $live['upcomming'];

//print_r($upcomming);
if(isset($clive['ref_doc']) && $clive['ref_doc']!=''){
		$cref_doc = "../uploads/videos/refdoc/".$clive['ref_doc'];
		if(!file_exists($cref_doc))$cref_doc='';
}
else $cref_doc='';
$gclass=$_GET['class'];

//print_r($clive);
?>        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"><?=$subjectname?> <span class="badge badge-warning">Upcoming</span></h6>
                  <button type="button" onclick="window.location.href='index.php?action=add-new-live-sessions&class=<?=$gclass?>&subject=<?=$subjectid?>'" class="btn btn-success btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Add New
                  </button>
                </div>
				<?php if(!empty($clive)){?>	
                <div class="table-responsive mt-5">
                  <table class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Time</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Document</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Today</td>
                        <td><span class="badge badge-danger">Live</span></td>
                        <td><?=$clive['vtitle']?></td>
                        <td><?php if(file_exists($cref_doc)){?><button type="button" class="btn btn-outline-info" onclick="window.open('<?=$cref_doc?>')" >View</button><?php } ?></td>
                        <td>
                          <button onclick="window.location.href='index.php?action=live-video&id=<?=$clive['id']?>'" type="button" class="btn btn-primary btn-sm">VISIT</button>
                        </td>
                      </tr>                     
                    </tbody>
                  </table>                  
                </div>
				<?php } ?>	
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Time</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Document</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for($i=0; $i<count($upcomming); $i++) {
							$u = $upcomming[$i];
							if($u['ref_doc']!=''){
							$uref_doc = "../uploads/videos/refdoc/".$u['ref_doc'];
							if(!file_exists($uref_doc))$uref_doc='';
							}
							else $uref_doc = "";
						?>
                      <tr>
                        <td><?=date('d/m/Y',strtotime($u['sub_start_at']))?></td>
                        <td><span class="badge badge-success"><?=date('H:iA',strtotime($u['sub_start_at']))?> to <?=date('H:iA',strtotime($u['sub_end_at']))?></span></td>
                        <td><?=$u['vtitle']?></td>
                        <td><?php if(file_exists($uref_doc)){?><button type="button" class="btn btn-outline-info"  onclick="window.open('<?=$uref_doc?>')" >View</button><?php } ?></td>
                        <td>                          
                          <a href="index.php?action=edit-new-live-sessions&id=<?=$u['id']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$u['id']?>)">
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
                        <th class="pt-0">Date</th>
                        <th class="pt-0">Time</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Document</th>
                        
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for($i=0; $i<count($past); $i++) {
							$p = $past[$i];
							if($p['ref_doc']!='')
							$uref_doc = "../uploads/videos/refdoc/".$p['ref_doc'];
							else $uref_doc = "";
						?>
                     <tr>
                        <td><?=date('d/m/Y',strtotime($p['sub_start_at']))?></td>
                        <td><span class="badge badge-success"><?=date('H:iA',strtotime($p['sub_start_at']))?> to <?=date('H:iA',strtotime($p['sub_end_at']))?></span></td>
                        <td><?=$p['vtitle']?></td>
                        <td><?php if(file_exists($uref_doc)){?><button type="button" class="btn btn-outline-info"  onclick="window.open('<?=$uref_doc?>')" >View</button><?php } ?></td>
                        <td>                          
                          <a href="index.php?action=live-video&id=<?=$p['id']?>" class="btn btn-danger btn-icon" data-toggle="tooltip" data-placement="top" title="View Video">
                            <i data-feather="video" class="mt-2"></i>
                          </a>
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
		window.location.href='index.php?action=live-sessions&class=<?=$_GET['class']?>&subject='+e.target.value
	});
	let hselsubjectid = $( "#hselsubjectid" ).val();
	if(hselsubjectid==''){
		window.location.href='index.php?action=live-sessions&class=<?=$_GET['class']?>&subject='+$( "#selsubject" ).val()
	}
	$( "#selsubject" ).val(hselsubjectid);
});
function deleteRecord(id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=video-delete&id='+id,
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