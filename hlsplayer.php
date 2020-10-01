<script src="https://vjs.zencdn.net/7.8.4/video.js"></script>
<script src="js/dist/videojs-contrib-hlsjs.min.js"></script>
<script src="js/dist/videojs-contrib-hlsjs.min.js.map"></script>
<script src="js/src/videojs.hlsjs.js"></script>
<script>
    var oldTime;
    var initTime;
    var studentId = "<?=$emp_ecode?>";
    var videoId = "<?=$vid_id?>";
    var surl = "<?=$surl?>";
    var player = videojs('player', {
                autoplay: true,
                html5: {
                    hlsjsConfig: {
                        debug: true
                    }
                }
            });
		//	alert(player.duration())
    $.ajax({
        url:surl+'?studentID='+studentId+"&action=search&videoID="+videoId,
        method:"GET",
        success: (res) =>{
				
                parsedJSON = JSON.parse(res);
                this.player.currentTime(parsedJSON.playtime)
                this.player.src({
                    src: parsedJSON.videolink,
                    type: 'application/x-mpegURL',
                });
                this.player.play()
        }
    })
    handleTimeChange = (studentId,videoId,duration) =>{
        playtime = parseInt(this.player.currentTime());
		//alert(playtime+'::'+duration)
        $.ajax({
            url:surl+'?studentID='+studentId+"&action=update&videoID="+videoId+"&pauseTime="+playtime+'&duration='+duration,
            method:"GET",
            success:()=>console.log("Success"),
            failure:()=>console.log("Failed")
        })
    }
    player.on('pause',()=>{
        handleTimeChange(studentId,videoId,player.duration());
		//alert()
    })
    player.on('play',setInterval(() => {
            if(oldTime != this.player.currentTime()){
               // handleTimeChange(studentId,videoId)
            }
            oldTime = this.player.currentTime()
        }, 3000));
    player.on('seeking',()=>{
       // handleTimeChange(studentId,videoId)
    })
</script>
