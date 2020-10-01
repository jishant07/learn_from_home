var $messages = $('#messages-content'),
    d, h, m,
    i = 0;

var myName = "";

$(window).load(function() {
 $messages.mCustomScrollbar();
  myName=$('#sender').val();
  sendername=$('#sendername').val();
  senderpic=$('#senderpic').val();
  sendtime=$('#sendtime').val();
  
  let receiver=getCookie('receiver');
	let receiverimage=getCookie('image');
	let receiver_name=getCookie('student_name');
	
	let queryString = window.location.search;
	let urlParams = new URLSearchParams(queryString);
	let chatwindow = urlParams.get('chatwindow')

	if(chatwindow==1 || chatwindow==2)
	$('#chatwindow').addClass('user-chat-show')
	
	if(chatwindow==1){
	$('#receivername').html(receiver_name)	
	$("#rcimage").attr("src",receiverimage);
	}
	if(chatwindow==2){
	$('#receivername').html('Group Chat')	
	$("#rcimage").attr("src",'../../uploads/avtar.png');
	}
	
	
  firebase.database().ref("messages").on("child_added", function (snapshot) {
	
	if(chatwindow==1){
	if (snapshot.val().sender == myName && snapshot.val().receiver==receiver) {
		//let sdate =setDate()
		let sendhtml=`<li class="right">
			<div class="conversation-list">    
				<div class="user-chat-content" id="message-${snapshot.key}">
					<div class="ctext-wrap">
						<div class="ctext-wrap-content">
							<p class="mb-0">
								${snapshot.val().message}
							</p>
							<p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${snapshot.val().sendtime}</span></p>
						</div>   
					</div>
					<div class="conversation-name"><button class="btn btn-primary btn-icon mt-2" data-id="${snapshot.key}" onclick="deleteMessage(this);"><i data-feather="x"></i>X</button></div>
				</div>
			</div>
		</li>`;
      $(sendhtml).appendTo($('.mCSB_container')).addClass('new');
      $('#message-input').val(null);
    } else {	
		
	if(snapshot.val().receiver == myName && snapshot.val().sendername.trim()==receiver_name){
		let gethtml=`<li>
		<div class="conversation-list">
				<div class="user-chat-content" id="message-${snapshot.key}">
					<div class="ctext-wrap">
						<div class="ctext-wrap-content">
							<p class="mb-0">
								${snapshot.val().message}
							</p>
							<p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${snapshot.val().sendtime}</span></p>
						</div>						
					</div>
					
				</div>
			</div>
		</li>`
      $(gethtml).appendTo($('.mCSB_container')).addClass('new');
		}
    }
	} else{
		
		if (snapshot.val().sender == myName && snapshot.val().receiver == receiver) {
		//let sdate =setDate()
		let sendhtml=`<li class="right">
			<div class="conversation-list">    
				<div class="user-chat-content" id="message-${snapshot.key}">
					<div class="ctext-wrap">
						<div class="ctext-wrap-content">
							<p class="mb-0">
								${snapshot.val().message}
							</p>
							<p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${snapshot.val().sendtime}</span></p>
						</div>   
					</div>
					<div class="conversation-name"><button class="btn btn-primary btn-icon mt-2" data-id="${snapshot.key}" onclick="deleteMessage(this);"><i data-feather="x"></i>X</button></div>
				</div>
			</div>
		</li>`;
      $(sendhtml).appendTo($('.mCSB_container')).addClass('new');
      $('#message-input').val(null);
    } else if(snapshot.val().receiver == receiver){	
		let gethtml=`<li>
		<div class="conversation-list">
				<div class="user-chat-content" id="message-${snapshot.key}">
					<div class="ctext-wrap">
						<div class="ctext-wrap-content">
							<p class="mb-0">
								${snapshot.val().message}
							</p>
							<p class="chat-time mb-0"><i class="ri-time-line align-middle"></i> <span class="align-middle">${snapshot.val().sendtime}</span></p>
						</div>						
					</div>
					<div class="conversation-name">${snapshot.val().sendername}</div>
				</div>
			</div>
		</li>`
      $(gethtml).appendTo($('.mCSB_container')).addClass('new');
		
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
    //$('<div class="timestamp">' + d.getHours() + ':' + m + '</div>').appendTo($('.message:last'));
	return d.getHours() + ':' + m 
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