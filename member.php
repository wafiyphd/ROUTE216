<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	if (isset($_SESSION['user'])!="" ) { 
	$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow= mysqli_fetch_array($res);
	} else {
		header("Location: index.php");	
	}
	
	$userid = $userRow['user_id'];
	$query = mysqli_query($mysqli, "SELECT * from member WHERE user_id='$userid'");
	$memberRow = mysqli_fetch_array($query);
	
	if ( isset($_GET['success']) && $_GET['success'] == 0) {
		$alertType = "success";
		$errMSG = "Successfully submitted review.";
	}
	elseif (isset($_GET['success']) && $_GET['success'] == 1) {
		$alertType = "danger";
		$errMSG = "Failed to submit review.";
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Home - ROUTE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<script src="https://use.fontawesome.com/3c53deecc4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Palanquin" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">

	<link rel="stylesheet" href="css/home.css">
	<link rel="stylesheet" href="css/alert.css">
	<link rel="stylesheet" href="css/navfooter.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>

</head>

<body>

	<div class="container-fluid nav-fluid">
		<div class="navbar navbar-default"><!-- Navigation bar -->
			<div class="container">
				<div class="navbar-header">
				  <button class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span> 
				  </button>
				  <a class="navbar-brand" href="index.php"><img class="img-responsive" src="images/routeb.png"></a>
				</div>
				
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav navbar-left"> 
						<li><a href="index.php"><button class="btn navbar-btn" ><strong>Home</strong></button></a></li>
						<li><a href="about.php"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
						<li><a href="contact.php"><button class="btn navbar-btn"><strong>Contact</strong></button></a></li>		
					</ul>

					<?php if ( isset($_SESSION['user'])!="" ) { ?>
					<ul class="nav navbar-nav navbar-right desktop">
						<li class="dropdown ">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle">
								<button class="btn navbar-btn"><span><i class="fa fa-user" aria-hidden="true"></i></span>&nbsp;&nbsp;<strong><?php echo ucwords($userRow['fullname']); ?></strong>&nbsp;&nbsp;<b class="caret"></b></button>
							</a>
								<ul class="dropdown-menu">
									<li><a href="profile.php">Profile</a></li>
									<li class="divider"></li>
									<li><a href="logout.php?logout"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>&nbsp;Log Out</a></li>
								</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right mobile">
						<li><a href="#"><button class="btn navbar-btn">Profile</button></a></li>
						<li><a href="logout.php?logout"><button class="btn navbar-btn"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>&nbsp;Log Out</button></a></li>
					</ul>
					<?php } else { ?>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="signup.php"><button class="btn navbar-btn" ><strong>Sign Up</strong></button></a></li>
						<li><a><button class="btn navbar-btn" data-toggle="modal" data-target="#loginModal"><strong>Log In</strong></button></a></li>
					</ul>
					<?php }?>
				</div>
			</div>
			
		</div><!-- End of nav bar -->

	</div>
		
	<div class="container-fluid main-fluid">
	
		<div class="container page-info">
			<div class="row">
				<a href="member.php"><div class="col-lg-3 info-box ">
					<strong>HOME PAGE</strong>
				</div></a>
				<?php if (isset($alertType)) { ?>
					<div class="col-lg-6">
						<div class="alert alert-box-s type-<?php echo $alertType; ?> alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							&nbsp;<?php echo $errMSG; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class= "container content-container">
			
			<div class="row">
				<div class="col-lg-12">
					<div class="panel home-panel">
						<div class="panel-body">
							<img src="images/man.png" class="img-responsive img-circle"></img>
							<div class="col-lg-4">
								
								<p class="name"><?php echo ucwords($userRow['fullname']); ?></p>
								<ul>
									<li><strong>Joined As: </strong><?php echo ucwords($userRow['user_kind']); ?></li>
									<li><strong>Training Level:  </strong><?php echo ucwords($memberRow['level']); ?></li>
									<li><strong>Email Address: </strong><?php echo $userRow['email']; ?></li>
								</ul>
							</div>
							<div class="col-lg-4">
								<?php $reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE reviewer_id = '$userid'");
								$count = mysqli_fetch_array($reviewcount);
								$count = $count['count']; ?>
								<p>&nbsp;</p>
								<ul>
									<li><strong>&nbsp; </strong></li>
									<li><strong>No. of sessions joined:  </strong><?php echo $memberRow['joined']; ?></li>
									<li><strong>No. of reviews submitted:  </strong><?php echo $count; ?></li>
								<ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
			
				<a href="joinsessionslist.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/11.png"></img><div class="overlay"><div class="moreinfo">Join one or more of the many available sessions the trainers have set up.</div></div>
				</div></a>
				
				<a href="managemember.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/21.png"></img><div class="overlay"><div class="moreinfo">View and manage a list of all the upcoming sessions you've joined.</div></div>
				</div></a>
							
				<a href="viewhistory.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/31.png"></img><div class="overlay"></img><div class="moreinfo">View and manage all the sessions you've completed. You may also review the 
					sessions and its trainers.</div></div>
				</div></a>
				<a href="profile.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/41.png"></img><div class="overlay"></img><div class="moreinfo">Edit your profile.</div></div>
				</div></a>
			
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<p class="title">UPCOMING TRAINING SESSIONS</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<tr>
							  <th>Sesssion Name</th>
							  <th>Category</th>
							  <th>Date</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<?php $sessions = "SELECT category, title, date, status from session WHERE NOT status = 'Completed' ORDER BY date LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; ?></td>
								<td><?php echo ucfirst($row[0]); ?></td>
								<td><?php echo $row[2]; ?></td>
							</tr>
							<?php }} ?>
						  </tbody>
						</table>
					</div>
				</div>
				
				<div class="col-lg-6">
					<p class="title">POPULAR GROUP SESSIONS</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<tr>
							  <th>Session Name</th>
							  <th>Date</th>
							  <th>Joined</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<?php $sessions = "SELECT category, title, date, status, count from session, group_session g WHERE NOT status = 'Completed' AND category='group' ORDER BY count DESC LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; ?></td>
								<td><?php echo $row[2]; ?></td>
								<td><?php echo $row[4]; ?></td>
							</tr>
							
							<?php }} ?>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
			

		</div>
	</div>
	
	<div class="container-fluid footer-container">
		
		<div class="container footer-col">
			<div class="row">
				<div class="col-lg-6">
					<img src="images/routeW.png"></img><br>
					<ul class="social-icons">
							<li><a href="#"><i class="fa fa-3x fa-facebook-square"></i></a></li>
							<li><a href="#"><i class="fa fa-3x fa-twitter-square"></i></a></li>
							<li><a href="#"><i class="fa fa-3x fa-instagram"></i></a></li>
					</ul>
				</div>
			
				<div class="col-lg-6">
					<span style="float:right;"><a href="#top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a></span>
					
				</div>
			</div>
		</div>
		
		<div class="container sub-footer"><!-- Sub Footer -->				
			
			<div class="col-sm-12 col-lg-6">
			&copy Copyright 2017 <strong>ROUTE.</strong>
			</div>
			
			<div class="col-sm-12 col-lg-6">
				<span style="float:right">
					<a href="index.php">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="about.php">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="contact.php">Contact</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>