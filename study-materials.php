      <section class="container-fluid documents-list mainwrapper">
        <div class="row">
            <?php for($s=0;$s<count($study); $s++) { 
				$sm = & $study[$s];
			?>
			<div class="col-lg-3 col-md-4 col-6">
                <a href="index.php?action=study-materials-single&sid=<?=$sm['subjectid']?>" class="documents-thumb d-flex align-items-end" >
                    <div class="content">
                        <h1><?=$sm['subject_name']?></h1>
                        <div class="text">
                        <?=$sm['cnt']?> documents
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
            
        </div>
      </section>
<?php include('javascript.php') ?>