<?php $students = watchingVideos($video['vid_id']);?>
<script type="text/javascript" src="https://content.jwplatform.com/libraries/P7tGbqKk.js"></script>
<script type="text/javascript">jwplayer.key="Df1+QHbNEKwrJaZ/gVnAoOQqjosU5yycYtQcnPGsmgY=";</script>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
	<nav class="page-breadcrumb">
		<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="#"><?=getClassName($video['vid_class'])?></a></li>
		<li class="breadcrumb-item"><a href="#"><?=getSubject($video['vid_sub'])?></a></li>
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
				<!--img src="assets/images/live.jpg" width="100%" /-->
				<div id="player">
	
				</div>
				<script type="text/javascript">
				jwplayer("player").setup({
				width: '100%',
				autostart: true,
				image: "<?//=$video['vthumb']?>",
				//  logo: {file:""},
				sources: [{
				file: "<?php echo $video['videolink'] ?>"
				},{
			   // file: "rtsp://35.154.134.191:1935/live/myStream"
				}],
				/*
				rtmp: {
				bufferlength: 3
				},*/
				fallback: true,
				androidhls: true,
				aspectratio: "16:9",
				
				});
				</script>
				<?php if(file_exists($video['ref_doc'])){?>
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
 