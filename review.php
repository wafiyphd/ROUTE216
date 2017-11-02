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
	
	$userid = $userRow['user_id'];
	
	if ($userRow['user_kind'] == 'trainer') {
		header("Location: trainer.php");
	}
	
	if ( isset($_GET['id']) ) {
		$sessionid = $_GET['id'];
	} else {
		header("Location: viewhistory.php");	
	}
	
	// not allow reviewing based on entering id in URL
	$checkalreadyreviewed = mysqli_query($mysqli, "SELECT COUNT(*) AS reviewed FROM review WHERE reviewer_id='$userid' AND session_id='$sessionid'");
	$checkalreadyreviewed = mysqli_fetch_array($checkalreadyreviewed);
	$checkalreadyreviewed = $checkalreadyreviewed['reviewed'];
	if ($checkalreadyreviewed > 0) { 
		header("Location: index.php");	
	}
	
	$checksession = mysqli_query($mysqli, "SELECT category, status FROM session WHERE session_id='$sessionid'");
	$checksession = mysqli_fetch_row($checksession);
	$category = $checksession[0]; $status = $checksession[1];
	
	if ($status != "Completed") {
		header("Location: viewhistory.php");	
	}
	
	if ($category == "personal") {
		$personal = mysqli_query($mysqli, "SELECT member_id FROM personal_session WHERE session_id='$sessionid'");
		$personal = mysqli_fetch_row($personal);
		$checkid = $personal[0];
		if ($checkid != $userid)
			header("Location: viewhistory.php");	
	}
	
	elseif ($category == "group") {
		$checkjoin = mysqli_query($mysqli, "SELECT j.session_id, member_id FROM joined_group j, group_session g WHERE j.session_id = '$sessionid' AND member_id = '$userid'");
		$checkjoin = mysqli_fetch_row($checkjoin);
		if ($checkjoin == 0)
			header("Location: viewhistory.php");
	}
	
	if ( isset($_GET['danger']) && $_GET['danger'] == 1) {
		$alertType = "danger";
		$errMSG = "Please enter a rating for all criterias."; 
	}
	
	if( isset($_POST['review']) ) {
		
		$sessionid = $_POST['sessionid'];
	 
		if (!isset($_POST['prating']) || !isset($_POST['erating']) || !isset($_POST['srating'])) {
			 header('Location: review.php?danger=1&id='.$sessionid);
		}
		
		else {
			
			$sessionid = $_POST['sessionid'];
			
			$query = mysqli_query($mysqli, "SELECT trainer_id, trainer_name from session where session_id = '$sessionid'");
			$sessionRow = mysqli_fetch_row($query);
			
			$reviewerid = $userRow['user_id'];
			$reviewername = $userRow['fullname'];
			$trainerid = $sessionRow[0];
			$trainername = $sessionRow[1];
			
			$prating = $_POST['prating'];
			$erating = $_POST['erating'];
			$srating = $_POST['srating'];
			
			$avg = ($prating + $erating + $srating) / 3;

			$comments = $_POST['comments'];
			$comments =  mysqli_real_escape_string($mysqli, $comments);
			
			$query = "INSERT INTO review(reviewer_id, reviewer_name, trainer_id, trainer_name, session_id, profrat, engrat, sesrat, totalrating, comments) 
						VALUES('$reviewerid','$reviewername','$trainerid','$trainername','$sessionid','$prating','$erating','$srating','$avg','$comments')";
			$res = mysqli_query($mysqli, $query);
			
		    if ($res) {
			 $errType = "success";
			 $errMSG = "Successfully submitted review";
			 header("Location: member.php?success=0");
			 unset($reviewerid);unset($reviewername);unset($trainerid);unset($trainername);unset($sessionid);unset($prating);unset($erating);unset($srating);unset($avg);unset($comments);
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
	
	<title>Review - ROUTE</title>
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

	<link rel="stylesheet" href="css/review.css">
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
		
	<div class="container-fluid content-container">
		
		<div class="container page-info">
			<div class="row">
				<a href="review.php"><div class="col-lg-3 info-box ">
					<strong>REVIEWING TRAINER</strong>
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
		
		<div class="container review-container">
		<?php $session = mysqli_query($mysqli, "SELECT * from session WHERE session_id = '$sessionid'");
		$row = mysqli_fetch_row($session); 
		if ($row[1] == "personal"){
			$personal_query = mysqli_query($mysqli, "SELECT notes from personal_session where session_id='$sessionid'");
			$personalRow = mysqli_fetch_row($personal_query);
		}
		else {
			$group_query = mysqli_query($mysqli, "SELECT type, maxpax, count from group_session where session_id='$sessionid'");
			$groupRow = mysqli_fetch_row($group_query);
		}
		
		?>
			<div class="row">
				<div class="col-lg-6">
					<div class="review-wrap text-center">
						<div class="review-form">
						
							<div class="row text-center">
								
								<form id="#rating" class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
									
									<table class="rating-table noborder"><col width=150><col width=180><col width=80>
										<tr>
											<td style="vertical-align: middle;"><span class="inline pull-left">Professionalism Rating:</span></td>
											<td>
													<input class="star star-5" id="pstar-5" type="radio" name="prating" value=5 onclick="pvalue(); overall();"></input>
													<label class="star star-5" for="pstar-5"></label>
													<input class="star star-4" id="pstar-4" type="radio" name="prating" value=4 onclick="pvalue(); overall();"></input>
													<label class="star star-4" for="pstar-4"></label>
													<input class="star star-3" id="pstar-3" type="radio" name="prating" value=3 onclick="pvalue(); overall();"></input>
													<label class="star star-3" for="pstar-3"></label>
													<input class="star star-2" id="pstar-2" type="radio" name="prating" value=2 onclick="pvalue(); overall();"></input>
													<label class="star star-2" for="pstar-2"></label>
													<input class="star star-1" id="pstar-1" type="radio" name="prating" value=1 onclick="pvalue(); overall();"></input>
													<label class="star star-1" for="pstar-1"></label>
												
											</td>
											<script>
											function pvalue() {
												var prating = document.getElementsByName("prating");
												var pselected;

												for(var i = 0; i < prating.length; i++) {
													if(prating[i].checked)
														pselected = prating[i].value;
												}
												
												var btncolor;
												if (pselected >= 4)
													btncolor = "btn-green";
												else if (pselected >= 3)
													btncolor = "btn-yellow";
												else if (pselected >= 1)
													btncolor = "btn-red";
												document.getElementById("prating").innerHTML = "<small><button onclick=\"return false;\" class=\"btn "+btncolor+" btn-static btn-xs btn-review num\">"+pselected+".0</button></small>";
											}
											</script>
											<td><span id="prating" class="inline pull-right"><small><button onclick="return false;" class="btn btn-red btn-static btn-xs btn-review num">N/A</button></small></span></td>
										</tr>

										<tr>
											<td><span class="inline pull-left">Engagement Rating:</span></td>
											<td>
													<input class="star star-5" id="estar-5" type="radio" name="erating" value=5 onclick="evalue(); overall();"></input>
													<label class="star star-5" for="estar-5"></label>
													<input class="star star-4" id="estar-4" type="radio" name="erating" value=4 onclick="evalue(); overall();"></input>
													<label class="star star-4" for="estar-4"></label>
													<input class="star star-3" id="estar-3" type="radio" name="erating" value=3 onclick="evalue(); overall();"></input>
													<label class="star star-3" for="estar-3"></label>
													<input class="star star-2" id="estar-2" type="radio" name="erating" value=2 onclick="evalue(); overall();"></input>
													<label class="star star-2" for="estar-2"></label>
													<input class="star star-1" id="estar-1" type="radio" name="erating" value=1 onclick="evalue(); overall();"></input>
													<label class="star star-1" for="estar-1"></label>
												
											</td>
												<script>
												function evalue() {
													var erating = document.getElementsByName("erating");
													var eselected;
													
													for(var i = 0; i < erating.length; i++) {
														if(erating[i].checked)
															eselected = erating[i].value;
													}
													
													var btncolor;
													if (eselected >= 4)
														btncolor = "btn-green";
													else if (eselected >= 3)
														btncolor = "btn-yellow";
													else if (eselected >= 1)
														btncolor = "btn-red";
													document.getElementById("erating").innerHTML = "<small><button onclick=\"return false;\" class=\"btn "+btncolor+" btn-static btn-xs btn-review num\">"+eselected+".0</button></small>";
												}
												</script>
											<td><span id="erating" class="inline pull-right"><small><button onclick="return false;" class="btn btn-red btn-static btn-xs btn-review num">N/A</button></small></span></td>
										</tr>

										<tr>
											<td><span class="inline pull-left">Session Rating:</span></td>
											<td>
													<input class="star star-5" id="sstar-5" type="radio" name="srating" value=5 onclick="svalue(); overall();"></input>
													<label class="star star-5" for="sstar-5"></label>
													<input class="star star-4" id="sstar-4" type="radio" name="srating" value=4 onclick="svalue(); overall();"></input>
													<label class="star star-4" for="sstar-4"></label>
													<input class="star star-3" id="sstar-3" type="radio" name="srating" value=3 onclick="svalue(); overall();"></input>
													<label class="star star-3" for="sstar-3"></label>
													<input class="star star-2" id="sstar-2" type="radio" name="srating" value=2 onclick="svalue(); overall();"></input>
													<label class="star star-2" for="sstar-2"></label>
													<input class="star star-1" id="sstar-1" type="radio" name="srating" value=1 onclick="svalue(); overall();"></input>
													<label class="star star-1" for="sstar-1"></label>
												
											</td>
											<script>
											function svalue() {
												var srating = document.getElementsByName("srating");
												var sselected; 
												
												for(var i = 0; i < srating.length; i++) {
													if(srating[i].checked)
														sselected = srating[i].value;
												}
												var btncolor;
												if (sselected >= 4)
													btncolor = "btn-green";
												else if (sselected >= 3)
													btncolor = "btn-yellow";
												else if (sselected >= 1)
													btncolor = "btn-red";
												document.getElementById("srating").innerHTML = "<small><button onclick=\"return false;\" class=\"btn "+btncolor+" btn-static btn-xs btn-review num\">"+sselected+".0</button></small>";
											}
											</script>
											<td><span id="srating" class="inline pull-right"><small><button onclick="return false;" class="btn btn-red btn-static btn-xs btn-review num">N/A</button></small></span></td>
										</tr>
										<tr class="hr"><td></td><td></td><td></td></tr>
										<tr class="average">
											<td><span class="inline pull-left">Overall Average Rating:</span></td>
											<td></td>
											<td>
											<div id="average" class="inline pull-right"><small><button onclick="return false;" class="btn btn-red btn-static btn-xs btn-review num">N/A</button></small></div>
											</td>
											<script>
											function overall() {
												var prating = document.getElementsByName("prating");
												var pselected;

												for(var i = 0; i < prating.length; i++) {
													if(prating[i].checked)
														pselected = parseInt(prating[i].value);
												}
												
												var erating = document.getElementsByName("erating");
												var eselected;
												
												for(var i = 0; i < erating.length; i++) {
													if(erating[i].checked)
														eselected = parseInt(erating[i].value);
												}
												
												var srating = document.getElementsByName("srating");
												var sselected; 
												
												for(var i = 0; i < srating.length; i++) {
													if(srating[i].checked)
														sselected = parseInt(srating[i].value);
												}
												
												var total = pselected + eselected + sselected;
												var average = parseFloat(total / 3.0).toFixed(2);
												var btncolor;
												if (average >= 3.75)
													btncolor = "btn-green";
												else if (average >= 2.8)
													btncolor = "btn-yellow";
												else if (average >= 0)
													btncolor = "btn-red";
												
												if (pselected > 0 && eselected > 0 && sselected > 0) {
													document.getElementById("average").innerHTML = "<small><button onclick=\"return false;\" class=\"btn "+btncolor+" btn-static btn-xs btn-review num\">"+average+"</button></small>";
												}
												
											}
											</script>
										</tr>
									</table>
									<br><br>
									<div class="group">
										<label for="comments" class="label">Comments</label>
										<textarea id="comments"  type="text" name="comments" rows="8" class="input-text"></textarea>
									</div>
									
									<div class="group">
										<input name="sessionid" class="hidden" value="<?php echo $row[0]; ?>"></input>
									</div>
									
									<div class="group">
										<input type="submit" name="review" class="button" value="Submit"></input>
									</div>
									
								</form>		
							</div>
						</div>	
						
					</div>
				</div>
				
				<div class="col-lg-6">
					<div class="row">
						<div class="col-lg-12 col-sm-12 session-info">
							<div class="info-wrap">
								<div class="info-content">
									<p class="big">Session Info </p>
									<hr>
									<div class="row">
										<div class="col-lg-6">
											<ul class="session">
												<li><strong>Session Name: </strong><?php echo ucfirst($row[2]); ?></li>
												<li><strong>Date: </strong><?php $date = date('j F Y',strtotime($row[3])); echo $date; ?></li>
												<li><strong>Fee: </strong>RM <?php echo ucfirst($row[5]); ?></li>
											</ul>
										</div>
										<div class="col-lg-6">
											<ul class="session">
												<li><strong>Category: </strong><?php echo ucfirst($row[1]); ?></li>
												<?php if ($row[1] == "personal"){ ?>
												<li><strong>Notes: </strong><?php echo $personalRow[0]; ?></li>
												<?php } else {?>
												<li><strong>Session Type: </strong><?php echo $groupRow[0]; ?></li>
												<li><strong>Max participants: </strong><?php echo $groupRow[1]; ?></li>
												<li><strong>Joined: </strong><?php echo $groupRow[2]; ?></li>
												<?php } ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 col-sm-12 session-info">
							<div class="info-wrap">
								<div class="info-content">
									<p class="big">Trainer Info </p>
									<hr>
									<div class="row">
										<div class="col-lg-12">
										<?php $trainerid = $row[7];
										$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE trainer_id = '$trainerid'");
										$rcount = mysqli_fetch_array($reviewcount);
										$rcount = $rcount['count']; 
										
										$reviewquery = mysqli_query($mysqli, "SELECT trainer_id, profrat, engrat, sesrat, totalrating from review WHERE trainer_id='$trainerid'");
										if ($rcount == 0) {
											$selfaverage = "N/A";
											$paverage = "N/A";
											$eaverage = "N/A";
											$saverage = "N/A";
										}

										else {
											
										$paverage = mysqli_query($mysqli, "SELECT AVG(profrat) AS average FROM review WHERE trainer_id='$trainerid'");
										$paverage = mysqli_fetch_array($paverage);
										$paverage = $paverage['average'];
										$paverage = number_format((float)$paverage, 2, '.', '');
										
										$eaverage = mysqli_query($mysqli, "SELECT AVG(engrat) AS average FROM review WHERE trainer_id='$trainerid'");
										$eaverage = mysqli_fetch_array($eaverage);
										$eaverage = $eaverage['average'];
										$eaverage = number_format((float)$eaverage, 2, '.', '');
										
										$saverage = mysqli_query($mysqli, "SELECT AVG(sesrat) AS average FROM review WHERE trainer_id='$trainerid'");
										$saverage = mysqli_fetch_array($saverage);
										$saverage = $saverage['average'];
										$saverage = number_format((float)$saverage, 2, '.', '');
										
										$selfaverage = mysqli_query($mysqli, "SELECT AVG(totalrating) AS average FROM review WHERE trainer_id='$trainerid'");
										$selfaverage = mysqli_fetch_array($selfaverage);
										$selfaverage = $selfaverage['average'];
										$selfaverage = number_format((float)$selfaverage, 2, '.', ''); }
										
										$sessioncount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM session WHERE trainer_id = '$trainerid'");
										$scount = mysqli_fetch_array($sessioncount);
										$scount = $scount['count'];
										?>
											<?php $findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$trainerid'");
											$count = mysqli_num_rows($findimage);
											if($count > 0){
												$findimage = mysqli_fetch_array($findimage);
												$image = $findimage['image_name'];
												$image_src = "images/upload/".$image;
												
												echo "<img class=\"small-photo img-responsive\" src=\"$image_src\" width=\"120\" height=\"120\">";
											}
											else {
												echo "<img src=\"images/man.jpg\" class=\"small-photo img-responsive\" width=\"120\" height=\"120\">";
											}
											?>
											<ul class="trainer">
												<li><strong>Trainer Name: </strong><?php echo ucfirst($row[8]); ?></li>
												<li><strong>Total Sessions Managed: </strong><?php echo $scount; ?></li>
												<li>&nbsp;</li>
												<table class="noborder">
												<col width="180">
												<col width="80">
												<tr><td><strong>Overall average rating:</strong></td><td>
																									<small><?php echo '<button class="btn btn-static btn-xs '; 
																										if ($selfaverage >= 3.5) { echo ' btn-green'; }
																										elseif ($selfaverage >=2.5) { echo ' btn-yellow'; }
																										elseif ($selfaverage >= 0) { echo ' btn-red'; }
																										echo ' num">'; echo $selfaverage; echo '</button>' ?></small></td></tr>
												<tr><td><strong>Average Professionalism:</strong></td><td> <small><?php echo '<button class="btn btn-static btn-xs '; 
																										if ($paverage >= 3.5) { echo ' btn-green'; }
																										elseif ($paverage >=2.5) { echo ' btn-yellow'; }
																										elseif ($paverage >= 0) { echo ' btn-red'; }
																										echo ' num">'; echo $paverage; echo '</button>' ?></small></td></tr>
												<tr><td><strong>Average Engagement:</strong></td><td>
																								<small><?php echo '<button class="btn btn-static btn-xs '; 
																										if ($saverage >= 3.5) { echo ' btn-green'; }
																										elseif ($saverage >=2.5) { echo ' btn-yellow'; }
																										elseif ($saverage >= 0) { echo ' btn-red'; }
																										echo ' num">'; echo $saverage; echo '</button>' ?></small></td></tr>
												<tr><td><strong>Average Session:</strong></td><td>
																								<small><?php echo '<button class="btn btn-static btn-xs '; 
																										if ($eaverage >= 3.5) { echo ' btn-green'; }
																										elseif ($eaverage >=2.5) { echo ' btn-yellow'; }
																										elseif ($eaverage >= 0) { echo ' btn-red'; }
																										echo ' num">'; echo $eaverage; echo '</button>' ?></small></td></tr>
												</table>
											</ul>
										</div>
									</div>
								</div>
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