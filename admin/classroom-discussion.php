<?php $classn = $_GET['class']?>   
   <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Classroom Discussion</li>
            </ol>
          </nav>          
        </div>
        
        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-baseline mb-2">
                  <h6 class="card-title mb-0"></h6>
                  <button type="button" onclick="window.location.href='index.php?action=start-new-discussion&class=<?=$classn?>'" class="btn btn-success btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Start New
                  </button>
                </div>
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0 wd-300">Title</th>
                        <th class="pt-0">Created Date</th>
                        <th class="pt-0">Created By</th>
                        <th class="pt-0">Total Comments</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php for($i=0; $i<count($data); $i++){
						$d = & $data[$i];
						$std = getStudentInfo($d['ecode']);
					?>
                      <tr>
                        <td><?=$d['details']?></td>
                        <td><span class="badge badge-success"><?=date('d M Y',strtotime($d['date']))?></span></td>
                        <td>
                          <div class="d-flex align-items-center discussionstudent">
                            <div class="mr-1">
                              <img src="../uploads/images/students/<?=$std['image']?>" class="rounded-circle" alt="user">
                            </div>
                            <div class="w-100">
                              <div class="d-flex justify-content-between">
                                <h6 class="text-body"><?=$std['student_name']?></h6>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td><span class="badge badge-warning"><?=$d['cnt']?></span></td>
                        <td>
                          
                          <a href="index.php?action=single-discussion&id=<?=$d['ask_id']?>" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="view">
                            <i data-feather="eye" class="mt-2"></i>
                          </a>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$d['ask_id']?>)">
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
function deleteRecord(id){
	if(confirm('Are you sure?')){
	$.ajax({
		url: 'index.php?action=discussion-delete&id='+id,
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
