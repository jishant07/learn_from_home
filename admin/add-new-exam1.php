<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Learn From Home</title>
	<!-- core:css -->
	<link rel="stylesheet" href="assets/vendors/core/core.css">
	<!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/sweetalert2/sweetalert2.min.css">
	<link rel="stylesheet" href="assets/vendors/select2/select2.min.css">
	<link rel="stylesheet" href="assets/vendors/jquery-tags-input/jquery.tagsinput.min.css">
	<link rel="stylesheet" href="assets/vendors/dropzone/dropzone.min.css">
	<link rel="stylesheet" href="assets/vendors/dropify/dist/dropify.min.css">
	<link rel="stylesheet" href="assets/vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.css">
	<link rel="stylesheet" href="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
	<!-- end plugin css for this page -->
	<!-- inject:css -->
	<link rel="stylesheet" href="assets/fonts/feather-font/css/iconfont.css">
	<link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
	<!-- endinject -->
  <!-- Layout styles -->  
	<link rel="stylesheet" href="assets/css/demo_1/style.css">
	<link rel="stylesheet" href="assets/css/demo_1/cust.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>
<body>
	<div class="main-wrapper">

		<?php include('_sidebar.php') ?>
	
		<div class="page-wrapper">
					
			<?php include('_header.php') ?>

			<div class="page-content">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <nav class="page-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Class 1</a></li>
              <li class="breadcrumb-item"><a href="exams.php">Exams</a></li>
              <li class="breadcrumb-item active" aria-current="page">Add New Exam</li>
            </ol>
          </nav>
          
        </div>


        <div class="row">
          <div class="col-lg-5 mb-3">
            <div class="card">
              <div class="card-body">
                <form class="forms-sample">
									
                  <div class="form-group">
										<label>Schedule Date for Exam</label>
										<div class="input-group date datepicker" id="datePickerExample">
                      <input type="text" class="form-control"><span class="input-group-addon"><i data-feather="calendar"></i></span>
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="row">
                          <div class="col-6">
                              <label>Start Time</label>
                              <div class="input-group date timepicker" id="datetimepickerExample" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample"/>
                                  <div class="input-group-append" data-target="#datetimepickerExample" data-toggle="datetimepicker">
                                      <div class="input-group-text"><i data-feather="clock"></i></div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-6">
                              <label>End Time</label>
                              <div class="input-group date timepicker" id="datetimepickerExample2" data-target-input="nearest">
                                  <input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerExample2"/>
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
                              <label>Total Marks</label>
                              <input type="text" class="form-control" value="0" readonly>
                          </div>
                          
                      </div>
  
                  </div>
                  
									
									<button type="submit" class="btn btn-primary mr-2 mt-2">Save</button>
								</form>
               
                
              </div> 
            </div>
          </div>
          <div class="col-lg-7">
            <!--question box-->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <form class="forms-sample">
                      
                      <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label class="d-inline-block mt-2 mr-2">Marks</label>
                                <input type="text" class="form-control d-inline-block wd-80" >
                            </div>
                            
                        </div>
    
                      </div>
                      <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control" placeholder="Title">
                      </div>
                      <div class="form-group">
                        <label>Discription</label>
                        <textarea class="form-control" placeholder="Discription" rows="5"></textarea>
                      </div>
                      <div class="form-group">
                        <label>Upload Referance Document</label>
                        <input type="file" name="img[]" class="file-upload-default">
                        <div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Document">
                          <span class="input-group-append">
                            <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                          </span>
                        </div>
                      </div>
                      <hr>
                      <div class="form-check form-check-flat form-check-primary">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                          Student can upload document
                        </label>
                      </div>
                      
                      
                      <button type="submit" class="btn btn-success mr-2 mt-2">Save</button>
                      <button type="button" class="btn btn-primary btn-icon mt-2" data-toggle="tooltip" data-placement="top" title="Delete" onclick="showSwal('passing-parameter-execute-cancel')">
                          <i data-feather="x"></i>
                      </button>
                    </form>
                  
                    
                  </div> 
                </div>
              </div>
            </div>
            <!--end question box-->
            <div class="row">
              <div class="col-md-4"></div>
              <div class="col-md-8 col-12">
                <button type="submit" class="btn btn-warning ml-2 d-inline-block float-right">Add Question</button>
                <div class="form-group d-inline-block float-right">
                  <select class="form-control mb-3 ">
                    <option selected>Select Question Type</option>
                    <option value="1">Question & Answer</option>
                    <option value="2">Fill in the blanks</option>
                    <option value="3">Match The Following</option>
                    <option value="4">Checkbox</option>
                    <option value="5">Radio Buttons</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- row -->
        

			</div>
      
		  <?php include('_footer.php') ?>
			
		
		</div>
  </div>
  
  

	<!-- core:js -->
	<script src="assets/vendors/core/core.js"></script>
	<!-- endinject -->
  <!-- plugin js for this page -->
  <script src="assets/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script src="assets/vendors/promise-polyfill/polyfill.min.js"></script>
	<script src="assets/vendors/jquery-validation/jquery.validate.min.js"></script>
	<script src="assets/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
	<script src="assets/vendors/inputmask/jquery.inputmask.min.js"></script>
	<script src="assets/vendors/select2/select2.min.js"></script>
	<script src="assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
	<script src="assets/vendors/jquery-tags-input/jquery.tagsinput.min.js"></script>
	<script src="assets/vendors/dropzone/dropzone.min.js"></script>
	<script src="assets/vendors/dropify/dist/dropify.min.js"></script>
	<script src="assets/vendors/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
	<script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="assets/vendors/moment/moment.min.js"></script>
	<script src="assets/vendors/tempusdominus-bootstrap-4/tempusdominus-bootstrap-4.js"></script>
	<!-- end plugin js for this page -->
	<!-- inject:js -->
	<script src="assets/vendors/feather-icons/feather.min.js"></script>
	<script src="assets/js/template.js"></script>
	<!-- endinject -->
  <!-- custom js for this page -->
  <script src="assets/js/sweet-alert.js"></script>
  
	<script src="assets/js/form-validation.js"></script>
	<script src="assets/js/bootstrap-maxlength.js"></script>
	<script src="assets/js/inputmask.js"></script>
	<script src="assets/js/select2.js"></script>
	<script src="assets/js/typeahead.js"></script>
	<script src="assets/js/tags-input.js"></script>
	<script src="assets/js/dropzone.js"></script>
	<script src="assets/js/dropify.js"></script>
	<script src="assets/js/bootstrap-colorpicker.js"></script>
	<script src="assets/js/datepicker.js"></script>
	<script src="assets/js/timepicker.js"></script>
  
</html>    