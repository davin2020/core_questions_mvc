<!DOCTYPE html>

<!--  <?php error_reporting(E_ALL); ini_set('display_errors', '1'); ?> -->

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Wellbeing Tracker - Dashboard</title>
	<link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
	<h1>Wellbeing Tracker - Dashboard for <?php echo $user['nickname'] ?></h1>

	<?php
		$userId = $_SESSION['userId'];
		$user = $_SESSION['existingUser'];
		// var_dump("<br>user from session <br>");
		// var_dump($user);

		$dateJoined = $user['date_joined'];
		$name = $user['nickname'];
		$fullname = $user['fullname'];
		$userID = $user['user_id'];
		$dateObj = date_create($dateJoined);
		// format date syntax eg Saturday 20 February 2021 - maybe this should be a method? ie pass in string date, convert to Date obj & get nicely formatted string back?
		$formattedDate = date_format($dateObj, 'l j F Y');

		// var_dump("<br>session coreIsLoggedIn<br>");
		// var_dump($_SESSION['coreIsLoggedIn']);
		// var_dump("<br>session contents<br>");
		// var_dump($_SESSION);

		if (isset($_SESSION['coreIsLoggedIn'])  && $_SESSION['userId'] == 99) {
			$user = $_SESSION['existingUser'];
	?>	
			<br>
			<!-- format this a button like the others?? -->
			<h2><a href="/adminConsole">Admin Console</a></h2>
			<br>
	<?php 
		}
	?>

	<section>
	<h2>My Profile</h2>

		<p>ID: <?php echo $userID ?> </p>
		<p>Nickname: <?php echo $name ?></p>

		<p>Full Name: <?php echo $user['fullname'] ?></p>
		
		<p>Email: <?php echo $user['email'] ?></p>
		<!-- format date as DMY -->
		<p>Date Joined: <?php echo $formattedDate ?> </p>
		<!-- Maybe show date when questions were last anaswers & last score? -->

		<!-- logout feature, need to style like other buttons -->
		<form method="POST" action="/logoutUser">
            <input type="submit" value="Logout">
        </form>

	</section>

	<section>

	<h2>Answer Questions</h2>
	<!-- what if there are other question forms? eg core-34 etc -->

	<!-- success msg if answeres to questions are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Your Answers have been Saved</em>';
	} ?>

		<form method="GET" action="/questionForm">
		<div class="buttonContainer">
				<button name="btnShowQuestions" type="submit" class="submitButton">Answer Questions</button>
			</div>
		</form>

	</section>

	<section>

	<h2>My History</h2>
	<!-- show last date & overall score here -->

		<form method="GET" action="/showUserHistory">
			<div class="buttonContainer">
				<button name="btnShowHistory" type="submit" class="submitButton">Show My History</button>
			</div>
		</form>

	</section>

</body>
</html>