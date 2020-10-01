<?php 
session_start();
error_reporting(E_ALL);
error_reporting(0);
include '../../model/config.php';
include '../functions/functions.php';


$tclasses = getTeacherClassesNew();
//echo'<pre>',print_r($tclasses);exit;
$students= getAllClassStudents($tclasses);

$teachers =getAllClassTeachers($tclasses);
///


$totstumess=0; 
for($i=0;$i<count($students); $i++){
	$sname=$students[$i]['ecode'];
	//if($stdinfo['ecode']==$sname) continue;
	$totstumess = $totstumess + getChatMessage($sname,$_SESSION['tcode'],'1');
}
$totteachermess=0; 
for($t=0; $t<count($teachers); $t++){
	$i = & $teachers[$t];
	$code = $i['t_code'];
	$totteachermess = $totteachermess + getChatMessage($code,$_SESSION['tcode'],'1')	;
}

$openchat = getOpenChatMessage($tclasses);
foreach($openchat as $k=>$v){
	//echo $v;
	$_SESSION['tot_open_chat'][$k]=$v;
	$_SESSION['open_chat'][$k][]=$v;
	
}
//echo'<pre>',print_r($_SESSION['tot_open_chat']),'</pre>';
//if(isset($_GET['chatwindow']) && $_GET['chatwindow']==2)$tot_open_chat=0;
if($_GET['chatwindow']==2){
	$gclass=$_GET['class'];
	$_SESSION['remove_open'][$gclass]=0;
}
?>
<!doctype html>
<html lang="en">    
	<head>        
        <meta charset="utf-8" />
        <title>Learn From Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.png">

        <!-- magnific-popup css -->
        <link href="assets/libs/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css" />

        <!-- owl.carousel css -->
        <link rel="stylesheet" href="assets/libs/owl.carousel/assets/owl.carousel.min.css">

        <link rel="stylesheet" href="assets/libs/owl.carousel/assets/owl.theme.default.min.css">

        <!-- Bootstrap Css -->
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    </head>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>

<link rel="stylesheet" href="css/normalize.css">

    <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Open+Sans'>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css'>

        <link rel="stylesheet" href="css/style.css">

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#config-web-app -->

<script>
  // Your web app's Firebase configuration
  var firebaseConfig = {
  apiKey: "AIzaSyBp-acW7Z5B9TC0yTNuciIRsTAhhBug4AI",
  authDomain: "webchat-c2993.firebaseapp.com",
  databaseURL: "https://webchat-c2993.firebaseio.com",
  projectId: "webchat-c2993",
  storageBucket: "webchat-c2993.appspot.com",
  messagingSenderId: "122903932513",
  appId: "1:122903932513:web:4b7b63ae5d1ed38bac8420",
  measurementId: "G-1F5N5DN03X"
  };
  
  
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  firebase.database().ref("messages").on("child_removed", function (snapshot) {
    document.getElementById("message-" + snapshot.key).innerHTML = "This message has been deleted";
  });

  function deleteMessage(self) {
    var messageId = self.getAttribute("data-id");
    firebase.database().ref("messages").child(messageId).remove();
  }

  function sendMessage() {
    var message = document.getElementById("message-input").value;
   let receiver=getCookie('receiver');
   let studentimage=getCookie('image');
	let student_name=getCookie('student_name');
    firebase.database().ref("messages").push().set({
      "message": message,
      "sender": myName,
      "sendername": sendername,
      "senderpic": senderpic,
      "receiver": receiver,
      "receiverimage": studentimage,
      "receiver_name": student_name,
      "sendtime": sendtime
	  
    });
	saveMessage(message,myName,receiver);
    return false;
  }
  
  

