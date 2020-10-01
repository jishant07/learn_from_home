<?php 
if(isset($_GET['cid'])){
	$cid=$_GET['cid'];	
	$row= getCourse($cid);
	//print_r($row);;
	$listvideos=explode(',',$row['videos']);
	$listprefs=explode(',',$row['preferences']);
	$chapter = $row['chapter'];
	
	if(count($listvideos)==count($listprefs))
	$newarr=array_combine($listprefs,$listvideos);
	else $newarr=$listvideos;
	
				
	//$newarr=array_combine($listprefs,$listvideos);
	//$newarr = $row['videoarr'];
	ksort($newarr);	
	if(!isset($_GET['id'])){
		$varray=$newarr;
		$varr = array_values($varray);
		
		$_GET['id']=$varr[0];
	}
}
else{
	$vid=$_GET['id'];
	$sql= "SELECT * FROM `courses` WHERE FIND_IN_SET('$vid',videos) order by id desc limit 1";
	$sqlr= $conn -> query($sql);
	if ($sqlr->num_rows > 0){
		$row = $sqlr->fetch_assoc();
		$cid=$row['id'];
		//$row= getCourse($cid);
		//print_r($row);;
		$listvideos=explode(',',$row['videos']);
		$listprefs=explode(',',$row['preferences']);
		$chapter = $row['chapter'];
	//	$newarr=array_combine($listprefs,$listvideos);
	if(count($listvideos)==count($listprefs))
		$newarr=array_combine($listprefs,$listvideos);
		else $newarr=$listvideos;
		//$newarr = $row['videoarr'];
		ksort($newarr);	
		//print_r($newarr);
	}
}

//echo $cid;
//echo $_GET['id'];

	$video = course_video($_GET['id']);
	//print_r($video);
	$subject_name = getSubject($video['subject']);

	$teacherid =  $video['teacher']; 
	
	$subid=$video['subject'];
	$sqhw= $conn -> query("SELECT count(hw_id) as cnthw from homeworks WHERE hw_sub='$subid'");
	$rowhw = $sqhw->fetch_assoc();
	
	$sqex= $conn -> query("SELECT count(id) as cntex from tbl_evolution WHERE evolutiontype='exercise' and subject='$subid' and status=1");
	$rowex = $sqex->fetch_assoc();
	$vid_id = $video['id'];



$emp_ecode = $_SESSION["eid"];
$cls='fa fa-toggle-off';
$dis='';$rid ='';
$sq="SELECT flag,id FROM raise_question  WHERE vid='$vid_id' and stid='$emp_ecode'";
$vid_qs_sql = $conn -> query($sq);
if($vid_qs_sql->num_rows > 0){
	$vid_qs_row = $vid_qs_sql->fetch_assoc();
	$flag = $vid_qs_row['flag'];
	$rid = $vid_qs_row['id'];
	$cls='fa fa-toggle-on';
	$dis='disabled';
	$sql_msg = "SELECT a.*,t.t_name,r.flag FROM `ask_questions` a, teachers t,raise_question r,courses_videos v WHERE ecode= '$emp_ecode' AND  t.t_id=a.teachervid and r.id=a.raiseid and v.id=r.vid and v.id='$vid_id' order by ask_id desc";
	$sq_ques = $conn -> query( $sql_msg );		
}
$sql_watch = $conn -> query("select * from videowatchlist where stdid='$emp_ecode' and vid='$vid_id'");
if($sql_watch->num_rows > 0){
	$wflag=1;
	
	} else{ $wflag=0;}

$videolink = $video['vlink'];

