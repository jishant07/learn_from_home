<?php $students = watchingVideos($video['vid_id']);?>

<link href="../css/video-js-live.css" rel="stylesheet" />
<script src="http://vjs.zencdn.net/5.19.2/video.js"></script>
<script src="http://unpkg.com/videojs-contrib-media-sources@4.4.4/dist/videojs-contrib-media-sources.min.js"></script>
<script src="../js/dist/videojs-contrib-hlsjs.min.js"></script>



<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
	<nav class="page-breadcrumb">
		<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#"><?=getClassName($video['vid_class'])?></a></li>
		<li class="breadcrumb-item"><a href="index.php?action=live-sessions&class=<?=$video['vid_class']?>&subject=<?=$video['vid_sub']?>"><?=getSubject($video['vid_sub'])?></a></li>
		<li class="breadcrumb-item active" aria-current="page">Live</li>
		</ol>
	</nav>
	
	</div>

	<div class="row">
		<div class="col-12">
			<h4 id="default"><?=$video['vtitle']?></h4>
			<p class="mb-3"><?=$video['vdesc']?></p>
		</div>
	</div>

	


	<div class="row">
		
	<div class="col-md-7 stretch-card">
		<div class="card">
			<div class="card-body">
				<video id="player" class="player-dimensions video-js vjs-default-skin"  controls preload="none">
					<source src="<?=$video['aws_link']?>"
							type="application/x-mpegURL"/>
				</video>
				<?php $sub_start_at = $video['sub_start_at'];
				 $times =  explode(':',date('H:i:s',strtotime($sub_start_at)));
				 $start_sec = $times[0]*3600+$times[1]*60+$times[2]; 
				 $currentTimeinSeconds = time();  
				  
				 $currentDate = date('H:i:s', $currentTimeinSeconds); 
				 $times2 =  explode(':',$currentDate);
				 $current_sec = $times2[0]*3600+$times2[1]*60+$times2[2];
				 $diff = $current_sec-$start_sec;
				 ?>
				
				<script>
					var player = videojs('#player');
					player.currentTime(<?=$diff?>);
				</script>  
				<?php if($video['ref_doc']!=''){?>
				<button type="button" class="btn btn-primary btn-icon-text mt-3" onclick="window.open('<?=$video['ref_doc']?>')">
					<i class="btn-icon-prepend" data-feather="file-text"></i>
					Document
				</button>
				<?php } ?>
			</div>
		
		</div>
	</div>
	<div class="col-md-5 stretch-card">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-baseline mb-2">
					<span class="badge badge-warning"><?=count($students)?> Students Watching</span>
				
				</div>
				<div class="d-flex flex-column mt-2">
					<div class="live-scrollbar">
						<?php 
							$flag ='';	
							for($s=0; $s<count($students); $s++){
							$flag = checkRaise($students[$s]['ecode'],$_GET['id']);
						?>
						<div class="d-flex align-items-center border-bottom pt-2 pb-2">
							<div class="mr-3">
								<img src="<?=$students[$s]['image']?>" class="rounded-circle wd-30" alt="user">
							</div>
							<div class="w-100">
								<div class="d-flex justify-content-between">
									<span class="text-body mb-0"><?=$students[$s]['student_name']?>
									<?php if($flag=='0'){?><img src="assets/images/svg/hand.svg" class="wd-20" /></span>
									<button type="button" class="btn btn-success btn-xs">Allow</button>
									<?php }  ?>
								</div>
							</div>
						</div>
						<?php } ?>						
					</div>
					
					
				</div>
			</div>
		</div>
	</div>
</div> <!-- row -->
<?php include('javascript.php') ?>

			
  <script>
      var scrollbarExample = new PerfectScrollbar('.live-scrollbar');
  </script>
 