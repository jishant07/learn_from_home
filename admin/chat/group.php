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

    <body>
<input type='hidden' id='ctime' value="<?=date('H:i A')?>">
        <div class="layout-wrapper d-lg-flex">

           


            <!-- Start User chat -->
            <div class="w-100"><form action="/" method="POST" id="form" style="width: 100% !important;" autocomplete='off'>
                <div class="d-lg-flex">

                    <!-- start chat conversation section -->
                    <div class="w-100" id='header-list'>
                        
						
                        <!-- end chat user head -->
    
                        <!-- start chat conversation -->
                        <div class="chat-conversation p-3 p-lg-4" data-simplebar="init">
                            <ul class="list-unstyled mb-0" id='left-side'>
                                
                                
                            </ul>
                        </div>
                        <!-- end chat conversation end -->
    
                        <!-- start chat input section -->
                        <div class="p-3 p-lg-4 border-top mb-0">
                            <div class="row no-gutters">
                                <div class="col">
                                    <div>
                                        <input type="text" class="form-control form-control-lg bg-light border-light" placeholder="Enter Message..." id="chat-message">
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

        <!-- JAVASCRIPT -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
        
        <script src="assets/libs/jquery/jquery.min.js"></script>
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







<script>
    var socket = io.connect('ws://chatapp-ejs.herokuapp.com/');
    //var socket = io();
    var userName = "<?=$_GET['username']?>"
    var userpic = "<?=$_GET['userpic']?>"
    var room = "<?=$_GET['room']?>"
    socket.emit('connection',({
        userName : userName,
        "roomName": "<?=$_GET['room']?>",
    }));
    $('#form').on('submit',(event)=>{    
        event.preventDefault();
        if($('#chat-message').val() !== "")
        {
			saveChat();
            chatMessage = $('#chat-message').val();
			chatMessage = chatMessage+'Upic#1*'+userpic;
            socket.emit('chat_message',chatMessage)
            $('#chat-message').val("")
        }
    })
    socket.on('chat_message',(chatInfo)=> {
		//alert(chatInfo.userName +'!=='+ userName)
        if(chatInfo.userName !== userName)
        {
           
			let message = chatInfo.message.split('Upic#1*');
			let time =$('#ctime').val();
			let newchtml = `<li>
                                    <div class="conversation-list">
                                        <div class="chat-avatar">
                                            <img src="../uploads/images/students/${message[1]}" alt="">
                                        </div>
    
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0">
                                                       ${message[0]}
                                                    </p>
                                                    <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${time}</span></p>
                                                </div>
                                                
                                            </div>
                                            <div class="conversation-name">${chatInfo.userName}</div>
                                        </div>
                                    </div>
                                </li>`
								;

		   $('#left-side').append(newchtml);
        }
        else
        {
			let message = chatInfo.message.split('Upic#1*');
			let time =$('#ctime').val();
												
			let newchtml = `<li class="right">
                                    <div class="conversation-list">
                                        <div class="chat-avatar">
                                            <img src="../uploads/images/students/${message[1]}" alt="">
                                        </div>
    
                                        <div class="user-chat-content">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0">
                                                        ${message[0]}
                                                    </p>
                                                    <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${time}</span></p>
                                                </div>
                                                    
                                                
                                            </div>
                                            <!--div class="conversation-name">Patricia Smith</div-->
                                        </div>
                                    </div>
                                </li>`						
									//alert(chtml)
            $('#left-side').append(newchtml);
        }
    })
    socket.on('is_online',(data) => {
		
		//if(data.username!=userName){
			let uname =data.username.trim();
			uname = uname.split(" ").join("")
			let hlist =`<div class="p-3 p-lg-4 border-bottom" id="${uname}">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-8">
                                    <div class="media align-items-center">
                                        <div class="d-block d-lg-none mr-2">
                                            <a href="javascript: void(0);" class="user-chat-remove text-muted font-size-16 p-2"><i class="ri-arrow-left-s-line"></i></a>
                                        </div>
                                        <!--div class="mr-3">
                                            <img src="../uploads/images/students/${userpic}" class="rounded-circle avatar-xs" alt="">
                                        </div-->
                                        <div class="media-body overflow-hidden">
                                            <h5 class="font-size-16 mb-0 text-truncate"><a href="#" class="text-reset user-profile-show">${data.username}</a> <i class="ri-record-circle-fill font-size-10 text-success d-inline-block ml-1"></i></h5>
                                        </div>
                                    </div>									
                                </div>                                
                            </div>							
                        </div>`
			
			$("#"+uname).remove();
        $('#header-list').prepend(hlist);
		//}
    })
    socket.on('disconnect',userList=>{
        socket.emit('disconnect');
    })
    socket.on('change-users', userList => {
        $('#user-list').empty()
        userList.forEach(element => {
            if(element.roomName == "<?=$_GET['room']?>")
            {
                $('#user-list').append("<li class='list-group-item' style='text-align:center;margin:auto;width:100%'>"+ element.userName + "<li>")
            }
        });
    })
</script>
<script>
function saveChat(){
let mesage = $('#chat-message').val();
	$.ajax({
        type: "POST",
        url: "../index.php?action=savechat",
        data: 'message=' + mesage,
        beforeSend: function() {
          //$("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 150px");
        },
        success: function(data) {
          //alert(data)
        }
      });
}
</script>












    </body>

</html>
