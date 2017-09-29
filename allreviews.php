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
	
	//count total reviews received 
	$count = mysqli_query($mysqli, "SELECT COUNT(*) AS count FROM review WHERE trainer_id='$id'");
	$count = mysqli_fetch_array($count);
	$count = $count['count'];
	
	//get average ratings of all criteria for the trainer
	$paverage = mysqli_query($mysqli, "SELECT AVG(profrat) AS average FROM review WHERE trainer_id='$id'");
	$paverage = mysqli_fetch_array($paverage);
	$paverage = $paverage['average'];
	$paverage = number_format((float)$paverage, 2, '.', '');
	
	$eaverage = mysqli_query($mysqli, "SELECT AVG(engrat) AS average FROM review WHERE trainer_id='$id'");
	$eaverage = mysqli_fetch_array($eaverage);
	$eaverage = $eaverage['average'];
	$eaverage = number_format((float)$eaverage, 2, '.', '');
	
	$saverage = mysqli_query($mysqli, "SELECT AVG(sesrat) AS average FROM review WHERE trainer_id='$id'");
	$saverage = mysqli_fetch_array($saverage);
	$saverage = $saverage['average'];
	$saverage = number_format((float)$saverage, 2, '.', '');
	
	$selfaverage = mysqli_query($mysqli, "SELECT AVG(totalrating) AS average FROM review WHERE trainer_id='$id'");
	$selfaverage = mysqli_fetch_array($selfaverage);
	$selfaverage = $selfaverage['average'];
	$selfaverage = number_format((float)$selfaverage, 2, '.', '');
	
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

	<link rel="stylesheet" href="css/allreviews.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>$('#loginModal').modal('show'); </script>

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
									<li><a href="#">Profile</a></li>
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
				<p class="header">All Reviews Received. &nbsp;<span class="title">Read all the reviews the members have written regarging you & your sessions.</span></p>
			</div>
		</div>
		
	</div>
	
	<div class= "container-fluid content-fluid">
		<div class="container review-container">
			<div class="row">
			<div class="col-lg-3" >
					<div class="panel panel-default">
						<div class="panel-body">
							<ul class="review">
								<li><p><strong>Overall Review Information</strong></p></li>
								<li><strong>Total reviews received: </strong><?php echo $count; ?></li>
								<li>&nbsp;</li>
								<li><strong>Total average rating: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																					</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($selfaverage >= 3.5) { echo ' btn-green'; }
																						elseif ($selfaverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($selfaverage >= 0) { echo ' btn-red'; }
																						echo '">'; echo $selfaverage; echo '</button>' ?></small></li>
								<li><strong>Average Professionalism: &nbsp;&nbsp;</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($paverage >= 3.5) { echo ' btn-green'; }
																						elseif ($paverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($paverage >= 0) { echo ' btn-red'; }
																						echo '">'; echo $paverage; echo '</button>' ?></small></li>
								<li><strong>Average Engagement: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																				</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($saverage >= 3.5) { echo ' btn-green'; }
																						elseif ($saverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($saverage >= 0) { echo ' btn-red'; }
																						echo '">'; echo $saverage; echo '</button>' ?></small></li>
								<li><strong>Average Session: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																				</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($eaverage >= 3.5) { echo ' btn-green'; }
																						elseif ($eaverage >=2.5) { echo ' btn-yellow'; }
																						elseif ($eaverage >= 0) { echo ' btn-red'; }
																						echo '">'; echo $eaverage; echo '</button>' ?></small></li>
								
							</ul>
						</div>
					</div>
				</div>
			<?php $reviews = "SELECT reviewer_name, r.trainer_id, r.session_id, title, timestamp, profrat, engrat, sesrat, totalrating, comments, date, category from review r, session s
								WHERE r.session_id = s.session_id AND r.trainer_id = '$id' ORDER BY timestamp";
				if ($result = mysqli_query($mysqli, $reviews)) {
					while ($row = mysqli_fetch_row($result)){ ?>
						<div class="col-lg-9 pull-right">
							<div class="row">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="col-lg-6 border-right">
											<ul class="review">
												<li><strong><p><span class="title"><?php echo ucfirst($row[0]); ?></span></strong><small> rated 
												<?php echo '<button class="btn btn-static btn-xs '; 
													if ($row[8] >= 3.5) { echo ' btn-green'; }
													elseif ($row[8] >=2.5) { echo ' btn-yellow'; }
													elseif ($row[8] >= 0) { echo ' btn-red'; }
													echo '">'; echo $row[8]; echo '</button></small>'; ?>
												<strong><?php echo time_elapsed_string($row[4]) ?></strong></p></li>	
												<li><strong>Session Name: </strong><?php echo $row[3]; ?></li>
												<li><strong>Session Date: </strong><?php echo $row[10]; ?></li>
												<li><strong>Category: </strong><?php echo ucfirst($row[11]); ?></li>
												<li>&nbsp;</li>
												<li><strong>Professional Rating: &nbsp;&nbsp;&nbsp;</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($row[5] >= 3.5) { echo ' btn-green'; }
																						elseif ($row[5] >=2.5) { echo ' btn-yellow'; }
																						elseif ($row[5] >= 0) { echo ' btn-red'; }
																						echo '">'; echo $row[5]; echo '</button>' ?></small></li>
												<li><strong>Engagement Rating: &nbsp;&nbsp;</strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($row[6] >= 3.5) { echo ' btn-green'; }
																						elseif ($row[6] >=2.5) { echo ' btn-yellow'; }
																						elseif ($row[6] >= 0) { echo ' btn-red'; }
																						echo '">'; echo $row[6]; echo '</button>' ?></small></li>
												<li><strong>Session Rating: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </strong><small><?php echo '<button class="btn btn-static btn-xs '; 
																						if ($row[7] >= 3.5) { echo ' btn-green'; }
																						elseif ($row[7] >=2.5) { echo ' btn-yellow'; }
																						elseif ($row[7] >= 0) { echo ' btn-red'; }
																						echo '">'; echo $row[7]; echo '</button>' ?></small></li>
											</ul>
										</div>
										<div class="col-lg-6 ">
											<ul class="review">	
												<li class="comments"><strong>Comments: </strong><?php echo $row[9]; ?></li>
											</ul>
										</div>
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