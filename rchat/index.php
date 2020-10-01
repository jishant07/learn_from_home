<?php
session_start();
include '../model/config.php';
include '../model/functions.php';

$students =  getAllStudents();


//print_r($students)
?>

<!-- The core Firebase JS SDK is always required and must be listed first -->
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
    var message = document.getElementById("message").value;
   // var receiver = document.getElementById("receiver").value;
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
      "receiver_name": student_name
    });
    return false;
  }
</script>

<style>
  figure.avatar {
    bottom: 0px !important;
  }
  .btn-delete {
    background: red;
    color: white;
    border: none;
    margin-left: 10px;
    border-radius: 5px;
  }
</style>
<?php
$stdinfo=getStudentInfo($_SESSION['eid']);
$img = $stdinfo['image'];

?>
<h1>welcome <?=$stdinfo['student_name']?></h1>
<div>
<?php 
$filelocation='../';
for($i=0;$i<count($students); $i++){
	$sname=$students[$i]['ecode'];
	$image='../'.$students[$i]['image'];
	$student_name=$students[$i]['student_name'];
	
	?>
	<a href='#' onclick="changeReceiver('<?=$sname?>','<?=$image?>','<?=$student_name?>')">
	<img src='<?=$image?>' width='40'>
	<?=$student_name?></a><BR><BR>
	<?php
}

?>

<input type='hidden' id='sender' value="<?=$_SESSION['eid']?>">
<input type='hidden' id='sendername' value="<?=$stdinfo['student_name']?>">
<input type='hidden' id='senderpic' value="<?=$img?>">
<input type='hidden' id='receiver'>
<script>
function changeReceiver(name,image,student_name){
	//location.reload();
	document.cookie = "receiver="+name;
	document.cookie = "image="+image;
	document.cookie = "student_name="+student_name;
	location.reload();
	//window.location.href='changereceiver.php?receiver=name';
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
</div>
<div class="chat">
  <div class="chat-title">
    <h1>Chat Room</h1>
    <h2 id='receivername'></h2>
    <figure class="avatar">
      <img src="https://p7.hiclipart.com/preview/349/273/275/livechat-online-chat-computer-icons-chat-room-web-chat-others.jpg" /></figure>
  </div>
  <div class="messages">
    <div class="messages-content"></div>
  </div>
  <div class="message-box">
    <textarea type="text" class="message-input" id="message" placeholder="Type message..."></textarea>
    <button type="submit" class="message-submit">Send</button>
  </div>

</div>
<div class="bg"></div>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js'></script>

        <script src="js/index.js?v=<?= time(); ?>"></script>