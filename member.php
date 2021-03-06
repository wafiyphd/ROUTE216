<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	if (isset($_SESSION['user'])!="" ) { 
	$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow= mysqli_fetch_array($res);
	} else {
		header("Location: index.php");	
	}
	
	if ($userRow['user_kind'] == 'trainer') {
		header("Location: trainer.php");
	}
	
	$userid = $userRow['user_id'];
	$query = mysqli_query($mysqli, "SELECT * from member WHERE user_id='$userid'");
	$memberRow = mysqli_fetch_array($query);
	
	if ( isset($_GET['success']) && $_GET['success'] == 0) {
		$alertType = "success";
		$errMSG = "Successfully submitted review.";
	}
	elseif (isset($_GET['success']) && $_GET['success'] == 1) {
		$alertType = "danger";
		$errMSG = "Failed to submit review.";
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

	<link rel="stylesheet" href="css/home.css">
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
		
	<div class="container-fluid main-fluid">
	
		<div class="container page-info">
			<div class="row">
				<a href="member.php"><div class="col-lg-3 info-box ">
					<strong>HOME PAGE</strong>
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
		
		<div class= "container content-container">
			
			<div class="row">
				<div class="col-lg-12">
					<div class="panel home-panel">
						<div class="panel-body">
							<div class="row">
								<?php
								$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$userRow[0]'");
								$count = mysqli_num_rows($findimage);
								if($count > 0){
									$findimage = mysqli_fetch_array($findimage);
									$image = $findimage['image_name'];
									$image_src = "images/upload/".$image;
									
									echo "<img class=\"photo\" src=\"$image_src\" width=\"130\" height=\"130\">";
								}
								else {
									echo "<img src=\"images/man.jpg\" class=\"photo img-responsive\" width=\"130\" height=\"130\">";
								}
								?>
								<div class="col-lg-4">
									<p class="name"><?php echo ucwords($userRow['fullname']); ?></p>
									<ul>
										<li><strong>Joined As: </strong><?php echo ucwords($userRow['user_kind']); ?></li>
										<li><strong>Training Level:  </strong><?php echo ucwords($memberRow['level']); ?></li>
										<li><strong>Email Address: </strong><?php echo $userRow['email']; ?></li>
									</ul>
								</div>
								<div class="col-lg-4">
									<?php $reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE reviewer_id = '$userid'");
									$count = mysqli_fetch_array($reviewcount);
									$count = $count['count']; ?>
									<p>&nbsp;</p>
									<ul>
										<li><strong>&nbsp; </strong></li>
										<li><strong>No. of sessions joined:  </strong><?php echo $memberRow['joined']; ?></li>
										<li><strong>No. of reviews submitted:  </strong><?php echo $count; ?> (<a href="allmemberreviews.php">View</a>)</li>
									<ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
			
				<a href="joinsessionslist.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/h1.png"></img><div class="overlay"><div class="moreinfo">Join one or more of the many available sessions the trainers have set up.</div></div>
				</div></a>
				
				<a href="managemember.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/h2.png"></img><div class="overlay"><div class="moreinfo">View and manage a list of all the upcoming sessions you've joined.</div></div>
				</div></a>
							
				<a href="viewhistory.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/h3.png"></img><div class="overlay"></img><div class="moreinfo">View all the sessions you've completed and review the sessions.</div></div>
				</div></a>
				<a href="profile.php"><div class="pic-container col-sm-4 col-lg-3">
					<img src="images/h4.png"></img><div class="overlay"></img><div class="moreinfo">Edit your profile.</div></div>
				</div></a>
			
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<p class="title">UPCOMING TRAINING SESSIONS</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<col width="150">
							<col width="80">
							<col width="50">
							<tr>
							  <th>Sesssion Name</th>
							  <th>Date</th>
							  <th>Category</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<col width="150">
							<col width="90">
							<col width="50">
							<?php $sessions = "SELECT category, title, date, status from session WHERE NOT status = 'Completed' AND NOT status = 'Cancelled' ORDER BY date LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; if ($row[3] == "Available"){ echo '&nbsp;<small><button class="btn btn-static btn-green btn-xs">Available</button></small>';}
													elseif ($row[3] == "Unavailable") {echo '&nbsp;<small><button class="btn btn-static btn-red btn-xs">Taken</button></small>'; } 
													elseif ($row[3] == "Full") {echo '&nbsp;<small><button class="btn btn-static btn-red btn-xs">Full</button></small>'; } ?>
								</td>
								<td><?php $date = date('j F Y',strtotime($row[2])); echo $date; ?></td>
								<td><?php echo ucfirst($row[0]); ?></td>
							</tr>
							<?php }} ?>
						  </tbody>
						</table>
					</div>
				</div>
				
				<div class="col-lg-6">
					<p class="title">POPULAR GROUP SESSIONS</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<col width="150">
							<col width="80">
							<col width="40">
							<tr>
							  <th>Session Name</th>
							  <th>Date</th>
							  <th>Joined</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<col width="150">
							<col width="90">
							<col width="30">
							<?php $sessions = "SELECT category, title, date, status, count, maxpax, count from session s, group_session g WHERE 
							s.session_id = g.session_id AND NOT status = 'Completed' AND NOT status = 'Cancelled' AND category='group' ORDER BY count DESC LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; if ($row[3] == "Available"){ echo '&nbsp;<small><button class="btn btn-static btn-green btn-xs">Available</button></small>';}
													elseif ($row[3] == "Full") {echo '&nbsp;<small><button class="btn btn-static btn-red btn-xs">Full</button></small>'; } ?>
								</td>
								<td><?php $date = date('j F Y',strtotime($row[2])); echo $date; ?></td>
								<td><?php echo $row[4]; ?></td>
							</tr>
							
							<?php }} ?>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-lg-6">
					<p class="title">UPCOMING PERSONAL SESSIONS JOINED</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<tr>
							<col width="150">
							<col width="100">
							<col width="80">
							  <th>Sesssion Name</th>
							  <th>Trainer Name</th>
							  <th>Date</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<col width="150">
							<col width="100">
							<col width="90">
							<?php $userid = $userRow['user_id'];
							$sessions = "SELECT category, title, date, status, member_id, trainer_name, trainer_id from session s, personal_session p 
							WHERE p.session_id = s.session_id  AND category='personal' AND member_id = '$userid' AND NOT status = 'Completed' AND NOT status='Cancelled' ORDER BY date LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								if ((mysqli_num_rows($result)) == 0) { ?>
							<div class="alert alert-box type-danger">
							<p><strong>You have not joined any personal sessions.</strong></p>
							<a href="joinsessionslist.php"><button class="btn btn-alert" style="padding-top: 10px;">Join a session</button></a>
							</div>
							<?php }
							else {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; ?></td>
								<td>
									<?php 
										$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$row[6]'");
										$count = mysqli_num_rows($findimage);
										if($count > 0){
											$findimage = mysqli_fetch_array($findimage);
											$image = $findimage['image_name'];
											$image_src = "images/upload/".$image;
											
											echo "<img class=\"small-photo img-responsive\" src=\"$image_src\" width=\"20\" height=\"20\">";
										}
										else {
											echo "<img src=\"images/man.png\" class=\"small-photo img-responsive\" width=\"20\" height=\"20\">";
										} echo "&nbsp;"; echo ucwords($row[5]); 
									?>
								</td>
								<td><?php $date = date('j F Y',strtotime($row[2])); echo $date; ?></td>
							</tr>
							<?php }}} ?>
						  </tbody>
						</table>
					</div>
				</div>
				
				<div class="col-lg-6">
					<p class="title">UPCOMING GROUP SESSIONS JOINED</p>
					<div class="tbl-header">
						<table cellpadding="0" cellspacing="0" border="0">
						  <thead>
							<col width="150">
							<col width="100">
							<col width="80">
							<tr>
							  <th>Session Name</th>
							  <th>Trainer Name</th>
							  <th>Date</th>
							</tr>
						  </thead>
						</table>
					  </div>
					  <div class="tbl-content">
						<table class="table-hover" cellpadding="0" cellspacing="0" border="0">
						  <tbody>
							<col width="150">
							<col width="100">
							<col width="90">
							<?php $userid = $userRow['user_id'];
							$sessions = "SELECT category, title, date, status, trainer_name, member_id, trainer_id
							from session s, group_session g, joined_group j WHERE category='group' AND g.session_id = s.session_id AND g.session_id = j.session_id AND 
							j.member_id = '$userid' AND NOT status = 'Completed' AND NOT status='Cancelled' ORDER BY date LIMIT 5";
							if ($result = mysqli_query($mysqli, $sessions)) {
								if ((mysqli_num_rows($result)) == 0) { ?>
							<div class="alert alert-box type-danger">
							<p><strong>You have not joined any group sessions.</strong></p>
							<a href="joinsessionslist.php"><button class="btn btn-alert" style="padding-top: 10px;">Join a session</button></a>
							</div>
							<?php }
							else {
								while ($row = mysqli_fetch_row($result)){ ?>
							<tr>
								<td><?php echo $row[1]; ?></td>
								<td>
									<?php 
										$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$row[6]'");
										$count = mysqli_num_rows($findimage);
										if($count > 0){
											$findimage = mysqli_fetch_array($findimage);
											$image = $findimage['image_name'];
											$image_src = "images/upload/".$image;
											
											echo "<img class=\"small-photo img-responsive\" src=\"$image_src\" width=\"20\" height=\"20\">";
										}
										else {
											echo "<img src=\"images/man.png\" class=\"small-photo img-responsive\" width=\"20\" height=\"20\">";
										} echo "&nbsp;"; echo ucwords($row[4]); 
									?>
								</td>
								<td><?php $date = date('j F Y',strtotime($row[2])); echo $date; ?></td>
							</tr>
							
							<?php }}} ?>
						  </tbody>
						</table>
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