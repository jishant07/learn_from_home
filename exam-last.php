<?php
$details = $lastexam['qdetails'];
$totquestions = count($details);
$totsolved = count($ans);
$notsolved = $totquestions-$totsolved;
//$per= 100*$totsolved/$totquestions;
$twrong=0;
$tcorrect=0;
$obtmarks=0;
$totsummarks=0;
for($d=0; $d<count($details); $d++){
	$totsummarks=$totsummarks+$details[$d]['marks'];
}
for($i=0; $i<count($ans); $i++){
	$a = & $ans[$i];
	if($a['marks']>0) { $tcorrect++;} 
	else { $twrong++;}
	$obtmarks = $obtmarks+$a['marks'];
}					
?>
      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <h1 class="main-title"><?php if($lastexam['opendate']!='') echo date('d M Y',strtotime($lastexam['opendate'])) ?></h1>
                
            </div>
            <div class="col-5 col-md-2">
                <div class="date-picker">
                    
                    <input class="form-control" id="date" name="date" type="text" autocomplete='off'/>
                </div>
            </div>
        </div>
        <div class="row exam-wrapper mt-4">
            <div class="col-md-4">
                <a href="" class="last-exam-box">
                    <div class="row LastExamScore">
                        <div class="col">
                            <!--h1>Subject : <span><?=$subject?></span></h1>
                            <div class="status"><span class="text-green"><?=$tcorrect?></span> Answers</div>
                            <div class="status"><span class="text-red"><?=$twrong?></span> Wrong Answers </div>
                            <div class="status"><span class="text-red"><?=$notsolved?></span> Not submited</div-->
							 <?php if(!empty($lastexam)){?>
						  <h1>Last Exam Score <span>Subject : <?=getSubject($lastexam['subject'])?></span></h1>
						  <div class="status"><span class="text-green"><?=$lastexam['correct']?></span> Answers</div>
						  <div class="status"><span class="text-red"><?=$lastexam['wrong']?></span> Wrong Answers </div>
						  <div class="status"><span class="text-red"><?=$lastexam['notsolved']?></span> Not submited</div>
						<?php } ?>
                        </div>
                        <div class="col-2">
                            <div class="score"><?=$obtmarks?></div>
                        </div>
                        
                    </div>
                </a>

            </div>
            <div class="col-md-8">
                <ul class="examtable">
                    <div class="title">
                        <div class="row">
                            <div class="col-6">
                                <div class="name"><?=$subject?></div>
                            </div>
                            <div class="col-6">
                                <div class="scrore"><?=$obtmarks?>/<?=$totsummarks?> Marks</div>
                            </div>
                        </div>
                    </div>
					<?php
					if(count($lastexam['qdetails'])>0){	
					for($i=0; $i<count($lastexam['qdetails']); $i++){
							$ex = & $lastexam['qdetails'][$i];
							$styl='';
							$ansid = checkAnswer($emp_ecode,$ex['id'],$ex['qtype']);
							if($ansid!='') {
								$status='Submited';
								$href="index.php?action=exam-single-checked&id=$ansid";
							}
							else {
								$status='Pending';
								$href="#";//index.php?action=exam-single&id=".$ex['id']."&type=".$ex['qtype'];
								
							}
							
					?>
                    <li>
                        <div class="row">
                            <div class="col-12 col-md-7">
                                <div class="disc" style='<?=$styl?>'><span><?=$i+1?>.</span> <?=$ex['question']?></div>
                            </div>
                            
                            <div class="col-6 col-md-2">
                                <div class="status <?=strtolower($status)?>"><?=$status?></div>
                            </div>
                            <div class="col-2 col-md-1 text-right">
                                <a href="<?=$href?>" data-toggle="tooltip" data-placement="top" title="View" class="btn-sky d-flex align-items-center justify-content-center"><img src="images/icons/view_task.svg" /></a>
                            </div>
                            <div class="col-4 col-md-2 marks">
                                <?=$ex['marks']?> Marks
                            </div>
                        </div>
                    </li>
                    <?php } 
					} else { ?>
                    <div class='alert alert-info'>No records found!</div>
                    <?php } ?>
				  
                </ul>
            </div>
        </div>
      </section>
    </div>
    <?php include('javascript.php') ?>
  
<script>
	$(document).ready(function(){
		var date_input=$('input[name="date"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'mm/dd/yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
    
</script>
