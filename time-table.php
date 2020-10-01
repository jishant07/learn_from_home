<?php 
//echo'<pre>',print_r($timetbl),'</pre>';
$newarray=array();
foreach($timetbl as $key => $value){
   $newarray[$value['day_name']][$key] = $value;
}
array_values($newarray);

//echo'<pre>',print_r($newarray),'</pre>';
if(isset($_GET['date']) && $_GET['date']!='') {
	$day_arr=array();
	for($d=0;$d<=6;$d++){
		$day_arr[] = date('l',strtotime("+$d day",strtotime($_GET['date'])));
	}
	$seldate = $_GET['date'];
}
else{
$day_arr=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$seldate = 'Today';
}
?>    
	<section class="container-fluid wrapper-inner time-table mainwrapper">
      <div class="row">
            <div class="col-md-10">
                
            </div>
            <div class="col-md-2">
                <div class="date-picker">
                    
                    <input class="form-control" id="date" name="date" placeholder="Today" type="text" onchange="changeDate(this.value)" autocomplete='off' value="<?=$seldate?>"/>
                </div>
            </div>
        </div>
        <div class="cd-schedule cd-schedule--loading margin-top-lg margin-bottom-lg js-cd-schedule mt-4">
            <div class="cd-schedule__timeline">
                <ul>
				<?php for($i=0;$i<count($timetbl);$i++) {?>
                    <li><span><?=$timetbl[$i]['time1']?></span></li>
				<?php } ?>
                </ul>
            </div> <!-- .cd-schedule__timeline -->
        
            <div class="cd-schedule__events">
            <ul>
                <?php 
					for( $i=0; $i <= 6 ; $i++ ) {
						if(isset($_GET['date']) && $_GET['date']!='') {
							$j=$i;
							$date = date("d M Y, l", strtotime("+$j day",strtotime($_GET['date'])));
							//$date = date("d M Y, l", strtotime($day_arr[$i].' this week'));
						}
						else $date = date("d M Y, l", strtotime($day_arr[$i].' this week'));
						$day_slots= $newarray[$day_arr[$i]];
						//print_r($day_slots);
						$maxslot=array();
						foreach ($day_slots as $key => $value){
							$maxslot[$key] = $value['time2'];
						}
						$lasttime = max($maxslot);
				
				?>
				<li class="cd-schedule__group">
                <div class="cd-schedule__top-info"><span><?php echo $date?></span><BR><BR><BR></div>
				<div style='clear:both' class='clearfix'></div>
                <ul>
				
				<?php	
				$maxslotarr=array();
				$event = count($day_slots);
				foreach($day_slots as $sk=>$vk ) {
						 $slot1 = $vk['time1'];
						 $slot2 = $vk['time2'];
						$type = $vk['tpye'];
						$subject_name = $vk['subject_name'];
						if($type=='livesession')$link='index.php?action=live-video&id='.$vk['id'];
						
						if($type=='assignment'){
							
							$start_date = date('Y-m-d',strtotime($vk['start_date']));
							$link='index.php?action=assignments&adate='.$start_date;
							
							$slot1 = date('H:i', strtotime($lasttime)); // 10:09 + 1 hour
							if($slot1<'10:30')$slot1='10:30';
							$slot2 = date('H:i',strtotime($slot1) + 120*60); // 10:09 + 1 hour
							$maxslotarr[] = $slot2;
							$subject_name = 'Assignment';						
						}
						if($type=='exam'){
							$lasttime = max($maxslotarr);
							//if($lasttime<$slot1) $lasttime=$slot1;
							$link='index.php?action=exams&adate='.$vk['start_date'];
							$slot1 = date('H:i', strtotime($lasttime)); // 10:09 + 1 hour
							if($slot1<'10:30')$slot1='10:30';
							$slot2 = date('H:i',strtotime($slot1) + 120*60); // 10:09 + 1 hour
							$maxslotarr[] = $slot2;
							$subject_name = 'Exam';						
						}
					?>
                    <li class="cd-schedule__event" onclick="redirect('<?=$link?>')">
                    <a href='#'  data-start="<?=$slot1?>" data-end="<?=$slot2?>" data-content="event-abs-circuit" data-event="event-<?=$event?>" href="#0">
                        <em class="cd-schedule__name"><?=$subject_name?></em>
                    </a>
                    </li>					
					<?php
					$event--;
					} ?>                   
                </ul>
                </li>
        <?php } ?>               
            </ul>
            </div>
        
            <div class="cd-schedule-modal">
            </div>
        
        </div>
      </section>
    	  <?php include('javascript.php') ?>
 
<script>
	$(document).ready(function(){
		var date_input=$('input[name="date"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'yyyy-mm-dd',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
    
	function redirect(link){ 
		window.location.href=link
	}
	function changeDate(datev){
		window.location.href="index.php?action=timetable&date="+datev
	}
</script>
<script>document.getElementsByTagName("html")[0].className += " js";</script>
<script src="js/tilmetable-util.js"></script>
<script src="js/tilmetable-main.js"></script>
    
    