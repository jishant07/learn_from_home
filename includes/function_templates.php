<?php
function tmp_getSliders(){
	global $conn;
	$sq_slides = $conn -> query("select * from slider where enb='1'"); 
	if ($sq_slides->num_rows > 0):
                  ?>
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
	  <?php
		$slide_index = 0;
		while($slide_row = $sq_slides->fetch_assoc()):
	  ?>
	  <div class="item <?php echo ($slide_index=='0')?"active":"";?>">
		<img src="../uploads/images/slides/<?php echo $slide_row['image'];?>" alt="...">
		<div class="carousel-caption">
		  <div class="banner_txt_wrapper">
			<h2 class="banner_heading"><?php echo $slide_row['title'];?></h2>
			<p class="banner_subtxt"><?php echo $slide_row['cont'];?></p>
		  </div>
		</div>
	  </div>
	  <?php
		  $slide_index++;
		endwhile;
	  ?>
	</div>

	<!-- Controls -->
	<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
	  <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	  <span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
	  <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	  <span class="sr-only">Next</span>
	</a>
  </div>

  <?php
	endif;
  
}


function tmp_notice_board(){
	global $conn;
	?>
	<div class="notice_board_wrapper">
	<p class="notice_heading">Notice Board</p>
	<?php
	  $sq_notice = $conn -> query("select * from notice where status='1' LIMIT 10 "); 
		if ($sq_notice->num_rows > 0):
	?>
	<marquee behavior="scroll" height="400px" direction="up"  onmouseover="this.stop();" onmouseout="this.start();">
	  <?php
		while($notice_row = $sq_notice->fetch_assoc()):
	  ?>
	  <div class="notice_row">
		<p class="notice_time"><?php echo date('d M Y',strtotime($notice_row['date'])); ?></p>
		<p class="notice_details">
		  <b><?php echo $notice_row['notice_title']; ?></b><br>
		  <?php echo $notice_row['notice_details']; ?>
		</p>
	  </div>
	  <?php
		endwhile;
	  ?>
	</marquee>
	<?php
	  else:
	?>
	<div class="notice_row">
	  <p class="notice_details">No Notice to display</p>
	</div>
	<?php
	  endif;
	?>
  </div>
  <?php 
}

function tmp_getTodaysBirthdays(){
	global $conn;
	?>
	<div class="bday_tbl_wrapper">
	  <table class="table">
		<tbody>
		  <?php
			$today_date = date('m-d');
			$sql_stud_bday= $conn -> query("SELECT * FROM students where status='1' AND  DATE_FORMAT(`date_birth`, '%m-%d')='$today_date'");
			if($sql_stud_bday->num_rows > 0):
			  while($stud_bday_row = $sql_stud_bday->fetch_assoc()):
		  ?>
		  <tr class="bday_row">
			<td class="bday_img">
			  <img src="assets/img/teacher.png" class="img-responsive"/>
			</td>
			<td class="bday_name">
			  <?php echo $stud_bday_row['student_name'];?>
			</td>
			<td class="bday_icon">
			  <img src="assets/img/bday_ic.JPG" class="img-responsive"/>
			</td>
		  </tr>
		  <?php
			  endwhile;
			endif;
		  ?>
		</tbody>
	  </table> 
	</div>
<?php	
}

function tmp_getAchievements(& $achivements){
	global $conn;
?>
<div class="bday_tbl_wrapper">
  <table class="table">
	<tbody>
	 <?php for($i=0;$i<count($achivements);$i++){ 
	  //$img = $achivements[$i]['sthumb'];
	  ?>
	  <tr class="bday_row">
		<td class="bday_img">
		  <img src="assets/img/teacher.png" class="img-responsive"/>
		</td>
		<td class="bday_name">
		  <?=$achivements[$i]['winner_name']?>
		  <span class="achhive_topic"><?=$achivements[$i]['title']?></span>
		</td>
		<td class="achive_rank">
		  <p class="rank_ttl">1<sup>st</sup></p>
		</td>
	  </tr>
	  <?php } ?>
	</tbody>
  </table> 
</div>
<?php }

function tmp_getTalkTeacher(& $talks){
	global $conn;
	for($i=0;$i<count($talks);$i++){ ?>
	 <div class=" col-md-12 pdlr0 tt_detailsrow">
		<p class="tt_detail_txt"><?=$talks[$i]['q_details']?></p>
		<div class="col-md-12 pdlr0">
		  <div class="col-md-8 pdlr0">
			<p><img src="assets/img/teacher.png" class="img-responsive"/>
			  <span><?=$talks[$i]['student_name']?></span>
			</p>
		  </div>
		  <div class="col-md-4 pdlr0">
			<p class="tt_date"><?=$talks[$i]['qdate']?></p>
		  </div>
		</div>
	  </div>
<?php } 
}

function tmp_getTimeTable(& $time){	
?>
<table class="table">
  <tbody>
	<?php
	 for($i=0;$i<count($time);$i++){
	?>
	<tr class="tt_row">
	  <td class="tt_desc">
		<?php
		  echo $time[$i]['tt_desc'];
		?>  
	  </td>
	  <td class="tt_time">
	  <?php
		echo $time[$i]['period_slot'];
	  ?>
	  </td>
	</tr>
	<?php
		}
	?>
  </tbody>
</table>
<?php
}

function tmp_getClassStudents(& $students){
	if(count($students)>0){		
?>
<table class="table">
  <tbody>
	<?php
	for($c=0;$c<count($students);$c++){
	?>
	<tr class="stud_row">
	  <td class="stud_img">
		<img src="assets/img/teacher.png"> 
	  </td>
	  <td class="stud_name">
		<?php echo $students[$c]['student_name']; ?>
	  </td>
	</tr>
	<?php
	}
	?>
  </tbody>
</table>
<?php
		
	}	
}

function tmp_getAskQuestionReply(& $ask_id){
	global $conn;
	$sqla="select * from ask_question_reply where ask_id='$ask_id'";
	$resa = mysqli_query($conn,$sqla);
	?>	
	<div class="container">
	<div class="row">
	<div class="col-md-4">
	  Answer
	</div>
	<div class="col-md-4">
	  Sent By
	</div>
	<div class="col-md-4">
	  Date
	</div>
	</div>


	<?php 
	while($rowa=mysqli_fetch_array($resa)){
		?>			
		<div class="row">
			<div class="col-md-4">
			  <?=$rowa['msg']?>
			</div>
			<div class="col-md-4">
			  <?php if($rowa['sentby']=='S') echo 'SELF'; else echo 'Teacher'?>
			</div>
			<div class="col-md-4">
			  <?=date('d m Y H:i A',strtotime($rowa['date']))?>
			</div>
		</div>

		<?php
	}
	}
?>
