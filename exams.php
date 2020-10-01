<?php 
//print_r($details);
$totquestions = count($exams);//count($details);
$totsolved = count($ans);
$notsolved = $totquestions-$totsolved;
$sper = 100*($totsolved/$totquestions);
if(isset($_GET['adate']) && $_GET['adate']!='') $today =date('l, d M Y',strtotime($_GET['adate']));
else $today='Today';
	
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
$currdate = date('Y-m-d H:i:s');
//addExamAttendance();
?>
  <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <h1 class="main-title"><?=$today?> Exam</h1>                
            </div>
            <div class="col-5 col-md-2">
                <div class="date-picker">
                    
                    <input class="form-control" id="date" name="date" placeholder="Today" type="text" autocomplete='off' value="<?=$today?>" onchange="changeDate(this.value)"/>
                </div>
            </div>
        </div>
        <div class="row exam-wrapper mt-4">
            <div class="col-md-4">
                <a href="index.php?action=exam-last&eid=<?=$lastexam['evid']?>" class="last-exam-box">
                    <div class="row LastExamScore">
                        <div class="col">
                            <?php if(!empty($lastexam)){?>
						  <h1>Last Exam Score <span>Subject : <?=getSubject($lastexam['subject'])?></span></h1>
						  <div class="status"><span class="text-green"><?=$lastexam['correct']?></span> Answers</div>
						  <div class="status"><span class="text-red"><?=$lastexam['wrong']?></span> Wrong Answers </div>
						  <div class="status"><span class="text-red"><?=$lastexam['notsolved']?></span> Not submited</div>
						<?php } ?>
                        </div>					
                        <div class="col-2">
                            <div class="score"><?=$lastexam['marks']?></div>
                        </div>                        
                    </div>
                </a>
                <div class="details-box">
                    <h1>
                    You have <?=count($exams)?> Questions on <?=$today?>
                    </h1>
                    <h5><?=$notsolved?> unfinished tasks<span class="float-right">Submit before 6 PM</span></h5>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=$sper?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="subject"><span>Subject :</span> <?=$examdetails['subject_name']?></div>
                </div>

            </div>
            <div class="col-md-8">
                <ul class="examtable">
                    <div class="title">
                        <div class="row">
                            <div class="col-6">
                                <div class="name"><?=$subject?></div>
                            </div>
                            <div class="col-6">
                                <div class="scrore"><?=$totsummarks?> Marks</div>
                            </div>
                        </div>
                    </div>
					<?php
					if(count($exams)>0){	
					$closedate = $examdetails['closedate'];
					$opendate =  $examdetails['opendate'];
					if($currdate<$opendate){
					?>
					<li>
                        <div class="row">
                            <div class="col-12 col-md-7">
                                <div class="disc">Exam will start at <?php echo date('d M Y H:i A',strtotime($opendate))?></div>
                            </div>                    
                           
                        </div>
                    </li>
					<?php
					} else{	
					addExamAttendance($examdetails['evid']);
					for($i=0; $i<count($exams); $i++){
							$ex = & $exams[$i];
							$styl='';
							$ansid = checkAnswer($emp_ecode,$ex['id'],$ex['qtype']);
							if($ansid!='') {
								$status='Submited';
								$href="index.php?action=exam-single-checked&id=$ansid";
							}
							else {
								$status='Pending';
								$href="index.php?action=exam-single&id=".$ex['id']."&type=".$ex['qtype'];
								
								if($currdate>$closedate)$href="javascript:alert('Submission date is expired')";
								if($currdate<$opendate){
									$href="javascript:alert('You can not submit before start date')";
									$styl='display:none;';
									
								}
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
					}
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
			format: 'yyyy-mm-dd',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
    function changeDate(datev){
		window.location.href="index.php?action=exams&adate="+datev
	}
</script>
