<?php //print_r($_SESSION)?>
<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between">
<p class="text-muted text-center text-md-left">Copyright © <?=date('Y')?> <a href="#" target="_blank">Learn From home</a>. All rights reserved</p>
<p class="text-muted text-center text-md-left mb-0 d-none d-md-block"><img src="assets/images/amuze_logo.svg" height="20px" /></p>
</footer>
<!--div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <iframe class="chat-iframe" src="chat/group.html"></iframe>
    </div-->
 
<div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <!--iframe class="chat-iframe" src="../chat/group.php?username=<?=$_SESSION['tcode']?>&room=<?=$_SESSION['class']?>&user=<?=$_SESSION['username']?>" ></iframe-->
		<iframe class="chat-iframe" src="chat/index.php?username=<?=$_SESSION['tcode']?>&user=<?=$_SESSION['username']?>&userpic=<?=$_SESSION['pic']?>" ></iframe>
    </div>
 <!--chat-->

<script>
    function openNav() {
  document.getElementById("mySidenav").style.width = "350px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