</script>


    <body>

        <div class="layout-wrapper d-lg-flex">

            <!-- Start left sidebar-menu -->
            <div class="side-menu flex-lg-column mr-lg-1">
                <!-- LOGO -->
                
                <!-- end navbar-brand-box -->

                <!-- Start side-menu nav -->
                <div class="flex-lg-column my-auto">
                    <ul class="nav nav-pills side-menu-nav justify-content-center" role="tablist">
                        
                        <!--li class="nav-item" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Chats">
                            <a class="nav-link active" id="pills-chat-tab" data-toggle="pill" href="#pills-chat" role="tab">
                                <i class="ri-message-3-line"></i>
                                <label>Recent</label>
								
                            </a>
                            
                        </li-->
                        <li class="nav-item" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Students">
                            <a class="nav-link" id="pills-students-tab" data-toggle="pill" href="#pills-students" role="tab">
                                <i class="ri-user-line"></i>
                                <label>Students<?php if($totstumess>0) echo "[$totstumess]"?></label>
                            </a>
                        </li>
                        
                        <li class="nav-item" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Teachers">
                            <a class="nav-link" id="pills-teachers-tab" data-toggle="pill" href="#pills-teachers" role="tab">
                                <i class="ri-user-line"></i>
                                <label>Teachers<?php if($totteachermess>0) echo "[$totteachermess]"?></label>
                            </a>
                        </li>
                        <li class="nav-item" data-toggle="tooltip" data-trigger="hover" data-placement="top" title="Groups">
                            <a class="nav-link" id="pills-groups-tab" data-toggle="pill" href="#pills-groups" role="tab">
                                <i class="ri-group-line"></i>
                                <label>Group<?php // if($tot_open_chat>0) echo "[$tot_open_chat]"?></label>
                            </a>
                        </li>
                        
                        
                    </ul>
                </div>
                <!-- end side-menu nav -->

               
                <!-- Side menu user -->
            </div>
            <!-- end left sidebar-menu -->

            <!-- start chat-leftsidebar -->
            <div class="chat-leftsidebar mr-lg-1">

                <div class="tab-content">
                    

                     <!-- Start students tab-pane -->
                     <div class="tab-pane fade show active" id="pills-students" role="tabpanel" aria-labelledby="pills-students-tab">
                        <!-- Start chats content -->
                        <div>
                            <div class="px-4 pt-4">
                                <h4 class="mb-4">Students</h4>
                                <div class="search-box chat-search-box">
                                    <div class="input-group mb-3 bg-light  input-group-lg rounded-lg">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-link text-muted pr-1 text-decoration-none" type="button">
                                                <i class="ri-search-line search-icon font-size-18"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control bg-light" placeholder="Search messages or students">
                                    </div> 
                                </div> <!-- Search Box-->
                            </div> <!-- .p-4 -->
    

                            <!-- Start chat-message-list -->
                            <div class="px-2">

                                <div class="chat-message-list" data-simplebar>
            
                                    <ul class="list-unstyled chat-list chat-user-list">
                                        <?php 
									$filelocation='../';
									for($i=0;$i<count($students); $i++){
										$sname=$students[$i]['ecode'];
										$image='../'.$students[$i]['image'];
										$student_name=$students[$i]['student_name'];
										$no = getChatMessage($sname,$_SESSION['tcode'],'1')										
										?>

                                        <li>
                                            <a href="#" onclick="changeReceiver('<?=$sname?>','<?=$image?>','<?=$student_name?>','students')">
                                                <div class="media">
                            
                                                    <div class="chat-user-img online align-self-center mr-3">
                                                        <img src="<?=$image?>" class="rounded-circle avatar-xs" alt="">
                                                        <span class="user-status"></span>
                                                    </div>
                            
                                                    <div class="media-body overflow-hidden">
                                                        <h5 class="text-truncate font-size-15 mt-2"><?=$student_name?>										
														</h5>
                                                    </div>
													<?php if($no>0){?>
													<div class="unread-message">
                                                        <span class="badge chat-count badge-pill"><?=$no?></span>
                                                    </div>
													<?php } ?>
                                                </div>
                                            </a>
                                        </li>
									<?php } ?>
                                        
    
    
    
                                    </ul>
                                </div>
        
                            </div>
                            <!-- End chat-message-list -->
                        </div>
                        <!-- Start chats content -->
                    </div>
                    <!-- End students tab-pane -->

                    <!-- Start teachers tab-pane -->
                     <div class="tab-pane fade" id="pills-teachers" role="tabpanel" aria-labelledby="pills-teachers-tab">
                        <!-- Start chats content -->
                        <div>
                            <div class="px-4 pt-4">
                                <h4 class="mb-4">Teachers</h4>
                                <div class="search-box chat-search-box">
                                    <div class="input-group mb-3 bg-light  input-group-lg rounded-lg">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-link text-muted pr-1 text-decoration-none" type="button">
                                                <i class="ri-search-line search-icon font-size-18"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control bg-light" placeholder="Search messages or Teachers">
                                    </div> 
                                </div> <!-- Search Box-->
                            </div> <!-- .p-4 -->
    

                            <!-- Start teachers-message-list -->
                            <div class="px-2">

                                <div class="chat-message-list" data-simplebar>
            
                                    <ul class="list-unstyled chat-list chat-user-list">
									<?php for($t=0; $t<count($teachers); $t++){
											$i = & $teachers[$t];
											$pic = $i['t_pic'];
											$code = $i['t_code'];
											if($code==$_SESSION['tcode'])continue;
											$t_name = $i['t_name'];
											$no = getChatMessage($code,$_SESSION['tcode'],'1');
										?>
                                        <li>
                                            <a href="#" onclick="changeReceiver('<?=$code?>','<?=$pic?>','<?=$t_name?>','teachers')">
                                                <div class="media">
                            
                                                    <div class="chat-user-img online align-self-center mr-3">
                                                        <img src="<?=$pic?>" class="rounded-circle avatar-xs" alt="">
                                                        <span class="user-status"></span>
                                                    </div>
                            
                                                    <div class="media-body overflow-hidden">
                                                        <h5 class="text-truncate font-size-15 mt-2"><?=$i['t_name']?></h5>
                                                    </div>
													<?php if($no>0){?>
													<div class="unread-message">
                                                        <span class="badge chat-count badge-pill"><?=$no?></span>
                                                    </div>
													<?php } ?>
                                                </div>
                                            </a>
                                        </li>    
									<?php } ?>	
                                    </ul>
                                </div>
        
                            </div>
                            <!-- End chat-message-list -->
                        </div>
                        <!-- Start chats content -->
                    </div>
                    <!-- End teachers tab-pane -->
                    
                    <!-- Start groups tab-pane -->
                    <div class="tab-pane" id="pills-groups" role="tabpanel" aria-labelledby="pills-groups-tab">
                        <!-- Start Groups content -->
                        <div>
                            <div class="p-4">
                                <?php //print_r($openchat)?>
                                <?php //print_r($_SESSION['tot_open_chat'])?>
                                <h4 class="mb-4">Groups</h4>           

                                <div class="search-box chat-search-box">
                                    <div class="input-group bg-light  input-group-lg rounded-lg">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-link text-decoration-none text-muted pr-1" type="button">
                                                <i class="ri-search-line search-icon font-size-18"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control bg-light" placeholder="Search groups...">
                                    </div>
                                </div>
                                <!-- end search-box -->
                            </div>

                            <!-- Start chat-group-list -->
                            <div class="p-4 chat-message-list chat-group-list" data-simplebar> 

                                <ul class="list-unstyled chat-list">
                                    <?php 
									for($t=0; $t<count($tclasses); $t++){
										$g = & $tclasses[$t];
										
										$class = getClassName($g);
										if(isset($_SESSION['remove_open'][$g])) $conut=0;
										else $conut = $_SESSION['tot_open_chat'][$g];
									?>
									<li>
                                        <a href="#" onclick="changeReceiver1('<?=$g?>','','<?=$class?>','class')">
                                            <div class="media align-items-center">
                                                <div class="chat-user-img mr-3">
                                                    <div class="avatar-xs">
                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                            <?=$t+1?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="media-body overflow-hidden">
                                                    <h5 class="text-truncate font-size-14 mb-0"><?=$class?>[<?=$conut?>]</h5>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
									<?php } ?>
                                </ul>
                            </div>
                            <!-- End chat-group-list -->
                        </div>
                        <!-- End Groups content -->
                    </div>
                    <!-- End groups tab-pane -->

                    
                    
                  
                </div>
                <!-- end tab content -->

            </div>
            <!-- end chat-leftsidebar -->

            <!-- Start User chat -->
            <div class="user-chat w-100" id='chatwindow'>
                <div class="d-lg-flex">