addViews($vid_id,$emp_ecode);
$surl = "https://flowrow.com/lfh/api/videoapi.php";
//echo 'vid_id: ',$vid_id;
?>
<link href="css/video-js.css" rel="stylesheet" />

 <input type='hidden' id='hvideoid' value="<?=$_GET['id']?>">
 <input type='hidden' id='coursid' value="<?=$cid?>">
 <input type='hidden' id='wflag' value="<?=$wflag?>">
 
 	
   <section class="container-fluid single-video mainwrapper">
        <div class="row">
            <div class="col-md-7">               
				<?php
				$tday = date('Y-m-d H:i:s');
				if($video['sheduledate']<=$tday){
				?>
				 <video id="player" class="player-dimensions video-js vjs-default-skin " controls></video>

			
                   <?php } else {?>
				<div class="Waiting-video d-flex align-items-center justify-content-center"><div><h2>This video is sheduled at </h2><div class="text"><?php echo date('d M Y h:i A',strtotime($video['sheduledate']));?></div></div></div><?php } ?>
                
            </div>
			<div class="col-md-5 content">
                <div class="chapter"><?=$subject_name?></div>
               <?php if(isset($row['name'])){ ?> <h1><?php echo stripslashes($row['name'])?></h1><?php } ?>
                <div class="disc">By <span><?php isTeacherOnline($teacherid)?></span></div>
                <!--div class="dropdown ">
                    <div class="custom-select">
                        
                    </div>                
                        
                </div-->
				<?php 
			//	print_r($newarr);
				
				if(isset($newarr) && count($newarr)>0){?>
				<div class="dropdown">
				<select id='videolist'>
						 <?php foreach($newarr as $k=>$v){?>
                            <option value="<?=$v?>"><?=getCourseVideo($v)?></option>
						 <?php } ?>
                 </select></div>
				<?php } else {?>
					<div class="content"><BR><?=$video['vtitle']?>
					</div>
					
				<?php } ?>	
				 <div class="chapter">Views <?=getViews($vid_id)?></div>
				 <p><?=$video['description']?></p>
				
                <?php if(isset($row['description'])){ ?>  <p>
                <?php echo stripslashes($row['description'])?>
                </p><?php } else {?>
				 <?php echo stripslashes($video['vdesc'])?>
				<?php } ?>			
                <div class="status row">
					
                    <div class="col-6">                    
                    <div id='divaddwatchlist'>                    
                        <a href="javascript:void(0);"  onclick='addToWatchlist()' class="button1 btn-grey-red d-flex d-flex align-items-center justify-content-center watchlist-add">
                        <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 14.5 14.5">
  <path id="add_watchlist" d="M13.205,5.955H8.8A.259.259,0,0,1,8.545,5.7v-4.4a1.295,1.295,0,0,0-2.589,0V5.7a.259.259,0,0,1-.259.259h-4.4a1.295,1.295,0,0,0,0,2.589H5.7a.259.259,0,0,1,.259.259v4.4a1.295,1.295,0,1,0,2.589,0V8.8A.259.259,0,0,1,8.8,8.545h4.4a1.295,1.295,0,0,0,0-2.589Zm0,0"/></svg>Add Watchlist
                        </a>
						</div>
						<div id='divaddedwatchlist'>					
                        <a href="javascript:void(0);"  class="button1 btn-grey-red watchlist-added d-flex d-flex align-items-center justify-content-center" onclick='removeWatchlist()'>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 13.5">
                            <path id="added_watchlist" d="M0,71.767l6.164,6.176L18,66.119l-1.7-1.676L6.164,74.568,1.676,70.08Z" transform="translate(0 -64.443)"/></svg>
                        Added Watchlist
                        </a>
                    
						</div>

                    </div>
					<!--div class="col-6 mt-1" id='divaddedwatchlist'>					
                        <a href="javascript:void(0);"  class="button1 btn-grey-red watchlist-added d-flex d-flex align-items-center justify-content-center" onclick='removeWatchlist()'>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 13.5">
                            <path id="added_watchlist" d="M0,71.767l6.164,6.176L18,66.119l-1.7-1.676L6.164,74.568,1.676,70.08Z" transform="translate(0 -64.443)"/></svg>
                        Added Watchlist
                        </a>
                    
                    </div-->
					<?php if($video['document']!=''){?>
                    <div class="col-6">
					<a href="index.php?action=pdfreader&type=course&file=<?=$video['document']?>" class="button1 btn-grey-red d-flex d-flex align-items-center justify-content-center <?=$disable_smaterial?>">
                        <svg id="document" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.051 18.487">
  <path id="Path_94" data-name="Path 94" d="M119.434,297.9c0-.307-.213-.49-.589-.49a1.3,1.3,0,0,0-.312.03v.985a1.179,1.179,0,0,0,.253.019C119.186,298.446,119.434,298.244,119.434,297.9Z" transform="translate(-115.706 -286.008)"/>
  <path id="Path_95" data-name="Path 95" d="M194.145,297.681a1.554,1.554,0,0,0-.341.03v2.182a1.367,1.367,0,0,0,.262.015,1.034,1.034,0,0,0,1.128-1.168A.957.957,0,0,0,194.145,297.681Z" transform="translate(-188.091 -286.267)" />
  <path id="Path_96" data-name="Path 96" d="M55.184,0H47.759a1.971,1.971,0,0,0-1.968,1.968V9.244H45.6a.793.793,0,0,0-.793.793v4.809a.793.793,0,0,0,.793.793h.192v.882a1.97,1.97,0,0,0,1.968,1.968H57.89a1.97,1.97,0,0,0,1.967-1.968V4.657Zm-8.3,10.9a6.215,6.215,0,0,1,1.019-.069,1.6,1.6,0,0,1,1.019.267.975.975,0,0,1,.356.772,1.066,1.066,0,0,1-.307.792,1.547,1.547,0,0,1-1.084.351A1.953,1.953,0,0,1,47.633,13v1.192h-.747Zm11,6.378H47.759A.763.763,0,0,1,47,16.52v-.882h9.445a.793.793,0,0,0,.793-.793V10.037a.793.793,0,0,0-.793-.793H47V1.968a.763.763,0,0,1,.762-.761L54.733,1.2V3.777A1.365,1.365,0,0,0,56.1,5.141l2.525-.007.028,11.385A.763.763,0,0,1,57.89,17.282Zm-8.128-3.1V10.9a6.791,6.791,0,0,1,1.019-.069,2.147,2.147,0,0,1,1.366.356,1.476,1.476,0,0,1,.564,1.257,1.69,1.69,0,0,1-.554,1.351,2.394,2.394,0,0,1-1.539.43A6.527,6.527,0,0,1,49.762,14.18Zm5.4-1.939v.613h-1.2v1.341h-.757V10.86h2.038v.618H53.965v.762Z" transform="translate(-44.806)"/>
</svg>


                         Documents
                        </a>
                    </div>
					<?php } ?>
                    
                </div>
            </div>
            
            
        </div>
		<?php
		$courses =  getCourses($subid,$emp_ecode);
				//print_r($courses);
				?>
        <div class="row mt-4">
            <div class="videos-chapters col-12">                
                <h1 class="title"><?=ucwords($subject_name)?><h1>
                <div class="session-loop mt-2" id="session-loop">
				<?php	
				
				if(count($courses)>0){	
						for($c=0;$c<count($courses);$c++){	
							$row = & $courses[$c];
							$cid1 = $row['id'];
							$videoarr = $row['videoarr'];
							$vid =$videoarr[0];
							$thumb = $row['cthumb'];//getCourseVideoThumb($vid);
							?>
                    <div class="item">
                        <a href="index.php?action=history-videos&cid=<?=$cid1?>" class="video-thumb d-flex align-items-end" style="background: url('<?=$thumb?>');" title="<?=$row['name']?>">
                            <div class="content">
                                <h2><?=$row['name']?></h2>
                                <div class="text">
                                <?=count($row['videoarr'])?> videos
                                </div>
                                <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </a>
                    </div>
				<?php }} ?>                    
                    
                
                
                </div>
            </div>
        </div>
      </section>
	
   	 <?php include('javascript.php') ?>
	 <?php  include 'hlsplayer.php'?>
   <script>
	
