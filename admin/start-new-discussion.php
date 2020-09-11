                <nav class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="#"><?=getClassName($_GET['class'])?></a></li>
						<li class="breadcrumb-item"><a href="index.php?action=classroom-discussion&class=<?=$_GET['class']?>">Classroom Discussion</a></li>
						<li class="breadcrumb-item active" aria-current="page">Start New Discussion</li>
					</ol>
                </nav>
                <div class="row">
					<div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
								<form class="forms-sample">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" placeholder="Title">
                                    </div>
                                   
									
                                    
                                   
									
									<button type="submit" class="btn btn-primary mr-2 mt-2">Submit</button>
									<button class="btn btn-light mt-2">Cancel</button>
								</form>
                            </div>
                        </div>
					</div>
					
				</div>
<?php include('javascript.php') ?>