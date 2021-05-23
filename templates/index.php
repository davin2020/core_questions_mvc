<!DOCTYPE html>
<html lang="en">
<html>
<!-- Davin updated this file for CoreQuestions -->
<head>
	<meta charset="utf-8"/>
	<title>GP-Core Question Form</title>
	<link href="style.css" type="text/css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Lato:400' rel='stylesheet' type='text/css'>
</head>

<body>
	<h1>Core Questions App / Wellbeing Tracker</h1>
	<!-- Wellbeing Tacking App?  -->

	<h2>Register A New User</h2>

		<div class="core_form">
			<!--  should this be save user or register user? -->
			<form method="POST" action="/saveUser">
				<label>Full Name:
					<input type="text" size="50" name="inputFullName" id="inputFullName" required placeholder="What's your first name and last name?">
				</label>
				<br>

				<label>Nickname:
					<input type="text" name="inputNickname" id="inputNickname" required placeholder="What are you usually called?">
				</label>
				<br>

				<label>Email:
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

				<!-- this should be auto calcualted  -->
				<!-- <label>Date Joined: -->
					<input type="Date" name="itemDate" id="itemDate" 
					value="<?php echo date('Y-m-d');?>" required hidden="true">
				<!-- </label> -->

				<br>
				<div class="buttonContainer">
					<button name="btnAddItem" type="submit" class="submitButton">Register</button>
				</div>

			</form>
		</div>
		<br>


	<h2>Login An Existing User</h2>
		<div class="core_form">
			<!--  get headers here eg User-Message -->

			<?php  
			if ( isset($_GET['success']) && $_GET['success']== 1) {
				echo '<em>Success msg goes here</em>';
			} 

			// echo $userMessage;
			// echo '<br>';
			// echo $userHeaders;

			?>

		
			


			<!--  need to create login controller class & factory ! -->
			<!--  DONT use GET for login forms that contain PASSWORDS ! -->
			<form method="POST" action="/loginUser">

				<!-- <label for="existingUserID">TEMP Select Existing User:
						<select name="existingUserID">
							// Need to make choosing a name mandatory 
						  	<option value="">Select...</option>
							<?php
							foreach($usersList as $user) { ?>
							 	<option value="<?php echo $user["user_id"]?>"><?php echo $user["nickname"]?></option>
							<?php 
							}
							?>
						</select>
					</label>
					<br>
					<br> -->


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

</body>
</html>