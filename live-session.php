<?php 
	$ctime =date('Hi');
	$today=date('Y-m-d');									
	$tomorrow = date("Y-m-d", strtotime("+1 day"));
	$arr = array();

	foreach ($videos as $key => $item) {
	   $arr[$item['sub_start']][$key] = $item;
	}

	ksort($arr, SORT_NUMERIC);
	
	$todate=date('Y-m-d H:i:s');
	//echo'<pre>',print_r($arr),'</pre>';
?>								    
	<section class="container-fluid live-list mainwrapper">
        <div class="row">
            <div class="col-12">
                <ul>
				<?php 
				//$sessdate='';
				foreach($arr as $k=>$v){
					if($k == $today) $currday='Today'; 
					else if($k==$tomorrow)$currday='Tomorrow'; 
					else { 
					$currday = date('l',strtotime($k));
					
					}

					$sessdate = date('F d, Y',strtotime($k));
					?>	
                    <li>
                        <div class="row">
                            <div class="col-md-2 timeday">
                                <h1><?=$currday?></h1>
                                <h3>
								<?=$sessdate?>
								</h3>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
								<?php 
								foreach($v as $kk=>$vd){
									 $vid = $vd['id'];
									$start=$vd['sub_start_at'];
									$end=$vd['sub_end_at'];
									$startdatev = date('Y-m-d',strtotime($start));
									
									$endv = date('Y-m-d',strtotime($end));
									

									if($today==$startdatev){
										$todayvtime = date('Hi',strtotime($start));
										$endvtime = date('Hi',strtotime($end));
										$currtime=date('Hi');
										if($currtime>=$todayvtime && $currtime<=$endvtime)
										{
											$clivecss='active-live'; 
											$pslot = 'Live';
											$href="index.php?action=live-video&id=".$vid;
										}
										else {
											$pslot = date('H:i A',strtotime($start));
											$clivecss='';
											$href="javascript:void(0);";
											$href="index.php?action=live-video&id=".$vid;
										}
									}
									else {
										$pslot = date('H:i A',strtotime($start));$clivecss='';
									}
									$href="index.php?action=live-video&id=".$vid;
									if($end>=$todate){	
									?>
                                    <div class="col-md-4">
                                        <a href="<?=$href?>" title="<?=$vd['vtitle']?>" class="box <?=$clivecss?> d-flex">
                                            <div class="pic">
                                                <img src="<?=$vd['tpic']?>" alt="<?=$vd['t_name']?>" />
                                            </div>
                                            <div class="content">
                                                <h5><?=$pslot?></h5>
                                                <p><?=$vd['vtitle']?></p>
                                                <div class="disc">By <span><?=$vd['t_name']?></span> on <span><?=$vd['subject_name']?></span></div>
                                            </div>
                                        </a>
                                    </div>
								<?php }
								}?>                                    
                                </div>
                            </div>
                        </div>
                    </li>
				<?php } ?>
                       </div>
                        </div>
                    </li>                    
                </ul>
            </div>       
        </div>
      </section>
	  <?php include('javascript.php') ?>