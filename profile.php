<?php

	ob_start();
	session_start();
	include_once 'dbconnect.php';
	date_default_timezone_set('Asia/Singapore');
	
	$error = false;
	
	if ( isset($_SESSION['user'])!="" ) { 
		$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
		$userRow= mysqli_fetch_array($res);
	} else {
		header("Location: index.php");	
	}
	
	if( isset($_POST['update']) ) {

			$fullname = $_POST['fullname'];
			$email = $_POST['email'];
			$level = $_POST['level'];
			$specialty = $_POST['specialty'];
			
			$query = "UPDATE user SET fullname='$fullname', email='$email' WHERE user_id =".$_SESSION['user'];
			$res = mysqli_query($mysqli, $query);
				
			$memquery = "UPDATE member SET fullname='$fullname', level='$level' WHERE user_id =".$_SESSION['user'];
			$res = mysqli_query($mysqli, $memquery);			
				
			$traquery = "UPDATE trainer SET fullname='$fullname', specialty='$specialty' WHERE user_id =".$_SESSION['user'];
			$res = mysqli_query($mysqli, $traquery);				

			
		    if ($res) {
			 $errType = "success";
			 $errMsg = "Successfully updated profile.";
		    } else {
			 $errType = "danger";
			 $errMsg = "Something went wrong, try again later..."; 
		    } 
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>View Profile - ROUTE</title>
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
	
	<link rel="stylesheet" href="css/profile.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>
	
</head>

<body>

	<div class="container-jumbo">
	
		<nav class="nav navbar-default"><!-- Navigation bar -->
			<div class="container">
				<ul class="nav navbar-nav navbar-left"> 
					<li><a href="trainer.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
					<li><a href="trainer.php"><button class="btn navbar-btn"><strong>Home</strong></button></a></li>
					<li><a href="#"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
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
			<h2><strong>Welcome, <?php echo $userRow['fullname']?>!</strong></h2>
			<h3>You may view your profile here.</h3>
			<hr>
		</div>
	</div>
	
	<?php $user = mysqli_query($mysqli, "SELECT * from user WHERE user_id =".$_SESSION['user']);
	$row = mysqli_fetch_row($user);
	
	$member = mysqli_query($mysqli, "SELECT * from member WHERE user_id =".$_SESSION['user']);
	$mrow = mysqli_fetch_row($member);
	
	$trainer = mysqli_query($mysqli, "SELECT * from trainer WHERE user_id =".$_SESSION['user']);
	$trow = mysqli_fetch_row($trainer); ?>

	<div class="container-fluid main-container">
		<div class="container profile-container">
			<div class="row">
			
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
					<div class="profile-wrap text-center">
						<div class="profile-form">
							<div class="row text-center">
								
								<form class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
									<div class = "group">
										<img src="images/man.png" class="photo"><hr>
										<label for = "type" class = "photolabel"><?php echo $row[1]?></label>
									</div>

									<div class = "group">
										<div class="row">
											<div class="col-lg-6">
												<div class="group">
													<label for = "username" class = "label">USERNAME</label>
													<p class="info"><?php echo $row[2]?></p>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="group">
													<label for = "fullname" class = "label">FULL NAME</label>
													<input id="fullname" type="text" name="fullname" class="input" value="<?php echo $row[4]?>" required>
												</div>
											</div>
										</div>	
										<div class="row">
											<div class="col-lg-6">	
												<div class="group">
													<label for = "date" class = "label">DATE CREATED</label>
													<p class="info"><?php  $date = date('j F Y',strtotime($row[6]));
													echo $date; ?></p>
												</div>
											</div>
											<div class="col-lg-6">											
												<div class="group">
													<label for = "email" class = "label">E-MAIL</label>
													<input id="email" type="email" name="email" class="input" value="<?php echo $row[3]?>" required></input>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<div class="group">
													<label for = "time" class = "label">TIME CREATED</label>
													<p class="info"><?php $time = date('g:i A',strtotime($row[6]));
													echo $time; ?></p>
												</div>
											</div>
											<div class="col-lg-6">											
												<div class="details" id="member" style="display: none;">

												<div class = "group">
													<label for ="level" class = "label">LEVEL</label>
														<select id="level" name="level" class="input" required>
															<option value="beginner" <?php if ($mrow[3] == "beginner") echo "selected"; ?>>Beginner</option>
															<option value="intermediate" <?php if ($mrow[3] == "intermediate") echo "selected"; ?>>Intermediate</option>
															<option value="expert" <?php if ($mrow[3] == "expert") echo "selected"; ?>>Expert</option>
														</select>
												</div>
												</div>					
												<div class="form" id="trainer" style="display: none;">
												<div class="group">
													<label for="session" class="label">SPECIALTY</label>
													<input id="specialty" type="text" name="specialty" class = "input" value="<?php echo $trow[3]; ?>"></div>
												</div>
														<?php 
															if ($row[1] == 'member') {
																$showdiv = 'member';
															}
															else if ($row[1] == 'trainer') {
																$showdiv = 'trainer';
															}
															echo "<script type=\"text/javascript\">document.getElementById('".$showdiv."').style.display = 'block';</script>";
														?>
												<div class = "group">
													<button type="update" name="update" class="button" value="Update Profile">UPDATE</button>
													</div>
												</div>
											</div>		
												</div>											
											</div>
										<?php
											if ( isset($errMsg) ) {
											?>
											<div class="form-group">
													 <div class="alert alert-<?php echo ($errType=="success") ? "success" : $errType; ?>">
														<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMsg; ?>
													</div>
											</div>
														<?php
										   }
										   ?>											
										</div>

									</form>	
									</div>									
								</div>
							</div>	
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
					<a href="trainer.php">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">About</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
</body>

</html>