<?php 
ob_start();
session_start();
require_once 'dbconnect.php';

 if ( isset($_SESSION['user'])!="" ) { 
 $res=mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
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
		   $errMSG = "Incorrect Credentials for logging in, please try again...";
	   }
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>About - ROUTE</title>
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
	
	<link rel="stylesheet" href="css/about.css">
	<link rel="stylesheet" href="css/login.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
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
				
					<?php if ( isset($_SESSION['user'])!="" ) { ?>
					<ul class="nav navbar-nav navbar-right desktop">
						<li class="dropdown ">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle">
								<button class="btn navbar-btn"><span><i class="fa fa-user" aria-hidden="true"></i></span>&nbsp;&nbsp;<strong><?php echo ucwords($userRow['fullname']); ?></strong>&nbsp;&nbsp;<b class="caret"></b></button>
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
					<?php } else { ?>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="signup.php"><button class="btn navbar-btn" ><strong>Sign Up</strong></button></a></li>
						<li><a><button class="btn navbar-btn" data-toggle="modal" data-target="#loginModal"><strong>Log In</strong></button></a></li>
					</ul>
					<?php }?>
				</div>		
			</nav><!-- End of nav bar -->
		</div>
		
		<?php if (isset($errMSG)) { ?>
				<div class="container fail-login">
					<div class="alert alert-danger text-center">
						<p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<?php echo $errMSG; ?></p>
					</div>
				</div> <?php } ?>
		
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
		
		<div class="container header-container">
			<div class="container main-header">
				<p class="header">About ROUTE. &nbsp;<span class="title">Learn more about ROUTE.</span></p>
			</div>
		</div>	
	</div>

	<div class="container-fluid info-container"> 
		<div class="container">
			<div class = "background">	
			<h2 class="text-center">What ROUTE Is</h2><br>
				<div class="row">
					<div class="col-sm-5 col-sm-offset-1">
						<blockquote class="blockquote-reverse">
							<h5>What We Do</h5>
							<p>ROUTE creates a seamless experience between both trainers and members. It allows both parties to create 
							and modify their schedules on the go. Both fitness trainers and fitness members alike can log their free 
							<strong>time</strong> for training sessions. You can schedule both personal and group sessions with ROUTE.</p>
						</blockquote>
						</div>
						<div class="col-sm-5">
						<blockquote class="blockquote-reverse">
							<h5>What We Want</h5>
							<p>ROUTE aims to create a one-stop application that will synchronize your schedules, along with the schedules 
							of other fitness trainers and members. We want you to have a more interactive relationship, allowing you to 
							book sessions without having to meet face to face, saving time and putting the main emphasis on <strong>fitness</strong>.</p>
						</blockquote>
						</div>
						<div class="col-sm-5 col-sm-offset-1">
							<blockquote class="blockquote-reverse">
							<h5>Who We Are</h5>
							<p>ROUTE intends to reduce the lack of time, especially for fitness trainers and members.
							Not everyone can be there at a gym or studio to log and book every session, and we understand that.
							Many of us have conflicting schedules. As hectic as life can get, <strong>you</strong> can't always be 
							there in the same gym or studio to book a session.</p>
						</blockquote>
						</div>
						<div class="col-sm-5">
							<blockquote class="blockquote-reverse">
							<h5>Why We Do It</h5>
							<p>We know how important your fitness can be to you. We also know how important your time can be to you.
							We don't want you to have to worry about planning your schedule around your health. We want your main
							focus to be <strong>worry-free</strong> exercise, not having to spend time you could be putting into your fitness regime
							into scheduling a training session.</p>
						</blockquote>
					</div>
				</div>
			</div>
	 
			<div class="features-wrap">
			<h2 class = "text-center">Join Us At ROUTE</h2>
				<div class = "row">
					<div class = "col-sm-5 col-sm-offset-1 text-right">
						<div class = "features">
							<h3>As a Member</h3>
							<blockquote class="blockquote-reverse">
								<h5>Join training sessions</h5>
								<h4>Register for training sessions created by your favourite trainers.</h4>
							</blockquote>
							<blockquote class="blockquote-reverse">
								<h5>View your training history</h5>
								<h4>Find your completed classes with quick and easy access.</h4>
							</blockquote>
							<blockquote class="blockquote-reverse">
								<h5>Submit reviews</h5>
								<h4>Provide trainers with feedback on how their classes went.</h4>
							</blockquote>
						</div>
					</div>
					<div class = "col-sm-5 text-right">
						<div class = "features">
							<h3>As a Trainer</h3>
							<blockquote class="blockquote-reverse">
								<h5>Create training sessions</h5>
								<h4>Create personal and interactive sessions with fitness members.</h4>
							</blockquote>
							<blockquote class="blockquote-reverse">
								<h5>Manage training sessions</h5>
								<h4>Seamlessly update your sessions with the latest information.</h4>
							</blockquote>
							<blockquote class="blockquote-reverse">
								<h5>Read reviews</h5>
								<h4>See how you well you did based on what fitness members say.</h4>
							</blockquote>
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
					<a href="contact.php">Contact</a><?php if ( !isset($_SESSION['user'])!="" ) { ?>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="signup.php">Sign Up</a><?php } ?>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>