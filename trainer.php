<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	if (isset($_SESSION['user'])!="" ) { 
	$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow= mysqli_fetch_array($res);
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
	
		<nav class="nav navbar-default"><!-- Navigation bar -->
			<div class="container">
				<ul class="nav navbar-nav navbar-left"> 
					<li><a href="index.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
					<li><a href="index.php"><button class="btn navbar-btn"><strong>Home</strong></button></a></li>
					<li><a href="about.php"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
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
			</div>
		</nav>
		
		<div class="container container-header">
			<h2><strong>Welcome, <?php echo $userRow['fullname']?>! You are a Trainer!</strong></h2>
			<h3>This is your home page. You may now start using ROUTE.</h3>
			<hr>
		</div>
	</div>
	
	<div class= "container content-container">
		<div class="row">
			<div class="col-xs-12 col-lg-9">
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="record.php"><img src="images/option1.jpg"></img><div class="caption">CREATE NEW SESSION</div></a>		
					</div>
				</div>
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="#"><img src="images/option2.jpg"></img><div class="caption">MANAGE MY SESSIONS</div></a>	
					</div>
				</div>
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="#"><img src="images/option3.jpg"></img><div class="caption">ALL MY REVIEWS</div></a>	
					</div>
				</div>
				<div class="pic-container col-lg-12">
					<div class="hover">
						<a href="#"><img src="images/option1.jpg"></img><div class="caption">EDIT PROFILE</div></a>	
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-lg-3">
				<p><strong>May put something here</strong></p>
				<img src="images/thinking.png"></img><img src="images/thinking.png"></img><img src="images/thinking.png"></img><img src="images/thinking.png"></img><br>
				<img src="images/thinking.png"></img><img src="images/thinking.png"></img><img src="images/thinking.png"></img><img src="images/thinking.png"></img>
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
					<a href="#">About</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
</body>

</html>