$(document).ready(function() {
	let wflag=$('#wflag').val();
	if(wflag==1){
		$('#divaddwatchlist').hide(); $('#divaddedwatchlist').show();}
	else{
		$('#divaddwatchlist').show(); $('#divaddedwatchlist').hide();
	}
	
		let hvideoid=$('#hvideoid').val();	

	   $('#videolist').val(hvideoid);


$( "#videolist" ).change(function() {	
	let vid = $('#videolist').val();
	//alert(vid)
	window.location.href="index.php?action=history-videos&cid=<?=$cid?>&id="+vid
});

});
/*
var x, i, j, l, ll, selElmnt, a, b, c;
x = document.getElementsByClassName("custom-select");
l = x.length;
for (i = 0; i < l; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  ll = selElmnt.length;
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < ll; j++) {
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        var y, i, k, s, h, sl, yl;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        sl = s.length;
        h = this.parentNode.previousSibling;
        for (i = 0; i < sl; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            yl = y.length;
            for (k = 0; k < yl; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
      
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
}
function closeAllSelect(elmnt) {
 
  var x, y, i, xl, yl, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  xl = x.length;
  yl = y.length;
  for (i = 0; i < yl; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < xl; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

document.addEventListener("click", closeAllSelect);



for (var i in options) {
var item = options[i];
item.container = '#' + i;
item.swipeAngle = false;
if (!item.speed) { item.speed = speed; }

if (doc.querySelector(item.container)) {
  sliders[i] = tns(options[i]);

} else if (i.indexOf('responsive') >= 0) {
  if (isTestPage && initFns[i]) { initFns[i](); }
}
}*/

// goto
function addToWatchlist(){
	let vid = $('#hvideoid').val();
	let coursid = $('#coursid').val();
	///alert(vid)
	 $.ajax({
        url:'ajax/addtowatchlist.php?type=add&vid='+vid+'&cid='+coursid,
       
        type: 'POST',
      beforeSend: function(){
            $('#divaddwatchlist').hide();
          },
      complete: function(){
             $('#divaddedwatchlist').show();
          },
      success: function (data) {
		 // alert(data)
            $('#divaddedwatchlist').show();
         
        },
        cache: false,
        contentType: false,
        processData: false
    })
}
function removeWatchlist(){
	let vid = $('#hvideoid').val();
	///alert(vid)
	 $.ajax({
        url:'ajax/addtowatchlist.php?type=remove&vid='+vid,
       
        type: 'POST',
      beforeSend: function(){
            $('#divaddedwatchlist').hide();
          },
      complete: function(){
            $('#divaddwatchlist').show();
          },
      success: function (data) {
		 // alert(data)
            $('#divaddwatchlist').show();
         
        },
        cache: false,
        contentType: false,
        processData: false
    })
}
</script>
  </body>
</html>