<?php //print_r($_GET)?>
                    <!-- start chat conversation section -->
                    <div class="w-100">
                        <div class="p-3 p-lg-4 border-bottom">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-8">
                                    <div class="media align-items-center"><?php 
										if($_GET['ctype']=='students') $bhref='pills-students';
										if($_GET['ctype']=='teachers') $bhref='pills-teachers';
										if($_GET['ctype']=='class') $bhref='pills-groups';
										?>
                                        <div class="d-block d-lg-none mr-2">
                                            <a class="user-chat-remove text-muted font-size-16 p-2" id="pills-teachers-tab" data-toggle="pill" href="#<?=$bhref?>" role="tab"><i class="ri-arrow-left-s-line"></i></a>											
                                        </div>
                                        <div class="mr-3">
                                            <img src="assets/images/users/avatar-4.jpg" class="rounded-circle avatar-xs" alt="" id='rcimage'>
                                        </div>
                                        <div class="media-body overflow-hidden">
                                            <h5 class="font-size-16 mb-0 text-truncate"><a href="#" class="text-reset user-profile-show"><span id='receivername'></span></a> <i class="ri-record-circle-fill font-size-10 text-success d-inline-block ml-1"></i></h5>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <!-- end chat user head -->
    
                        <!-- start chat conversation -->
                        <div class="chat-conversation p-3 p-lg-4" data-simplebar="init">
                            <ul class="list-unstyled mb-0" id='messages-content'>
                                
                            </ul>
                        </div>
                        <!-- end chat conversation end -->
    
                        <!-- start chat input section -->
                        <div class="p-3 p-lg-4 border-top mb-0">
                            <div class="row no-gutters">
                                <div class="col">
                                    <div>
                                        <input type="text" class="form-control form-control-lg bg-light border-light" placeholder="Enter Message..." id='message-input' autocomplete='off'>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ml-md-2">
                                        <ul class="list-inline mb-0">
                                            
                                            <li class="list-inline-item">
                                                <button type="submit" class="btn btn-send font-size-16 btn-lg chat-send waves-effect waves-light">
                                                    <i class="ri-send-plane-2-fill"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <!-- end chat input section -->
                    </div>
                    <!-- end chat conversation section -->                   
                </div>
            </div>
            <!-- End User chat -->
        </div>
        <!-- end  layout wrapper -->

