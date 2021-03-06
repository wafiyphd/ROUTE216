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

$error = false;

$userid = $userRow['user_id'];
$query = mysqli_query($mysqli, "SELECT * from member WHERE user_id='$userid'");
$memberRow = mysqli_fetch_array($query);


if( isset($_POST['unjoin-personal']) ) {
	
	$userid = $userRow['user_id'];
	$userfullname =$userRow['fullname'];
	$sessionid = $_POST['id'];
	
	$status = mysqli_query($mysqli, "UPDATE session SET status = 'Available' WHERE session_id = '$sessionid' ");
	$unjoin = mysqli_query($mysqli, "	UPDATE personal_session SET member_id = NULL, member_name = NULL WHERE session_id ='$sessionid' ");
	$count = mysqli_query ($mysqli, "UPDATE member SET joined=joined-1 WHERE user_id = '$userid'");
	if ($unjoin && $status && $count){
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
	$group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
				from session s, group_session g WHERE g.session_id = s.session_id AND g.session_id = '$sessionid'";
	
	$join = mysqli_query($mysqli, "	DELETE FROM joined_group WHERE member_id = '$userid' AND session_id ='$sessionid' ");
	$update = mysqli_query($mysqli, "UPDATE group_session SET count = count - 1 WHERE session_id = $sessionid");
	$count = mysqli_query ($mysqli, "UPDATE member SET joined=joined-1 WHERE user_id = '$userid'");
	$groupRow = mysqli_query($mysqli, $group_query);
	$groupRow = mysqli_fetch_row($groupRow);
	$checkmax = $groupRow[10]; $checkcount = $groupRow[11];
	if ($checkmax > $checkcount)
		$full = mysqli_query($mysqli, "UPDATE session SET status = 'Available' WHERE session_id = $sessionid");
	
	if ($join && update && $count && $full){
		$alertType = "success";
		$errMSG = "Successfully unjoined.";
	}
	else {
		$alertType = "danger";
		$errMSG = "Failed to unjoin this session.";
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
	<link href="https://fonts.googleapis.com/css?family=Palanquin" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Droid+Sans+Mono" rel="stylesheet">

	<link rel="stylesheet" href="css/sessions.css">
	<link rel="stylesheet" href="css/alert.css">
	<link rel="stylesheet" href="css/navfooter.css">

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
	
	<div class="container-fluid info">
	
		<div class="container page-info">
			<div class="row">
				<a href="managemember.php"><div class="col-lg-3 info-box ">
					<strong>MANAGING SESSIONS</strong>
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
				from session s, personal_session p where category='personal' AND p.session_id = s.session_id AND member_id = '$userid' AND status = 'Unavailable' AND NOT status = 'Completed' ORDER BY date";
				if ($result = mysqli_query($mysqli, $personal_query)) {
					while ($row = mysqli_fetch_row($result)){ ?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li><strong><p class="title"><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											<li><strong>Notes: </strong><?php echo $row[9]; ?></li>
										</ul>
									</div>
									<div class="col-lg-6">
									<?php $trainerid = $row[7];
									
										$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE trainer_id = '$trainerid'");
										$rcount = mysqli_fetch_array($reviewcount);
										$rcount = $rcount['count']; 
										
										if ($rcount == 0) {
											$traineraverage = "N/A";
										}
										
										else {
											$traineraverage = mysqli_query($mysqli, "SELECT AVG(totalrating) AS average FROM review WHERE trainer_id='$trainerid'");
											$traineraverage = mysqli_fetch_array($traineraverage);
											$traineraverage = $traineraverage['average'];
											$traineraverage = number_format((float)$traineraverage, 2, '.', ''); 
										}
										
									?>
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($traineraverage >= 3.5) { echo ' btn-green'; }
																						elseif ($traineraverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($traineraverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $traineraverage; echo '</button>' ?></small></li>		
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
				 <?php $count = $memberRow['joined']; if ($count == 0) { ?>
				<div class="alert alert-box type-primary alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p>You have not joined any sessions yet.</p> 
				<a href="joinsessionslist.php"><button class="btn btn-alert">Join a Session</button></a>
				</div>
				<?php } ?>
					
			</div>
			
			
			<div id="#group" class="row group">
				<hr>
				<?php $userid = $userRow['user_id'];
				$group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count, member_id
				from session s, group_session g, joined_group j WHERE category='group' AND g.session_id = s.session_id AND g.session_id = j.session_id AND j.member_id = '$userid' 
				AND NOT status = 'Completed' ORDER BY date";
				if ($result = mysqli_query($mysqli, $group_query)) {
					while ($row = mysqli_fetch_row($result)){ 
						?>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="col-lg-6 border-right">
										<ul>
											<li><strong><p class="title"><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
											<li><strong>Joined (current/max): </strong><?php echo $row[11]; ?> / <?php echo$row[10]; ?></li>
											<li><strong>Type: </strong><?php echo $row[9]; ?></li>
											<li><strong>Time: </strong><?php echo $row[4]; ?></li>
											<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
											
										</ul>
									</div>
									<div class="col-lg-6">
										<?php $trainerid = $row[7];
									
										$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE trainer_id = '$trainerid'");
										$rcount = mysqli_fetch_array($reviewcount);
										$rcount = $rcount['count']; 
										
										if ($rcount == 0) {
											$traineraverage = "N/A";
										}
										
										else {
											$traineraverage = mysqli_query($mysqli, "SELECT AVG(totalrating) AS average FROM review WHERE trainer_id='$trainerid'");
											$traineraverage = mysqli_fetch_array($traineraverage);
											$traineraverage = $traineraverage['average'];
											$traineraverage = number_format((float)$traineraverage, 2, '.', ''); 
										}
										
										?>
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($traineraverage >= 3.5) { echo ' btn-green'; }
																						elseif ($traineraverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($traineraverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $traineraverage; echo '</button>' ?></small></li>
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
				<?php $count = $memberRow['joined']; if ($count == 0) { ?>
				<div class="alert alert-box type-primary alert-dismissable">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<p>You have not joined any sessions yet.</p> 
				<a href="joinsessionslist.php"><button class="btn btn-alert">Join a Session</button></a>
				</div>
				<?php } ?>
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