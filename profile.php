      <section class="container-fluid mainwrapper">
        <div class="row profile justify-content-center">
            <div class="col-lg-5 col-md-5 col-12">
                <div class="profile-pic">
                    <img src="<?=$student['image']?>" alt="<?=$student['student_name']?>"/>
                    <h2><?=$student['student_name']?> <?=$student['student_lastname']?></h2>
                </div>
                
                    <ul>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Class  :</div><div class="col-8"> <?php echo $student['class_name'];?> <?php echo $student['designation']  ?></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Roll No.  :</div><div class="col-8"><?=$student['roll_no']?></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Gender  :</div><div class="col-8"><?php echo $student['gender']  ?></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Birthdate  :</div><div class="col-8"><?=$student['date_birth']?></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Address  :</div>
						  <div class="col-8">
						  <?php echo $student['branch']  ?> 
						  <?php echo $student['state']  ?>
						  </div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Father Name  :</div><div class="col-8"><?=$student['father_name']?> <br><?=$student['father_contact']?></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Mother Name  :</div><div class="col-8"><?=$student['mother_name']?> <br><?=$student['mother_contact']?></div>
                        </div>
                      </li>                      
                      <li>
                        <div class="row">
                          <div class="col-4 title">Email ID  :</div><div class="col-8"><?php echo $student['email']  ?></div>
                        </div>
                      </li>
                      
                    </ul>
				</div>
            
        </div>
      </section>
     <?php include('javascript.php') ?>
