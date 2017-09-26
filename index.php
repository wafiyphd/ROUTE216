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
		
	   $query = "SELECT user_id, username, password FROM user WHERE username='$username'";
	   $res=mysqli_query($mysqli,$query);
	   
	   // check whether user exists in the database
	   $row=mysqli_fetch_array($res);
	   $count = mysqli_num_rows($res);
	   
	   // check whether user is a member
	   $querymember = "SELECT user_id FROM member WHERE username='$username'";
	   $qm = mysqli_query($mysqli,$querymember);
	   $cm = mysqli_num_rows($qm);
	   
	   // check whether user is a trainer
	   $querytrainer = "SELECT user_id FROM trainer WHERE username='$username'";
	   $qt = mysqli_query($mysqli, $querytrainer);
	   $cq = mysqli_num_rows($qt);
	   
	   if( $count == 1 && $row['password']==$password ) {
		   if ($cm == 1) {
			   $_SESSION['user'] = $row['user_id'];
			   $errMSG = "Successful Login";
		       header("Location: member.php");	
		   }
		   
		   else {
			   $_SESSION['user'] = $row['user_id'];
			   $errMSG = "Successful Login";
		       header("Location: trainer.php");	
		   }   
	   } 
	   
	   else {
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

	<link rel="stylesheet" href="css/main.css">
	<link rel="stylesheet" href="css/login.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>
  
</head>

<body>

	<div id="#top" class="container-jumbo">
	
		<div class="container">	
			<nav class="nav navbar-default"><!-- Navigation bar -->
		
				<div class="navbar-header">
				  <button class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span> 
				  </button>
				</div>
				
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav navbar-left"> 
						<li><a href="index.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
						<li><a href="index.php"><button class="btn navbar-btn"><strong>Home</strong></button></a></li>
						<li><a href="about.php"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
					</ul>
	
					<ul class="nav navbar-nav navbar-right">
						<li><a href="signup.php"><button class="btn navbar-btn" ><strong>Sign Up</strong></button></a></li>
						<li><a><button class="btn navbar-btn" data-toggle="modal" data-target="#loginModal"><strong>Log In</strong></button></a></li>
					</ul>
				</div>
			</nav><!-- End of nav bar -->
		</div>
		<?php if (isset($errMSG)) {
				echo '<div class="container fail-login">
						<div class="alert alert-danger text-center">
							<p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;'; echo $errMSG; echo'</p>
						</div>
					  </div>';}
		?>
		
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
		
		<div class="container main-container">

			<div class = "main-header">
				<br> ROUTE. <br>
				Commit to a healthier lifestyle.
				<hr><br><br><br><br>
			</div>
			
		</div>
		
	</div><!-- Jumbotron ends here -->
	
	<div class="container-fluid info-fluid text-center">
		
		<div class="container info-container">
			
			<div class="container">
			<hr>
				<div class="row">
					
					<div class="pic-container col-sm-4 col-lg-4 ">
						<img src="images/phone.jpg"></img><div class="overlay"><div class="caption">CONVENIENT<hr></div><div class="moreinfo">Despite a busy schedule, 
														both parties can simply book sessions online, review a trainer, etc. from anywhere.</div></div>
					</div>
					
					<div class="pic-container col-sm-4 col-lg-4 ">
						<img src="images/mirror.jpg"></img><div class="overlay"><div class="caption">PRODUCTIVITY<hr></div><div class="moreinfo">Allow sessions to be booked on the go,
						without having to interfere with a trainer’s or member’s busy schedule, with real-time updates.</div></div>
					</div>
					
					<div class="pic-container col-sm-4 col-lg-4 ">
						<img src="images/winner.jpg"></img><div class="overlay"></img><div class="caption">TIME-SAVING<hr></div><div class="moreinfo">Book a session without having to 
						hysically be at a gym or a studio.</div></div>
					</div>
					
				</div>
				<hr>
				<a href="about.php"><p class="pull-right">Learn More&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i></p></a>
			</div>
		</div>
	</div>
	
		
	<div class="container-fluid features-container text-center">
		
		<div class="container pic-container-2">
		
			<h3>ROUTE Features.</h3>
			<hr>

			<div class="col-md-6">
				<a href="https://placeholder.com"><img class="img-responsive" src="http://via.placeholder.com/500x250"></a><div class="caption">Join individual training sessions</div>
			</div>
			<div class="col-md-6">
				<a href="https://placeholder.com"><img class="img-responsive" src="http://via.placeholder.com/500x250"></a><div class="caption">Join group sessions</div>
			</div>
		</div>
		<br>
		<div class="container pic-container-2">
			<div class="col-md-6">
				<a href="https://placeholder.com"><img class="img-responsive" src="http://via.placeholder.com/500x250"></a><div class="caption">Record sessions history</div>
			</div>
			<div class="col-md-6">
				<a href="https://placeholder.com"><img class="img-responsive" src="http://via.placeholder.com/500x250"></a><div class="caption">Review trainers</div>
			</div>
		</div>
		
	</div>

	<div class="container-fluid signup-container ">
		<div class="container main-header">
			<div class="row">
				<div class="col-lg-6 col-xs-12">
					<span class="big">Register for ROUTE.</span><br>
					Free to join. <br> Free sessions every week.
					<hr>
				</div>
				
				<div class="signupnow col-lg-6 col-xs-12 text-center">
					<span class="big">Ready to join up?</span>
					<div class="row">
						<div class="col-lg-12 col-xs-12">
							<a href='signup.php'><button class="btn"><strong>Sign Up</strong></button></a>
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
					<a href="#">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="signup.php">Sign Up</a>
				</span>
			</div>		
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>