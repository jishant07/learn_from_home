<?php
error_reporting(0);
 if(isset($_SESSION['exam']['section_complete']))
 $section_complete = array_values(array_unique($_SESSION['exam']['section_complete']));
// unset($_SESSION['exam']['qcount']);
 
//echo'<pre>',print_r($_SESSION['exam']);
?> 
<div class="row">
	<div class="col-lg-5 mb-3">
		<div class="card">
		<div class="card-body">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-9"><h6 class="card-title">Select Section</h6></div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						
						<?php for($i=0;$i<$_SESSION['exam']['totsections']; $i++) {	
							$btnval="Sections ".$_SESSION['exam']['post']['section'][$i];
							if(in_array($_SESSION['exam']['seclastid'][$i],$_SESSION['exam']['questionsforsection'])){
								$btnval="Section ".$_SESSION['exam']['post']['section'][$i].' completed';
							?>
							<div class="col-12"><input type='button' class="btn btn-primary mb-3" value="<?=$btnval?>" class='tabbtn' /></div>
							<?php } else { ?>
						<div class="col-12"><input type='button' class="btn btn-primary mb-3" value="<?=$btnval?>" class='tabbtn' onclick="redirect(<?=$_SESSION['exam']['seclastid'][$i]?>)" /></div>
						<?php }
						} ?>
					
					</div>
						
				</div>	 
			</div>	 
		</div>	 
	</div>	 
</div>	 	 
 <?php include 'javascript.php'?>
 <script>function redirect(s){window.location.href='index.php?action=planquestion&section='+s; } </script>
