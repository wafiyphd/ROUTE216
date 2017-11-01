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
	
	$id = $userRow['user_id'];
	$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE reviewer_id = '$id'");
	$count = mysqli_fetch_array($reviewcount);
	$count = $count['count'];
	
	// how long ago function
	function time_elapsed_string($datetime, $full = false) {
	date_default_timezone_set('Asia/Singapore');
	$now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>All My Reviews - ROUTE</title>
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

	<link rel="stylesheet" href="css/allreviews.css">
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
				<a href="allreviews.php"><div class="col-lg-3 info-box ">
					<strong>VIEWING ALL REVIEWS</strong>
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
			<div class="row">
			<div class="col-lg-3" >
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<ul class="review">
								<?php
								$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$id'");
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
								 
								$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE reviewer_id = '$id'");
								$count = mysqli_fetch_array($reviewcount);
								$count = $count['count'];
								?>
								<li><strong>Total reviews written: </strong><?php echo $count; ?></li>
							</ul>
						</div>
					</div>
				</div>
				<a href="#" class="btn btn-review openall">Expand All</a> <a href="#" class="btn btn-review closeall">Collapse All</a>
				<script>
					$('.closeall').click(function(){
					  $('.panel-collapse.in')
						.collapse('hide');
					});
					$('.openall').click(function(){
					  $('.panel-collapse:not(".in")')
						.collapse('show');
					});
				</script>
				
			<?php $i=0; $reviews = "SELECT reviewer_name, r.trainer_id, r.session_id, title, timestamp, profrat, engrat, sesrat, totalrating, comments, date, category, reviewer_id, r.trainer_name from review r, session s
								WHERE r.session_id = s.session_id AND reviewer_id = '$id' ORDER BY timestamp DESC";
				if ($result = mysqli_query($mysqli, $reviews)) {
					while ($row = mysqli_fetch_row($result)){ $i++;?>
						<div class="col-xs-12 col-lg-9 pull-right">
							
							<div class="row">
								<div class="panel panel-default">
									<div class="panel-heading" id="acc_heading<?php echo $i?>">
										<a data-toggle="collapse" href="#panelcontent<?php echo $i?>" class="panel-title" aria-expanded="false">
											
											<strong><span class="title">
											<?php echo $row[3]; ?></span></strong><small>&nbsp;&nbsp;
											<?php echo '<button class="btn btn-static btn-xs '; 
												if ($row[8] >= 3.5) { echo ' btn-green'; }
												elseif ($row[8] >=2.5) { echo ' btn-yellow'; }
												elseif ($row[8] >= 0) { echo ' btn-red'; }
												echo ' num">'; echo $row[8]; echo '</button></small>'; ?>
											<span class="pull-right">
												<span class="date"><?php echo time_elapsed_string($row[4]) ?></span>
												<i class="fa fa-chevron-right pull-right"></i>
												<i class="fa fa-chevron-down pull-right"></i>
											</span>
										</a>
									</div>
									
									<div id="panelcontent<?php echo $i?>" class="panel-collapse collapse">		
									
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-12 text-center">
													<?php
													$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$row[1]'");
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
													?><br>
													<strong>Trainer Name: </strong><?php echo $row[13] ?>
												</div>
											</div>
											<br>
											<div class="col-lg-6">
												<ul class="review">
													<li><strong>Session Name: </strong><?php echo $row[3]; ?></li>
													<li><strong>Session Date: </strong><?php $date = date('j F Y',strtotime($row[10])); echo $date; ?></li>
													<li><strong>Category: </strong><?php echo ucfirst($row[11]); ?></li>					
												</ul>
											</div>
										
											<div class="col-lg-6 ">
												<ul class="review">	
													<?php $prating = $row[5];
													$prating = number_format((float)$prating, 1, '.', '');
													$erating = $row[6];
													$erating = number_format((float)$erating, 1, '.', '');
													$srating = $row[7];
													$srating = number_format((float)$prating, 1, '.', ''); ?>
													<table class="noborder">
													<col width="125">
													<col width="80">
													<tr><td><strong>Professional Rating:</td><td> </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																							if ($row[5] >= 3.5) { echo ' btn-green'; }
																							elseif ($row[5] >=2.5) { echo ' btn-yellow'; }
																							elseif ($row[5] >= 0) { echo ' btn-red'; }
																							echo ' num">'; echo $prating; echo '</button>' ?></small></td>
													</tr>
													<tr><td><strong>Engagement Rating:</td><td> </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																							if ($row[6] >= 3.5) { echo ' btn-green'; }
																							elseif ($row[6] >=2.5) { echo ' btn-yellow'; }
																							elseif ($row[6] >= 0) { echo ' btn-red'; }
																							echo ' num">'; echo $erating; echo '</button>' ?></small></td>
													</tr>										
													<tr><td><strong>Session Rating:</td><td> </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																							if ($row[7] >= 3.5) { echo ' btn-green'; }
																							elseif ($row[7] >=2.5) { echo ' btn-yellow'; }
																							elseif ($row[7] >= 0) { echo ' btn-red'; }
																							echo ' num">'; echo $srating; echo '</button>' ?></small></td>
													</tr>										
													</table>					
												</ul>
											</div>
											<div class="col-lg-12">
												<p class="comments"><strong>Comments: </strong><?php echo $row[9]; ?></p>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						
						
				<?php }}
				 ?>
				<?php 
				$reviewcount = mysqli_query($mysqli, "SELECT COUNT(*) as count FROM review WHERE reviewer_id = '$id'");
				$count = mysqli_fetch_array($reviewcount);
				$count = $count['count'];
				if ($count == 0) { ?>
				<div class="col-lg-9">
					<div class="alert alert-box type-primary alert-dismissable">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p>You have not received any reviews yet.</p> 
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