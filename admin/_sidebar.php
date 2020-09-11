<?php if(isset($_GET['action']))
$action=$_GET['action'];
else $action='home';
?>
<nav class="sidebar">
      <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
          <img src="assets/images/logo.svg" height="20px" />
        </a>
        <div class="sidebar-toggler not-active">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="sidebar-body">
        <ul class="nav">
          <?php //if($_SESSION['u_type']=='admin'){?>
		  <li class="nav-item nav-category">Main</li>
          <li class="nav-item">
            <a href="index.php" class="nav-link <?=$actionc==''?'active':''?>">
              <i class="link-icon" data-feather="box"></i>
              <span class="link-title">Dashboard</span>
            </a>
          </li>
		  <li class="nav-item">
            <a href="index.php?action=teachers" class="nav-link <?=$actionc=='teachers'?'active':''?>">
              <i class="link-icon" data-feather="star"></i>
              <span class="link-title">Teachers</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="index.php?action=students" class="nav-link <?=$actionc=='students'?'active':''?>">
              <i class="link-icon" data-feather="users"></i>
              <span class="link-title">Students</span>
            </a>
          </li>
		  <?php //} ?>	
          <li class="nav-item nav-category">Classes</li>
		  <?php
		  
		  for($t=0;$t<count($tclasses); $t++) { 
				if($_SESSION['u_type']=='teacher'){
					$classroom = & $tclasses[$t]['classroom'];
				}
				else $classroom = & $tclasses[$t]['class_id'];
				
		  ?>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#class<?=$classroom?>" role="button" aria-expanded="false" aria-controls="emails">
              <i class="link-icon" data-feather="folder"></i>
              <span class="link-title"><?=getClassName($classroom)?></span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse" id="class<?=$classroom?>">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="index.php?action=classroom&class=<?=$classroom?>" class="nav-link <?=$actionc=='classroom'?'active':''?>">Classroom</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=live-sessions&class=<?=$classroom?>" class="nav-link <?=$actionc=='live-sessions'?'active':''?>">Live Sessions</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=courses&class=<?=$classroom?>" class="nav-link <?=$actionc=='courses'?'active':''?>">Courses</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=videos&class=<?=$classroom?>" class="nav-link <?=$actionc=='videos'?'active':''?>">Videos</a>
                </li>
				<li class="nav-item">
                  <a href="index.php?action=subjects&class=<?=$classroom?>" class="nav-link <?=$actionc=='subjects'?'active':''?>">Subjects</a>
                </li>
				<li class="nav-item">
                  <a href="index.php?action=books&class=<?=$classroom?>" class="nav-link <?=$actionc=='books'?'active':''?>">Books</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=assignments&class=<?=$classroom?>" class="nav-link <?=$actionc=='assignments'?'active':''?>">Assignments</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=exams&class=<?=$classroom?>" class="nav-link <?=$actionc=='exams'?'active':''?>">Exams</a>
                </li>
                <li class="nav-item">
                  <a href="index.php?action=documents&class=<?=$classroom?>" class="nav-link <?=$actionc=='documents'?'active':''?>">Documents</a>
                </li>
				<li class="nav-item">
                  <a href="index.php?action=classroom-discussion&class=<?=$classroom?>" class="nav-link <?=$actionc=='classroom-discussion'?'active':''?>">Classroom Discussion</a>
                </li>
              </ul>
            </div>
          </li>
		  <?php } ?>		
		</ul>
      </div>
    </nav>