<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Classroom</li>
            </ol>
          </nav>
        </div>

        <div class="row">
          <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
              <div class="card-body d-flex align-items-center justify-content-center">
                
                <h3><?=getClassName($_GET['class'])?></h3>
              </div>
            </div>
          </div>
          <div class="col-md-4 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-2">Class Teacher</h6>
                  
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="d-flex align-items-center">
                      <div class="mr-3">
                        <img src="<?=$classteacher['t_pic']?>" class="rounded-circle wd-40" alt="user">
                      </div>
                      <div class="w-100">
                        <div class="d-flex justify-content-between">
                          <h6 class="text-body mt-1"><?=$classteacher['t_name']?></h6>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline">
                  <h6 class="card-title mb-2">Subjects</h6>
                  <?php if($_SESSION['u_type']=='admin'){?>
				  <a href="index.php?action=subjects&class=<?=$_GET['class']?>"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
				  <?php } ?>
                </div>
                <div class="row">
                  <div class="col-md-12">
					<?php
					for($i=0; $i<count($subjects); $i++){	
					?>
                    <span class="badge badge-warning"><?=$subjects[$i]['subject_name']?></span>
                    <?php } ?>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div> <!-- row -->
        <div class="row">
          <div class="col-lg-6 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0">Teachers</h6>
                  
                </div>
               
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Name</th>
                        <th class="pt-0">Subject</th>
                       <?php if($_SESSION['u_type']=='admin'){?> <th class="pt-0">Action</th><?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                     <?php for($t=0; $t<count($classteachers); $t++) {
							$c = & $classteachers[$t];
							$tassign = getAssignSubjectByTeacher($c['t_id'],$_GET['class']);
							//print_r($tassign);
					?>
                      <tr>
                        <td>
                          <a href="index.php?action=edit-teacher&id=<?=$c['t_id']?>" class="d-flex align-items-center">
                            <div class="mr-3">
                              <img src="<?=$c['t_pic']?>" class="rounded-circle wd-40" alt="user">
                            </div>
                            <div class="w-100">
                              <div class="d-flex justify-content-between">
                              <h6 class="text-body mt-1"><?=$c['t_name']?><?php if($c['t_id']==$classteacher['t_id']) {?> <span class="badge badge-pill badge-primary">CT</span><?php } ?></h6>
                              </div>
                            </div>
                          </a>
                          
                        </td>
                        <td>
						<?php if(!empty($tassign)){
							for($s=0;$s<count($tassign); $s++){
								echo '<span class="badge badge-info">',$tassign[$s]['subject_name'],"</span> ";
							}
						}		
							?>
						<?//=$c['subject_name']?></td>
						<?php if($_SESSION['u_type']=='admin'){?>
                        <td>
                          <button type="button" class="btn btn-warning btn-icon"  data-toggle="modal" data-target="#editteacher" data-placement="top" title="Edit" onclick="setTeacherid(<?=$c['t_id']?>)">
                            <i data-feather="edit-2"></i>
                          </button>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteTeacher(<?=$c['assign_id']?>)">
                            <i data-feather="x"></i>
                          </button>
                        </td><?php } ?>
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
                  <h6 class="card-title mb-0">Students</h6>
                  <span class="badge badge-light">Boys <?=$students[count($students)-1]['Male']?></span> <span class="badge badge-light">Girls <?=$students[count($students)-1]['Female']?></span>
                  
                </div>
               
                <div class="table-responsive mt-5">
                  <table id="dataTableExample2" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Name</th>
                        <th class="pt-0">Roll no.</th>
                      </tr>
                    </thead>
                    <tbody>
                     <?php for($s=0; $s<count($students)-1; $s++){
							$d = & $students[$s];
							
						 ?>
                      <tr>
                        <td>
                          <a href="index.php?action=edit-student&id=<?=$d['std_id']?>" class="d-flex align-items-center">
                            <div class="mr-3">
                              <img src="<?=$d['image']?>" class="rounded-circle wd-40" alt="user">
                            </div>
                            <div class="w-100">
                              <div class="d-flex justify-content-between">
                                <h6 class="text-body mt-1"><?=$d['student_name']?></h6>
                              </div>
                            </div>
                          </a>
                        </td> 
                        <td>
                         <?=$d['roll_no']?> 
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
			  
      <!-- Modal teacher edit-->
      <div class="modal fade" id="editteacher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Teacher Name</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
			<form class="forms-sample" id='frmassignteacher'>	
            <div class="modal-body">                
				<input type='hidden' id='assignteacherid' name='assignteacherid'>	
				<input type='hidden' id='assignteacherclass' name='assignteacherclass' value="<?=$_GET['class']?>">	
				<div class="form-group">
					<label>Assign Subjects</label>
					<div class="clearfix"></div>
					<?php 
					if(count($subjects_na)>0){
					for($s=0; $s<count($subjects_na); $s++){?>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input type="checkbox" name='assignsubject' class="form-check-input" value="<?=$subjects_na[$s]['subject_id']?>">
							<?=$subjects_na[$s]['subject_name']?>
						</label>
					</div>
					<?php }
					} else {?>
					<label>There is no subject to assign.</label>
					<?php } ?>
				</div>
			  <hr>
			  <div class="form-group">
				<label>Class Teacher</label>
				<div class="clearfix"></div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input type="checkbox" name="makeclassteacher" value='1' class="form-check-input">
							Make class teacher
						</label>
					</div>
					
				</div>
			</div>
            
			<div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
			</form>
          </div>
        </div>
      </div>
       <!-- Modal new teacher-->
      

      <!-- Modal subject -->
      <div class="modal fade" id="editsubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add/Remove Subject</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample">									
									<div class="form-group">
										<?php for($s=0; $s<count($subjectsall); $s++){
											if(in_array($subjectsall[$s]['subject_id'],$subject_ids)) $chk='checked';
											else $chk='';
											?>
										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="checkbox" class="form-check-input" <?=$chk?> value="<?=$subjectsall[$s]['subject_id']?>">
												<?=$subjectsall[$s]['subject_name']?>
											</label>
										</div>
										<?php } ?>
									</div>
                  
                                    
								</form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>

<script>
function deleteTeacher(assignid){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=assign-teacher-delete&assignid='+assignid,
		success: function(data) {
			location.reload();			
		},
		cache: false,
		contentType: false,
		processData: false
	});
	}
}	
function setTeacherid(id){
	$('#assignteacherid').val(id);	
}
$(document).ready(function(){
	$("#frmassignteacher").on('submit', function(e) {
		e.preventDefault();
		let classid=$('#classid').val();
		//if(courseValidation()){
		
		let subject=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=assign-subject-teacher',
			data: formData,
			type: 'POST',
			success: function(data) {
				location.reload();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		//}
	})
})
    
</script>
