<?php

ob_start();
session_start();
include_once 'dbconnect.php';
date_default_timezone_set('Asia/Singapore');

$error = false;

if ( isset($_SESSION['user'])!="" ) { 
	$res= mysqli_query($mysqli, "SELECT * FROM user WHERE user_id=".$_SESSION['user']);
	$userRow= mysqli_fetch_array($res);
} else {
	header("Location: index.php");	
}

if ( isset($_GET['success']) && $_GET['success'] == 0) {
	$alertType = "success";
	$alertMsg = "Successfully changed your password.";
}
	
if( isset($_POST['update']) ) {
	$fullname = trim($_POST['fullname']);
	$fullname = strip_tags($fullname);
	$fullname = htmlspecialchars($fullname);
	  
	$email = trim($_POST['email']);
	$email = strip_tags($email);
	$email = htmlspecialchars($email);		

	$level = trim($_POST['level']);
	$level = strip_tags($level);
	$level = htmlspecialchars($level);

	$specialty = trim($_POST['specialty']);
	$specialty = strip_tags($specialty);
	$specialty = htmlspecialchars($specialty);
	
	if (!$error) {

		$fullname = $_POST['fullname'];
		$email = $_POST['email'];
		$level = $_POST['level'];
		$specialty = $_POST['specialty'];
		
		$query = "UPDATE user SET fullname='$fullname', email='$email' WHERE user_id =".$_SESSION['user'];
		$res = mysqli_query($mysqli, $query);
			
		$memquery = "UPDATE member SET fullname='$fullname', level='$level' WHERE user_id =".$_SESSION['user'];
		$res = mysqli_query($mysqli, $memquery);			
			
		$traquery = "UPDATE trainer SET fullname='$fullname', specialty='$specialty' WHERE user_id =".$_SESSION['user'];
		$res = mysqli_query($mysqli, $traquery);				

		
		if ($res) {
		 $alertType = "success";
		 $alertMsg = "Successfully updated profile.";
		} else {
		 $alertType = "danger";
		 $alertMsg = "Something went wrong, try again later..."; 
		} 
	}
	}
	
if(isset($_POST['picupload'])){
 
	$id = $userRow['user_id'];
	$checkoriginal = mysqli_query($mysqli, "SELECT * from avatar WHERE user_id ='$id'");
	$count = mysqli_num_rows($checkoriginal);
	if ($count > 0) {
		$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$userRow[0]'");
		$findimage = mysqli_fetch_array($findimage);
		$image = $findimage['image_name'];
		$image_src = "images/upload/".$image;
		unlink($image_src);
		$query = mysqli_query($mysqli, "DELETE from avatar where user_id = '$id'");
	}
	
	$name = $_FILES['image']['name'];
	$target_dir = "images/upload/";
	$target_file = $target_dir . basename($_FILES['image']['name']);

	// Select file type
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	// Valid file extensions
	$extensions_arr = array("jpg","jpeg","png");

	
	// Check extension
	if(in_array($imageFileType, $extensions_arr)){
		
		// change name of file if exists same name
		if(file_exists("images/upload/".$name)) {
			$actual_name = pathinfo($name,PATHINFO_FILENAME);
			$original_name = $actual_name;
			$extension = pathinfo($name, PATHINFO_EXTENSION);
			
			$i = 1;
			while(file_exists('images/upload/'.$actual_name.".".$extension))
			{           
				$actual_name = (string)$original_name.$i;
				$name = $actual_name.".".$extension;
				$i++;
			}
		}
		
		// Insert record
		$insert = mysqli_query($mysqli,"insert into avatar(image_name, user_id) values('".$name."','$id')");
		// Upload file
		$upload = move_uploaded_file($_FILES['image']['tmp_name'],'images/upload/'.$name);

		if($insert && $upload){
			$alertType = "success";
			$alertMsg = "Successfully uploaded profile picture.";
		}
		else {
			$alertType = "danger";
			$alertMsg = "Failed to upload image.";
		} 
	}
	
	else {
			$alertType = "danger";
			$alertMsg = "Invalid file extension. (.jpg or .png only)";
	} 
 
}

