<?php

	ob_start();
	session_start();
	include_once 'dbconnect.php';
	date_default_timezone_set('Asia/Singapore');
	
	$error = false;
	
	if ( isset($_SESSION['user'])!="" ) { 
	$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow= mysqli_fetch_array($res);
	}

	if (isset($_POST['record'])) {
		$title = trim($_POST["title"]);
		$title = strip_tags($title);
		$title = htmlspecialchars($title);
	  
		$day = trim($_POST["day"]);
		$day = strip_tags($day);
		$day = htmlspecialchars($day);
		
		$month = trim($_POST["month"]);
		$month = strip_tags($month);
		$month = htmlspecialchars($month);
		
		$year = trim($_POST["year"]);
		$year = strip_tags($year);
		$year = htmlspecialchars($year);
		
		$clock = trim($_POST["clock"]);
		$clock = strip_tags($clock);
		$clock = htmlspecialchars($clock);
	  
		$period = $_POST["timeperiod"];
	  
		$fee = trim($_POST["fee"]);
		$fee = strip_tags($fee);
		$fee = htmlspecialchars($fee);
	  
		$status = trim($_POST["status"]);
		$status = strip_tags($status);
		$status = htmlspecialchars($status);
	  
		$notes = trim($_POST["notes"]);
		$notes = strip_tags($notes);
		$notes = htmlspecialchars($notes);
	  
		$maxpax = trim($_POST["maxpax"]);
		$maxpax = strip_tags($maxpax);
		$maxpax = htmlspecialchars($maxpax);  
		
		$category = $_POST['session'];
	
		// date validation --
		if ($day < 1 Or $day > 31) {
			$error = true;
			$errTyp = "danger";
			$errMsg = "Please enter a valid date.";
		}
		
		if ($month == 4 or $month == 6 or $month == 9 or $month == 11) {
			if ($day > 30) {
				$error = true;
				$errTyp = "danger";
				$errMsg = "Please enter a valid date.";
			}
		}
		
		if ($month == 2) {
			if ($day > 29) {
				$error = true;
				$errTyp = "danger";
				$errMsg = "Please enter a valid date.";
			}
		}
		
		if ($year < 2017) {
			$error = true;
			$errTyp = "danger";
			$errMsg = "Please enter a valid year.";
		}
		// -- ends here 
		
		if (!isset($_POST['timeperiod'])) {
			$error = true;
			$errTyp = "danger";
			$errMsg = "Please select AM or PM.";
		}
			
		if ($category == "group") {
			if (!isset($_POST['type'])) {
				$error = true;
				$errTyp = "danger";
				$errMsg = "Please pick a session type.";
			}
			
			if (empty(($_POST['maxpax']))) {
				$error = true;
				$errTyp = "danger";
				$errMsg = "Please enter a maximum number of participants.";
			}
		}
		
		if(!isset($_POST['session'])) {
			$error = true;
			$errTyp = "danger";
			$errMsg = "Please select either Personal or Group.";
		}
		
		if( !$error ) {
			
			$date = $year.'-'.$month.'-'.$day;
			$time = $clock .' '.$period;
			
			if ($category == "personal") {
				$trainer_id = $userRow['user_id'];
				$trainer_name = $userRow['fullname'];
				
				$query = "INSERT INTO session(title, category, date, time, fee, status, trainer_id, trainer_name)
				VALUES ('$title', '$category', '$date', '$time', '$fee', 'Available', '$trainer_id', '$trainer_name')";
				$res = mysqli_query($mysqli, $query);
				$id = mysqli_insert_id($mysqli);

				$newquery = "INSERT INTO personal_session(notes, session_id)
				VALUES ('$notes', '$id')";
				$res = mysqli_query($mysqli, $newquery);
			}

			elseif ($category == "group") {
				$type = $_POST['type'];
				$trainer_id = $userRow['user_id'];
				$trainer_name = $userRow['fullname'];
				
				$query = "INSERT INTO session(title, category, date, time, fee, status, trainer_id, trainer_name)
				VALUES ('$title', '$category', '$date', '$time', '$fee', 'Available', '$trainer_id', '$trainer_name')";
				$res = mysqli_query($mysqli, $query);
				$id = mysqli_insert_id($mysqli);

				$newquery = "INSERT INTO group_session(type, maxpax, session_id)
				VALUES ('$type', '$maxpax', '$id')";
				$res = mysqli_query($mysqli, $newquery);	
			}
			
			if ($res) {
				$errTyp = "success";
				$errMsg = "Successfully recorded a training session.";
			
				unset($title); unset($date); unset($time); unset($fee); unset($notes); unset($type); unset($maxpax);
				header("Location: trainer.php");
			} else {
				$errTyp = "danger";
				$errMsg = "Something went wrong, try again later..."; 
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Record a Training Session - ROUTE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<script src="https://use.fontawesome.com/3c53deecc4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">

	<link rel="stylesheet" href="css/record.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>
	
	<script>
	function show1(){
	document.getElementById('#personalsession').style.display ='block';
	document.getElementById('#groupsession').style.display ='none';
	}
	function show2(){
	  document.getElementById('#personalsession').style.display ='none';
	  document.getElementById('#groupsession').style.display ='block';
	}
	</script>
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
			<h3>You may record a new training session here.</h3>
		</div>
	</div>
	
	<div class="container-fluid main-container">
		<div class="container record-container">			
			
			<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
				
				<div class="record-wrap text-center">
					<div class="record-form">
						<h3><strong>Record a Training Session</strong></h3>
						<br>	
						
						<form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
							<div class = "group">
								<label for="title" class="label">Title</label>
								<input id="title" type="text" name="title" class="input" required>
							</div>

							<div class = "group">
								<label for="date" class="label">Date</label>
								<div class="row">
									<div class="col-lg-3">
										<input id="day" type="number" name="day" class="input date" value="1" required>
									</div>
									<div class="col-lg-6">
										<select id="month" name="month" class="input" required>
											<option value="1">January</option><option value="2">February</option>
											<option value="3">March</option><option value="4">April</option>
											<option value="5">May</option><option value="6">June</option>
											<option value="7">July</option><option value="8">August</option>
											<option value="9" selected>September</option><option value="10">October</option>
											<option value="11">November</option><option value="12">December</option>
										</select>
									</div>
									<div class="col-lg-3">
										<input id="year" type="number" name="year" class="input date" value="2017" required>
									</div>
								</div>
							</div>

							<div class = "group">
								<label for="time" class="label">Time</label>
								<div class="row">
									<div class="col-lg-9">
										<select id="clock" name="clock" class="input" required>
											<option value="1:00">1:00</option><option value="1:30">1:30</option>
											<option value="2:00">2:00</option><option value="2:30">2:30</option>
											<option value="3:00">3:00</option><option value="3:30">3:30</option>
											<option value="4:00">4:00</option><option value="4:30">4:30</option>
											<option value="5:00">5:00</option><option value="5:30">5:30</option>
											<option value="6:00">6:00</option><option value="6:30">6:30</option>
											<option value="7:00">7:00</option><option value="7:30">7:30</option>
											<option value="8:00">8:00</option><option value="8:30">8:30</option>
											<option value="9:00">9:00</option><option value="9:30">9:30</option>
											<option value="10:00">10:00</option><option value="10:30">10:30</option>
											<option value="11:00">11:00</option><option value="11:30">11:30</option>
											<option value="12:00">12:00</option><option value="12:30">12:30</option>
										</select>
									</div>
									<div class="col-lg-3">
										<div class="radio-group timeperiod">
											<input type="radio" id="AM" name="timeperiod" value="AM"><label for="AM">AM</label>
											<input type="radio" id="PM" name="timeperiod" value="PM"><label for="PM">&nbsp;PM</label>
										</div>
									</div>
								</div>
							</div>

							<div class = "group">
								<label for="fee" class="label">Fee</label>
								<input id="fee" type="number" name="fee" class="input" required>
							</div>

							<div class="group">			
								<label for="session" class="label">Type of Session</label>
									<div class="row">
										<div class="col-sm-12 col-lg-6">
											<label class="radio">
												<input type="radio" name="session" value="personal" onclick="show1();">
												<div class="choice">Personal</div>
											</label>
										</div>
									<div class="col-sm-12 col-lg-6">
										<label class="radio">
											<input type="radio" name="session" value="group" onclick="show2();">
											<div class="choice">Group</div>
										</label>
									</div>
								</div>
							</div>

							<div id="#groupsession" class="group session" style="display:none">
								<label for="session" class="label">Session Type</label>
									<label class="radio">
										<input type="radio" name="type" value="Sport">
										<div class="choice">Sport</div>
									</label>
									
									<label class="radio">
										<input type="radio" name="type" value="Dance">
										<div class="choice">Dance</div>
									</label>
									
									<label class="radio">
										<input type="radio" name="type" value="MMA">
										<div class="choice">MMA</div>
									</label>
												
									<label for = "participants" class = "label">Max participants</label>
									<input id = "maxpax" type = "number" name = "maxpax" class = "input" min="2" max= "30">
							</div>
											
							<div id="#personalsession" class="personal session" style="display:none">
								<div class = "group">
									<label for ="notes" class = "label">Notes</label>
									<input id="notes" input type="text" name="notes" class = "input"></div>
								</div>
											
						<div class = "group">
							<button type="record" name="record" class="button" value="Record Session">Record Session</button>
						</div>
						
						<?php
							if ( isset($errMsg) ) {
						?>
						
						<div class="form-group">
							<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
								<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMsg; ?>
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
