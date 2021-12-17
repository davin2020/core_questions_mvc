<!DOCTYPE html>
<html lang="en">
<!-- Davin updated this file for CoreQuestions -->
<head>
	<meta charset="utf-8"/>
	<title>Wellbeing Tracker - Register or Login</title>
	<link href="style.css" type="text/css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Lato:400' rel='stylesheet' type='text/css'>
	<!-- needed for mobile devices/MQ -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

	<h1>Wellbeing Tracker</h1>

	<section>
		<h2>Register</h2>
		<!-- without this div words Register and Full name dont line up nicely, need to adjust css later -->
		<div>
			<form method="POST" action="/saveUser">
				<!-- <div class="wrapper"> -->
				<!-- makign thsi ' input size="50" ' makes it overflow its div on narrrow screens -->
				<!-- <label>Full Name: -->
					<input type="text" name="inputFullName" id="inputFullName" placeholder="What's your first name and last name?">
				<!-- </label> -->
				<br>
				<!-- </div> -->

				<label>Nickname:
					<input type="text" name="inputNickname" id="inputNickname" required placeholder="What are you usually called?">
				</label>
				<br>

				<label>Email:
					<!-- why is email field length shwon shorter than others on FE? -->
					<input type="Email" name="inputEmail" id="inputEmail" required>
				</label>
				<br>

				<!--  need to limit pwd to 72 chars long, due to php password_hash() function, otherwise longer pwds will get truncated to 72 chars 
				need to implement some FE complexity checking for pwd ie upper & lowercase etc -->
				<label>Password
					<input type="Password" name="inputPassword" id="inputPassword" 
					required>
				</label>
				<br>

				<br>
				<div class="buttonContainer">
					<button name="btnAddItem" type="submit" class="submitButton">Register</button>
				</div>

			</form>
		</div>
	</section>


	<section>
		<h2>Login</h2>
			<div>
				<!--  get headers here eg User-Message -->
				<?php  
				if ( isset($_GET['success']) && $_GET['success']== 1) {
					echo '<em>Success msg goes here</em>';
				} 

				if ( isset($_GET['failure']) && $_GET['failure']== 1) {
					echo '<em user_warning>First Failure</em>';
				} 

				// only show the msg if it had been set - need to style error msg
				if ( isset($messageForUser) ) {
					echo '<em class="user_warning">' . $messageForUser . '</em>';
				} 
				?>

				<form method="POST" action="/loginUser">

					<!-- if login failed cos pwd wrong, shoudl email field be pre-populated with same email address? -->
					<label>Email:
						<input type="Email" name="inputEmail" id="inputEmail" required>
					</label>
					<br>

					<label>Password
						<input type="Password" name="inputPassword" id="inputPassword" 
						required>
					</label>
					<br>

					<div class="buttonContainer">
						<button name="btnAddItem" type="submit" class="submitButton">Login</button>
					</div>

				</form>

			</div>
		</section>

</body>
</html>