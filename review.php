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
	
	if( isset($_POST['review']) ) {
	 
		if (!isset($_POST['prating']) || !isset($_POST['erating']) || !isset($_POST['srating'])) {
			 $errType = "danger";
			 $errMSG = "Please pick a rating for all the criterias.";
		}
		
		else {
			
			$sessionid = $_POST['sessionid'];
			
			$query = mysqli_query($mysqli, "SELECT trainer_id, trainer_name from session where session_id = '$sessionid'");
			$sessionRow = mysqli_fetch_row($query);
			
			$reviewerid = $userRow['user_id'];
			$reviewername = $userRow['fullname'];
			$trainerid = $sessionRow[0];
			$trainername = $sessionRow[1];
			
			$prating = $_POST['prating'];
			$erating = $_POST['erating'];
			$srating = $_POST['srating'];
			
			$avg = ($prating + $erating + $srating) / 3;

			$comments = htmlspecialchars($_POST['comments']);
			
			$query = "INSERT INTO review(reviewer_id, reviewer_name, trainer_id, trainer_name, session_id, timestamp, profrat, engrat, sesrat, totalrating, comments) 
						VALUES('$reviewerid','$reviewername','$trainerid','$trainername','$sessionid', NOW(),'$prating','$erating','$srating','$avg','$comments')";
			$res = mysqli_query($mysqli, $query);
			
		    if ($res) {
			 $errType = "success";
			 $errMSG = "Successfully submitted review";
			 header("Location: member.php?success=0");
		    } else {
			 $errType = "danger";
			 $errMSG = "Something went wrong, try again later..."; 
		    } 
		}
	 
	}
	
	if ( isset($_GET['id']) ) {
		$sessionid = $_GET['id'];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Review - ROUTE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<script src="https://use.fontawesome.com/3c53deecc4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">

	<link rel="stylesheet" href="css/review.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
					<ul class="nav navbar-nav navbar-right mobile">
						<li><a href="#"><button class="btn navbar-btn">Profile</button></a></li>
						<li><a href="logout.php?logout"><button class="btn navbar-btn"><span><i class="fa fa-sign-out" aria-hidden="true"></i></span>&nbsp;Log Out</button></a></li>
					</ul>
				</div>
			</nav>
		</div>
		
		<div class="container header-container">
			<div class="container main-header">
				<p class="header">Reviewing Session & Trainer. &nbsp;<span class="title">Provide constructive feedback for the trainers.</span></p>
			</div>
		</div>
		
	</div>
	<?php $session = mysqli_query($mysqli, "SELECT * from session WHERE session_id = '$sessionid'");
	$row = mysqli_fetch_row($session); ?>
		
	<div class="container-fluid content-container">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="review-wrap text-center">
						<div class="review-form">
						
							<div class="row text-center">
								
								<form id="#rating" class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
									
									<div class="rating">
										<span class="inline pull-left">Professionalism Rating:</span>
										<div class="rating-wrap">
											<input class="star star-5" id="pstar-5" type="radio" name="prating" value="5"></input>
											<label class="star star-5" for="pstar-5"></label>
											<input class="star star-4" id="pstar-4" type="radio" name="prating" value="4"></input>
											<label class="star star-4" for="pstar-4"></label>
											<input class="star star-3" id="pstar-3" type="radio" name="prating" value="3"></input>
											<label class="star star-3" for="pstar-3"></label>
											<input class="star star-2" id="pstar-2" type="radio" name="prating" value="2"></input>
											<label class="star star-2" for="pstar-2"></label>
											<input class="star star-1" id="pstar-1" type="radio" name="prating" value="1"></input>
											<label class="star star-1" for="pstar-1"></label>
										</div>
									</div>

									<div class="rating">
										<span class="inline pull-left">Engagement Rating:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<div class="rating-wrap">
											<input class="star star-5" id="estar-5" type="radio" name="erating" value="5"></input>
											<label class="star star-5" for="estar-5"></label>
											<input class="star star-4" id="estar-4" type="radio" name="erating" value="4"></input>
											<label class="star star-4" for="estar-4"></label>
											<input class="star star-3" id="estar-3" type="radio" name="erating" value="3"></input>
											<label class="star star-3" for="estar-3"></label>
											<input class="star star-2" id="estar-2" type="radio" name="erating" value="2"></input>
											<label class="star star-2" for="estar-2"></label>
											<input class="star star-1" id="estar-1" type="radio" name="erating" value="1"></input>
											<label class="star star-1" for="estar-1"></label>
										</div>
									</div>

									<div class="rating">
										<span class="inline pull-left">Session Rating:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
										<div class="rating-wrap">
											<input class="star star-5" id="sstar-5" type="radio" name="srating" value="5"></input>
											<label class="star star-5" for="sstar-5"></label>
											<input class="star star-4" id="sstar-4" type="radio" name="srating" value="4"></input>
											<label class="star star-4" for="sstar-4"></label>
											<input class="star star-3" id="sstar-3" type="radio" name="srating" value="3"></input>
											<label class="star star-3" for="sstar-3"></label>
											<input class="star star-2" id="sstar-2" type="radio" name="srating" value="2"></input>
											<label class="star star-2" for="sstar-2"></label>
											<input class="star star-1" id="sstar-1" type="radio" name="srating" value="1"></input>
											<label class="star star-1" for="sstar-1"></label>
										</div>
										
									</div>
									
									<div id="#group" class="group">
										
									</div>
									
									<div class="group">
										<label for="comments" class="label">Comments</label>
										<textarea id="comments"  type="text" name="comments" rows="8" class="input" required></textarea>
									</div>
									
									<div class="group">
										<input name="sessionid" class="hidden" value="<?php echo $row[0]; ?>"></input>
									</div>
									
									<div class="group">
										<input type="submit" name="review" class="button" value="Submit"></input>
									</div>
									
									<?php
									if ( isset($errMSG) ) {
									?>
									<div class="form-group">
											 <div class="alert alert-<?php echo $errType; ?>">
												<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
											</div>
									</div>
												<?php
								   }
								   ?>
									
								</form>		
							</div>
						</div>	
						
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="row">
						<div class="col-lg-12 col-sm-12 session-info">
							<div class="info-wrap">
								<div class="info-content">
									<p class="big">Session Info </p>
									<hr>
									<div class="row">
										<div class="col-lg-6">
											<ul class="session">
												<li><strong>Session Name: </strong><?php echo ucfirst($row[2]); ?></li>
												<li><strong>Date: </strong><?php echo ucfirst($row[3]); ?></li>
												<li><strong>Fee: </strong>RM <?php echo ucfirst($row[5]); ?></li>
												<li><strong>Status: </strong><?php echo ucfirst($row[6]); ?></li>
												
											</ul>
										</div>
										<div class="col-lg-6">
											<ul class="session">
												<li><strong>Category: </strong><?php echo ucfirst($row[1]); ?></li>
												<li><strong>Some Info: </strong>Bla</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-sm-12 session-info">
							<div class="info-wrap">
								<div class="info-content">
									<p class="big">Trainer Info </p>
									<hr>
									<div class="row">
										<div class="col-lg-6">
											<ul class="trainer">
												<li><strong>Trainer Name: </strong><?php echo ucfirst($row[8]); ?></li>
												<li><strong>Specialty: </strong>Not Implemented Yet</li>
												<li><strong>Total Sessions Managed: </strong>Not Yet Too</li>
											</ul>
										</div>
										<div class="col-lg-6">
											<ul>
												<li><strong>Overall Average Rating:</strong></li>
												<li>Professionalism Rating: </strong></li>
												<li>Engagement Rating: </li>
												<li>Session Rating: </li>
											</ul>
										</div>	
									</div>
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