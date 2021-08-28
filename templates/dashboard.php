<!DOCTYPE html>
<?php 
// session_name("CORE_SESSION");
// session_start();  //seems i dont nedd to call it here if its called in controller
// To use a named session, call session_name() before calling session_start().
?> 

<?php error_reporting(E_ALL); ini_set('display_errors', '1'); ?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Wellbeing Tracker - Dashboard</title>
	<link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
	<h1>Wellbeing Tracker - Dashboard for <?php echo $user['nickname'] ?></h1>

	<!--  TO DO need to get this User Info from teh Dashboard Controller ! -->

		<!-- $user = $this->userModel->getUserFromID($args['user_id']); -->
		<!-- $userName = $user['name']; -->


	<?php
		// $assocArrayArgs['userName'] = $userName; 
		// $assocArrayArgs['user'] = $user; 
		// $user is implied to exist from this above

		//new array details, used w sessions
	//do i get the user details from teh session or from the $newAssocArray?
		// $newAssocArray = [];
		// $newAssocArray['user'] = $user; //how do i get the id from teh user, do i have a getter?
		// $newAssocArray['user_id'] = $user['user_id'];

	// get teh u ser from teh SESSION not from teh ARGS!
		$userId = $_SESSION['userId'];
		$user = $_SESSION['existingUser'];
		var_dump("<br>user from session <br>");
		var_dump($user);


		$dateJoined = $user['date_joined'];
		$name = $user['nickname'];
		$fullname = $user['fullname'];
		$userID = $user['user_id'];
		$dateObj = date_create($dateJoined);
		// format date syntax eg Saturday 20 February 2021 - maybe this should be a method? ie pass in string date, convert to Date obj & get nicely formatted string back?
		$formattedDate = date_format($dateObj, 'l j F Y');

		//shoudl i be accessing sessino stuff here on in controller?
		var_dump("<br>session coreIsLoggedIn<br>");
		var_dump($_SESSION['coreIsLoggedIn']);
		// $_SESSION coreIsLoggedIn
		var_dump("<br>session contents<br>");
		var_dump($_SESSION);
		//this is now using session info from other local webiste  ie http://localhost:8000/index.php !
		// array(4) { ["isRedirectFromAccountsToIndex"]=> bool(true) ["$errorMessage"]=> string(53) "Your not logged in, so redirected from Page2 to Index" ["isLoggedIn"]=> bool(true) ["existingUser"]=> array(6) { ["user_id"]=> string(1) "4" ["fullname"]=> string(14) "Johnny Jaqobis" ["nickname"]=> string(6) "Johnny" ["email"]=> string(18) "johnny@example.com" ["password"]=> string(60) "$2y$10$Nrw9GuifeUaO7SmhAMfauuhlKPnduXVSeCVs460Ml/ynU8/sz6DKe" ["date_joined"]=> string(10) "2021-04-04" } }

		if (isset($_SESSION['coreIsLoggedIn'])  && $_SESSION['userId'] == 99) {
			//do i get the user from teh session or from the $newAssocArray?
			$user = $_SESSION['existingUser'];
			echo '<br><br>heres the ADMIN user<br>';
			var_dump($user);
			//this is ok in incognito is doesnt show session info from other localhost test_sessions app
			// exit;
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
		<!-- format date in DMY -->
		<p>Date Joined: <?php echo $formattedDate ?> </p>



		<!-- show date when questions were last anaswers & last score? -->

		<!-- logout feature -->
		<!-- <form method="POST" action="/loginUser"> -->
		<form method="POST" action="/logoutUser">
            <input type="submit" value="Logout">
        </form>
	</section>

	<section>
	<h2>Answer Questions</h2>
	<!-- on dashboard page, add  link to quiz page/question form -->
	<!-- what if there are other forms? -->

	<!-- success msg if answeres to questions are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Your Answers have been Saved</em>';
	} ?>

<!-- questionForm -->
		<!-- <form method="GET" action="/questionForm/<?php echo $userID; ?>"> -->
		<form method="GET" action="/questionForm">
			<!-- how to pass assoc array or sesssion stuff witih user_id in it to next page? -->
		<div class="buttonContainer">
				<button name="btnShowQuestions" type="submit" class="submitButton">Answer Questions</button>
			</div>

		</form>
	</section>

	<section>
	
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

			<!-- how do i use a button to pass an arry w userid in it to next page? or do i just access gloabl session var on history page?-->
			<form method="GET" action="/showUserHistory">
			<!-- <form method="GET" action="/showUserHistory/<?php echo $userID; ?>"> -->

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
		</section>

</body>
</html>