<?php
 ob_start();
 session_start();
 date_default_timezone_set('Asia/Singapore');
 
 include_once 'dbconnect.php';

 $error = false;
 // user login php
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
		       header("Location: membertest.php");	
		   }
		   
		   else {
			   $_SESSION['user'] = $row['user_id'];
			   $errMSG = "Successful Login";
		       header("Location: trainertest.php");	
		   }   
	   } 
	   
	   else {
		   $errMSG = "Incorrect Credentials, Try again...";
	   }
  }
}

// user signup php
 if ( isset($_POST['signup']) ) {
  
  // trimming and checking all inputs
  $username = trim($_POST['username']);
  $username = strip_tags($username);
  $username = htmlspecialchars($username);
  
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);
  
  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['password']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  
  $rpass = trim($_POST['rpassword']);
  $rpass = strip_tags($rpass);
  $rpass = htmlspecialchars($rpass);
	  
  //check if email is taken
  $query = "SELECT username FROM user WHERE username='$username'";
  $sql = mysqli_query($mysqli, $query);
  if (mysqli_num_rows($sql)) {
	$error = true;
	$errTyp = "danger";
	$errMSG = "Username is already taken."; 
  }
  
  //check if username is taken
  $query = "SELECT email FROM user WHERE email='$email'";
  $sql = mysqli_query($mysqli, $query);
  if (mysqli_num_rows($sql)) {
	$error = true;
	$errTyp = "danger";
	$errMSG = "Email address is already taken."; 
  } 
  
  if($pass != $rpass){
	$error = true;
	$errTyp = "danger";
	$errMSG = "Password does not match."; 
    }

  if ($_POST['user'] == "trainer") {
	  
	  if (empty(($_POST['specialty']))) {
			$error = true;
			$errTyp = "danger";
			$errMSG = "Please enter your specialty as a trainer.";
		}
	}
	
  if ($_POST['user'] == "member") {
		if (!isset($_POST['level'])) {
			$error = true;
			$errTyp = "danger";
			$errMSG = "Please pick a training level.";
		}
	}
	
  if(!isset($_POST['user'])) {
	$error = true;
	$errTyp = "danger";
	$errMSG = "Please select either Member or Trainer.";
	}

  // if there's no error, continue to signup
  if( !$error ) {
	// password encrypt using SHA256();
    $password = hash('sha256', $pass);
	
	if ($_POST['user'] == "member") {
		$level = $_POST['level'];
		
		$query = "INSERT INTO user(username, email, fullname, password) VALUES('$username','$email','$name','$password')";
		$res = mysqli_query($mysqli, $query);
		$id = mysqli_insert_id($mysqli);
		
		$newquery = "INSERT INTO member(user_id, username, fullname, level) VALUES ('$id','$username','$name','$level')";
		$res = mysqli_query($mysqli, $newquery);	
	}
	
	elseif ($_POST['user'] == "trainer") { 
	
		$specialty = trim($_POST['specialty']);
		
		$query = "INSERT INTO user(username, email, fullname, password) VALUES('$username','$email','$name','$password')";
		$res = mysqli_query($mysqli, $query);	
		$id = mysqli_insert_id($mysqli);
		
		$newquery = "INSERT INTO trainer(user_id, username, fullname, specialty) VALUES ('$id','$username','$name','$specialty')";
		$res = mysqli_query($mysqli, $newquery);	
	}
	
	
   if ($res) {
    $errTyp = "success";
	$errMSG = "Successfully signed up. You may now log in.";
	
    unset($username); unset($name); unset($email); unset($pass); unset($rpass); unset($specialty); unset($level);
   } 
   
   else {
	   $errTyp = "danger";
	   $errMSG = "Something went wrong, try again later..."; 
   } 
  }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Sign Up - ROUTE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<script src="https://use.fontawesome.com/3c53deecc4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">

	<link rel="stylesheet" href="css/signup.css">
	<link rel="stylesheet" href="css/login.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>
	<script>
	function show1(){
	document.getElementById('#member').style.display ='block';
	document.getElementById('#trainer').style.display ='none';
	}
	function show2(){
	  document.getElementById('#member').style.display ='none';
	  document.getElementById('#trainer').style.display ='block';
	}
	</script> 

