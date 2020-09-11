		<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Subjects</li>
            </ol>
          </nav>
        </div>
		
        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"></h6>
                  <button type="button" data-toggle="modal" data-target="#subject" class="btn btn-success btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Add New
                  </button>
                </div>
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Subject Name</th>
                        <th class="pt-0">Created Date</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php for($i=0; $i<count($subjects); $i++){
						$s = & $subjects[$i];
						$subject_name = $s['subject_name'];
					?>	
                      <tr>
                        <td><?=$s['subject_name']?></td>
                        <td><?=date('d M Y',strtotime($s['subject_createdat']))?></td>
                        <td>
                          
                          <button type="button" class="btn btn-warning btn-icon" data-toggle="modal" data-target="#subject" onclick="getSubject('<?=$subject_name?>',<?=$s['subject_id']?>)">
                            <i data-feather="edit-2"></i>
                          </button>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$s['subject_id']?>)">
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
			
    <!-- Modal subject -->
      <div class="modal fade" id="subject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add/Edit Subject</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="forms-sample" action="index.php?action=actionsubject" method='post'>
				<div class="modal-body">                
					<input type='hidden' name='subid' id='subid'>
					<input type='hidden' name='classid' id='classid' value="<?=$_GET['class']?>">
					<div class="form-group">										
                    <label>Subject Name</label>
                    <input type="text" class="form-control" Value="" id='subname' name='subname'>					
					</div>                
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="clearForm()">Close</button>
				  <button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
          </div>
        </div>
      </div>
<script>
function clearForm(){
$('#subname').val('')		
	$('#subid').val('')		
	
}
function getSubject(name,id){
	$('#subname').val(name)		
	$('#subid').val(id)	
}
function deleteRecord(id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=subject-delete&id='+id,
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