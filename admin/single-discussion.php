<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <nav class="page-breadcrumb">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="#"><?=getClassName($data['class'])?></a></li>
	  <li class="breadcrumb-item" aria-current="page"><a href="index.php?action=classroom-discussion&class=<?=$data['class']?>">Classroom Discussion</a></li>
	  <li class="breadcrumb-item active" aria-current="page">Discussion Title</li>
	</ol>
  </nav>  
</div>

<div class="row">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">
		
		<div class="row">
		  <div class="col-12">
			<div class="d-flex align-items-center pb-3">
			  <div class="mr-3">
				<img src="../uploads/images/students/<?=$data['image']?>" class="rounded-circle wd-35" alt="user">
			  </div>
			  <div class="w-100">
				<div class="d-flex justify-content-between">
				  <h6 class="text-body mb-1"><?=$data['student_name']?></h6>
				</div>
				<p class="text-muted tx-13">on <?=date('d M Y',strtotime($data['qdate']))?></p>
			  </div>
			</div>

		  </div>
		  <div class="col-12">
				
				<h6 id="default" class="mb-3"><?=stripslashes($data['q_details'])?></h6>
				<span class="badge badge-warning"><?=count($comments)?> Comments</span>
		  </div>
		  
		</div>
		
	  </div> 
	</div>
  </div>
</div> <!-- row -->
<div class="row mt-3">
  <div class="col-lg-12 stretch-card">
	<div class="card">
	  <div class="card-body">
		<?php for($i=0; $i<count($comments); $i++){
			$std = getStudentInfo($comments[$i]['ecode']);
		?>
		<div class="row mb-4">
		  <div class="col-12">
			<div class="d-flex align-items-center pb-3">
			  <div class="mr-3">
				<img src="../uploads/images/students/<?=$std['image']?>" class="rounded-circle wd-35" alt="user">
			  </div>
			  <div class="w-100">
				<div class="d-flex justify-content-between">
				  <h6 class="text-body mb-1"><?=$std['student_name']?></h6>
				</div>
				<p class="text-muted tx-13">on <?=date('d M Y',strtotime($comments[$i]['timestamp']))?></p>
			  </div>
			</div>

		  </div>
		  <div class="col-12 border-bottom">
				
			<p class="mb-3"><?=$comments[$i]['comment']?></p>
		  </div>	  
		</div>
		<?php } ?>
		<div class="row">
		  <div class="col-12 mt-5"><div id='result'></div>
				<form class="forms-sample" id='feedbackfrm'>	
					<input type='hidden' name='classid' value="<?=$data['class']?>">
					<div class="form-group">
						<label>Your Feedback</label>
						<textarea class="form-control" rows="5" id='txtdiscussion' name='txtdiscussion'></textarea>
					</div>
					
					<button class="btn btn-primary" type="submit">SUBMIT FEEDBACK</button>
				</form>

		  </div>

		</div>
		
	  </div> 
	</div>
  </div>
</div> <!-- row -->

<?php include('javascript.php') ?>
<script>
$( document ).ready(function() {
    $("#feedbackfrm").on('submit', function(e) {
		e.preventDefault();
		if($('#txtdiscussion').val()!=''){
		
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=discussion-feedback',
			data: formData,
			type: 'POST',
			success: function(data) {
				$("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		} else{ $("#result").html("Please enter feedback");
			}
	})	
});
</script>