       <?php $class = $_GET['class'];?>
		<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Books</li>
            </ol>
          </nav>
        </div>
        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="mb-2">
                  
                  <button type="button" onclick="window.location.href='index.php?action=add-new-book&class=<?=$class?>'" class="btn btn-success btn-icon-text float-right">
                    <i class="btn-icon-prepend" data-feather="plus-square"></i>
                    Add New
                  </button>
                </div>
                <div class="table-responsive mt-5">
                  <table id="dataTableExample" class="table table-hover mb-0">
                    <thead>
                      <tr>
                        <th class="pt-0">Thumbnail</th>
                        <th class="pt-0">Title</th>
                        <th class="pt-0">Created Date</th>
                        <th class="pt-0">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php for($b=0; $b<count($books); $b++){					
						$k = & $books[$b];
						$file = "../uploads/my_books/".$k['book_link'];
					?>
                      <tr>
                        <td><img src="../uploads/my_books/thumb/<?=$k['book_thumb']?>" class="tableimg" /></td>
                        <td><?=$k['book_name']?></td>
                        <td><?=date('d M Y',strtotime($k['created']))?></td>
                        
                        <td>
                          
                          <a href="index.php?action=edit-book&id=<?=$k['book_id']?>&class=<?=$_GET['class']?>" class="btn btn-warning btn-icon" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i data-feather="edit-2" class="mt-2"></i>
                          </a>
                          <a href="javascript:window.open('<?=$file?>')" target="_blank" class="btn btn-info btn-icon" data-toggle="tooltip" data-placement="top" title="View">
                            <i data-feather="eye" class="mt-2"></i>
                          </a>
                          
                          <button type="button" class="btn btn-primary btn-icon" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteRecord(<?=$k['book_id']?>)">
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
		url: 'index.php?action=books-delete&id='+id,
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
