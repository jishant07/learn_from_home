<nav class="page-breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><?=getClassName($course['class'])?></li>
		<li class="breadcrumb-item"><a href="index.php?action=courses&class=<?=$course['class']?>&subject=<?=$course['subject']?>">Courses</a></li>
		<li class="breadcrumb-item active" aria-current="page">Edit Course</li>
	</ol>
</nav>
<div class="row">
	<div class="col-md-6 grid-margin stretch-card">
		<div class="card">
			<div class="card-body"><div id='result'></div>
				<form class="forms-sample" id="edit-course" method='post' autocomplete="off" enctype= "multipart/form-data">
					<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
					<input type='hidden' name='classid' id='classid' value="<?=$course['class']?>">
					<input type='hidden' name='subject' id='subject' value="<?=$course['subject']?>">
						
					<div class="form-group">
						<label>Title*</label>
						<input type="text" class="form-control" placeholder="Title" id='title' name='title' value="<?=$course['name']?>">
					</div>
				   
					
					
					<div class="form-group">
						<label>Upload Thumbnail*</label>
						<input type="file" id="myDropify" name="myDropify" class="border"/>
					</div>
					<?php if($course['cthumb']!=''){ ?><a href="../uploads/images/courses/<?=$course['cthumb']?>"  target='_blank'><?=$course['cthumb']?></a><BR><?php } ?>
					<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
					<button class="btn btn-light mt-2">Cancel</button>
					
					
		
				</form>
			</div>
		</div>
	</div>
<?php
//echo '<pre>',print_r($course);
 $cvideos = explode(',',$course['videos']);
 $preferences = explode(',',$course['preferences']);


if(count($cvideos)==count($preferences))
				$newarr=array_combine($preferences,$cvideos);
				else $newarr=$cvideos;
				//$newarr = $row['videoarr'];
				ksort($newarr);	
			$newarr = array_values($newarr);
//echo '<pre>',print_r($newarr);



?>
	<div class="col-md-6 grid-margin">
		<div class="card">
		<form class="forms-sample" id="edit-preference" method='post'>
					<input type='hidden' name='id' id='id' value="<?=$_GET['id']?>">
			<div class="card-body">
				<div class="list-group">
								
						<!--div id="example1" class="list-group col">
							<?php for($c=0;$c<count($newarr);$c++){
								$cvid = getCourseVideo($newarr[$c]);
								$p=$c+1;
								?>
							<div class="list-group-item">
							<?php echo $cvid['title']?><input type=hidden name=matchprefs[] value="<?=$p?>" >
							</div>
							<?php } ?>
							
						</div-->			
						
						<ul id="sortable" class='droptrue sortable3 list-group pl-0'>
						<?php for($c=0;$c<count($cvideos);$c++){
								$cvid = getCourseVideo($cvideos[$c]);
								?>			
							 <li class="list-group-item"><?php echo $cvid['title']?><input type=hidden name=matchans[] value='<?php echo $cvideos[$c]?>'>            
						
						<?php } ?>
						</ul>

						
					<!--a href="#" class="list-group-item list-group-item-action"><?//=$cvideos[$i]?></a-->
				</div><!--button type="submit" class="btn btn-primary mr-2 mt-2">Set Preferences</button-->
			</div>
			</form>
		</div>
	</div>
</div>

    <!-- Modal -->
  <div class="modal fade" id="viewlive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div> 
  
  <script src="assets/Sortable.js"></script>

	
	<script src="assets/app.js"></script>  

<?php include('javascript.php') ?>
   
<script>
$( document ).ready(function() {

	$("#edit-course").on('submit', function(e) {
		e.preventDefault();
		let classid=$('#classid').val();
		if(courseValidation()){
		
		let subject=$('#subject').val();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=course-edit',
			data: formData,
			type: 'POST',
			success: function(data) {
				if(data=='') window.location.href='index.php?action=courses&class='+classid+'&subject='+subject
				else $("#result").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		}
	});
	
	$("#edit-preference").on('submit', function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		$.ajax({
			url: 'index.php?action=course-edit-preference',
			data: formData,
			type: 'POST',
			success: function(data) {
				alert(data)
			},
			cache: false,
			contentType: false,
			processData: false
		});
		
	});
	
	
});
function courseValidation(){
if(document.getElementById('title').value.trim()==''){
	$("#result").html("<div class='alert alert-warning'>Please enter title</div>");
	document.getElementById('title').focus();
	return false;
}
/*if(document.getElementById('myDropify').value.trim()==''){
	$("#result").html("Please select image");
	document.getElementById('myDropify').focus();
	return false;
}*/
return true
}
</script>
