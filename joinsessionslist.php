<?php 
ob_start();
session_start();
require_once 'dbconnect.php';

if ( isset($_SESSION['user'])!="" ) { 
$res=mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
$userRow=mysqli_fetch_array($res);
}
 
$offset = 0;
$page_result = 5; 
	
if($_GET['pageno'])
{
 $page_value = $_GET['pageno'];
 if($page_value > 1)
 {	
  $offset = ($page_value - 1) * $page_result;
 }
}

$error = false;

if( isset($_POST['join-personal']) ) {
	
	$userid = $userRow['user_id'];
	$userfullname =$userRow['fullname'];
	$sessionid = $_POST['id'];
	
	$status = mysqli_query($mysqli, "UPDATE session SET status = 'Unavailable' WHERE session_id = '$sessionid' ");
	$join = mysqli_query($mysqli, "	UPDATE personal_session SET member_id = '$userid', member_name = '$userfullname' WHERE session_id ='$sessionid' ");
	
	if ($join && $status){
		$alertType = "success";
		$errMSG = "Successfully joined.";
	}
	else {
		$alertType = "danger";
		$errMSG = "Failed to join this session.";
	}	
}

if( isset($_POST['unjoin-personal']) ) {
	
	$userid = $userRow['user_id'];
	$userfullname =$userRow['fullname'];
	$sessionid = $_POST['id'];
	
	$status = mysqli_query($mysqli, "UPDATE session SET status = 'Available' WHERE session_id = '$sessionid' ");
	$unjoin = mysqli_query($mysqli, "	UPDATE personal_session SET member_id = NULL, member_name = NULL WHERE session_id ='$sessionid' ");
	
	if ($unjoin && $status){
		$alertType = "success";
		$errMSG = "Successfully unjoined.";
	}
	else {
		$alertType = "danger";
		$errMSG = "Failed to unjoin this session.";
	}	
}

if( isset($_POST['join-group']) ) {
	
	$userid = $userRow['user_id'];
	$userfullname =$userRow['fullname'];
	$sessionid = $_POST['id'];
	
	$join = mysqli_query($mysqli, "	INSERT INTO joined_group(session_id, member_id, member_name) values ('$sessionid','$userid','$userfullname')");
	$update = mysqli_query($mysqli, "UPDATE group_session SET count = count + 1 WHERE session_id = $sessionid");
	
	if ($join && update){
		$alertType = "success";
		$errMSG = "Successfully joined.";
	}
	else {
		$alertType = "danger";
		$errMSG = "Failed to join this session.";
	}	
}

if( isset($_POST['unjoin-group']) ) {
	
	$userid = $userRow['user_id'];
	$sessionid = $_POST['id'];
	
	$join = mysqli_query($mysqli, "	DELETE FROM joined_group WHERE member_id = '$userid' AND session_id ='$sessionid' ");
	$update = mysqli_query($mysqli, "UPDATE group_session SET count = count - 1 WHERE session_id = $sessionid");
	
	if ($join && update){
		$alertType = "success";
		$errMSG = "Successfully unjoined.";
	}
	else {
		$alertType = "danger";
		$errMSG = "Failed to unjoin this session.";
	}	
}

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
		   $alertType = "danger";
		   $errMSG = "Incorrect Credentials for logging in, please try again...";
	   }
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Joining Session - ROUTE</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

	<script src="https://use.fontawesome.com/3c53deecc4.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Catamaran" rel="stylesheet">

	<link rel="stylesheet" href="css/joinsessionslist.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>
	function show1(){
	document.getElementById('#personal').style.display ='block';
	document.getElementById('#group').style.display ='none';
	}
	function show2(){
	  document.getElementById('#personal').style.display ='none';
	  document.getElementById('#group').style.display ='block';
	}
	</script> 
  
</head>

