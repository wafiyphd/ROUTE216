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
				<div class="nav navbar-header">
					<ul class="nav navbar-nav">
					  <li><a href="index.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
					</ul>
				</div>
				
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
			</ul>
			</div>
		</nav>
	</div>
	
	<div class="container text-center">
		<h1>YOU ARE A MEMBER<h1>
		<h3>PLACEHOLDER HOME. WELCOME TO YOUR HOME.</h3>
		<button class="btn btn-primary"><a href="review.php">Test Reviewing</a></button>
	</div>
</body>

</html>