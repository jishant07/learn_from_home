<?php
$type=$_GET['type'];
$question =$exam['question'];
?>   
   <?php include('javascript.php') ?>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
  } );
  </script> <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <a href="index.php?action=exams" class="main-title-link"><img src="images/icons/left-arrow.svg" /> BACK</a>
                
            </div>
            
        </div>
        <div class="row exam-signle-wrapper mt-4">
            <div class="exam-question col-md-6">
                <div class="marks"><?=$exam['marks']?> Marks</div>
                <h1><?=$exam['question']?></h1>
                <p></p>
                <a href=""><i class="fa fa-file-text-o" aria-hidden="true"></i> Ref. Document</a>
            </div>

            <div class="exam-answer col-md-6">
                <h1>YOUR ANSWER</h1>
				<form action='index.php?action=submitexam' method='post' autocomplete='off' enctype="multipart/form-data">
				<input type='hidden' name='type' value="<?=$type?>">
				<input type='hidden' name='id' value="<?=$exam['id']?>">
				<input type='hidden' name='marks' value="<?=$exam['marks']?>">
				<input type='hidden' name='answer' value="<?=$exam['answer']?>">
				<input type='hidden' name='evid' value="<?=$exam['evid']?>">
				<input type='hidden' name='correctans' value="<?=$exam['answer']?>">
				 <div class="form-group">
				<?php if($type=='tbl_fillblank'){
					$fields = explode('...',trim($question));
					//print_r($fields)
					$field= "<input type='text' name='fillblankans[]'>";
					echo str_replace('...', $field, $question);
					?>
				
				
				<?php } else if($type=='tbl_match'){
					
					$cols1ex = explode('-',$exam['cols1']);
					$cols2ex = explode('-',$exam['cols2']);
					$anscolsex = explode('-',$exam['answer']);					
					?>
					
					<div id='matchrows'>
						<div class='matchcol'>
						<ul id="sortable3" class='droptruex sortable3'>
						<?php for($c=0;$c<count($cols1ex);$c++){?>				
								<li id="que<?=$c+1?>"><?php echo $cols1ex[$c] ?> </li>       
							
						<?php } ?></ul>
						</div>
						<div class='matchcol' id='swapcols'>
						<ul id="sortable" class='droptrue sortable3'>
						<?php for($c=0;$c<count($cols1ex);$c++){?>			
							 <li><?php echo $cols2ex[$c]?><input type=hidden name=matchans[] value='<?php echo $cols2ex[$c]?>'><span class='fa fa-arrows' aria-hidden='true'></span> </li>
							<!--li id="ans<?=$c+1?>"></li-->            
						
					<?php } ?></ul>
						</div>
					</div>
                <?php } else if($type=='tbl_multiplechoice'){ 
				$optionsex = explode('-',$exam['options']);
				$answersex = explode('-',$exam['answer']);	
				?><div class='radio-ans'>
				<?php
				for($o=0;$o<count($optionsex);$o++){
					$ch =$o+1;
				?>				
				 
					<label>
					<input type="checkbox" class="option-input checkbox" id="mulans<?=$ch?>" name="mulans[]" value="<?=$ch?>" > <?php echo $optionsex[$o]?></label> 				
				
		<?php } ?></div>
				
				 <?php } else if($type=='tbl_singlechoice'){ 
				 $optionsex = explode('-',$exam['options']);
				 ?><div class='radio-ans'>
				 <?php
				 for($o=0;$o<count($optionsex);$o++){				
					?>
				
					 <label><input type="radio" class="option-input radio" id="sinans<?=$o+1?>" name="singleanswer" value="<?=$o+1?>" /> <?php echo $optionsex[$o]?> </label>				

		<?php } ?></div>
				<?php } 
				
				else if($type=='tbl_questiondoc'){?>
				<input type='file' name='uploaddoc' class="leftfl upload " >
                <div class="clearfix"></div>
				<?php 
				}
				else if($type=='tbl_freetext'){ ?>
				<div class="form-group">
                    <textarea class="form-control" placeholder="ANSWER" name='freeanswer' id='freeanswer' rows="6"></textarea>
                </div>
                
                <div class="clearfix"></div>
				<?php } ?>
				</div>
								 <div class="form-group">
                <input type='submit' class="button2 btn-red" value='SUBMIT'>
				</div>
				</form>
            </div>
        </div>
        
      </section>
     
    