<script type="text/javascript" src="https://content.jwplatform.com/libraries/P7tGbqKk.js"></script>
<script type="text/javascript">jwplayer.key="Df1+QHbNEKwrJaZ/gVnAoOQqjosU5yycYtQcnPGsmgY=";</script>
<?php

$videolink = $_GET['videolink'];
?>
<div class="video">
                    <div id="player">
                    
					</div>
                </div>
				<script type="text/javascript">
				jwplayer("player").setup({
				width: '100%',
				autostart: true,
				image: "<?//=$video['vthumb']?>",
				//  logo: {file:""},
				sources: [{
				file: "<?php echo $videolink ?>"
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