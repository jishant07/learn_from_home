<nav class="navbar">
				<a href="#" class="sidebar-toggler">
					<i data-feather="menu"></i>
				</a>
				<div class="navbar-content">					
					<ul class="navbar-nav">						
						<?php if($_SESSION['u_type']=='teacher'){ ?>
						<li class="nav-item">
							<a class="nav-link " onclick="openNav()" href="#" role="button" >
								<i data-feather="message-square" class="icon-lg"></i>
							</a>							
						</li>
						<?php } ?>
						<!--li class="nav-item dropdown nav-notifications">
							<a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i data-feather="bell"></i>
								<div class="indicator">
									<div class="circle"></div>
								</div>
							</a>
							<div class="dropdown-menu" aria-labelledby="notificationDropdown">
								
								<div class="dropdown-body">
									<a href="javascript:;" class="dropdown-item">
										
										<div class="content">
											<p>New customer registered </p>
											<p class="sub-text text-muted">2 sec ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										
										<div class="content">
											<p>New Order Recieved</p>
											<p class="sub-text text-muted">30 min ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										
										<div class="content">
											<p>Server Limit Reached!</p>
											<p class="sub-text text-muted">1 hrs ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										
										<div class="content">
											<p>Apps are ready for update</p>
											<p class="sub-text text-muted">5 hrs ago</p>
										</div>
									</a>
									<a href="javascript:;" class="dropdown-item">
										
										<div class="content">
											<p>Download completed</p>
											<p class="sub-text text-muted">6 hrs ago</p>
										</div>
									</a>
								</div>
								<div class="dropdown-footer d-flex align-items-center justify-content-center">
									<a href="javascript:;">View all</a>
								</div>
							</div>
						</li-->
						<li class="nav-item dropdown nav-profile">
							<a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php 
							if($_SESSION['pic']!=''){
								$pic = "../uploads/teacher/".$_SESSION['pic'];
								if(!file_exists($pic)) $pic="../uploads/avtar.png";
							}
							else $pic="../uploads/avtar.png";
							?>
								<img src="<?=$pic?>" alt="profile">
							</a>
							<div class="dropdown-menu" aria-labelledby="profileDropdown">
								<div class="dropdown-header d-flex flex-column align-items-center">
									<div class="figure mb-3">
										<img src="<?=$pic?>" alt="">
									</div>
									<div class="info text-center">
										<p class="name font-weight-bold mb-0"><?=$_SESSION['username']?></p>
										<p class="email text-muted mb-3"><?=$_SESSION['email']?></p>
									</div>
								</div>
								<div class="dropdown-body">
									<ul class="profile-nav p-0 pt-3">
										<li class="nav-item">
											<a href="index.php?action=edit-profile" class="nav-link">
												<i data-feather="user"></i>
												<span>Profile</span>
											</a>
										</li>
										<li class="nav-item">
											<a href="index.php?action=change-password" class="nav-link">
												<i data-feather="lock"></i>
												<span>Change Password</span>
											</a>
										</li>
																	
										<li class="nav-item">
											<a href="index.php?action=logout" class="nav-link">
												<i data-feather="log-out"></i>
												<span>Log Out</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</nav>