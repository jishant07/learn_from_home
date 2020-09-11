<?php
$sort = '';
$class = $_SESSION["class"];
 if(isset($_GET['sort'])) {
	$sort = $_GET['sort'];
	if($sort=='latest')$sql = "select * from ask_questions where class='$class' order by qdate desc";
	if($sort=='latestcomment') $sql = "select q.* from ask_questions q left join comment_section c on q.ask_id=c.ask_id where class='$class' GROUP by q.ask_id order by c.id desc";
	if($sort=='mostcommented') $sql = "select q.*,COUNT(q.ask_id) as cnt from ask_questions q left join comment_section c on q.ask_id=c.ask_id where class='$class' GROUP by q.ask_id order BY c.ask_id desc";
 }
else
$sql = "select * from ask_questions where class='$class' order by qdate asc";
$result = $conn->query($sql);
$number_of_rows = $result->num_rows;
?>
<div>
      <section class="container-fluid wrapper-inner mainwrapper">
        <div class="row">
            <div class="col-12">
                <h1 class="main-title">Classroom Discussion</h1>
                
            </div>
        </div>
        <div class="row discussion-wrapper mt-4">
            <div class="col-md-3 mb-4">
                <a href="" class="new-discussion" data-toggle="modal" data-target="#new-discussion">START NEW DISCUSSION</a>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-6 col-md-3">
                        <div class="shorting">
                            <select onchange="sortDiscussion(this.value)">
                                <option value=''>Sort by</option>
                                <option value="latest">Latest Added</option>
                                <option value="latestcomment">Latest Commented</option>
                                <option value="mostcommented">Most Commented</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-9">
                        <div class="search">
                            <input type="text" placeholder="Search" id="search"/>
                        </div>

                    </div>

                </div>
                <ul class="discussiontable">
                <?php 
                    if($result -> num_rows > 0)
                    {
                        $count = 0;
                        $class_count = 1;
                        while($row = $result->fetch_assoc())
                        {
                            //if($row['q_title'] != ""){

                ?>
                                <li class="listItem page_<?php echo $class_count ?>">
                                    <?php if((++$count)%5 == 0) {$class_count ++;} ; ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="index.php?action=classroom-discussion-single&ask_id=<?php echo $row['ask_id'] ?>" class="q-title"><?php echo $row['q_details'] ?></a>
                                        </div>
                                        <div class="col-8 col-md-4">
                                            <div class="upload-details">by <span>
                                            <?php 
                                                $ecode = $row['ecode'];
                                                $sel_sql = "select student_name from students where `ecode` ='$ecode'";
                                                $inner_result = $conn->query($sel_sql);
                                                print_r($inner_result->fetch_all()[0][0]);
                                            ?>
                                            </span> on <span><?php echo explode(" ",$row['qdate'])[0] ?></span></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="comments"><i class="fa fa-comment" aria-hidden="true"></i> <?php 
                                                $int_ask_id = $row['ask_id'];
                                                $comment_count = "select * from comment_section where `ask_id`='$int_ask_id'";
                                                $inner_result = $conn->query($comment_count);
                                                echo ($inner_result->num_rows);
                                            ?> Comments</div>
                                        </div>
                                    </div>
                                </li>
                <?php 
                           // }
                        }
                    }
                    else{
                        ?>
                            <li>
                                <div class="row">
                                    <div class="col-12">
                                        <a href="#">No Discussions yet.</a>
                                    </div>
                                </div>
                            </li>
                        <?php
                    }
                ?>  
                </ul>
                <input type="hidden" name="numberOfPages" id="num_of_pages" value = "<?php echo $class_count; ?>">
                <ul class="pagination justify-content-center">
                    <?php for($i = 1; $i<= $class_count;$i++){ ?>
                        <li class="page-item"><a class="page-link sel_page_<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
      </section>
    </div>
    <div class="modal fade" id="new-discussion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog task-popup" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>Add New Discussion</h1>
                    <form action='index.php?action=submit_discussion' method='post'>
                        <!--div class="form-group">
                            <input type='text' class="form-control" placeholder="Subject" name='txtsubject'/>
                        </div-->
						<div class="form-group">
                            <textarea class="form-control" placeholder="Start Discussion" name='txtdiscussion' rows="6"></textarea>
                        </div>
                        <div class="form-group d-flex align-items-center justify-content-center">
                            <button type='submit' class="button2 btn-red">SUBMIT</button>
                        </div>
                    </form>
                </div>
            
            </div>
        </div>
    </div>
<?php include('javascript.php') ?>

<script>
    $('#search').on('keyup' , (e) => {
        var search_term = e.target.value.toLowerCase();
        var arr = document.getElementsByClassName('listItem');
        for (let index = 0; index < arr.length; index++) {
            const element = arr[index].getElementsByClassName('q-title')[0];
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
    var pageIndex = 1;
    var active_page = ".page_1";
    var num_of_pages = parseInt($('#num_of_pages').val())
    function set_active_page(active_page)
    {
        for(var i=1;i<=num_of_pages;i++)
        {
            if(active_page !== '.page_'+i)
            {
                $('.page_'+i).css('display',"none");
            }
            else
            {
                $('.page_'+i).css('display',"block");
            }
        }
    }
    set_active_page(active_page);
    $('.pagination').on('click',(e) => {
            set_active_page(".page_"+e.target.classList[1].slice(-1))
    })
	
	function sortDiscussion(val){		
		window.location.href="index.php?action=classroom_discussion&sort="+val
	}
</script>