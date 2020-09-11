      <section class="container-fluid wrapper-classroom mainwrapper">
        <div class="row class-details">
            <div class="col-md-2 col-6">
              <div class="stdDV">
                <div class="box"> 
                  <div class="std d-flex align-items-center justify-content-center"><?php echo trim(str_replace('Class','',$room['class_name']))?></div>
                </div>
                <div class="div"><?php echo $room['designation']?></div>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="classTeacher">
                <h2>Class Teacher</h2>
                <div class="name">
                  <img src="<?=$classteacher['t_pic']?>" />
                  <h3><?=$classteacher['t_name']?><h3>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="sub-details">
                <h2>SUBJECTS</h2>
				<?php for($s=0; $s<count($subjects); $s++) { ?>
                <div class="sub"><?=$subjects[$s]['subject_name']?></div>
                <?php } ?>
              </div>
            </div>
            
        </div>
        <div class="row teachers-details">
           <?php
            for($i=0;$i<count($teachers);$i++){
            ?>
			<div class="col-md-3 col-6">
            <div class="name">
              <img src="<?php echo $teachers[$i]['t_pic']; ?>" />
              <div class="details">
                <h3><?php echo $teachers[$i]['t_name']; ?><h3>
                <span><?php echo $teachers[$i]['subject_name']; ?></span>
              </div>
                
            </div>
          </div>
			<?php } ?>          
        </div>
        <div class="row students-details">
          <div class="col-12">
            <div class="row">
              <div class="col-6 col-md-4">
                <div class="search">
                  <input type="text" placeholder="Search student" id='searchstudent' name='searchstudent' />
                </div>
              </div>
              <div class="col-6 col-md-8">
                <div class="attendence">
                  <h2>today's attendance</h2>
                  <div class="status present">Present (<?=count($students)?>)</div>
                  <div class="status absent">Absent (6)</div>

                </div>    
              </div>
            </div>
            <div class="row students-names">
              <?php for($s=0; $s<count($students); $s++) {
				  $ecode =$students[$s]['ecode'];
				  ?>
			  <div class="col-md-3 col-6 listItem">
                <a href="javascript:void(0);" onclick="getStudentInfo('<?=$ecode?>')" class="name present" data-toggle="modal" data-target="#view-student">
                  <img src="<?=$students[$s]['image']?>" />
                  <div class="details">
                    <h3 class='sss'><?=$students[$s]['student_name']?><h3>
                    <span>Roll No. <?=$students[$s]['roll_no']?></span>
                  </div>                    
                </a>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        
      </section>
    
    
    <div class="modal fade" id="view-student" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog view-student-popup" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    
                </div>
            
            </div>
        </div>
        </div>
  	   <?php include('javascript.php') ?>
<script>
    $('#searchstudent').on('keyup' , (e) => {
        var search_term = e.target.value.toLowerCase();
        var arr = document.getElementsByClassName('listItem');
        for (let index = 0; index < arr.length; index++) {
            const element = arr[index].getElementsByClassName('sss')[0];
            var text = element.innerText.toLowerCase();
            if(text.indexOf(search_term) === -1)
            {
                arr[index].style.display = "none";
            }
            else{
                arr[index].style.display = "block";
            }   
        }
    })
 


function getStudentInfo(ecode){	
	let html=''
	 $.ajax({
        url:'index.php?action=getstudentinfo&stid='+ecode,
       
        type: 'POST',
      beforeSend: function(){
          },
      complete: function(){
          },
      success: function (data) {
		var obj = eval("(" + data + ')');		
       html  =`<div class="profile">
                      <img src="${obj.image}" />
                      <h2>${obj.student_name}</h2>
                      <h3>Roll No. ${obj.roll_no}</h3>
                    </div>
                    <ul>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Gender  :</div><div class="col-8">${obj.gender}</div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Birthdate  :</div><div class="col-8">${obj.date_birth}</div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Address  :</div><div class="col-8">${obj.branch},<BR>${obj.state}</div>
                        </div>
                      </li>
                      <!--li>
                        <div class="row">
                          <div class="col-4 title">Father Name  :</div><div class="col-8">Father Full Name <br>9876543210</div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col-4 title">Mother Name  :</div><div class="col-8">Mother Full Name <br>9876543210</div>
                        </div>
                      </li-->                      
                      <li>
                        <div class="row">
                          <div class="col-4 title">Email ID  :</div><div class="col-8">${obj.email}</div>
                        </div>
                      </li>
                      
                    </ul>
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="" class="button2 btn-red" data-toggle="modal" data-target="#view-student">CLOSE</a>
                    </div>`;
				$('.modal-body').html(html)	
        },
        cache: false,
        contentType: false,
        processData: false
    })
}
</script>	   
	   