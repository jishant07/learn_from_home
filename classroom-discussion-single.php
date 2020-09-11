<?php
error_reporting(E_ALL);
        extract($_GET);
		
		$sel_comments_count = "select id from comment_section where `ask_id`= $ask_id";
		$result_count = $conn -> query($sel_comments_count);
		 
?>
      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-7 col-md-10">
                <a href="index.php?action=classroom_discussion" class="main-title-link"><img src="images/icons/left-arrow.svg" /> BACK</a>
            </div>
        </div>
        <div class="row discussion-wrapper mt-4">
        <?php 
            $sel_sql = "select * from ask_questions where `ask_id` = $ask_id";
            $result = $conn->query($sel_sql);
            while($row = $result->fetch_assoc())
            {
          ?>
            <div class="col-12">
                <div class="title">
                  <div class="row">
                    <div class="col-12">
                      <div class="name">
					  <?php 
                              $ecode = $row['ecode'];
                              $sel_sql = "select student_name,image from students where `ecode` ='$ecode'";
                              $inner_result = $conn->query($sel_sql);
							  $rowc = $inner_result->fetch_assoc();
							  $simg = 'uploads/images/students/'.$rowc['image'];
                             // print_r($inner_result->fetch_all()[0][0]);
                          ?>
                        <img src="<?=$simg?>" />
                        <div class="details">
                          <h3><?php
                              print_r($rowc['student_name']);
                          ?><h3>
                          </span> on <span><?php echo explode(" ",$row['qdate'])[0] ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <h1><?php echo $row['q_details'] ?></h1>
                      <div class="total-comment"><i class="fa fa-comment" aria-hidden="true"></i> <?=$result_count->num_rows?> Comments</div>
                    </div>
                  </div>
                </div>
        <?php } ?>
                <div class="comment-section">
                    <ul>
                    <?php 
                      $sel_comments = "select c.*,s.image as simg from comment_section c,students s where `ask_id`= $ask_id and c.ecode=s.ecode order by c.id asc";
                      $result = $conn -> query($sel_comments);
                      while($row = $result->fetch_assoc())
                      {
						  $simg = 'uploads/images/students/'.$row['simg'];
                    ?>
                      <li>
                        <div class="name">
                          <img src="<?=$simg?>" />
                          <div class="details">
                            <h3><?php 
                              $ecode = $row['ecode'];
                              $sel_sql = "select student_name from students where `ecode` ='$ecode'";
                              $inner_result = $conn->query($sel_sql);
                              print_r($inner_result->fetch_all()[0][0]);
                            ?><h3>
                            <span><?php echo explode(" ",$row['timestamp'])[0] ?></span>
                          </div>
                        </div>
                        <p><?php echo $row['comment'] ?></p>
                      </li>
                    <?php } ?>
                    </ul>
                    <div class="yourcomment">
                      <h2>Your Comment</h2>
                      <form action="add_comment.php" method="POST">
                        <input type="hidden" name="ecode" value="<?php echo $emp_ecode;?>">
                        <input type="hidden" name="ask_id" value="<?php echo $ask_id;?>">
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Comments" rows="3" name="comment"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="button2 btn-red pull-right">SUBMIT</button>
                        </div>
                      </form>
                    </div>
                </div>

            </div>
        </div>
      </section>
    </div>
    <?php include('javascript.php') ?>   
    