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

if ( isset($_GET['success']) && $_GET['success'] == 1) {
		$alertType = "success";
		$errMSG = "Successfully updated session info.";
	}
 
$error = false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>View Session History - ROUTE</title>
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
				<a href="viewhistory.php"><div class="col-lg-3 info-box ">
					<strong><?php $kind = $userRow['user_kind'];
							if ($kind == "member") { ?>
							VIEWING COMPLETED SESSIONS
							<?php } else { ?>
							VIEWING CREATED SESSIONS
							<?php } ?>
				</strong>
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
				<?php $userkind = $userRow['user_kind'];
				if ($userkind == "member") {
					$personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
				from session s, personal_session p where category='personal' AND status='Completed' AND p.session_id = s.session_id
				AND member_id=".$_SESSION['user']; " ORDER BY date";
				} elseif ($userkind == "trainer") {
					$personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
				from session s, personal_session p where category='personal' AND p.session_id = s.session_id AND NOT status='Completed' AND NOT status='Cancelled'
				AND trainer_id=".$_SESSION['user']; " ORDER BY date";					
				}
				if ($result = mysqli_query($mysqli, $personal_query)) {
					while ($row = mysqli_fetch_row($result)){ ?>
						<div class="col-lg-6">
							<div class="panel panel-default session-panel">
								<div class="panel-body">
									
									<div class="col-lg-6 <?php if ($userkind == "member")  { ?> border-right <?php } else {} ?>">
										<ul>
											<li><strong><p><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Status: </strong><?php echo $row[6]; ?></li>
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
										
										if ($userkind == "member") {?>
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($traineraverage >= 3.5) { echo ' btn-green'; }
																						elseif ($traineraverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($traineraverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $traineraverage; echo '</button>' ?></small></li>		
										</ul>
										<?php } else { ?>
										<ul>
											<li>&nbsp;</li>
											<li>&nbsp;</li>
										</ul>
										<?php } 
										$checkalreadyreviewed = mysqli_query($mysqli, "SELECT COUNT(*) AS reviewed FROM review WHERE reviewer_id='$row[10]' AND session_id='$row[0]'");
										$checkalreadyreviewed = mysqli_fetch_array($checkalreadyreviewed);
										$checkalreadyreviewed = $checkalreadyreviewed['reviewed'];
										?>
											
											<?php if ($userkind == "member") {
													if ($checkalreadyreviewed > 0) { ?> <button name="review" id="review" class="btn btn-un pull-right" disabled>Already Reviewed</button> <?php }
													else {
														if ($row[6] == "Available") { ?>
															<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button> 
												<?php } elseif ($row[6] == "Unavailable") { ?>
															<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button>
												<?php } elseif ($row[6] == "Completed") { ?>
															<a href="review.php?id=<?php echo $row[0]; ?>"><button name="review" id="review" class="btn btn-join pull-right">Review</button></a>
												<?php } elseif ($row[6] == "Cancelled") { ?>
														<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button>
												<?php }}} elseif ($userkind == "trainer") {
															if ($row[6] == "Available") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php		} elseif ($row[6] == "Unavailable") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php 		} elseif ($row[6] == "Completed") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php 		} elseif ($row[6] == "Cancelled") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php }} ?>											
									</div>
								</div>
							</div>
						</div>
				<?php }}
				 ?>

			</div>
			
			
			<div id="#group" class="row group">
				<hr>
				<?php $userkind = $userRow['user_kind'];
				if ($userkind == "member") {
					$group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
					from session s, group_session g, joined_group j WHERE category='group' AND status='Completed' AND g.session_id = s.session_id 
					AND j.session_id = s.session_id AND j.member_id=".$_SESSION['user']; " ORDER BY date";
				} elseif ($userkind == "trainer") {
					$group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
					from session s, group_session g WHERE category='group' AND g.session_id = s.session_id  AND NOT status='Completed' AND NOT status='Cancelled'
					AND s.trainer_id=".$_SESSION['user']; " ORDER BY date";
				}
				if ($result = mysqli_query($mysqli, $group_query)) {
					while ($row = mysqli_fetch_row($result)){ 
						?>
						<div class="col-lg-6">
							<div class="panel panel-default session-panel">
								<div class="panel-body">
									<div class="col-lg-6 <?php if ($userkind == "member")  { ?> border-right <?php } else {} ?>">
										<ul>
											<li><strong><p class="title"><?php echo ucfirst($row[2]); ?></p></strong> </li>
											<li><strong>Status: </strong><?php echo $row[6]; ?></li>
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
										
										if ($userkind == "member") {?>
										<ul>
											<li><strong>Trainer Name: </strong><?php echo ucwords($row[8]); ?> </li>
											<li><strong>Average Rating: </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($traineraverage >= 3.5) { echo ' btn-green'; }
																						elseif ($traineraverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($traineraverage >= 0) { echo ' btn-red'; }
																						echo ' num">'; echo $traineraverage; echo '</button>' ?></small></li>
										</ul>
										<?php } else { ?>
										<ul>
											<li>&nbsp;</li>
											<li>&nbsp;</li>
											<li>&nbsp;</li>
										</ul>
										<?php } ?>
						
											<?php $userid = $userRow['user_id'];
											$checkjoin = mysqli_query($mysqli, "SELECT j.session_id, member_id FROM joined_group j, group_session g WHERE j.session_id = '$row[0]' AND member_id = '$userid'");
											$checkjoin = mysqli_fetch_row($checkjoin);	
											$checkalreadyreviewed = mysqli_query($mysqli, "SELECT COUNT(*) AS reviewed FROM review WHERE reviewer_id='$userid' AND session_id='$row[0]'");
											$checkalreadyreviewed = mysqli_fetch_array($checkalreadyreviewed);
											$checkalreadyreviewed = $checkalreadyreviewed['reviewed'];
																						
											if ($userkind == "member") {
												if ($checkalreadyreviewed > 0) { ?> <button name="review" id="review" class="btn btn-un pull-right" disabled>Already Reviewed</button> <?php } 
												else { 
													if ($row[6] == "Available") { 
														if ($row[11] < $row[10]) {
															if ($checkjoin > 0 ) {?>
																<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button> 
												<?php 		} else { ?>
																<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button> 		
												<?php 	}} elseif ($row[11] == $row[10] && $checkjoin > 0) { ?>
																<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button> 
												<?php 		} else { ?>
																<button name="review" id="review" class="btn btn-un pull-right" disabled>Review</button> 
												<?php }} elseif ($row[6] == "Completed") { ?>
																<a href="review.php?id=<?php echo $row[0]; ?>"><button name="review" id="review" class="btn btn-join pull-right">Review</button></a> 	
												<?php }}} elseif ($userkind == "trainer") {
															if ($row[6] == "Available") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php		} elseif ($row[6] == "Unavailable") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php 		} elseif ($row[6] == "Completed") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php 		} elseif ($row[6] == "Cancelled") { ?>
																<a href="updatesession.php?id=<?php echo $row[0]; ?>"><button name="update" id="update" class="btn btn-join pull-right">Update</button></a>
												<?php }}
											?>
									</div>
								</div>
							</div>
						</div>
					<?php }}
				 ?>
			</div>
			
			<div class="row">
				<?php $kind = $userRow['user_kind'];
				if ($kind == "trainer") {?>
				<hr>
				<div class="col-lg-12">
					<ul class="nav	nav-tabs nav-justified">
					  <li class="active"><a data-toggle="tab" href="#completed">Completed Sessions</a></li>
					  <li><a data-toggle="tab" href="#cancelled">Cancelled Sessions</a></li>
					</ul>
					<br>
				</div>
				<div class="col-lg-12">	
					<div class="tab-content">
						<div id="completed" class="tab-pane fade in active">
							<div class="row">
								<div class="col-lg-6">
									<p class="ctitle">PERSONAL</p>
									<?php $i=0; $personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
									from session s, personal_session p where status='Completed' AND category='personal' AND p.session_id = s.session_id
									AND trainer_id=".$_SESSION['user']; " ORDER BY date";					
									
									if ($result = mysqli_query($mysqli, $personal_query)) {
										while ($row = mysqli_fetch_row($result)){ $i++ ?>
											<div class="col-lg-12">
												<div class="panel panel-default complete-panel">
													<div class="panel-heading" id="acc_headingp<?php echo $i?>">
														<a data-toggle="collapse" href="#panelcontentp<?php echo $i?>" class="panel-title" aria-expanded="false">
															<?php echo $row[2]; ?>
															<span class="pull-right">
																<span class="date"><?php $date = date('j M Y',strtotime($row[3])); echo $date; ?></span>
																<i class="fa fa-chevron-right pull-right"></i>
																<i class="fa fa-chevron-down pull-right"></i>
															</span>
														</a>
													</div>
													
													<div id="panelcontentp<?php echo $i?>" class="panel-collapse collapse">		
													
														<div class="panel-body">
															<div class="col-lg-6">
																<ul>
																	<li><strong>Session Title: </strong><?php echo ucfirst($row[2]); ?> </li>
																	<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
																	<li><strong>Time: </strong><?php echo $row[4]; ?></li>
																	<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
																	<li><strong>Notes: </strong><?php echo $row[9]; ?></li>							
																</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
									<?php } } ?>
								</div>
								
								<div class="col-lg-6">
									<p class="ctitle">GROUP</p>
									<?php $i=0; $group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
									from session s, group_session g WHERE status='Completed' AND category='group' AND g.session_id = s.session_id 
									AND s.trainer_id=".$_SESSION['user']; " ORDER BY date";
									
								if ($result = mysqli_query($mysqli, $group_query)) {
									while ($row = mysqli_fetch_row($result)){ $i++; ?>
									<div class="col-lg-12">
										<div class="panel panel-default complete-panel">
											<div class="panel-heading" id="acc_headingg<?php echo $i?>">
												<a data-toggle="collapse" href="#panelcontentg<?php echo $i?>" class="panel-title" aria-expanded="false">
													<?php echo $row[2]; ?>
													<span class="pull-right">
														<span class="date"><?php $date = date('j M Y',strtotime($row[3])); echo $date; ?></span>
														<i class="fa fa-chevron-right pull-right"></i>
														<i class="fa fa-chevron-down pull-right"></i>
													</span>
												</a>
											</div>
											
											<div id="panelcontentg<?php echo $i?>" class="panel-collapse collapse">		
											
												<div class="panel-body">
													<div class="col-lg-6">
														<ul>
															<li><strong>Session title:  </strong><?php echo ucfirst($row[2]); ?></li>
															<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
															<li><strong>Joined (current/max): </strong><?php echo $row[11]; ?> / <?php echo$row[10]; ?></li>
															<li><strong>Type: </strong><?php echo $row[9]; ?></li>
															<li><strong>Time: </strong><?php echo $row[4]; ?></li>
															<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>					
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
							<?php }} ?>
								</div>
							</div>
						</div>
						
						<div id="cancelled" class="tab-pane fade in">
							<div class="row">
								<div class="col-lg-6">
									<p class="ctitle">PERSONAL</p>
									<?php $i=0; $personal_query = "SELECT p.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, notes, member_id 
									from session s, personal_session p where status='Cancelled' AND category='personal' AND p.session_id = s.session_id
									AND trainer_id=".$_SESSION['user']; " ORDER BY date";					
									
									if ($result = mysqli_query($mysqli, $personal_query)) {
										while ($row = mysqli_fetch_row($result)){ $i++ ?>
											<div class="col-lg-12">
												<div class="panel panel-default complete-panel">
													<div class="panel-heading" id="acc_headingpc<?php echo $i?>">
														<a data-toggle="collapse" href="#panelcontentpc<?php echo $i?>" class="panel-title" aria-expanded="false">
															<?php echo $row[2]; ?>
															<span class="pull-right">
																<span class="date"><?php $date = date('j M Y',strtotime($row[3])); echo $date; ?></span>
																<i class="fa fa-chevron-right pull-right"></i>
																<i class="fa fa-chevron-down pull-right"></i>
															</span>
														</a>
													</div>
													
													<div id="panelcontentpc<?php echo $i?>" class="panel-collapse collapse">		
													
														<div class="panel-body">
															<div class="col-lg-6">
																<ul>
																	<li><strong>Session Title: </strong><?php echo ucfirst($row[2]); ?> </li>
																	<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
																	<li><strong>Time: </strong><?php echo $row[4]; ?></li>
																	<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>
																	<li><strong>Notes: </strong><?php echo $row[9]; ?></li>							
																</ul>
															</div>
														</div>
													</div>
												</div>
											</div>
									<?php } } ?>
								</div>
								
								<div class="col-lg-6">
									<p class="ctitle">GROUP</p>
									<?php $i=0; $group_query = "SELECT g.session_id, category, title, date, time, fee, status, trainer_id, trainer_name, type, maxpax, count 
									from session s, group_session g WHERE status='Completed' AND category='group' AND g.session_id = s.session_id 
									AND s.trainer_id=".$_SESSION['user']; " ORDER BY date";
									
								if ($result = mysqli_query($mysqli, $group_query)) {
									while ($row = mysqli_fetch_row($result)){ $i++; ?>
									<div class="col-lg-12">
										<div class="panel panel-default complete-panel">
											<div class="panel-heading" id="acc_headingg<?php echo $i?>">
												<a data-toggle="collapse" href="#panelcontentg<?php echo $i?>" class="panel-title" aria-expanded="false">
													<?php echo $row[2]; ?>
													<span class="pull-right">
														<span class="date"><?php $date = date('j M Y',strtotime($row[3])); echo $date; ?></span>
														<i class="fa fa-chevron-right pull-right"></i>
														<i class="fa fa-chevron-down pull-right"></i>
													</span>
												</a>
											</div>
											
											<div id="panelcontentg<?php echo $i?>" class="panel-collapse collapse">		
											
												<div class="panel-body">
													<div class="col-lg-6">
														<ul>
															<li><strong>Session title:  </strong><?php echo ucfirst($row[2]); ?></li>
															<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
															<li><strong>Joined (current/max): </strong><?php echo $row[11]; ?> / <?php echo$row[10]; ?></li>
															<li><strong>Type: </strong><?php echo $row[9]; ?></li>
															<li><strong>Time: </strong><?php echo $row[4]; ?></li>
															<li><strong>Fee: </strong>RM <?php echo $row[5]; ?></li>					
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
							<?php }} ?>
								</div>
							</div>
						</div>
			
					</div>
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