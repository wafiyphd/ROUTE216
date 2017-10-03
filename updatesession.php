<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	$error = false;
	
	if (isset($_SESSION['user'])!="" ) { 
		$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
		$userRow= mysqli_fetch_array($res);
	} else {
		header("Location: index.php");	
	}
	
	if ( isset($_GET['id']) ) {
		$sessionid = $_GET['id'];
	}
	
	$trquery = "SELECT trainer_id from session WHERE trainer_id='$sessionid'";
	if (!$res = mysqli_query($mysqli, $trquery)) {
		header("Location: index: php");
	}
	
	if( isset($_POST['update']) ) {
		// date validation --
		if ($day < 1 or $day > 31) {
			$error = true;
			$errType = "danger";
			$errMsg = "Please enter a valid date.";
		}
			
		if ($month == 4 or $month == 6 or $month == 9 or $month == 11) {
			if ($day > 30) {
				$error = true;
				$errType = "danger";
				$errMsg = "Please enter a valid date.";
			}
		}
			
		if ($month == 2) {
			if ($day > 29) {
				$error = true;
				$errType = "danger";
				$errMsg = "Please enter a valid date.";
			}
		}
			
		if ($year < 2017) {
			$error = true;
			$errType = "danger";
			$errMsg = "Please enter a valid date.";
		}
		// -- ends here 
			
		if (!isset($_POST['timeperiod'])) {
			$error = true;
			$errType = "danger";
			$errMsg = "Please select AM or PM.";
		}
				
		if ($category == "group") {
			if (!isset($_POST['type'])) {
				$error = true;
				$errType = "danger";
				$errMsg = "Please pick a session type.";
			}
				
			if (empty(($_POST['maxpax']))) {
				$error = true;
				$errType = "danger";
				$errMsg = "Please enter a maximum number of participants.";
			}
		}
		
		if (!$error) {
			
			$sessionid = $_POST['sessionid'];
			
			$query = mysqli_query($mysqli, "SELECT * from session where session_id = '$sessionid'");
			$sessionRow = mysqli_fetch_row($query);
		
			$title = $_POST['title'];
			$date = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$time = $_POST['clock'] . ' ' . $_POST['timeperiod'];
			$fee = $_POST['fee'];
			$status = $_POST['status'];
			$notes = $_POST['notes'];
			$type = $_POST['type'];
			$maxpax = $_POST['maxpax'];
			
			$category = $sessionRow['category'];
			

			$query = "UPDATE session SET title='$title', date='$date', time='$time', fee='$fee', status='$status' WHERE session_id = '$sessionid'";
			$res = mysqli_query($mysqli, $query);
				
			$pquery = "UPDATE personal_session SET notes='$notes' WHERE session_id = '$sessionid'";
			$res = mysqli_query($mysqli, $pquery);			
				
			$newquery = "UPDATE group_session SET type='$type', maxpax='$maxpax' WHERE session_id = '$sessionid'";
			$res = mysqli_query($mysqli, $newquery);				

			
		    if ($res) {
				$errType = "success";
				$errMSG = "Successfully updated training session.";
				header("Location: trainer.php?success=1");
		    } else {
				$errType = "danger";
				$errMSG = "Something went wrong, try again later..."; 
		    } 
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>Update - ROUTE</title>
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

	<link rel="stylesheet" href="css/updatesession.css">
	<link rel="stylesheet" href="css/alert.css">
	<link rel="stylesheet" href="css/navfooter.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
	
	<div class="container-fluid content-container">
		
		<div class="container page-info">
			<div class="row">
				<a href="updatesession.php"><div class="col-lg-3 info-box ">
					<strong>UPDATING SESSION</strong>
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
		
		<?php $session = mysqli_query($mysqli, "SELECT * from session WHERE session_id = '$sessionid'");
		$row = mysqli_fetch_row($session);
		
		$psession = mysqli_query($mysqli, "SELECT * from personal_session WHERE session_id = '$sessionid'");
		$prow = mysqli_fetch_row($psession);
		
		$gsession = mysqli_query($mysqli, "SELECT * from group_session WHERE session_id = '$sessionid'");
		$grow = mysqli_fetch_row($gsession); ?>
		
		<div class="container update-container">
			<div class="row">
			
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
					<div class="update-wrap text-center">
						<div class="update-form">
							<div class="row text-center">
								
								<form class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
									<div class = "group">
										<label for="title" class="label">TITLE</label>
										<input id="title" type="text" name="title" class="input"  value="<?php echo $row[2]; ?>"required>
									</div>

									<div class = "group">
										<div class="row">
											<div class="col-lg-3">
											<label for="date" class="label">DAY</label>
											<?php $daystr = explode('-', $row[3]) ?>
												<input id="day" type="number" name="day" class="input date" value="<?php echo $daystr[2] ?>" required>
											</div>
											<div class="col-lg-6">
											<label for="date" class="label">MONTH</label>
											<?php $monthstr = explode('-', $row[3]) ?>
												<select id="month" name="month" class="input" required>
													<option value="1" <?php if ($monthstr[1] == 1) echo "selected"; ?>>January</option>
													<option value="2" <?php if ($monthstr[1] == 2) echo "selected"; ?>>February</option>
													<option value="3" <?php if ($monthstr[1] == 3) echo "selected"; ?>>March</option>
													<option value="4" <?php if ($monthstr[1] == 4) echo "selected"; ?>>April</option>
													<option value="5" <?php if ($monthstr[1] == 5) echo "selected"; ?>>May</option>
													<option value="6" <?php if ($monthstr[1] == 6) echo "selected"; ?>>June</option>
													<option value="7" <?php if ($monthstr[1] == 7) echo "selected"; ?>>July</option>
													<option value="8" <?php if ($monthstr[1] == 8) echo "selected"; ?>>August</option>
													<option value="9" <?php if ($monthstr[1] == 9) echo "selected"; ?>>September</option>
													<option value="10" <?php if ($monthstr[1] == 10) echo "selected"; ?>>October</option>
													<option value="11" <?php if ($monthstr[1] == 11) echo "selected"; ?>>November</option>
													<option value="12" <?php if ($monthstr[1] == 12) echo "selected"; ?>>December</option>
												</select>
											</div>
											<div class="col-lg-3">
												<label for="date" class="label">YEAR</label>
												<input id="year" type="number" name="year" class="input date" value="2017" required>
											</div>
										</div>
									</div>

									<div class = "group">
										<label for="time" class="label">TIME</label>
										<div class="row">
											<div class="col-lg-9">
											<?php $period = preg_split("/(:| )/", $row[4]); ?>
												<select id="clock" name="clock" class="input" required>
													<option value="1:00" <?php if ($period[0] == 1 && $period[1] == 00) echo "selected"; ?>>1:00</option>
													<option value="1:30" <?php if ($period[0] == 1 && $period[1] == 30) echo "selected"; ?>>1:30</option>
													<option value="2:00" <?php if ($period[0] == 2 && $period[1] == 00) echo "selected"; ?>>2:00</option>
													<option value="2:30" <?php if ($period[0] == 2 && $period[1] == 30) echo "selected"; ?>>2:30</option>
													<option value="3:00" <?php if ($period[0] == 3 && $period[1] == 00) echo "selected"; ?>>3:00</option>
													<option value="3:30" <?php if ($period[0] == 3 && $period[1] == 30) echo "selected"; ?>>3:30</option>
													<option value="4:00" <?php if ($period[0] == 4 && $period[1] == 00) echo "selected"; ?>>4:00</option>
													<option value="4:30" <?php if ($period[0] == 4 && $period[1] == 30) echo "selected"; ?>>4:30</option>
													<option value="5:00" <?php if ($period[0] == 5 && $period[1] == 00) echo "selected"; ?>>5:00</option>
													<option value="5:30" <?php if ($period[0] == 5 && $period[1] == 30) echo "selected"; ?>>5:30</option>
													<option value="6:00" <?php if ($period[0] == 6 && $period[1] == 00) echo "selected"; ?>>6:00</option>
													<option value="6:30" <?php if ($period[0] == 6 && $period[1] == 30) echo "selected"; ?>>6:30</option>
													<option value="7:00" <?php if ($period[0] == 7 && $period[1] == 00) echo "selected"; ?>>7:00</option>
													<option value="7:30" <?php if ($period[0] == 7 && $period[1] == 30) echo "selected"; ?>>7:30</option>
													<option value="8:00" <?php if ($period[0] == 8 && $period[1] == 00) echo "selected"; ?>>8:00</option>
													<option value="8:30" <?php if ($period[0] == 8 && $period[1] == 30) echo "selected"; ?>>8:30</option>
													<option value="9:00" <?php if ($period[0] == 9 && $period[1] == 00) echo "selected"; ?>>9:00</option>
													<option value="9:30" <?php if ($period[0] == 9 && $period[1] == 30) echo "selected"; ?>>9:30</option>
													<option value="10:00" <?php if ($period[0] == 10 && $period[1] == 00) echo "selected"; ?>>10:00</option>
													<option value="10:30" <?php if ($period[0] == 10 && $period[1] == 30) echo "selected"; ?>>10:30</option>
													<option value="11:00" <?php if ($period[0] == 11 && $period[1] == 00) echo "selected"; ?>>11:00</option>
													<option value="11:30" <?php if ($period[0] == 11 && $period[1] == 30) echo "selected"; ?>>11:30</option>
													<option value="12:00" <?php if ($period[0] == 12 && $period[1] == 00) echo "selected"; ?>>12:00</option>
													<option value="12:30" <?php if ($period[0] == 12 && $period[1] == 30) echo "selected"; ?>>12:30</option>
												</select>
											</div>
											<div class="col-lg-3">
												<div class="radio-group timeperiod">
												<?php $ampm = explode(' ', $row[4]); ?>
													<input type="radio" id="AM" name="timeperiod" value="AM" <?php if ($ampm[1] == "AM") echo "checked"; ?>><label for="AM">AM</label>
													<input type="radio" id="PM" name="timeperiod" value="PM" <?php if ($ampm[1] == "PM") echo "checked"; ?>><label for="PM">&nbsp;PM</label>
												</div>
											</div>
										</div>
									</div>
							
									<div class = "group">
										<label for="fee" class="label">FEE</label>
										<input id="fee" type="number" name="fee" class="input" value="<?php echo $row[5]?>" required>
									</div>
							
									<div class = "group">
										<label for="time" class="label">STATUS</label>
										<div class="row">
											<div class="col-lg-12">
												<select id="status" name="status" class="input" required>
													<option value="Cancelled" <?php if ($row[6] == "Cancelled") echo "selected"; ?>>Cancelled</option>
													<option value="Completed" <?php if ($row[6] == "Completed") echo "selected"; ?>>Completed</option>
													<option value="Available" <?php if ($row[6] == "Available") echo "selected"; ?>>Available</option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="form" id="personal" style="display: none;">
									
										<div class = "group">
											<label for ="notes" class = "label">NOTES</label>
											<input id="notes" input type="text" name="notes" class = "input" value="<?php echo $prow[0]; ?>"></div>
										</div>
										
										<div class="form" id="group" style="display: none;">
										<label for="session" class="label">SESSION TYPE</label>
											<label class="radio">
												<input type="radio" name="type" value="Sport"  checked="<?php if ($grow[0] == "sport") echo "checked"; ?>">
												<div class="choice">SPORT</div>
											</label>
											
											<label class="radio">
												<input type="radio" name="type" value="Dance" checked="<?php if ($grow[0] == "dance") echo "checked"; ?>">
												<div class="choice">DANCE</div>
											</label>
											
											<label class="radio">
												<input type="radio" name="type" value="MMA" checked="<?php if ($grow[0] == "mma") echo "checked"; ?>">
												<div class="choice">MMA</div>
											</label>
														
											<label for = "participants" class = "label">NO. OF PARTICIPANTS</label>
											<input id = "maxpax" type = "number" name = "maxpax" class = "input" min="2" max= "30" value="<?php echo $grow[1]; ?>">								
										</div>
										<?php 
											if ($row[1] == 'personal') {
											   $showdiv = 'personal';
											}
											else if ($row[1] == 'group') {
											   $showdiv = 'group';
											}
											echo "<script type=\"text/javascript\">document.getElementById('".$showdiv."').style.display = 'block';</script>";
										?>	
										
										<div class="group">
											<input name="sessionid" class="hidden" value="<?php echo $row[0]; ?>"></input>
										</div>
											
										<div class="group">
											<input type="submit" name="update" class="button" value="UPDATE"></input>
										</div>
											
										<?php
										if ( isset($errType) ) {
										?>
										<div class="form-group">
												 <div class="alert alert-box type-<?php echo $errType; ?> alert-dismissable">
												  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
													 <?php echo $errMsg; ?>
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