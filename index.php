<?php 
ob_start();
session_start();
require_once 'dbconnect.php';

// redirects to users' home page when already signed in
 if ( isset($_SESSION['user'])!="" ) { 
	$res=mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow=mysqli_fetch_array($res);
	$id = $userRow['user_id'];

	// check whether user is a member or trainer
	$userkind = $userRow['user_kind'];
	if ($userkind === "member") {
		header("Location: member.php");
	}
	
	elseif ($userkind == "trainer") {
		 header("Location: trainer.php");
	}
}
			
$error = false;

if( isset($_POST['login']) ) { 
  
  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);
  
  $pass = trim($_POST['password']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);  
  
  // if there's no error, continue to login
  if (!$error) {
	  
	   $password = hash('sha256', $pass); // password hashing using SHA256
		
	   $query = "SELECT user_id, username, user_kind, password FROM user WHERE username='$username'";
	   $res=mysqli_query($mysqli,$query);
	   
	   // check whether user exists in the database
	   $row=mysqli_fetch_array($res);
	   $count = mysqli_num_rows($res);
	   
	   if( $count == 1 && $row['password']==$password ) {
		   if ($row['user_kind'] == "member") {
			   $_SESSION['user'] = $row['user_id'];
			   $errMSG = "Successful Login";
		       header("Location: member.php");	
		   }
		   
		   elseif ($row['user_kind'] == "trainer"){
			   $_SESSION['user'] = $row['user_id'];
			   $errMSG = "Successful Login";
		       header("Location: trainer.php");	
		   }   
	   } 
	   
	   else {
		   $errTyp = "danger";
		   $errMSG = "Incorrect Credentials for logging in, please try again...";
	   }
  }
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
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
	
	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/alert.css">
	<link rel="stylesheet" href="css/navfooter.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>
  
</head>

<body>

	<div id="#top" class="container-fluid main-fluid">

	
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
					
					<ul class="nav navbar-nav navbar-right">
						<li><a href="signup.php"><button class="btn navbar-btn" ><strong>Sign Up</strong></button></a></li>
						<li><a><button class="btn navbar-btn" data-toggle="modal" data-target="#loginModal"><strong>Log In</strong></button></a></li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="container page-info">
			<div class="row">
				<?php if (isset($errTyp)) { ?>
					<div class="col-lg-6">
						<div class="alert alert-box-s type-<?php echo $errTyp; ?> alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							&nbsp;<?php echo $errMSG; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		<div class="container main-container">

			<div class = "main-header">
				<br><br> <div class = "title">ROUTE</div>
				<div class = "description">Commit to a healthier lifestyle.</div>
				<br><br><br>
			</div>
			
		</div>
		
	</div><!-- Jumbotron ends here -->
	
	<div class="modal fade" id="loginModal"><!-- Start of login modal -->
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="login-wrap">
					<div class="login-container">
						<h3><strong>Log In</strong></h3>
						<hr>
						
						<div class="login-form">
						
							<form id="login" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
						
								<div class="group">
									<label for="username" class="label">Username</label>
									<input id="username" type="text" name="username" class="input" required>
								</div>
								<div class="group">
									<label for="pass" class="label">Password</label>
									<input id="pass" type="password" name="password" class="input" data-type="password" required>
								</div>
								
								<div class="group">
									<button type="submit" name="login" class="button">Log In</button>
								</div>
							</form>
							
							<div class="forgot">
								<a href="#forgot">Forgot Password?</a>
							</div>
							<hr>
						
							
							<div class="sign-up">
								<p>Not a member yet?</p>
								<a href="signup.php"><input type="submit" class="button" value="Sign Up"></a>
							</div>
						</div>
					
					</div>
				</div>
			</div>
	
		</div>
	</div><!-- End of Login modal -->
		
	<div class="container-fluid info-fluid text-center">
		
		<div class="container info-container">
			
			<div class="container">
			
				<div class="row">
				
					<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-12">
						<br>
						<h3>About ROUTE</h3>
						<br><br>
						<div class="pic-container col-sm-4 col-lg-4">
							<div class = "about"><img src="images/phone.png"></img>
							<br><br>
								<div class = "point">CONVENIENCE</div><br>Despite a busy schedule, 
								both parties can simply book sessions online, review a trainer, etc. from anywhere.
								</div>
							</div>
					
						<div class="pic-container col-sm-4 col-lg-4">
							<div class = "about"><img src="images/calendar.png"></img>
							<br><br>
							<div class = "point">PRODUCTIVITY</div><br>Allow sessions to be booked on the go,
							without having to interfere with your schedule, with real-time updates.
							</div>
						</div>
					
						<div class="pic-container col-sm-4 col-lg-4">
							<div class = "about"><img src="images/clock.png"></img>
							<br><br>
							<div class = "point">TIME-SAVING</div><br>Book a session without having to 
							physically be at a gym or a studio. No more lines and no more waiting.
							</div>
						</div>
						<br>
					</div>
				
				</div>
				<br><br>
				<a href="about.php"><button class="btn btn-more pull-center">Learn More&nbsp;</button></a>		
			</div>
			<br>
		</div>
	</div>
	
	<div class="container-fluid features-container text-center">
		
		<div class="container">
			<br>
				<h3>Features of ROUTE</h3>
			<br>
			
			<div class="row pic-row">			
				<div class="col-lg-6 col-xs-12 images">
					<img class="img-responsive" src="images/memberpage.png">
				</div>
				
				<div class=" col-lg-6 col-xs-12">
					<br><br><br><br>
					<div class="caption">
						<span class="big">As a Member</span>
						<br>
						<div class="contents">Join sessions
							<br>Review trainers
							<br>Customize your profile
						</div>
					</div>
				</div>
				<br>
			</div>
			<br>
			
			<div class="row pic-row">
				
				<div class="col-lg-6 col-xs-12">
					<br><br><br><br>
					<div class="caption-right">
						<span class="big">As a Trainer</span>
						<br>
						<div class="contents-right">Create sessions
							<br>Receive feedback
							<br>Customize your profile
						</div>
					</div>
				</div>
				
				<div class="col-lg-6 col-xs-12 images ">
					<img class="img-responsive" src="images/trainerpage.png">
				</div>
			</div>
		</div>		
	</div>

	<div class="container-fluid combine">
	
		<div class="container-fluid signup-container ">
		
			<div class="container main-header">
			
				<div class="row">
					<div class="col-lg-4 col-xs-12 col-lg-offset-1 header">
						<span class="big">Ready to join us?</span>
						<br>
						<a href='signup.php'><button class="btn"><strong>Sign Up</strong></button></a>
						<br><br>
						<span class="big">Register for ROUTE.</span>
						<br>
						<p>Free to join. <br> Free sessions weekly.</p>
						<br>						
					</div>
					
					<div class = "col-lg-2 text-center">
						<p><br><br><br>- or -</p>
					</div>
							
					<div class="col-lg-4 col-xs-12 text-right">
						<span class="big">Contact us.</span><br>
						<p>Ask us anything.<br>Send us feedback.</p>
						<span class="big">Still have questions?</span><br>
						<a href='contact.php'><button class="btn"><strong>Contact Us</strong></button></a>			
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
					<a href="contact.php">Contact</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="signup.php">Sign Up</a>
				</span>
			</div>		
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>