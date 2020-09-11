                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <h4 class="mb-3 mb-md-0">Students</h4>
                    </div>
					<?php if($_SESSION['u_type']=='admin'){?>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        
                       <button type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block"  data-toggle="modal" data-target="#import">
                        <i class="btn-icon-prepend" data-feather="download"></i>
                        Import
                        </button>
                    
                        <button type="button" class="btn btn-primary btn-icon-text mr-2 mb-2 mb-md-0 d-none d-md-block" onclick="window.open('studentcsv.php')">
                        <i class="btn-icon-prepend" data-feather="download-cloud"></i>
                        Download Excel
                        </button>
                        <a href="index.php?action=add-new-student" type="button" class="btn btn-success btn-icon-text mb-2 mb-md-0">
                        <i class="btn-icon-prepend" data-feather="plus"></i>
                        Add Student
                        </a>
                    </div><?php } ?>
					
                </div>

       
                <div class="row">
                    <div class="col-lg-12 stretch-card">
                        <div class="card">
                            <div class="card-body">                            
                                <div class="table-responsive">
                                <table id="dataTableExample" class="table table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th class="pt-0">Name</th>
                                        <th class="pt-0">ID</th>
                                        <th class="pt-0">Created Date</th>
                                        <th class="pt-0">Class</th>
                                       <?php if($_SESSION['u_type']=='admin'){?> <th class="pt-0">Actions</th>
									   <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php for($i=0; $i<count($students); $i++){ 
											$s = & $students[$i];
											if($s['image']!=''){
												$pic = '../uploads/images/students/'.$s['image'];
												if(!file_exists($pic)) $pic = '../uploads/avtar.png';
											}
											else $pic = '../uploads/avtar.png';
									?>
                                        <tr>
                                            <td>
                                            <a href="index.php?action=edit-student&id=<?=$s['std_id']?>" class="d-flex align-items-center">
                                                <div class="mr-3">
                                                <img src="<?=$pic?>" class="rounded-circle wd-40" alt="user">
                                                </div>
                                                <div class="w-100">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="text-body mt-1"><?=$s['student_name']?> <?=$s['student_lastname']?></h6>
                                                </div>
                                                </div>
                                            </a>
                                            </td>
                                            <td><?=$s['ecode']?></td>
                                            <td><?=date('d M Y',strtotime($s['date_join']))?></td>
                                            <td><span class="badge badge-info"><?=getClassName($s['dept_id'])?></span></td>
                                            
												<?php if($_SESSION['u_type']=='admin'){?>
                                               <td> <a href="index.php?action=edit-student&id=<?=$s['std_id']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i data-feather="edit-2" class="mt-2"></i>
                                                </a>
                                                
                                                <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteStudent(<?=$s['std_id']?>)">
                                                    <i data-feather="x"></i>
                                                </button></td>
												<?php } ?>
												
                                           
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                
                                </div>
                            </div> 
                        </div>
                    </div>
                
                </div> <!-- row -->
				
				<div id='result'></div>
				<!-- Modal teacher edit-->
      <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
		<form class="forms-sample" id='frmupload'>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Import Students List</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
				<div class="form-group">
					<div class="form-group">
						<input type="file" id="myDropify" name="myDropify" class="border"/>
					</div>
				</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Upload</button>
            </div>
          </div>
		  </form>
        </div>
      </div>
       <!-- Modal new teacher-->
      
				
<?php include('javascript.php') ?>

<script>
$( document ).ready(function() {
	$("#frmupload").on('submit', function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=uploadcsv-student',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='1'){
					alert('CSV uploaded successfully')
					location.reload();
				} else	
				$('#result').html(data)
			},
			cache: false,
			contentType: false,
			processData: false
		});
	})	
});

function deleteStudent(id){
if(confirm('Are you sure?')){
$.ajax({
url: 'index.php?action=student-delete&id='+id,
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