</head>

<body>

	<div id="#top" class="container-jumbo">
	
		<nav class="nav navbar-default"><!-- Navigation bar -->
			<div class="container">
				<div class="nav navbar-header">
					<ul class="nav navbar-nav">
					  <li><a href="index.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
					</ul>
				</div>
				
				
				<ul class="nav navbar-nav navbar-right">
					<li><a><button class="btn navbar-btn" data-toggle="modal" data-target="#loginModal"><strong>Log In</strong></button></a></li>
				</ul>
				
			</div>
		</nav> <!-- End of Navigation bar -->
		
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
		
	</div><!-- Jumbotron ends here -->
	
	<div class="container-fluid main-container">
		<div class="container signup-container">
			<h3>Sign up for ROUTE</h3>
			<h4>Commit to a healthier lifestyle now</h4>
		
			<div class="row">
				
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
					
					<div class="signup-wrap text-center">
					
						<div class="signup-form">
								
							<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
							
								<div class="group">
									<label for="username" class="label">Username</label>
									<input id="username" type="text" name="username" class="input" value="<?php echo $username ?>" required>
								</div>
								
								<div class="group">
									<label for="name" class="label">Full Name</label>
									<input id="name"  type="text" name="name" class="input" value="<?php echo $name ?>" required>
								</div>
								
								<div class="group">
									<label for="email" class="label">Email address</label>
									<input id="email" type="email" name="email" class="input" value="<?php echo $email ?>" required>
								</div>

								<div class="group">
									<label for="pass" class="label">Password</label>
									<input id="password" type="password" name="password" class="input" data-type="password" required>
								</div>
								
								<div class="group">
									<label for="pass" class="label">Confirm Password</label>
									<input id="rpassword" type="password" name="rpassword" class="input" data-type="password" required>
								</div>
								
								<div class="group">			
									<label for="user" class="label">Joining as</label>
									<div class="row">
										<div class="col-sm-12 col-lg-6">
											<label class="radio">
											  <input type="radio" name="user" value="member" onclick="show1();">
											  <div class="choice">Member</div>
											</label>
										</div>
										<div class="col-sm-12 col-lg-6">
											<label class="radio">
											  <input type="radio" name="user" value="trainer" onclick="show2();">
											  <div class="choice">Trainer</div>
											</label>
										</div>
									</div>
								</div>
								
								<div id="#member" class="group member">
									<label for="level" class="label">Training Level</label>
									<label class="radio">
									  <input type="radio" name="level" value="beginner">
									  <div class="choice">Beginner</div>
									</label>
									<label class="radio">
									  <input type="radio" name="level" value="intermediate">
									  <div class="choice">Intermediate</div>
									</label>
									<label class="radio">
									  <input type="radio" name="level" value="expert">
									  <div class="choice">Expert</div>
									</label>
								</div>
								
								<div id="#trainer" class="group trainer">
									<label for="specialty" class="label">Specialty</label>
									<input id="specialty" type="text" name="specialty" class="input">
								</div>
								
								<p>By signing up, you agree to ROUTE’s Terms of Service and Privacy Policy.</p>
								<div class="group">
									<input type="submit" name="signup" class="button" value="Sign Up">
								</div>
								
								<?php
								if ( isset($errMSG) ) {
								?>
								<div class="form-group">
										 <div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
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
		</div>
	</div>
	
	<div class="container-fluid footer-container">
		
		<div class="container footer-col">
			<div class="col-md-6">
				<img src="images/routeW.png"></img>
			</div>
		
			<div class="col-md-6">
				<span style="float:right;"><a href="#top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a></span>
			</div>
		</div>
		
		<div class="container sub-footer"><!-- Sub Footer -->				
			
			<div class="col-sm-6">
			&copy Copyright 2017 <strong>ROUTE.</strong>
			</div>
			
			<div class="col-sm-6">
				<span style="float:right">
					<a href="#">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">Features</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">Log In</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">Sign Up</a>
				</span>
			</div>		
		</div><!-- End Sub Footer -->
		<br><br>
	</div>
	
</body>

</html>