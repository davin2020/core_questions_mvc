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
	<h1>LOGIN Page - Core Questions App</h1>

	<h2>Register A New User</h2>
	Actaullly need - fullname, nickname, email, password

		<div class="core_form">
			<form method="post" action="/saveUser">
				<label>Name of New User:
					<input type="text" name="itemName" id="itemName" required>
				</label>
				<br>

				<label>Date Joined:
					<input type="Date" name="itemDate" id="itemDate" 
					value="<?php echo date('Y-m-d');?>" required>
				</label>
				<br>
				<div class="buttonContainer">
					<button name="btnAddItem" type="submit" class="submitButton">Register</button>
				</div>

			</form>
		</div>
		<br>


	<h2>Login An Existing User - or Admin Login</h2>
		<div class="core_form">
			<form method="post" action="/loginUser">
				<label>UserName or Email:
					<input type="text" name="itemName" id="itemName" required>
				</label>
				<br>

				<label>Password
					<input type="Password" name="itemPassword" id="itemPassword" 
					required>
				</label>
				<br>
				<div class="buttonContainer">
					<button name="btnAddItem" type="submit" class="submitButton">Login</button>
				</div>

			</form>
		</div>


	<h2>TEMP - Admin Page</h2>
	<a href="/admin">Admin Page</a>

</body>
</html>