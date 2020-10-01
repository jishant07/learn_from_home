<?php //print_r($exam)?>        
		<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?=getClassName($exam['class'])?></a></li>
              <li class="breadcrumb-item"><a href="index.php?action=exams&class=<?=$exam['class']?>&subject=<?=$exam['subject']?>">Exams</a></li>
              <li class="breadcrumb-item"><a href="index.php?action=exam-student-list&id=<?=$_GET['id']?>&class=<?=$exam['class']?>"><?=date('d M Y',strtotime($exam['opendate']))?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><?=$student_info['student_name'].' '.$student_info['student_lastname']?></li>
            </ol>
          </nav>
          
        </div>


        <div class="row">
          <div class="col-lg-12 mb-3">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
									
                  <div class="form-group d-inline-block">
					<div class="mr-5">
                      <img src="../uploads/images/students/<?=$student_info['image']?>" class="rounded-circle" width=60 alt="<?=$student_info['student_name'].' '.$student_info['student_lastname']?>"> <?=$student_info['student_name'].' '.$student_info['student_lastname']?>
                    </div>
                    
                  </div>
                  <div class="d-inline-block float-lg-right">
                    <label class="d-inline-block mt-2 mr-2">Marks</label>
                    <input type="text" class="form-control d-inline-block wd-80" value="0" readonly >
                  </div>
                  <!--hr>			
				  <button type="submit" class="btn btn-primary mr-2 mt-2">Mark As Checked</button-->
				</form>
               
                
              </div> 
            </div>
          </div>
          <div class="col-lg-12">
            <!--question box-->
			<?php 
			$ans = array();
			for($i=0; $i<count($examdetails); $i++){
				  $q= & $examdetails[$i];
					$ans = getAnswerofQuestionByStudent($q['id'],$q['qtype'],$_GET['student']);	
					//print_r($ans);
					$readonly="readonly";
					$refdoc ='';
					if($q['qtype']=='tbl_freetext' || $q['qtype']=='tbl_questiondoc'){
						if($q['referdoc']!='') $refdoc = '../uploads/evaluation/referdoc/'.$q['referdoc'];
						$readonly="";
					}			
					else if($q['referdoc']!='') $refdoc = '../uploads/evaluation/referdoc/'.$q['referdoc'];
				?>
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    
                    <div class="row">
                      <div class="col-12">
                        <strong><?=stripslashes($q['question'])?></strong>
                        <p><?=stripslashes($q['description'])?></p>
						<?php if(file_exists($refdoc)){?>
                        <a href="javascript:window.open('<?=$refdoc?>')" class="badge badge-primary" target="_blank"><?=$q['referdoc']?></a>
						<?php } ?>
                      </div>
                      
                    </div>
                    <hr>
					<form class="mt-4" id='frmsubmit<?=$i?>'>	
					<input type='hidden' name='ansid<?=$i?>' id='ansid<?=$i?>' value="<?=$ans['id']?>">
                    <div class="row">
                      <div class="col-12">
                        <strong>Answer</strong>
                        <p><?php if(trim($ans['answer'])!='') echo stripslashes($ans['answer']); else echo 'Not Answered'?></p>
						<?php if($ans['document']!=''){
							$doc = '../uploads/evaluation'.$ans['document'];
							if(file_exists($doc)){
							?>
                        <a href="javascript:window.open('<?=$doc?>')" class="badge badge-warning" target="_blank"><?=$ans['document']?></a>
                        <?php } 
						}
						?>
                            <div class="form-group">
                              <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input" name="ques_result<?=$i?>" id="Currect<?=$i?>" value="correct" <?php if($ans['answer_result']=='correct') echo'checked';?>>
                                  Currect
                                </label>
                              </div>
                              <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" class="form-check-input" name="ques_result<?=$i?>" id="Wrong<?=$i?>" value="wrong" <?php if($ans['answer_result']=='wrong') echo'checked';?>>
                                  Wrong
                                </label>
                              </div>
									          </div>
                            <div class="form-group">
                                <label>Your Feedback</label>
                                <textarea class="form-control" rows="5" id='feedback<?=$i?>' name='feedback<?=$i?>'><?=stripslashes($ans['teacher_feedback'])?></textarea>
                            </div>
                       
                      </div>
                    </div>
                    <hr>
					<?php if(!empty($ans)){?>
                    <div class="row mt-4">
                        <div class="col-6">
                            <label class="d-inline-block mt-2 mr-2">Marks</label>
                           
							<input type="text" class="form-control d-inline-block wd-80" id='amarks<?=$i?>' name='amarks<?=$i?>' value="<?=$ans['marks']?>" <?=$readonly?>>
							
                            <button type="submit" class="btn btn-success ml-2" name='submit<?=$i?>'>Save</button>
                        </div>                        
                    </div>
					<?php } ?>
                   </form>
                    
                  </div> 
                </div>
              </div>
            </div>
			<?php } ?>
            
          </div>
        </div> <!-- row -->
        
 <?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
	<?php for($i=0; $i<count($examdetails); $i++){?>
	$("#frmsubmit<?=$i?>").on('submit', function(e) {
		e.preventDefault();
		//if(courseValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=changeStatusExam&i=<?=$i?>',
			data: formData,
			type: 'POST',
			success: function(data) {
				//alert(data)
				location.reload();
				//$("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		//}
	})
	<?php } ?>
});
</script>