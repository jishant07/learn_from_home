	</div>
	<div class="text-center mt-5">
        <img src="images/amuze_logo.svg" />
    </div>
	
	
	
    <!--chat-->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <iframe class="chat-iframe" src="chat/group.php?username=<?=$studentname?>&room=<?=$_SESSION['class']?>" ></iframe>
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
	
	
  </body>
</html>