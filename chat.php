<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <style>
        li{
            list-style: none;
        }
    </style>
    <title>Chat App - Test</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-3" style="background-color: beige;height: 100vh;width: 100%;">
                <ul class="list-group" id="user-list"></ul>
            </div>
            <div class="col-9"><ul class="list-group chat-list"></ul></div>
            <div class="col-12">
                <nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-dark">
                    <form action="/" method="POST" id="form" style="width: 100% !important;">
                    <input type="hidden" id='userName' value="Jishant">
                    <input type="hidden" id='roomName' value="3A">
                        <div class="row">
                            <div class="col-9">
                                <input 
                                    type="text"
                                    autocomplete="off"
                                    class="form-control" 
                                    id="chat-message" 
                                    placeholder="Enter Chat" 
                                    value="" 
                                    required 
                                    autofocus
                                    style="width: 100%;"
                                />
                            </div>
                            <div class="col-3">
                                <button 
                                    class="btn btn-primary"
                                    style="width:100%;" onclick="saveChat()">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>    
                </nav>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>
    var socket = io.connect('ws://chatapp-ejs.herokuapp.com/');
    //var socket = io();
    var userName = "<?=$_GET['username']?>"
    socket.emit('connection',({
        userName : userName,
        "roomName": "<?=$_GET['room']?>",
    }));
    $('#form').on('submit',(event)=>{    
        event.preventDefault();
        if($('#chat-message').val() !== "")
        {
            chatMessage = $('#chat-message').val();
            socket.emit('chat_message',chatMessage)
            $('#chat-message').val("")
        }
    })
    socket.on('chat_message',(chatInfo)=> {
        if(chatInfo.userName !== userName)
        {
            $('.chat-list').append("<li class='list-group-item' style='text-align:right'>" + chatInfo.message + " :" + chatInfo.userName)
        }
        else
        {
            $('.chat-list').append("<li class='list-group-item'>" + chatInfo.userName + " :" + chatInfo.message)
        }
    })
    socket.on('is_online',(data) => {
        $('.chat-list').append("<li class='list-group-item' style='text-align:center;margin:auto;'>"+ data.username + " is Online <li>");
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
        url: "index.php?action=savechat",
        data: 'message=' + mesage,
        beforeSend: function() {
          //$("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 150px");
        },
        success: function(data) {
          /////alert(data)
        }
      });
}
</script>
</html>