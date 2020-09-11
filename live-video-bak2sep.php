<?php
 $sub_start_at = $video['sub_start_at'];
$sub_end_at = $video['sub_end_at'];
	$video_id = $video['vid_id'];
	$studentw = watchingVideos($emp_ecode,$video_id);
	//print_r($studentw);
	$flag='';
	
	$teacherid =  $video['vid_teacher']; 
	$sq="SELECT flag,id FROM raise_question  WHERE vid='$video_id' and stid='$emp_ecode'";
	$vid_qs_sql = $conn -> query($sq);
	if($vid_qs_sql->num_rows > 0){
		$vid_qs_row = $vid_qs_sql->fetch_assoc();
		$flag = $vid_qs_row['flag'];
		$rid = $vid_qs_row['id'];					
	}
		
?>	
<input type='hidden' id='raiseflag' value='<?=$flag?>'>
<input type='hidden' id='videoid' value='<?=$video['vid_id']?>'>
	<script type="text/javascript" src="https://content.jwplatform.com/libraries/P7tGbqKk.js"></script>
    <script type="text/javascript">jwplayer.key="Df1+QHbNEKwrJaZ/gVnAoOQqjosU5yycYtQcnPGsmgY=";</script>
   <section class="container-fluid live-video mainwrapper">
        <div class="row">
            <div class="col-md-7">
			<?php $todate=date('Y-m-d H:i:s');
			//echo '<BR>',$sub_start_at;
				if($todate>=$sub_start_at && $todate<=$sub_end_at){
			?>			
                <div class="video">
                    <div id="player">
                    
					</div>
                </div>
				<?php } else {?>
				<div class="Waiting-video d-flex align-items-center justify-content-center"><div><h2>This live session will start at</h2><div class="text"><?php echo date('d M Y H:i A',strtotime($sub_start_at));?></div></div></div><?php } ?>
            </div>
			<script type="text/javascript">
                jwplayer("player").setup({
                width: '100%',
                autostart: true,
                image: "<?=$video['vthumb']?>",
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
            <div class="col-md-5 content">
                <h1><?=$video['vtitle']?></h1>
                <div class="disc">By <span><?=getTeacher($video['vid_teacher'])?></span> on <span><?=getSubject($video['vid_sub'])?></span></div>
                <p>
                <?=$video['vdesc']?>
                </p>
                <div class="status row">
				<?php if($todate>=$sub_start_at && $todate<=$sub_end_at){?>
                    <div class="col-6">
                        <a href="" class="button1 btn-grey-red d-flex d-flex align-items-center justify-content-center"  data-toggle="modal" data-target="#live-student" id='studentsshow'>
                        <svg id="student_watching" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.983 14.696">
  <g id="Group_310" data-name="Group 310" transform="translate(22.487 1.764)">
    <path id="Path_78" data-name="Path 78" d="M20.5,12.7A2.435,2.435,0,0,1,18,10.351a2.5,2.5,0,0,1,5,0A2.435,2.435,0,0,1,20.5,12.7Zm0-3.527a1.178,1.178,0,1,0,0,2.351,1.178,1.178,0,1,0,0-2.351Z" transform="translate(-18 -8)" />
  </g>
  <g id="Group_311" data-name="Group 311" transform="translate(22.487 7.642)">
    <path id="Path_79" data-name="Path 79" d="M24.871,17.7a.573.573,0,0,1-.625-.588V15.939a1.867,1.867,0,0,0-1.874-1.764H18.625a.589.589,0,1,1,0-1.176h3.748A3.006,3.006,0,0,1,25.5,15.939v1.176A.573.573,0,0,1,24.871,17.7Z" transform="translate(-18 -13)" />
  </g>
  <g id="Group_312" data-name="Group 312" transform="translate(2.499 1.764)">
    <path id="Path_80" data-name="Path 80" d="M4.5,12.7A2.435,2.435,0,0,1,2,10.351,2.435,2.435,0,0,1,4.5,8,2.435,2.435,0,0,1,7,10.351,2.435,2.435,0,0,1,4.5,12.7Zm0-3.527a1.178,1.178,0,1,0,0,2.351,1.178,1.178,0,1,0,0-2.351Z" transform="translate(-2 -8)" />
  </g>
  <g id="Group_313" data-name="Group 313" transform="translate(0 7.642)">
    <path id="Path_81" data-name="Path 81" d="M.625,17.7A.573.573,0,0,1,0,17.115V15.939A3.006,3.006,0,0,1,3.123,13H6.871a.589.589,0,1,1,0,1.176H3.123a1.867,1.867,0,0,0-1.874,1.764v1.176A.573.573,0,0,1,.625,17.7Z" transform="translate(0 -13)" />
  </g>
  <g id="Group_314" data-name="Group 314" transform="translate(11.244 0)">
    <path id="Path_82" data-name="Path 82" d="M12.748,13.554a3.534,3.534,0,1,1,0-7.054,3.534,3.534,0,1,1,0,7.054Zm0-5.878a2.435,2.435,0,0,0-2.5,2.351,2.435,2.435,0,0,0,2.5,2.351,2.435,2.435,0,0,0,2.5-2.351A2.435,2.435,0,0,0,12.748,7.676Z" transform="translate(-9 -6.5)" />
  </g>
  <g id="Group_315" data-name="Group 315" transform="translate(8.12 8.818)">
    <path id="Path_83" data-name="Path 83" d="M19.618,19.878a.573.573,0,0,1-.625-.588V16.939a1.867,1.867,0,0,0-1.874-1.764h-7.5a1.867,1.867,0,0,0-1.874,1.764v2.351a.573.573,0,0,1-.625.588.573.573,0,0,1-.625-.588V16.939A3.006,3.006,0,0,1,9.623,14h7.5a3.006,3.006,0,0,1,3.123,2.939v2.351A.573.573,0,0,1,19.618,19.878Z" transform="translate(-6.5 -14)"/>
  </g>
</svg>
<span><?=$studentw?></span> Students Watching
                        </a>
                    </div>
					
					
					
                    <div class="col-6" id='raiseQuestion'>
                        <a href="javascript:void(0);" class="button1 btn-grey-red d-flex d-flex align-items-center justify-content-center" id=''>
                        <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                        Raise Hand For Question
                        </a>
                    </div>
                    <div class="col-6" id='approvalwaitingid'>
                        <a href="" class="button1 waiting btn-grey-red d-flex d-flex align-items-center justify-content-center">
                        Waiting for teacher approval...
                        </a>
                    </div><br>
				<?php } ?>
					
					
					
                </div>
            <BR>
			<div class="row alert alert-success m10" id='messageraise'>Raise hand successfully</div>
            </div>
            
        </div>
      </section>
      
    </div>
    <div class="modal fade" id="live-student" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog live-student-popup" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row students-names">
                      
                      
                      
                    </div>
                    <div class="d-flex align-items-center justify-content-center mt-3">
                        <a href="" class="button2 btn-red" data-toggle="modal" data-target="#live-student">CLOSE</a>
                    </div>
                </div>
            
            </div>
        </div>
<?php include('javascript.php') ?>
  <script>
$(document).ready(function() { 
$("#messageraise").hide();
	let raiseflag=$('#raiseflag').val();	
	let videoid=$('#videoid').val();	
   $('#approvalwaitingid').hide();
	if(raiseflag=='0')$('#approvalwaitingid').show();
	if(raiseflag!='')$('#raiseQuestion').hide();
	
    $('#raiseQuestion').click(function(){
		$.post( "ajax/ques_ans.php?type=raise",{videoid:videoid}  , function( data ) {
			if(data==1){
				$('#raiseQuestion').hide();
				$('#approvalwaitingid').show();
				$("#messageraise").show();
				$('#messageraise').html('Raise hand successfully!');
				setTimeout(function(){
					$("#messageraise").html('');
					$("#messageraise").hide(500);
					}, 3000);		
			}
		});
	}) ;
	
	$('#studentsshow').click(function(){ 
	let html='';
		$.post( "index.php?action=student_watching&videoid="+videoid  , function( data ) {
			//alert(data)
			if(data!=''){				
				var object = $.parseJSON(data);
				$.each(object,(index,value)=>{ 
				html+=` <div class="col-6">
                        <div class="name" data-toggle="modal">
                          <img src="${object[index].image}" />
                          <div class="details">
                            <h3>${object[index].student_name}<h3>
                            <!--span>Roll No. 1</span-->
                          </div>
                            
                        </div>
                      </div>`
				})
			} else{
				html="<div class='alert alert-notice'>No students here!</div>";
			}
			$('.students-names').html(html);
		});
	}) ;
	
}) 
</script>