if(isset($_POST["remove"])){
	
	$id = $userRow['user_id'];
	$checkoriginal = mysqli_query($mysqli, "SELECT * from avatar WHERE user_id ='$id'");
	$count = mysqli_num_rows($checkoriginal);
	if ($count > 0) {
		$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$userRow[0]'");
		$findimage = mysqli_fetch_array($findimage);
		$image = $findimage['image_name'];
		$image_src = "images/upload/".$image;
		unlink($image_src);
		$query = mysqli_query($mysqli, "DELETE from avatar where user_id = '$id'");
		if ($query) {
			$alertType = "success";
			$alertMsg = "Successfully removed profile picture.";
		}
	}
	
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>View Profile - ROUTE</title>
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
	
	<link rel="stylesheet" href="css/profile.css">
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
	
	<div class="container-fluid profile-fluid">

		<div class="container page-info">
			<div class="row">
				<a href="profile.php"><div class="col-lg-3 info-box ">
					<strong>UPDATING PROFILE</strong>
				</div></a>
				<?php if (isset($alertType)) { ?>
					<div class="col-lg-6">
						<div class="alert alert-box-s type-<?php echo $alertType; ?> alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
							&nbsp;<?php echo $alertMsg; ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		
		<?php $user = mysqli_query($mysqli, "SELECT * from user WHERE user_id =".$_SESSION['user']);
		$row = mysqli_fetch_row($user);
		
		$member = mysqli_query($mysqli, "SELECT * from member WHERE user_id =".$_SESSION['user']);
		$mrow = mysqli_fetch_row($member);
		
		$trainer = mysqli_query($mysqli, "SELECT * from trainer WHERE user_id =".$_SESSION['user']);
		$trow = mysqli_fetch_row($trainer); ?>
		
		<div class="container profile-container">
			<div class="row">
			
				<div class="col-xs-12 col-sm-12 col-sm-offset-0 col-lg-6 col-lg-offset-3">
					<div class="profile-wrap text-center">
						<div class="profile-form">
							<div class="row text-center">
								<form class="col-lg-12" action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
									<div class="row">
										<div class = "group">
											<?php
											$findimage = mysqli_query($mysqli, "SELECT image_name FROM avatar WHERE user_id ='$userRow[0]'");
											$count = mysqli_num_rows($findimage);
											if($count > 0){
												$findimage = mysqli_fetch_array($findimage);
												$image = $findimage['image_name'];
												$image_src = "images/upload/".$image;
												
												echo "<img class=\"photo\" src=\"$image_src\" width=\"250\" height=\"250\">";
												echo '<div class="row"><button type="submit" name="remove" class="button-remove">Remove Image</button></div>';
											}
											else {
												echo '<img src="images/man.png" class="photo"><br>';
											}
						
											?>
											<br>
											<div class="col lg-6 col-lg-offset-4">
												<input type="file" name="image"/>
											</div>
											<br>
											<div class="col-lg-4 col-lg-offset-4">	
												<input type="submit" name="picupload" class="button" value="Upload"/>
											</div>	
											
										</div>
										
									</div>
									<hr>
									<div class="row">
										<div class="col-lg-6">
											<div class="group">
												<label for = "username" class = "label">USERNAME</label>
												<p class="info"><?php echo $row[2]?></p>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="group">
												<label for = "fullname" class = "label">FULL NAME</label>
												<input id="fullname" type="text" name="fullname" class="input" value="<?php echo $row[4]?>" required>
											</div>
										</div>
									</div>	
									<div class="row">
										<div class="col-lg-6">	
											<div class="group">
												<label for = "date" class = "label">DATE CREATED</label>
												<p class="info"><?php  $date = date('j F Y',strtotime($row[6]));
												echo $date; ?></p>
											</div>
										</div>
										<div class="col-lg-6">											
											<div class="group">
												<label for = "email" class = "label">E-MAIL</label>
												<input id="email" type="email" name="email" class="input" value="<?php echo $row[3]?>" required></input>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<div class="group">
												<label for = "time" class = "label">TIME CREATED</label>
												<p class="info"><?php $time = date('g:i A',strtotime($row[6]));
												echo $time; ?></p>
											</div>
										</div>
										<div class="col-lg-6">											
											<div class="details" id="member" style="display: none;">

												<div class = "group">
													<label for ="level" class = "label">LEVEL</label>
														<select id="level" name="level" class="input" required>
															<option value="beginner" <?php if ($mrow[3] == "beginner") echo "selected"; ?>>Beginner</option>
															<option value="intermediate" <?php if ($mrow[3] == "intermediate") echo "selected"; ?>>Intermediate</option>
															<option value="expert" <?php if ($mrow[3] == "expert") echo "selected"; ?>>Expert</option>
														</select>
												</div>
											</div>
											
											<div class="form" id="trainer" style="display: none;">
											
												<div class="group">
													<label for="session" class="label">SPECIALTY</label>
													<input id="specialty" type="text" name="specialty" class = "input" value="<?php echo $trow[3]; ?>">
												</div>
												
											</div>
											
											<?php 
												if ($row[1] == 'member') {
													$showdiv = 'member';
												}
												else if ($row[1] == 'trainer') {
													$showdiv = 'trainer';
												}
												echo "<script type=\"text/javascript\">document.getElementById('".$showdiv."').style.display = 'block';</script>";
											?>
										</div>
										<div class="row">
											<div class="col-lg-12">
												<a href="passwordchange.php"><p class="change">CHANGE PASSWORD</p></a>
											</div>
										</div>
										<div class = "group">
											<button type="submit" name="update" class="button" value="Update Profile">UPDATE</button>
										</div>	
									</div>					
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
					<a href="trainer.php">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="#">About</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
					<a href="contact.php">Contact</a>
				</span>
			</div>	
		</div><!-- End Sub Footer -->
		
		<br><br>
	</div>
</body>

</html>