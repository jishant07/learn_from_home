<section class="container-fluid video-list mainwrapper">
<div class="row">
	<?php for($i=0; $i<count($subjects); $i++){ 
		$sub = & $subjects[$i];	
		$thumb = $sub['sthumb'];
		$course = getLatestCourseBySubject($sub['subject_id'],$emp_ecode);
		//print_r($course);
		 $cid = & $course['id'];
		 if($cid=='') $href='#'; else $href="index.php?action=history-videos&cid=$cid";
	?>
   <div class="col-lg-3 col-md-4 col-6">
		<a href="<?=$href?>" class="video-thumb d-flex align-items-end" style="background: url('<?=$thumb?>');">
			<div class="content">
				<h1><?=$sub['subject_name']?></h1>
				<div class="text">
				<?=getTotalCourseBySubject($sub['subject_id'])?> Courses
				</div>
			</div>
		</a>
	</div>
	<?php } ?>            
</div>
</section>
     <?php include('javascript.php') ?>
