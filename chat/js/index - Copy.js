var $messages = $('#messages-content'),
    d, h, m,
    i = 0;

var myName = "";

$(window).load(function() {
 $messages.mCustomScrollbar();
  myName=$('#sender').val();
  sendername=$('#sendername').val();
  senderpic=$('#senderpic').val();
  
  let receiver=getCookie('receiver');
	let receiverimage=getCookie('image');
	let receiver_name=getCookie('student_name');
	$('#receivername').html(receiver_name)
	$('#chatwindow').addClass('user-chat-show')
	
  firebase.database().ref("messages").on("child_added", function (snapshot) {
	
	//let receiver=$('#receiver').val();
	
	//alert(receiver);return false;
    if (snapshot.val().sender == myName && snapshot.val().receiver==receiver) {
		let sendhtml=`<li class="right">
                                    <div class="conversation-list">    
                                        <div class="user-chat-content" id="message-${snapshot.key}">
                                            <div class="ctext-wrap">
                                                <div class="ctext-wrap-content">
                                                    <p class="mb-0">
                                                        ${snapshot.val().message}
                                                    </p>
                                                    <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">10:02</span></p>
                                                </div>
                                                    
                                                
                                            </div>
                                            <div class="conversation-name"><button class="btn-delete" data-id="${snapshot.key}" onclick="deleteMessage(this);">Delete</button></div>
                                        </div>
                                    </div>
                                </li>`;
      $('<li><figure class="avatar"><img src="'+snapshot.val().senderpic+'" width=40 /></figure><div id="message-' + snapshot.key + '">' + snapshot.val().message+ '<button class="btn-delete" data-id="' + snapshot.key + '" onclick="deleteMessage(this);">Delete</button></div></li>').appendTo($('.mCSB_container')).addClass('new');
      $('#message-input').val(null);
    } else {	
		
	if(snapshot.val().receiver == myName && snapshot.val().sendername.trim()==receiver_name){
      $('<li><figure class="avatar"><img src="'+snapshot.val().senderpic+'" width=40/></figure><div id="message-' + snapshot.key + '">' + snapshot.val().sendername + ': ' + snapshot.val().message + '</div></li>').appendTo($('.mCSB_container')).addClass('new');
		}
    }
    
    setDate();
    updateScrollbar();
  });

});

function updateScrollbar() {
  $messages.mCustomScrollbar("update").mCustomScrollbar('scrollTo', 'bottom', {
    scrollInertia: 10,
    timeout: 0
  });
}

function setDate(){
  d = new Date()
  if (m != d.getMinutes()) {
    m = d.getMinutes();
    $('<div class="timestamp">' + d.getHours() + ':' + m + '</div>').appendTo($('.message:last'));
  }
}

function insertMessage() {
  msg = $('#message-input').val();

  if ($.trim(msg) == '') {
    return false;
  }
  sendMessage();
}

$('#submit-message').click(function() {
	
  insertMessage();
});

$(window).on('keydown', function(e) {
  if (e.which == 13) {
    insertMessage();
    return false;
  }
});