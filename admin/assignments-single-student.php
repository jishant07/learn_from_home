        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($assign['class'])?></a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="index.php?action=assignments&class=<?=$assign['class']?>&subject=<?=$assign['subject']?>">Assignments</a></li>
              <li class="breadcrumb-item" aria-current="page"><a href="index.php?action=assignments-student-list&id=<?=$assign['id']?>&type=<?=str_replace('tbl_','',$data['question_type'])?>">Name of Assignments</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?=$data['student_name']?></li>
            </ol>
          </nav>
         
        </div>

        <?php 
		//print_r($data);
		if(file_exists($data['image'])) $img = $data['image']; else $img = '../uploads/avtar.png';
		$feedback ='';	
		if(trim($data['teacher_feedback'])!='') $feedback = stripslashes(trim($data['teacher_feedback']))?>

        <div class="row">
          <div class="col-lg-12 stretch-card">
            <div class="card">
              <div class="card-body">
                
                <div class="row mt-4 mb-4">
                  <div class="col-12">
                        <img src="<?=$img?>" class="rounded-circle" width="50" alt="user"><?=$data['student_name']?>
                        <h6 id="default" class="mt-4">Comments</h6>
                        <p class="mb-3"><?=$data['answer']?> </p>
                        <?php
						$document = '../uploads/evaluation/'.$data['document'];
						if($data['document']!==''){	
						?>
						<a href="" class="btn btn-success" onclick="window.open('<?=$document?>')"><span class="wd-20 mr-2" data-feather="file-text"></span> Document</a>
						<?php } ?>
                  </div>
                  <div class="col-12 mt-5">
						<div id='result' class=''></div>
                        <form id='frmsubmit' method='post'>	
							<input type='hidden' name='ansid' value="<?=$_GET['id']?>">
                            <div class="form-group">
                                <label>Your Feedback</label>
                                <textarea class="form-control" rows="5" id='feedback' name='feedback'><?=$feedback?></textarea>
                            </div>
                            <input type='checkbox' name='testcheck' value='2' <?php if($data['status']==2) echo'checked'?>>
                            <button class="btn btn-primary" type="submit">SUBMIT FEEDBACK</button>
                        </form>

                  </div>
                </div>
                
              </div> 
            </div>
          </div>
        </div> <!-- row -->        
<?php include('javascript.php') ?>
<script>
$( document ).ready(function() {

	$("#frmsubmit").on('submit', function(e) {
		e.preventDefault();
		//if(courseValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=changeStatusAssignment',
			data: formData,
			type: 'POST',
			success: function(data) {
				$("#result").addClass('alert alert-success')
				$("#result").html('Your feedback submitted successfully');
				setTimeout(function(){ $("#result").removeClass();$("#result").html(''); }, 3000);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		//}
	})
	
});
</script>