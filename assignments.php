<?php //if(isset($_GET['adate']) && $_GET['adate']!='') $today =$_GET['adate'];
	//else $today=date('Y-m-d'); 
	
	$solved = $tasks[count($tasks)-1]['solved'];
	$notsolved = $tasks[count($tasks)-1]['notsolved'];
	$total=$solved+$notsolved;
	$per= 100*$solved/$total;


if(isset($_GET['adate'])){
	$gdate=$_GET['adate'];
	if($gdate==date('Y-m-d')) $today ="Today"; else $today=date('l, d M Y',strtotime($gdate));
}	
else {
	$gdate=date('Y-m-d');
	$today ="Today";
}	
$currdate = date('Y-m-d');
	?>
      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <h1 class="main-title"><?=$today?> Assignments</h1>
                
            </div>
            <div class="col-5 col-md-2">
                <div class="date-picker">                    
                    <input class="form-control" id="date" name="date" placeholder="Today" type="text" autocomplete='off' value="<?=$gdate?>" onchange="changeDate(this.value)"/>
					

                </div>
            </div>
        </div>
        <div class="row assignment-wrapper mt-4">
            <div class="col-md-4">
                <div class="details-box">
                    <h1>
                        You have <?=$total?> Assignmets on <?=$today?>
                    </h1>
                    <h5><?=$notsolved?> unfinished tasks<span class="float-right">Submit before 6 PM</span></h5>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=$per?>%" aria-valuenow="<?=$per?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <a href="" data-toggle="modal" data-target="#add-new-task" class="add-new">ADD IN TASK LIST</a>
            </div>
            <div class="col-md-8">
			<?php if(count($tasks)>0){?>	
                <ul class="assignmenttable">
                    <?php for($s=0; $s<count($tasks)-1; $s++) {
							$ts= & $tasks[$s];
							if(isset($ts['freestatus'])){
								$type='freetext';
								$table='tbl_freetext';
							}else {
								$type='doc';
								$table='tbl_questiondoc';
							}
							
							$ansid = checkAnswer($emp_ecode,$ts['id'],$table);
							if($ansid!='') {
								$status='Submited';
								$href="index.php?action=assignment-single-submited&id=$ansid";
							}
							else {
								$status='Pending';
								$href="index.php?action=assignment-single&id=".$ts['id']."&type=".$type;
								$closedate = $ts['closedate'];
								$opendate = $ts['opendate'];
								if($currdate>$closedate)$href="javascript:alert('submission date is expired')";
								/*if($currdate<$opendate){
									$href="javascript:alert('You can not submit this question before start date')";
									$styl='display:none;';
								}*/
							}	
							
						?>
                    <li>
                        <div class="row">
                            <div class="col-4 col-md-2">
                                <div class="sub"><?=$ts['subject_name']?></div>
                            </div>
                            
                            <div class="col-6 col-md-2">
                                <div class="status <?=strtolower($status)?>"><?=$status?></div>
                            </div>
                            <div class="col-8 col-md-7">
                                <div class="disc"><?=$ts['question']?></div>
                            </div>
                            <div class="col-4 col-md-1 text-right">
                                <a href="<?=$href?>" data-toggle="tooltip" data-placement="top" title="View" class="btn-sky d-flex align-items-center justify-content-center"><img src="images/icons/view_task.svg" /></a>
                            </div>	
                        </div>
                    </li>
					<?php } ?>
                </ul>
            <?php } else {?>
			<div class='alert alert-info'>No assignments found</div>
            <?php } ?>
			
			</div>
        </div>
      </section>
    
	
	
	
	
	
	
	
	<div class="modal fade" id="add-new-task" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog task-popup" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>Add Task</h1>
                    <form action='' method='post' name='frmtask' id='frmtask' autocomplete='off'>
                        <div class="form-group">
                            <input type="text" class="form-control" id='task_name' name='task_name' placeholder="Task Name">
                        </div>
                        
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Description" name='description' id='description' rows="6"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6 col-md-4">
                                    <input class="form-check-input" type="checkbox" value="1" name="alldeaycheck" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        All Day
                                    </label>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="date-picker">
                                        
                                        <input class="form-control" id="date" name="date" placeholder="Today" type="text" value="<?=date('Y-m-d')?>"/>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="time-picker">
                                        <input class="form-control" type="time" id='time' name='time'>
                                       
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <select id="colorselector_1" name="colorselector_1">
                                    <option value="#A0522D" data-color="#A0522D">sienna</option>
									<option value="#CD5C5C" data-color="#CD5C5C" >indianred</option>
									<option value="#FF4500" data-color="#FF4500">orangered</option>
									<option value="#008B8B" data-color="#008B8B">darkcyan</option>
									<option value="#B8860B" data-color="#B8860B">darkgoldenrod</option>
									<option value="#32CD32" data-color="#32CD32" >limegreen</option>
									<option value="#FFD700" data-color="#FFD700">gold</option>
									<option value="#48D1CC" data-color="#48D1CC">mediumturquoise</option>
									<option value="#87CEEB" data-color="#87CEEB">skyblue</option>
									<option value="#FF69B4" data-color="#FF69B4">hotpink</option>
									<option value="#CD5C5C" data-color="#CD5C5C">indianred</option>
									<option value="#87CEFA" data-color="#87CEFA">lightskyblue</option>
									<option value="#6495ED" data-color="#6495ED">cornflowerblue</option>
									<option value="#DC143C" data-color="#DC143C">crimson</option>
									<option value="#FF8C00" data-color="#FF8C00">darkorange</option>
									<option value="#C71585" data-color="#C71585">mediumvioletred</option>
									<option value="#000000" data-color="#000000">black</option>

                                    </select>
                                    <label class="colorpick_l">Select Color</label>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-center">
                            <input type='submit' class="button2 btn-red" value='SAVE'>
                        </div>
                    </form>
                </div>            
            </div>
        </div>
    </div>
	

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
		});
		
		
		$(function() {
        window.prettyPrint && prettyPrint();

        $('#colorselector_1').colorselector();
        $('#colorselector_3').colorselector();
        $('#colorselector_2').colorselector({
          callback : function(value, color, title) {
            $("#colorValue").val(value);
            $("#colorColor").val(color);
            $("#colorTitle").val(title);
          }
        });

        $("#setColor").click(function(e) {
          $("#colorselector_2").colorselector("setColor", "#008B8B");
        })

        $("#setValue").click(function(e) {
          $("#colorselector_2").colorselector("setValue", 18);
        })

      });
	  
	  
	  $("#frmtask").on('submit', function(e){
	
		e.preventDefault();	
						
		if(taskValidation()){
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=savetask',
			data: formData,
			type: 'POST',
			beforeSend: function() {
				//$('#bigloading').show();
			},
			complete: function() {
				//$('#bigloading').fadeOut(1000);
			},
			success: function(data) {				
				$("#frmtask").trigger("reset");
				$('#add-new-task').modal('hide');
				location.reload();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	});
	
	
	function taskValidation(){
		if(document.getElementById('task_name').value.trim()==''){
			alert('Please enter task name');
			return flase;
		}
		if(document.getElementById('description').value.trim()==''){
			alert('Please enter description');
			return flase;
		}
		
		return true;
	}
	  
		
		
	})
	function changeDate(datev){
		window.location.href="index.php?action=assignments&adate="+datev
	}
    
</script>