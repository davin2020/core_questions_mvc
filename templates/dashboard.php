<!DOCTYPE html>
<?php error_reporting(E_ALL); ini_set('display_errors', '1'); ?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>GP-Core Questions Form - Dashboard</title>
	<link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
	<h1>Core Questions App - Dashboard for <?php echo $userName ?></h1>

	<!--  TO DO need to get this User Info from teh Dashboard Controller ! -->

		<!-- $user = $this->userModel->getUserFromID($args['user_id']); -->
		<!-- $userName = $user['name']; -->


	<?php
		// $assocArrayArgs['userName'] = $userName; 
		// $assocArrayArgs['user'] = $user; 
		// $user is implied to exist from this above
		$dateJoined = $user['date_joined'];
		$name = $user['nickname'];
		$fullname = $user['fullname'];
		$userID = $user['user_id'];
		$dateObj = date_create($dateJoined);
		// format date syntax eg Saturday 20 February 2021 - maybe this should be a method? ie pass in string date, convert to Date obj & get nicely formatted string back?
		$formattedDate = date_format($dateObj, 'l j F Y');
	?>

	<h2>My Profile</h2>
		<p>ID: <?php echo $userID ?> </p>
		<p>Nickname: <?php echo $name ?></p>
		<p>Full Name: <?php echo $user['fullname'] ?></p>
		<p>Email: <?php echo $user['email'] ?></p>
		<!-- format date in DMY -->
		<p>Date Joined: <?php echo $formattedDate ?> </p>

		<!-- show date when questions were last anaswers & last score? -->

	<h2>Answer Questions</h2>
	<!-- on dashboard page, add  link to quiz page/question form -->
	<!-- what if there are other forms? -->

	<!-- success msg if answeres to questions are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Your Answers have been Saved</em>';
	} ?>

<!-- questionForm -->
		<form method="GET" action="/questionForm/<?php echo $userID; ?>">
		<div class="buttonContainer">
				<button name="btnShowQuestions" type="submit" class="submitButton">Answer Questions</button>
			</div>

		</form>
	


	<h2>My History</h2>
	<!-- show last date & overall score -->

	<!-- for this user name - output date & score -->

	
<!-- echo '<li>' . $user["name"] . ' -- Joined: ' . $user["date_joined"] .
			' -- <a href="/showUserHistory/'. $user["user_id"] .'">User History</a></li>';
 -->

<!--  echo '<li>' . $user["name"] . ' -- Joined: ' . $user["date_joined"] .
			' -- <a href="/showUserHistory/'. $user["user_id"] .'">User History</a></li>'; -->

			<!-- <form method="GET" action="/showUserHistory/<?php $userID ?>"> -->

				<!-- // <form action="reply.php?id=<?php echo $id; ?>" -->
					<!-- // action="reply.php?id=<?php echo $id; ?>" -->
			<form method="GET" action="/showUserHistory/<?php echo $userID; ?>">
				<!-- <label>Name of New User:
					<input type="text" name="itemName" id="itemName" required>
				</label>
				<br>

				<label>Date Joined:
					<input type="Date" name="itemDate" id="itemDate" 
					value="<?php echo date('Y-m-d');?>" required>
				</label>
				<br> -->
				<div class="buttonContainer">
					<button name="btnShowHistory" type="submit" class="submitButton">Show My History</button>
				</div>

			</form>


	<h6><a href="/admin">TEMP LINK - Return to Core Questions Homepage</a></h6>


</body>
</html>