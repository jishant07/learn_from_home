      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <a href="index.php?action=exams" class="main-title-link"><img src="images/icons/left-arrow.svg" /> BACK</a>
                
            </div>
            
        </div>
        <div class="row exam-signle-wrapper mt-4">
            <div class="exam-question col-md-6">
                <div class="marks"><?=$tasks['marks']?> Marks</div>
                <h1><?=$tasks['question']?></h1>
                <p></p>
                <!---a href=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Ref. Document</a-->
            </div>

            <div class="exam-answer col-md-6">
                <h1>YOUR ANSWER</h1>
                <div class="status-bar"><?php if($ans['marks']>0){?><div class="ans-status currect">Currect	</div>
				<?php } else {?><div class="ans-status incurrect">Incurrect	</div>
				<?php } ?>
				<div class="marks">Marks <?=$ans['marks']?>/<?=$tasks['marks']?></div></div>
                <p><?=$ans['answer']?></p>
               <?php if($ans['document']!=''){
					$doc='uploads/evaluation/'.$ans['document'];
					if(file_exists($doc)){
					?>
				
                <div class="attachment">
                    <a href="javascript:window.open('<?=$doc?>')" class="document"><i class="fa fa-file-text-o" aria-hidden="true"></i> Document Name</a>
                </div>
					<?php } 
				} ?>
                <h2>TEACHER FEEDBACK</h2>
                <div class="feedback">
                <?php if($ans['teacher_feedback']!='') echo stripslashes($ans['teacher_feedback']); else echo'No Feedback'?>
                </div>
            </div>
        </div>
        
      </section>
    </div>
    <?php include('javascript.php') ?>