      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <a href="index.php?action=assignments" class="main-title-link"><img src="images/icons/left-arrow.svg" /> BACK</a>
                
            </div>
            
        </div>
        <div class="row assignment-signle-wrapper mt-4">
            <div class="assignment-question col-md-6">
                <div class="sub"><?=$tasks['subject_name']?></div>
                <h1><?=$tasks['question']?></h1>
                <p></p>
                <a href=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Ref. Document</a>
            </div>

            <div class="assignment-answer col-md-6">
                <h1>YOUR COMMENT</h1>
                <p><?=$ans['answer']?></p>
                <div class="attachment">
                    <a href="" class="document"><i class="fa fa-file-text-o" aria-hidden="true"></i> Document Name</a>
                </div>
				<?php if($ans['teacher_feedback']!=''){?>
                <h2>TEACHER FEEDBACK</h2>
                <div class="feedback">
                <?=$ans['teacher_feedback']?>
                </div>
                <?php } ?>
            </div>
        </div>
        
      </section>
   	   <?php include('javascript.php') ?>