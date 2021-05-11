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
		$name = $user['name'];
		$userID = $user['user_id'];
		$dateObj = date_create($dateJoined);
			// format date syntax eg Saturday 20 February 2021
		$formattedDate = date_format($dateObj, 'l j F Y');
	?>

	<h2>Profile</h2>
		<p>ID: <?php echo $userID ?> </p>
		<p>Full Name: <?php echo $name ?></p>
		<!-- format date in DMY -->
		<p>Date joined - <?php echo $formattedDate ?> </p>


	<!-- <h2>Answer CORE Questions</h2> -->
	<!-- what if there are other forms? -->


	<h2>User History</h2>
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
					<button name="btnShowHistory" type="submit" class="submitButton">Show User History</button>
				</div>

			</form>


	<h6><a href="/">Return to Core Questions Homepage</a></h6>


</body>
</html>