var $messages = $('.messages-content'),
    d, h, m,
    i = 0;

var myName = "";

$(window).load(function() {
  $messages.mCustomScrollbar();
  myName=$('#sender').val();
  sendername=$('#sendername').val();
  senderpic=$('#senderpic').val();
  firebase.database().ref("messages").on("child_added", function (snapshot) {
	
	//let receiver=$('#receiver').val();
	let receiver=getCookie('receiver');
	let receiverimage=getCookie('image');
	let receiver_name=getCookie('student_name');
	$('#receivername').html(receiver_name)
	//alert(receiver);return false;
    if (snapshot.val().sender == myName && snapshot.val().receiver==receiver) {
      $('<div class="message message-personal"><figure class="avatar"><img src="'+snapshot.val().senderpic+'" /></figure><div id="message-' + snapshot.key + '">' + snapshot.val().message+ '<button class="btn-delete" data-id="' + snapshot.key + '" onclick="deleteMessage(this);">Delete</button></div></div>').appendTo($('.mCSB_container')).addClass('new');
      $('.message-input').val(null);
    } else {
		
	if(snapshot.val().receiver == myName && snapshot.val().sendername==receiver_name){
      $('<div class="message new"><figure class="avatar"><img src="'+snapshot.val().senderpic+'" /></figure><div id="message-' + snapshot.key + '">' + snapshot.val().sendername + ': ' + snapshot.val().message + '</div></div>').appendTo($('.mCSB_container')).addClass('new');
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
  msg = $('.message-input').val();
  if ($.trim(msg) == '') {
    return false;
  }
  sendMessage();
}

$('.message-submit').click(function() {
	
  insertMessage();
});

$(window).on('keydown', function(e) {
  if (e.which == 13) {
    insertMessage();
    return false;
  }
});