<input type='hidden' id='sender' value="<?=$_SESSION['tcode']?>">
<input type='hidden' id='sendername' value="<?=$_SESSION['username']?>">
<input type='hidden' id='senderpic' value="<?=$_SESSION['pic']?>">
<input type='hidden' id='receiver'>
<input type='hidden' id='sendtime' value="<?=date("d M Y H:i A")?>">

<script>
	
function changeReceiver(name,image,student_name,ctype){
	document.cookie = "receiver="+name;
	document.cookie = "image="+image;
	document.cookie = "student_name="+student_name;
	var url  = window.location.href;     // Returns full URL (https://example.com/path/example.html)
	
	var url = url.replace(/&chatwindow=1/g, "");
	var url = url.replace(/&chatwindow=2/g, "");
	url=url+"&chatwindow=1&ctype="+ctype;
//	alert(url);
	window.location.href=url;
	//location.reload();
}

function changeReceiver1(name,image,student_name,ctype){
	document.cookie = "receiver="+name;
	document.cookie = "image="+image;
	document.cookie = "student_name="+student_name;
	var url  = window.location.href;     // Returns full URL (https://example.com/path/example.html)
	
	var url = url.replace(/&chatwindow=1/g, "");
	var url = url.replace(/&chatwindow=2/g, "");
	url=url+"&chatwindow=2&class="+name+"&ctype="+ctype;
	//alert(url);
	window.location.href=url;
	//location.reload();
}
function getCookie(name) {
    // Split cookie string and get all individual name=value pairs in an array
    var cookieArr = document.cookie.split(";");
    
    // Loop through the array elements
    for(var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        
        /* Removing whitespace at the beginning of the cookie name
        and compare it with the given string */
        if(name == cookiePair[0].trim()) {
            // Decode the cookie value and return
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    // Return null if not found
    return null;
}
</script>




        <!-- JAVASCRIPT -->
         <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>

        <!-- Magnific Popup-->
        <script src="assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>

        <!-- owl.carousel js -->
        <script src="assets/libs/owl.carousel/owl.carousel.min.js"></script>

        <!-- page init -->
        <script src="assets/js/pages/index.init.js"></script>

        <script src="assets/js/app.js"></script>





		
<script src='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js'></script>

		<script src="js/index.js?v=<?= time(); ?>"></script>
<script>		
function saveMessage(message,sender,receiver){
	let queryString = window.location.search;
	let urlParams = new URLSearchParams(queryString);
	let chatwindow = urlParams.get('chatwindow')
	//let fdata = {sender:sender,receiver:receiver,message:message,chatwindow:chatwindow};
	$.ajax({
		url: '../../index.php?action=add-chat-message&sender='+sender+'&receiver='+receiver+'&message='+message+'&chatwindow='+chatwindow,
		type: 'POST',
		success: function(data) {
			//alert(data)
		},
		cache: false,
		contentType: false,
		processData: false
	});	  
}

function removeChatMessage(sender,receiver){
	let queryString = window.location.search;
	let urlParams = new URLSearchParams(queryString);
	let chatwindow = urlParams.get('chatwindow')
	//let fdata = {sender:sender,receiver:receiver,message:message,chatwindow:chatwindow};
	$.ajax({
		url: '../../index.php?action=remove-chat-message&sender='+sender+'&receiver='+receiver+'&chatwindow='+chatwindow,
		type: 'POST',
		success: function(data) {
			//alert(data)
		},
		cache: false,
		contentType: false,
		processData: false
	});	  
}

</script>

    </body>

</html>
