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
	if ( isset($_GET['success']) && $_GET['success'] == 0) {
		$message = "Successfully submitted review.";
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

	<link rel="stylesheet" href="css/home.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>

</head>

<body>

	<div class="container-jumbo">
		
		<div class="container">
			<nav class="nav navbar-default"><!-- Navigation bar -->
				
					<div class="navbar-header">
					  <button class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span> 
					  </button>
					  <a class="navbar-brand" href="index.php"><img class="img-responsive" src="images/routeW.png"></a>
					</div>
					
					<div class="collapse navbar-collapse" id="myNavbar">
						<ul class="nav navbar-nav navbar-left"> 
							<li><a href="index.php"><button class="btn navbar-btn"><strong>Home</strong></button></a></li>
							<li><a href="about.php"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
							<li><a href="contact.php"><button class="btn navbar-btn"><strong>Contact</strong></button></a></li>		
						</ul>
					
						<ul class="nav navbar-nav navbar-right desktop">
							<li class="dropdown ">
								<a href="#" data-toggle="dropdown" class="dropdown-toggle">
									<button class="btn navbar-btn"><span><i class="fa fa-user" aria-hidden="true"></i></span>&nbsp;&nbsp;<strong><?php echo $userRow['fullname']?></strong>&nbsp;&nbsp;<b class="caret"></b></button>
								</a>
									<ul class="dropdown-menu">
										<li><a href="#">Profile</a></li>
										<li class="divider"></li>
										<li><a href="logout.php?logout"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>&nbsp;Log Out</a></li>
									</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right mobile">
							<li><a href="#"><button class="btn navbar-btn">Profile</button></a></li>
							<li><a href="logout.php?logout"><button class="btn navbar-btn"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>&nbsp;Log Out</button></a></li>
						</ul>
					</div>
			</nav>
		</div>
		
		<div class="container header-container">
			<div class="container main-header">
				<p class="header">Welcome, <?php echo ucwords($userRow['fullname']); ?>. This is your home page!</p>
				<p class="title">You may now start using ROUTE. Select what you want to do below.</p>
			</div>
		</div>
		
	</div>
	
	<div class= "container content-container">
		<?php if (isset($_GET['success'])) { ?>
					<div class="container fail-login">
						<div class="alert alert-success text-center">
							<p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<?php echo $message; ?></p>
						</div>
					</div> <?php } ?>
		<div class="row">
			<div class="col-xs-12 col-lg-9">
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="joinsessionslist.php"><img src="images/option1.jpg"></img><div class="caption">JOIN AVAILABLE SESSIONS</div></a>		
					</div>
				</div>
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="viewhistory.php"><img src="images/option2.jpg"></img><div class="caption">VIEW SESSIONS HISTORY</div></a>	
					</div>
				</div>
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="#"><img src="images/option3.jpg"></img><div class="caption">EDIT PROFILE</div></a>	
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-lg-3">
				
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