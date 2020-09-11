<?php //print_r($study);?>
      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <a href="index.php?action=documents" title="Documents" class="main-title-link"><img src="images/icons/left-arrow.svg" /> BACK</a>
                
            </div>
            
        </div>
        <div class="row study-materials-wrapper mt-4">
            <div class="col-md-3">
                <div class="details-box">
                    <h1>
                    <?=$study[0]['subject_name']?> Tips
                    </h1>
                    <h5><?=count($study)?> Documents</h5>
                </div>

            </div>
            <div class="col-md-9">
                <ul class="studymaterialtable">
                   <?php for($i=0; $i<count($study); $i++){ 
						$s = & $study[$i];
					?>
                    <li>
                        <div class="row">
                            <div class="col-1 col-md-1">
                                <div class="number"><?=$i+1?></div>
                            </div>
                            
                            <div class="col-7 col-md-10">
                                <h2><?=$s['vtitle']?></h2>
                                <div class="disc"><?=$s['name']?></div>
                            </div>
                            <div class="col-4 col-md-1 text-right">
                                <a href="index.php?action=pdf&type=study&file=<?=$s['studydoc']?>" target='_blank' title="Open" data-toggle="tooltip" data-placement="top" title="View" class="d-flex align-items-center justify-content-center">Open</a>
                            </div>
                        </div>
                    </li>
                    <?php } ?>                    
                    
                </ul>
            </div>
        </div>
      </section>
    
    <?php include('javascript.php') ?>
    
<script>
	$(document).ready(function(){
		var date_input=$('input[name="date"]'); //our date input has the name "date"
		var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
		date_input.datepicker({
			format: 'mm/dd/yyyy',
			container: container,
			todayHighlight: true,
			autoclose: true,
		})
	})
    
</script>
