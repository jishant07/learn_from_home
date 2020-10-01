<?php


?>     

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
				<?php if($tasks['document']!=''){
					$doc='uploads/evaluation/referdoc/'.$tasks['document'];
					
					?>
                <a href="javascript:window.open('<?=$doc?>')"><i class="fa fa-file-text-o" aria-hidden="true"></i> Ref. Document</a>
				<?php } ?>
            </div>
			
            <div class="assignment-answer col-md-6">
                <h1>YOUR COMMENT</h1>
                <form method='post' action="index.php?action=submit_assignment" enctype="multipart/form-data">
					<input type='hidden' name='qid' value="<?=$tasks['id']?>">
					<input type='hidden' name='evid' value="<?=$tasks['evid']?>">
					<input type='hidden' name='qtype' value="<?=$_GET['type']?>">
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Comment" name='answer' id='answer' rows="6"></textarea>
                    </div>
                
                <!--div class="attachment">
                    <a href="" class="document"><i class="fa fa-file-text-o" aria-hidden="true"></i> Document Name</a>
                    <a href="" class="delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                </div-->
                <?php //if($tasks['uploadflag']=='1'){?>
				<input type='file' name='uploaddoc' class="leftfl upload " >
                
				<?php //} ?>
                <div class="clearfix"></div><br>
                <input type='submit' name='submit' class="button2 btn-red" value='SUBMIT'>
				</form>
            </div>
			
        </div>
        
      </section>
   	   <?php include('javascript.php') ?>