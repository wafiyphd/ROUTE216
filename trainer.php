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
	$query = mysqli_query($mysqli, "SELECT * from trainer WHERE user_id='$userid'");
	$trainerRow = mysqli_fetch_array($query);
	
	if ( isset($_GET['success']) && $_GET['success'] == 0) {
		$alertType = "success";
		$errMSG = "Successfully created new training session.";
	} elseif ( isset($_GET['success']) && $_GET['success'] == 1) {
		$alertType = "success";
		$errMSG = "Successfully updated training session.";
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
	<link href="https://fonts.googleapis.com/css?family=Droid+Sans+Mono" rel="stylesheet">

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
				<a href="trainer.php"><div class="col-lg-3 info-box ">
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
							<div class="col-lg-3">
								
								<p class="name"><?php echo ucwords($userRow['fullname']); ?></p>
								<ul>
									<li><strong>Joined As: </strong><?php echo ucwords($userRow['user_kind']); ?></li>
									<li><strong>Specialty:  </strong><?php echo ucwords($trainerRow['specialty']); ?></li>
									<li><strong>Email Address: </strong><?php echo $userRow['email']; ?></li>
								</ul>
							</div>
							<div class="col-lg-3">
								<?php $reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE trainer_id = '$userid'");
								$rcount = mysqli_fetch_array($reviewcount);
								$rcount = $rcount['count']; 
								
								$sessioncount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM session WHERE trainer_id = '$userid'");
								$scount = mysqli_fetch_array($sessioncount);
								$scount = $scount['count'];
								
								?>
								<p>&nbsp;</p>
								<ul>
									<li><strong>&nbsp; </strong></li>
									<li><strong>No. of sessions managed:  </strong><?php echo $scount; ?></li>
									<li><strong>No. of reviews received:  </strong><?php echo $rcount; ?></li>
								<ul>
							</div>
							<div class="col-lg-3">
								<?php $reviewquery = mysqli_query($mysqli, "SELECT trainer_id, profrat, engrat, sesrat, totalrating from review WHERE trainer_id='$userid'");
								if ($rcount == 0) {
									$selfaverage = "N/A";
									$paverage = "N/A";
									$eaverage = "N/A";
									$saverage = "N/A";
								}

								else {
									
								$paverage = mysqli_query($mysqli, "SELECT AVG(profrat) AS average FROM review WHERE trainer_id='$userid'");
								$paverage = mysqli_fetch_array($paverage);
								$paverage = $paverage['average'];
								$paverage = number_format((float)$paverage, 2, '.', '');
								
								$eaverage = mysqli_query($mysqli, "SELECT AVG(engrat) AS average FROM review WHERE trainer_id='$userid'");
								$eaverage = mysqli_fetch_array($eaverage);
								$eaverage = $eaverage['average'];
								$eaverage = number_format((float)$eaverage, 2, '.', '');
								
								$saverage = mysqli_query($mysqli, "SELECT AVG(sesrat) AS average FROM review WHERE trainer_id='$userid'");
								$saverage = mysqli_fetch_array($saverage);
								$saverage = $saverage['average'];
								$saverage = number_format((float)$saverage, 2, '.', '');
								
								$selfaverage = mysqli_query($mysqli, "SELECT AVG(totalrating) AS average FROM review WHERE trainer_id='$userid'");
								$selfaverage = mysqli_fetch_array($selfaverage);
								$selfaverage = $selfaverage['average'];
								$selfaverage = number_format((float)$selfaverage, 2, '.', ''); }?>
								<p>&nbsp;</p>
								<ul>
									<li><strong>Overall average rating: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																					</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($selfaverage >= 3.5) { echo ' btn-green'; }
																						elseif ($selfaverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($selfaverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $selfaverage; echo '</button>' ?></small></li>
								<li><strong>Average Professionalism: &nbsp;&nbsp;</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($paverage >= 3.5) { echo ' btn-green'; }
																						elseif ($paverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($paverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $paverage; echo '</button>' ?></small></li>
								<li><strong>Average Engagement: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																				</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($saverage >= 3.5) { echo ' btn-green'; }
																						elseif ($saverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($saverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $saverage; echo '</button>' ?></small></li>
								<li><strong>Average Session: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																				</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($eaverage >= 3.5) { echo ' btn-green'; }
																						elseif ($eaverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($eaverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $eaverage; echo '</button>' ?></small></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<a href="record.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/12.png"></img><div class="overlay"><div class="moreinfo">Create training sessions for the members of ROUTE to join.</div></div>
				</div></a>
				
				<a href="viewhistory.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/22.png"></img><div class="overlay"><div class="moreinfo">View and manage a list of all the training sessions you've created.</div></div>
				</div></a>

				<a href="allreviews.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/32.png"></img><div class="overlay"></img><div class="moreinfo">View and read all the reviews you've received from the members.</div></div>
				</div></a>
				
				<a href="profile.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/41.png"></img><div class="overlay"></img><div class="moreinfo">Edit your profile.</div></div>
				</div></a>
				
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