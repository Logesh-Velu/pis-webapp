<header class="topbar">
	<nav class="navbar top-navbar navbar-expand-md navbar-dark">
		<div class="navbar-header">
			<a class="navbar-brand" href="home.php">
				<b><img src="img/admin-icon.png" alt="" class="light-logo" /></b>
				<span><img src="img/admin-text.png" class="light-logo" alt="" /></span>
			</a>
		</div>
		<div class="navbar-collapse">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
				<li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
			</ul>
			<ul class="navbar-nav my-lg-0">
				<li class="nav-item dropdown u-pro">
					<a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="img/users/admin-01.png" alt="user" class=""> <span class="hidden-md-down"> &nbsp; <?php echo $_SESSION['_username']; ?> &nbsp;<i class="fa fa-angle-down"></i></span> </a>
					<div class="dropdown-menu dropdown-menu-right animated fadeIn">
						<!--a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
						<div class="dropdown-divider"></div>
						<a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
						<div class="dropdown-divider"></div-->
						<a href="logout.php" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
					</div>
				</li>
				<li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
			</ul>
		</div>
	</nav>
</header>