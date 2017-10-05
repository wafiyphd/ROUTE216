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
				<br> ROUTE. <br>
				Commit to a healthier lifestyle.
				<hr><br><br><br><br>
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
			<h3>About ROUTE.</h3>
			<hr>
				<div class="row">
					
					<div class="pic-container col-sm-4 col-lg-4 padding-0">
						<img src="images/phone.jpg"></img><div class="overlay"><div class="caption">CONVENIENCE<hr></div><div class="moreinfo">Despite a busy schedule, 
														both parties can simply book sessions online, review a trainer, etc. from anywhere.</div></div>
					</div>
					
					<div class="pic-container col-sm-4 col-lg-4 padding-0">
						<img src="images/mirror.jpg"></img><div class="overlay"><div class="caption">PRODUCTIVITY<hr></div><div class="moreinfo">Allow sessions to be booked on the go,
						without having to interfere with a trainer’s or member’s busy schedule, with real-time updates.</div></div>
					</div>
					
					<div class="pic-container col-sm-4 col-lg-4 padding-0">
						<img src="images/winner.jpg"></img><div class="overlay"></img><div class="caption">TIME-SAVING<hr></div><div class="moreinfo">Book a session without having to 
						hysically be at a gym or a studio.</div></div>
					</div>
					
				</div>
				<hr>
				<a href="about.php"><button class="btn btn-more pull-right">Learn More&nbsp;</button></a>
			</div>
		</div>
	</div>
	
	<div class="container-fluid features-container text-center">
		
		<div class="container">
			<h3>ROUTE Features.</h3>
			<hr>
			
			<span class="big">As a Member</span>
			<div class="row pic-row">
				
				<div class="col-lg-6 col-xs-12 images">
					<img class="img-responsive" src="images/1.png">
				</div>
				<div class=" col-lg-6 col-xs-12 images">
					<img class="img-responsive " src="images/3.png"><div class="caption"></div>
				</div>
			</div>
			<hr>
			<span class="big">As a Trainer</span>
			<div class="row pic-row">
				
				<div class="col-lg-6 col-xs-12 images">
					<img class="img-responsive" src="images/2.png">
				</div>
				<div class="col-lg-6 col-xs-12 images ">
					<img class="img-responsive" src="images/4.png"><div class="caption"></div>
				</div>
			</div>
		</div>
		
	</div>

	<div class="container-fluid combine">
		<div class="container-fluid signup-container ">
			<div class="container main-header">
				<div class="row">
					<div class="col-lg-6 col-xs-12 header">
						<span class="big">Register for ROUTE.</span><br>
						<p>Free to join. <br> Free sessions every week.</p>
						
					</div>
					
					<div class="col-lg-6 col-xs-12 text-center">
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
		<hr>
		<div class="container-fluid contact-container ">
			<div class="container main-header">
				
				<div class="row">
					<div class="col-lg-6 col-xs-12 text-center">
						<span class="big">Got any inquries?</span>
						<div class="row">
							<div class="col-lg-12 col-xs-12">
								<a href='contact.php'><button class="btn"><strong>Contact Us</strong></button></a>
							</div>
						</div>
					</div>
						
					<div class="row">
						<div class="col-lg-6 col-xs-12 pull-right header">
							<span class="big">Contact Us.</span><br>
							<p>Send any feedback.<br>Send any inquiries.</p>
							
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
					<a href="contact.php">Contact</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="signup.php">Sign Up</a>
				</span>
			</div>		
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>