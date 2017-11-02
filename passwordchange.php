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
	
	if ( isset($_POST['updatepassword']) ) {
		
		$oldpass = $_POST['oldpassword'];
		$oldpassword = hash('sha256', $oldpass);
		
		$newpass = $_POST['newpassword'];
		$confpassword = $_POST['confpassword'];
		
		$change_query = mysqli_query($mysqli, "SELECT password FROM user WHERE user_id=".$_SESSION['user']);
		$res = mysqli_fetch_array($change_query);
		$currentpass = $res['password'];
		
		if ($currentpass != $oldpassword) {
			$error = true;
			$alertType = "danger";
			$alertMsg = "The old password you have entered is wrong.";
		}
		
		if ($newpass != $confpassword) {
			$error = true;
			$alertType = "danger";
			$alertMsg = "Your new and confirmed passwords do not match.";
		}
		
		if (!$error) {
			if ($currentpass == $oldpassword) {
				if ($newpass == $confpassword) {
					$newpassword = hash('sha256', $newpass);
					$update = "UPDATE user SET password='$newpassword' WHERE user_id=".$_SESSION['user'];
					$res = mysqli_query($mysqli, $update);
				}
			}
			
			if ($res) {
				$alertType = "success";
				$alertMsg = "Successfully updated password.";
				header("Location: profile.php?success=0");
			} else {
				$alertType = "danger";
				$alertMsg = "Something went wrong, please try again.";
			}
		}
	}
	?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Change Password - ROUTE</title>
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

	<link rel="stylesheet" href="css/passwordchange.css">
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
									<?php if ($userRow['user_kind'] == 'member') { ?>
									<li><a href="joinsessionslist.php">View Available Sessions</a></li>
									<li><a href="managemember.php">Manage Joined Sesssions</a></li>
									<li><a href="viewhistory.php">View Completed Sessions</a></li>
									<li><a href="allmemberreviews.php">All My Reviews</a></li>
									<?php } else { ?>
									<li><a href="record.php">Record New Session</a></li>
									<li><a href="viewhistory.php">Manage My Sessions</a></li>
									<li><a href="allreviews.php">View All Reviews</a></li>
									<?php } ?>
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

	<div class="container-fluid profile-fluid">

		<div class="container page-info">
			<div class="row">
				<a href="passwordchange.php"><div class="col-lg-3 info-box ">
					<strong>UPDATING PASSWORD</strong>
				</div></a>
				<?php if (isset($alertType)) { ?>
					<div class="col-lg-6">
						<div class="alert alert-box-s type-<?php echo $alertType; ?> alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							&nbsp;<?php echo $alertMsg; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<div class="container password-container">
			<div class="row">
			
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
					<div class="password-wrap text-center">
						<div class="password-form">
							<a href="profile.php"><img src="images/arrow-left.png" class="photo pull-left"></a>
							<div class="row">
								<form class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
									<div class="group">
										<label for = "oldpassword" class = "label">OLD PASSWORD</label>
										<input type="password" name="oldpassword" class="input" value="" required />
									</div>
									<div class="group">
										<label for = "newpassword" class = "label">NEW PASSWORD</label>
										<input type="password" name="newpassword" class="input" value="" required />
									</div>
									<div class="group">
										<label for = "confpassword" class = "label">CONFIRM PASSWORD</label>
										<input type="password" name="confpassword" class="input" value="" required />
									</div>
									<div class = "group">
										<button type="submit" name="updatepassword" class="button" value="Update Password">UPDATE</button>
									</div>
								</form>
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
					<script>
					  $("a[href='#top']").click(function() {
						 $("html, body").animate({ scrollTop: 0 }, "slow");
						 return false;
					  });
					</script>
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
					<a href="#">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="contact.php">Contact</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
</body>

</html>