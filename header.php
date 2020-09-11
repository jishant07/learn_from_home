<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Learn From Home</title>
    <link rel="icon" href="images/favicon.png" sizes="16x16" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" href="css/style.css" crossorigin="anonymous">
	<link rel="stylesheet" href="css/timetable.css" crossorigin="anonymous">

  </head>
  <body>
  <header class="container-fluid mainwrapper">
      <div class="row">
        <div class="col-12">
          <div class="logo">
            <a href="index.php" title='Learn From Home'><img src="images/logo.svg" alt='Learn From Home' /></a>
          </div>
          <div class="user dropdown">
            <div class="user-pic" data-toggle="dropdown">
              <img src="<?=$filelocation?>uploads/images/students/<?=$emp_ecode?>.jpg" />
            </div>
            
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="index.php?action=my-profile" title='My Profile'>Profile</a>
              <a class="dropdown-item" href="index.php?action=my_classroom" title='My Classroom'>My Classroom</a>
              <a class="dropdown-item" href="index.php?action=my_watchlist" title='My Watchlist'>My Watchlist</a>
              <a class="dropdown-item" href="index.php?action=timetable" title='Time Table'>Time Table</a>
              <a class="dropdown-item" href="index.php?action=documents" title='Documents'>Documents</a>
              <a class="dropdown-item" href="index.php?action=my-account" title='Account'>Account</a>
              <a class="dropdown-item" href="index.php?action=logout" title='Logout'>Logout</a>
            </div>
          </div>
          <div class="notification dropdown">
            <a href="#" class="header-shortcut red d-flex align-items-center justify-content-center" data-toggle="dropdown">
              <img src="images/icons/notification.svg" />
              
            </a>
            
            <div class="dropdown-menu dropdown-menu-right">
              <ul>
			   <?php 
				for($n=0;$n<count($notifications);$n++){
					$link = "index.php?action=".$notifications[$n]['page'];
					$id=$notifications[$n]['id'];
				  ?>
                <li>
                  <a href="<?=$link?>">
                    <h4><?=date('d M Y H:i A',strtotime($notifications[$n]['created']))?><!--5 min ago--></h4>
                    <p><?=stripslashes($notifications[$n]['comments'])?>	</p>
                  </a>
                </li>
			   <?php } ?>
              </ul>
            </div>
          </div>
          
          
          <a href="#" onclick="openNav()" class="header-shortcut yellow d-flex align-items-center justify-content-center">
            <img src="images/icons/chat.svg" />            
          </a>
          <a href="index.php?action=task" class="header-shortcut green d-flex align-items-center justify-content-center">
            <img src="images/icons/tasks.svg" />
          </a>
        </div>
      </div>
    </header>
    <section class="menu-wrapper">
      <nav class="nav-act container-fluid mainwrapper">
        <div class="row">
          <a href="index.php" class="col" title="Learn From Home">Home</a>
          <a href="index.php?action=live-session" class="col" title='Live Sessions'>Live Sessions</a>
          <a href="index.php?action=video-list" class="col" title='Videos'>Videos</a>
          <a href="index.php?action=my_books" class="col" title='Books'>Books</a>
          <a href="index.php?action=timetable" class="col" title='Time Table'>Time Table</a>
          <a href="index.php?action=classroom_discussion" class="col" title='Classroom Discussion'>Classroom Discussion</a>
          <a href="index.php?action=documents" class="col" title='Documents'>Documents</a>
          
        </div>
        
      </nav>
      
    </section>
    <section class="mobile-menu">
      <div class="nav-act container-fluid">
        <div class="row">
          <a href="index.php" class="col">
            <svg id="browser_1_" data-name="browser (1)" xmlns="http://www.w3.org/2000/svg" width="27.698" height="25.966" viewBox="0 0 27.698 25.966">
              <path id="Path_15" data-name="Path 15" d="M27.445,27.949,16.681,17.184a4.009,4.009,0,0,0-5.662,0L.254,27.949a.865.865,0,0,0,1.224,1.224l.687-.687v11a2.489,2.489,0,0,0,2.489,2.489H8.981a.541.541,0,0,0,.541-.541V33.431a2.7,2.7,0,0,1,2.7-2.7h3.246a2.7,2.7,0,0,1,2.7,2.7v8.007a.541.541,0,0,0,.541.541h4.328a2.489,2.489,0,0,0,2.489-2.489v-11l.687.687a.865.865,0,1,0,1.224-1.224Z" transform="translate(-0.001 -16.014)"/>
            </svg>
            
            <h4>Home</h4>
          </a>
          <a href="index.php?action=video-list" class="col">
            <svg id="live" xmlns="http://www.w3.org/2000/svg" width="26.196" height="26.196" viewBox="0 0 26.196 26.196">
              <g id="Group_209" data-name="Group 209">
                <path id="Path_30" data-name="Path 30" d="M13.1,0A13.1,13.1,0,1,0,26.2,13.1,13.1,13.1,0,0,0,13.1,0Zm5.516,13.515a.936.936,0,0,1-.42.42v0l-7.485,3.742a.936.936,0,0,1-1.354-.842V9.356a.936.936,0,0,1,1.354-.837l7.485,3.742A.936.936,0,0,1,18.614,13.515Z"/>
              </g>
            </svg>            
            <h4>Videos</h4>
          </a>
          <a href="index.php?action=live-session" class="col">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 149.457 149.458">
              <g id="speech-bubble" transform="translate(-0.001 0)">
                <path id="Path_99" data-name="Path 99" d="M255.438,241H241.859a30.606,30.606,0,0,1-30.208,26.272H181v13.136A21.919,21.919,0,0,0,202.894,302.3H236.11l16.232,16.523a4.378,4.378,0,0,0,7.475-3.1V301.86a22.271,22.271,0,0,0,17.806-21.453V262.893A22.17,22.17,0,0,0,255.438,241Zm-43.786,43.786a4.379,4.379,0,1,1,4.379-4.379A4.378,4.378,0,0,1,211.651,284.786Zm17.514,0a4.379,4.379,0,1,1,4.379-4.379A4.378,4.378,0,0,1,229.166,284.786Zm17.515,0a4.379,4.379,0,1,1,4.379-4.379A4.378,4.378,0,0,1,246.68,284.786Z" transform="translate(-128.165 -170.65)"/>
                <path id="Path_100" data-name="Path 100" d="M226.843,136l-5.837,4.379,5.837,4.379Z" transform="translate(-156.492 -96.3)"/>
                <path id="Path_101" data-name="Path 101" d="M121,121h17.515v17.514H121Z" transform="translate(-85.679 -85.679)"/>
                <path id="Path_102" data-name="Path 102" d="M17.807,87.424V101a4.378,4.378,0,0,0,7.475,3.1L41.514,87.864H83.487A21.919,21.919,0,0,0,105.38,65.971V21.893A21.919,21.919,0,0,0,83.487,0h-61.3A22.17,22.17,0,0,0,0,21.893V65.971A22.19,22.19,0,0,0,17.807,87.424Zm8.757-56.482a4.376,4.376,0,0,1,4.379-4.379H57.215a4.376,4.376,0,0,1,4.379,4.379v4.381L72.1,27.44a4.377,4.377,0,0,1,7,3.5V57.214a4.377,4.377,0,0,1-7,3.5l-10.51-7.883v4.381a4.376,4.376,0,0,1-4.379,4.379H30.943a4.376,4.376,0,0,1-4.379-4.379Z"/>
              </g>
            </svg>
            
            
            <h4>Live</h4>
          </a>
          <a href="index.php?action=my_books" class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="44" height="61.217" viewBox="0 0 44 61.217">
              <g id="book_2_" data-name="book (2)" transform="translate(-72)">
                <path id="Path_103" data-name="Path 103" d="M114.087,0h-34.2A7.9,7.9,0,0,0,72,7.891V52.609a8.618,8.618,0,0,0,8.609,8.609h33.478a1.913,1.913,0,0,0,0-3.826H80.609a4.783,4.783,0,0,1,0-9.565h33.478A1.913,1.913,0,0,0,116,45.913v-44A1.913,1.913,0,0,0,114.087,0ZM75.826,44.41V7.891a4.065,4.065,0,0,1,4.065-4.065h.12a.6.6,0,0,1,.6.6v39a.6.6,0,0,1-.553.6,8.544,8.544,0,0,0-3.366.928A.6.6,0,0,1,75.826,44.41Z"/>
                <path id="Path_104" data-name="Path 104" d="M163.391,424H129.913a1.913,1.913,0,1,0,0,3.826h33.478a1.913,1.913,0,1,0,0-3.826Z" transform="translate(-49.304 -373.304)"/>
              </g>
            </svg>
            
            
            <h4>Books</h4>
          </a>
          <a href="index.php?action=classroom_discussion" class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="41.576" height="37.906" viewBox="0 0 41.576 37.906">
              <g id="solid" transform="translate(-32 -48)">
                <path id="Path_105" data-name="Path 105" d="M47.164,216H34.691A2.694,2.694,0,0,0,32,218.691v13.921a2.694,2.694,0,0,0,2.691,2.691h1.021a.742.742,0,0,1,.431.138l4.023,2.874v-2.27a.742.742,0,0,1,.742-.742H54.551a2.694,2.694,0,0,0,2.691-2.691V226.64A14.154,14.154,0,0,1,47.164,216Zm-8.482,8.35a1.485,1.485,0,1,1,1.485,1.485A1.485,1.485,0,0,1,38.682,224.35Zm9.373,5.034a6.685,6.685,0,0,1-8.352,0,.743.743,0,1,1,.928-1.16,5.171,5.171,0,0,0,6.5,0,.743.743,0,0,1,.928,1.16Zm-.464-3.549a1.485,1.485,0,1,1,1.485-1.485A1.485,1.485,0,0,1,47.591,225.834Z" transform="translate(0 -152.409)"/>
                <path id="Path_106" data-name="Path 106" d="M220.621,48a12.621,12.621,0,0,0,0,25.243,12.827,12.827,0,0,0,1.4-.078.753.753,0,0,1,.082,0,.742.742,0,0,1,.742.742v1.4l4.454-3.959a.754.754,0,0,1,.094-.071A12.622,12.622,0,0,0,220.621,48Zm0,22.459a1.114,1.114,0,1,1,1.114-1.114A1.114,1.114,0,0,1,220.621,70.459Zm2.539-7.2a3.2,3.2,0,0,0-1.8,2.9v.176a.742.742,0,1,1-1.485,0v-.176a4.694,4.694,0,0,1,2.646-4.241,4.455,4.455,0,1,0-6.358-4.029.742.742,0,1,1-1.485,0,5.94,5.94,0,1,1,8.478,5.371Z" transform="translate(-159.666)"/>
              </g>
            </svg>
            
            
            <h4>Discussion</h4>
          </a>
          <a href="index.php?action=documents" class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="24.013" height="26.196" viewBox="0 0 24.013 26.196">
              <g id="documents" transform="translate(-356 -686)">
                <path id="Path_34" data-name="Path 34" d="M4.274,7.368A5.191,5.191,0,0,1,9.459,2.183h9.969A3,3,0,0,0,16.554,0H4A3,3,0,0,0,1,3V19.92a3,3,0,0,0,3,3h.273Z" transform="translate(355 686)"/>
                <path id="Path_42" data-name="Path 42" d="M21.554,4H9A3,3,0,0,0,6,7V22.828a3,3,0,0,0,3,3H21.554a3,3,0,0,0,3-3V7A3,3,0,0,0,21.554,4ZM19.371,22.555H11.185a.819.819,0,1,1,0-1.637h8.186a.819.819,0,1,1,0,1.637Zm0-4.366H11.185a.819.819,0,1,1,0-1.637h8.186a.819.819,0,1,1,0,1.637Zm0-3.82H11.185a.819.819,0,0,1,0-1.637h8.186a.819.819,0,1,1,0,1.637Zm0-4.366H11.185a.819.819,0,1,1,0-1.637h8.186a.819.819,0,0,1,0,1.637Z" transform="translate(355.458 686.366)" />
              </g>
            </svg>
            
            
            <h4>Documents</h4>
          </a>
        </div>
      </div>
      
    </section>
	<div class="main-body">