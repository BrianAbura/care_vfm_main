       <!--NAVBAR-->
        <!--===================================================-->
        <header id="navbar">
            <div id="navbar-container" class="boxed">

                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
                    <img src="..\img\care-int-logo.png" alt="Care-International" class="brand-icon" width="80%">
                        <div class="brand-title">
                           
                        </div>
                    </a>
                </div>
                <!--================================-->
                <!--End brand logo & name-->

                <!--Navbar Dropdown-->
                <!--================================-->
                <div class="navbar-content">
                <ul class="nav navbar-top-links">

                <!--Navigation toogle button-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle" href="#">
                        <i class="demo-pli-list-view"></i>
                    </a>
                </li>
                <li class="tgl-menu-btn">
                    <a class="text-white" href="#">
                    <h4 class="page-header" style="color:#fff">Requisitions</h4>
                    </a>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Navigation toogle button-->
                </ul>
                    <ul class="nav navbar-top-links">
                        <!--User dropdown-->
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                    <!--You can use an image instead of an icon.-->
                                    <!--<img class="img-circle img-user media-object" src="img/profile-photos/1.png" alt="Profile Picture">-->
                                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                    <i class="demo-pli-male"></i>
                                </span>
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <!--You can also display a user name in the navbar.-->
                                <!--<div class="username hidden-xs">Aaron Chavez</div>-->
                                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                            </a>

                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                                <ul class="head-list">
                                    <li>
                                        <a href="../profile"><i class="demo-pli-male icon-lg icon-fw"></i>My Profile</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="demo-pli-mail icon-lg icon-fw"></i> Messages</a>
                                    </li>
                                    <li>
                                        <a href="../logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                        <!--End user dropdown-->
 
                    </ul>
                </div>
                <!--================================-->
                <!--End Navbar Dropdown-->

            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <?php 
        $current_user = get_user($_SESSION['user_id']);
        
        $user_department = get_deparment($current_user['department_id']);
        $user_role = get_roles($current_user['role_id']);
        ?>

            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">
                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">

                                <!--Profile Widget-->
                                <!--================================-->
                                <div id="mainnav-profile" class="mainnav-profile">
                                    <div class="profile-wrap text-center">
                                        <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                            <p class="mnp-name"><?php echo $current_user['first_name'].' '.$current_user['last_name'];?></p>
                                            <span class="mnp-desc"><?php echo $current_user['email_address'];?></span><br/>
                                            <span class="mnp-desc"><strong>Role: </strong><?php echo $user_role;?></span><br/>
                                            <span class="mnp-desc"><strong>Dept: </strong><?php echo $user_department;?></span>
                                        </a>
                                    </div>
                                </div>

                                <ul id="mainnav-menu" class="list-group">

			<!--Category name-->
			<li class="list-header">Navigation</li>

			<!--Menu list item-->
			<li >
				<a href="../home">
					<i class="demo-pli-home" style="color:coral; font-size:16px; font-weight:bold"></i>
						<span class="menu-title">Dashboard</span>
				</a>
			</li>
			<br/>
			<li class="active-sub">
				<a href="../requisitions">
				<i class="ion-clipboard" style="color:coral; font-size:20px; font-weight:bold"></i> 
					<span class="menu-title">Requisitions</span>
				</a>
			</li>
			<br/>
			<li>
				<a href="../tenders">
					<i class="fa fa-newspaper-o" style="color:green; font-size:18px; font-weight:bold"></i>
					<span class="menu-title">Tenders</span>
				</a>
			</li>
			<br/>
			<li>
				<a href="../evaluations">
					<i class="ion-ios-toggle" style="color:blue; font-size:20px; font-weight:bold"></i>
					<span class="menu-title">Evaluations</span>
				</a>
			</li>
			<br/>
			<li>
				<a href="../reports">
					<i class="demo-psi-bar-chart" style="color:deeppink; font-size:20px; font-weight:bold"></i>
					<span class="menu-title">Reports</span>
				</a>
			</li>
			<br/>


			<!-- Menu Divider -->
			<li class="list-divider"></li>
			<!-- This menu section is for Authorisized Users only
				Role Based -->
<?php 
if($current_user['role_id'] == 1 || $current_user['role_id'] == 11){
//Restricted to System Administrator (SA) Role
?>
			<li>
				<a href="../departments">
					<i class="demo-pli-building" style="color:brown; font-size:17px; font-weight:bold"></i>
					<span class="menu-title">Departments</span>
				</a>
			</li>
			<br/>

			<li>
				<a href="../users">
					<i class="demo-psi-male-female" style="color:darkmagenta; font-size:17px; font-weight:bold"></i>
					<span class="menu-title">User Management</span>
				</a>
			</li>
			<br/>

			<li>
				<a href="../vendors">
					<i class="ion-ios-people" style="color:mediumblue; font-size:22px; font-weight:bold"></i>
					<span class="menu-title">Vendor Managment</span>
				</a>
			</li>
			<br/>

			<li>
				<a href="#">
				<i class="ion-settings" style="color:green; font-size:20px; font-weight:bold"></i> 
					<span class="menu-title">System Management</span>
					<i class="arrow"></i>
				</a>

				<ul class="collapse">
					<li><a href="../system_management/thresholds">Threshold Management</a></li>
				</ul>
			</li>

<?php  } //End System Administrator Roless
?>
		</ul>


		<!--Widget-->
		<!--================================-->
		<div class="mainnav-widget">

			<!-- Show the button on collapsed navigation -->
			<div class="show-small">
				<a href="#" data-toggle="menu-widget" data-target="#demo-wg-server">
					<i class="demo-pli-monitor-2"></i>
				</a>
			</div>

			<!-- Hide the content on collapsed navigation -->
			<div id="demo-wg-server" class="hide-small mainnav-widget-content">
				<ul class="list-group">
					<li class="pad-ver"><a href="#" class="btn btn-danger btn-bock"><i class="ion-lock-combination"></i> Logout</a></li>
				</ul>
			</div>
		</div>
		<!--================================-->
		<!--End widget-->

	</div>
</div>
</div>
<!--================================-->
<!--End menu-->

</div>
</nav>
<!--===================================================-->
<!--END MAIN NAVIGATION-->