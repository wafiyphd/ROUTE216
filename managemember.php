<?php 
ob_start();
session_start();
require_once 'dbconnect.php';

if ( isset($_SESSION['user'])!="" ) { 
$res=mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
$userRow=mysqli_fetch_array($res);
} else {
	header("Location: index.php");	
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

	<link rel="stylesheet" href="css/managemember.css">

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
				
					<ul class="nav navbar-nav navbar-right desktop">
						<li class="dropdown ">
							<a href="#" data-toggle="dropdown" class="dropdown-toggle">
								<button class="btn navbar-btn"><span><i class="fa fa-user" aria-hidden="true"></i></span>&nbsp;&nbsp;<strong><?php echo ucwords($userRow['fullname'])?></strong>&nbsp;&nbsp;<b class="caret"></b></button>
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
				</div>
			</nav>
		</div>
		
		<div class="container header-container">
			<div class="container main-header">
				<p class="header">View joined session. &nbsp;<span class="title">These are all the upcoming sessions you've joined.</span></p>
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
				<?php $userid = $userRow['user_id'];
				$personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
				from session s, personal_session p where category='personal' AND p.session_id = s.session_id AND member_id = '$userid' AND status = 'Unavailable' ORDER BY date";
				if ($result = mysqli_query($mysqli, $personal_query)) {
					while ($row = mysqli_fetch_row($result)){ ?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li><strong><p class="title"><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Status: </strong><?php echo $row[6]; ?></li>
											<li><strong>Date: </strong><?php echo $row[3]; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											<li><strong>Notes: </strong><?php echo $row[9]; ?></li>
										</ul>
									</div>
									<div class="col-lg-6">
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong>Not Yet</li>		
										</ul>
										<form id="join-personal" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
											<input name="id" value="<?php echo $row[0]; ?>" class="hidden"/>
												<button type="submit" name="unjoin-personal" id="unjoin-personal" class="btn btn-un pull-right">Unjoin</button>
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
				<?php $userid = $userRow['user_id'];
				$group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count, member_id
				from session s, group_session g, joined_group j WHERE category='group' AND g.session_id = s.session_id AND g.session_id = j.session_id AND j.member_id = '$userid' ORDER BY date";
				if ($result = mysqli_query($mysqli, $group_query)) {
					while ($row = mysqli_fetch_row($result)){ 
						?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li><strong><p class="title"><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Joined (current/max): </strong><?php echo $row[11]; ?> / <?php echo$row[10]; ?></li>
											<li><strong>Type: </strong><?php echo $row[9]; ?></li>
											<li><strong>Date: </strong><?php echo $row[3]; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											
										</ul>
									</div>
									<div class="col-lg-6">
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong>Not Yet</li>
										</ul>
										<form id="join-group" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
											<input name="id" value="<?php echo $row[0]; ?>" class="hidden"/>
												<button type="submit" name="unjoin-group" id="unjoin-group" class="btn btn-un pull-right">Unjoin</button>
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
					<a href="about.php">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="contact.php">Contact</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
	
</body>

</html>