<body>
	<div class="container-jumbo">
	
		<nav class="nav navbar-default"><!-- Navigation bar -->
			<div class="container">
				<ul class="nav navbar-nav navbar-left"> 
					<li><a href="index.php" class="navbar-brand" id="#top"><img class="img-responsive" src="images/routeW.png"></a></li>
					<li><a href="index.php"><button class="btn navbar-btn"><strong>Home</strong></button></a></li>
					<li><a href="about.php"><button class="btn navbar-btn"><strong>About</strong></button></a></li>		
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
		</nav><!-- End of nav bar -->
		
		<div class="container header-container">
			<div class="container main-header">
				<p class="header">Join a session.</p>
				<p class="title">Pick from the many available sessions the trainers have provided for you.</p>
			</div>
		</div>
		
	</div>
	
	<div class="container-fluid info">
		<div class="container info-container">
			<?php if (isset($errMSG)) { ?>
					<div class="container fail-login">
						<div class="alert alert-<?php echo $alertType; ?> text-center">
							<p><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<?php echo $errMSG; ?></p>
						</div>
					</div> <?php } ?>
		</div>

		<div class="container sessions-container text-center">
			<div class="row">	
				<div class="col-lg-12">
					<p class="big">Pick a session category</p>
					<div class="session-selector">
						<input id="personal" type="radio" name="session-selector" value="personal" onclick="show1();" checked/>
						<label class="picker personal" for="personal"  ></label>
						
						<input id="group" type="radio" name="session-selector" value="group" onclick="show2();" />
						<label class="picker group" for="group"  ></label>
					</div>
				</div>				
			</div>
			
			<div id="#personal" class="row personal">
				<hr>
				<p class="big">Choose which personal session to join</p>
				<?php $personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
				from session s, personal_session p where category='personal' AND p.session_id = s.session_id";
				if ($result = mysqli_query($mysqli, $personal_query)) {
					while ($row = mysqli_fetch_row($result)){ ?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li class="title"><strong>Session Info</strong> </li>
											<li><strong><?php echo ucfirst($row[2]); ?></strong> </li>
											<li><strong>Status: </strong><?php echo $row[6]; ?></li>
											<li><strong>Date: </strong><?php echo $row[3]; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											<li><strong>Notes: </strong><?php echo $row[9]; ?></li>
										</ul>
									</div>
									<div class="col-lg-6">
										<ul>
											<li><strong class="title">Trainer Info</strong></li>
											<li><strong>Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong>Not Yet</li>		
										</ul>
										<form id="join-personal" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
											<input name="id" value="<?php echo $row[0]; ?>" class="hidden"/>
											
											<?php $userid = $userRow['user_id'];
												if ($row[6] == "Available") { ?>
												<button type="submit" name="join-personal" id="join-personal" class="btn btn-join pull-right">Join</button> 
											<?php } elseif ($row[6] == "Unavailable") {
														if ($row[10] == $userid) { ?>
															<button type="submit" name="unjoin-personal" id="unjoin-personal" class="btn btn-un pull-right">Unjoin</button>
											<?php }     else { ?>
															<button type="submit" name="join-personal" id="join-personal" class="btn btn-un pull-right" disabled>Unavailable</button> 
											<?php }} ?>
										</form>
									</div>
								</div>
							</div>
						</div>
				<?php }}
				 ?>
			</div>
			
			
			<div id="#group" class="row group">
				<hr>
				<p class="big">Choose which group session to join</p>
				<?php $group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
				from session s, group_session g WHERE category='group' AND g.session_id = s.session_id";
				if ($result = mysqli_query($mysqli, $group_query)) {
					while ($row = mysqli_fetch_row($result)){ 
						?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li><strong>Session Info</strong></li>
											<li><strong><?php echo ucfirst($row[2]); ?></strong> </li>
											<li><strong>Joined (current/max): </strong><?php echo $row[11]; ?> / <?php echo$row[10]; ?></li>
												<li><strong>Type: </strong><?php echo $row[9]; ?></li>
											<li><strong>Date: </strong><?php echo $row[3]; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											
										</ul>
									</div>
									<div class="col-lg-6">
										<ul>
											<li><strong>Trainer Info</strong></li>
											<li><strong>Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong>Not Yet</li>
										</ul>
										<form id="join-group" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
											<input name="id" value="<?php echo $row[0]; ?>" class="hidden"/>
											<?php $userid = $userRow['user_id'];
											$checkjoin = mysqli_query($mysqli, "SELECT j.session_id, member_id FROM joined_group j, group_session g WHERE j.session_id = '$row[0]' AND member_id = '$userid'");
											$checkjoin = mysqli_fetch_row($checkjoin);	
												if ($row[11] < $row[10]) {
													if ($checkjoin > 0 ) {?>
														<button type="submit" name="unjoin-group" id="unjoin-group" class="btn btn-un pull-right">Unjoin</button> 
													<?php } else { ?>
														<button type="submit" name="join-group" id="join-group" class="btn btn-join pull-right">Join</button> 		
										<?php }}elseif ($row[11] == $row[10] && $checkjoin > 0) { ?>
														<button type="submit" name="unjoin-group" id="unjoin-group" class="btn btn-un pull-right">Unjoin</button> 
										<?php } else { ?>
													<button type="submit" name="join-group" id="join-group" class="btn btn-un pull-right" disabled>Unavailable</button> 
											<?php } ?>
										</form>
									</div>
								</div>
							</div>
						</div>
				<?php }}
				 ?>
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
					<a href="#">About</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>