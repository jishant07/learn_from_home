<?php //echo'<pre>',print_r($_SESSION);
//unset($_SESSION['exam'])
?>
<script src="js/validationnew.js"></script>

<form method='post' id='evolutionfrm'  autocomplete=off>

<div class="row">
  <div class="col-lg-5 mb-3">
	<div class="card">
	  <div class="card-body">
		  <div id='result'></div>			
		  <div class="form-group">
			<label>Schedule Date for Exam*</label>
			 <div class="input-group date datepicker" id="datePickerExample">
			  <input type="text" class="form-control" name='sdate' id='sdate'><span class="input-group-addon"><i data-feather="calendar"></i></span>
			</div>
		  </div>
		  <div class="form-group">
			  <div class="row">
				  <div class="col-6">
					  <label>Start Time*</label>
					  <div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
						  <input type="text" id='starttime' name='starttime' class="form-control datetimepicker-input" data-target="#datetimepickerExample"/>
						  <div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
							  <div class="input-group-text"><i data-feather="clock"></i></div>
						  </div>
					  </div>
				  </div>
				  <div class="col-6">
					  <label>End Time*</label>
					  <div class="input-group date timepicker" id="datetimepickerExample2" data-target-input="nearest">
						  <input type="text" id='endtime' name='endtime' class="form-control datetimepicker-input" data-target="#datetimepickerExample2"/>
						  <div class="input-group-append" data-target="#datetimepickerExample2" data-toggle="datetimepicker">
							  <div class="input-group-text"><i data-feather="clock"></i></div>
						  </div>
					  </div>
				  </div>
			  </div>

		  </div>
		  <div class="form-group">
			  <div class="row">
				  <div class="col-6">
					  <label>Total Marks*</label>
					  <input type="text" class="form-control" id='tot_marks' name='tot_marks'onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
				  </div>  
				<div class="col-6">
					  <label>Total Sections*</label>
					  <input type="text" class="form-control" name='totsections' id='totsections' onkeypress="if(isNaN( String.fromCharCode(event.keyCode))) return false;">
				  </div>  		
			  </div>  
		  </div>        
			<?php ///if(isset($_SESSION['evid'])) $qtcls='hide'; else $qtcls='show'?>			
			<button type="submit" class="btn btn-primary mr-2 mt-2" id='evsave'>Save</button>
			   
		
	  </div> 
	</div>
  </div> 
</div>
<input type='hidden' name='class' id='class' value="<?=$_GET['class']?>">
		<input type='hidden' name='subject' id='subject' value="<?=$_GET['subject']?>">

 	</form>
	<div id='dd'></div>
<?php include 'javascript.php';?>
 <script>
  $( document ).ready(function() {
 $("#evolutionfrm").on('submit', function(e) {
		e.preventDefault();
		if(evaluationvalid()){
	
		let dclass=$('#class').val();
		let sub=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'planoperations.php?type=evaluation&class='+dclass+'&subject='+sub,
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data!='')$("#result").html("<div class='alert alert-warning'>"+data+"</div>");
				else
				window.location.href='index.php?action=plansection&class='+dclass+'&subject='+sub
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} 
	});
	})

</script>