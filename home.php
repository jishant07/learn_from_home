<?php 
$totquestions = count($details);
$totsolved = count($ans);
$notsolved = $totquestions-$totsolved;
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
}?><section class="container-fluid mainwrapper">
        <div class="row">
          <div class="col-lg-4 col-md-6">
            <a href="index.php?action=task" class="homepage-box bg-red" title="Tasks">
              <h1>
                You have <?=($taskstatus['furnish']+$taskstatus['unfurnish'])?> task Today
              </h1>
              <h5><?=$taskstatus['unfurnish']?> unfinished tasks</h5>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?=$taskstatus['per']?>%" aria-valuenow="<?=$taskstatus['per']?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </a>
          </div>
          
          <div class="col-lg-4 col-md-6">
            <a href="index.php?action=assignments" title="Assignments" class="homepage-box bg-green">
              <h1>
                You have <?=$assign['total']?> Assignmets Today
              </h1>
              <h5><?=$assign['notsolved']?> unfinished tasks<span class="float-right">Submit before 6 PM</span></h5>
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?=$assign['per']?>%" aria-valuenow="<?=$assign['per']?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </a>
          </div>

          <div class="col-lg-4 col-md-12">
            <a href="index.php?action=exams" title="Exams" class="homepage-box bg-yellow">
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
			  <?php if(!empty($nextexam)){?>
               <div class="row LastExamScore mt-2">
                <div class="col">
                  <h1>Next Exam</h1>                  
                </div> 
				<div class="col">                 
                  <div class="exam-time">On <?=date('D h:i A',strtotime($nextexam['opendate']))?></div>
                </div> 
				<div class="col">                  
                  <div class="exam-time"><span class="float-right">Subject : <?=getSubject($nextexam['subject'])?></span></div>
                </div> 				
              </div>
			  <?php } ?>
            </a>
          </div>
        </div>
      </section>
      
      <section class="live-home container-fluid mainwrapper mt-4">
        <div class="row">
          <div class="col-12">
            <div class="live-loop" id="live-loop">
                <?php 
				
				for($i=0;$i<count($livesession);$i++){
					$day = date('l',strtotime($livesession[$i]['sub_start_at']));
					$cday = date('l');
					
					 $tomday = date("l", strtotime('tomorrow'));
					if($cday==$day) $showday='Today';
					else if($tomday==$day) $showday='Tomorrow';
					else $showday= $day;					
					 $todate=date('Y-m-d H:i:s');
					$sub_start_at=$livesession[$i]['sub_start_at'];
					$sub_end_at=$livesession[$i]['sub_end_at'];
					$cls='';
				if($todate>=$sub_start_at && $todate<=$sub_end_at){
					$showday='Live'; $cls='live';
				}
				if($sub_end_at>=$todate){
				
					?>
				<div class="item">
                    <a href="index.php?action=live-video&id=<?=$livesession[$i]['id']?>" class="live-thumb-home <?=$cls?>">
                      <img src="<?=$livesession[$i]['tpic']?>" />
                      <h4><?=$livesession[$i]['subject_name']?></h4>
                      <h5><?php echo $showday?> <?php if($showday!='Live') echo', ', date('h:i A ',strtotime($livesession[$i]['sub_start_at']))?></h5>
                    </a>
                </div>
                <?php } ?>
                <?php } ?>
              
            </div>
          </div>
        </div>
      </section>
      
      <section class="videos-tab-home container-fluid mainwrapper mt-4">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#continue-watching" role="tab" aria-controls="continue-watching" aria-selected="true" title='Continue Watching'>Continue Watching</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#session" role="tab" aria-controls="session" aria-selected="false" title='LATEST VIDEOS'>LATEST VIDEOS</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#books" role="tab" aria-controls="books" aria-selected="false" title='BOOKS'>BOOKS</a>
          </div>
        </nav>
        <div class="tab-content mt-3" id="nav-tabContent">
          <div class="videos-continue tab-pane fade show active" id="continue-watching" role="tabpanel" aria-labelledby="continue-watching-tab">
            <div class="continue-loop" id="continue-loop">
              <?php for($i=0;$i<count($pvideos);$i++){
				  $p = & $pvideos[$i];
				$vthumb = $p['vthumb']  ;
				$per = ($p['watchtime']*100)/$p['duration'];
				
			  ?>
			  <div class="item">
                  <a href="index.php?action=history-videos&id=<?=$pvideos[$i]['id']?>" title="<?=$pvideos[$i]['vtitle']?>" class="video-thumb d-flex align-items-end" style="background: url('<?=$vthumb?>');">
                    <div class="content">
                      <div class="text">
                        <?=$pvideos[$i]['vtitle']?>
                      </div>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=$per?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </a>
              </div>
              <?php } ?>
            
            </div>
          </div>		  
          <div class="videos-session tab-pane fade" id="session" role="tabpanel" aria-labelledby="session-tab">
            <div class="session-loop" id="session-loop">
             <?php for($l=0;$l<count($latestvideos);$l++){ 
					$lvid=  $latestvideos[$l];					
					$vthumb = $lvid['vthumb'];
					$href="index.php?action=history-videos&id=".$lvid['id'];
			  ?>
			 <div class="item">
                <a href="<?=$href?>" class="video-thumb d-flex align-items-end" style="background: url('<?=$vthumb?>');" title="<?=$lvid['title']?>">
                    <div class="content">
                        <h1><?=$lvid['title']?></h1>
                        <div class="text">
                        <?=$lvid['subject_name']?> 
                        </div>
                    </div>
                </a>
              </div>
             <?php } ?>              
            </div>
          </div>
          <div class="book-session tab-pane fade" id="books" role="tabpanel" aria-labelledby="books-tab">
            <div class="book-loop" id="book-loop">
             <?php
			for($b=0;$b<count($books);$b++){
				$bimg =  & $books[$b]['book_thumb'];
			?>
			 <div class="item">
                <a href="index.php?action=pdf&type=book&file=<?php echo $books[$b]['book_link'];?>" class="book-thumb d-flex align-items-end" style="background: url('<?php echo $bimg ;?>');">
                    
                </a>
              </div>
              <?php
			 }
			?>            
            </div>
          </div>          
        </div>        
      </section>
	   <?php include('javascript.php') ?>