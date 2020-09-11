<?php
function getSliders(){
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


function notice_board(){
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
		<p class="notice_time"><?php echo $notice_row['date']; ?></p>
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
?>