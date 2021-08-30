<!DOCTYPE html>
<html lang="en">
<html>

<head>
	<meta charset="utf-8"/>
	<title>Wellbeing Tracker - Admin Console</title>
	<link href="style.css" type="text/css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Lato:400' rel='stylesheet' type='text/css'>
</head>

<body>
	<h1>Wellbeing Tracker - Admin Console</h1>

	<form method="POST" action="/logoutUser">
        <input type="submit" value="Logout as Admin">
    </form>
		

	<h2>Show Existing Users & Dashboard Links</h2>

	<!-- how will this work to show data about each user, if im nont that user? ie if i dont have a session for that user? 
		does admin page need special pages to view others detaiils? or shoudl each controller check if admin is logge din and allow them access to toher users data? but if each user is taken from session data, maybe i need to add the other user to the session?
	-->
		<?php
		echo '<ul>';
		foreach($usersList as $user) {
			echo '<li>' . $user["nickname"] . 
			' -- <a href="/dashboard/'. $user["user_id"]  .'">Dashboard</a>' .
			' -- Joined: ' . $user["date_joined"] .
			' -- <a href="/showUserHistory/'. $user["user_id"] .'">User History</a></li>';
			}
		echo '</ul>';
		?>


	<h2>Add A New User</h2>
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
					<button name="btnAddItem" type="submit" class="submitButton">Add</button>
				</div>

			</form>
		</div>
		<br>


	<h2>Answer GP-Core Questions</h2>

	<!-- success msg if answeres to questions are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Your Answers have been Saved</em>';
	} ?>

	<div class="core_form flex_container">

		<!-- <div class="flex_container"> -->

			<form method="post" action="/saveAnswers">

				<div class="formNameDate">
					<label for="existingUserID">Name of Existing User:
						<select name="existingUserID">
							<!-- Need to make choosing a name mandatory -->
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
					<!--  need to make name & date compulsoyr & in the future! -->
					<label for="dateCompleted" >Date Form Completed:
						<input type="Date" name="dateCompleted" id="dateCompleted" 
						value="<?php echo date('Y-m-d');?>" required>
					</label>
					<br>

				</div>
				
				<div class="block_instructions">
					<div class="core_logo">
						<img src="gp_core_logo.png" alt="Logo for GP Core Form">
					</div>
					<!-- need to flex this on smaller screens -->
					<div class="instructions">
						<strong>IMPORTANT - PLEASE READ THIS FIRST</strong>
						<?php $maxQuestions = count($coreQuestionsAndPoints); ?>
						<p>This form has <?php echo $maxQuestions ?> statements about how you have been OVER THE LAST WEEK.
						<br>Please read each statement and think how often you felt that way last week.
						<br>Then tick the box which is closest to this.</p>
					</div>
				</div>

				<p>Over the last week...</p>

				<!-- New flex layout that wraps Answers below Questions on narrow screens -->
				<div class="flex-parent-qa">

					<?php 
					foreach($coreQuestionsAndPoints as $singleQuestionPoints) { 
					?>
						<div class="flex-child-qa-questions">
							<!-- btw db query does sort qs by gp_order, maybe rename function so its clearer? -->
							<?php 
							$q_id = $singleQuestionPoints["q_id"];
							$q_id_label = $singleQuestionPoints["q_id"] . ". ";
							echo $q_id_label;
							echo $singleQuestionPoints["question"];
							?>
						</div> 

						<div class="flex-child-qa-answers">
							<!-- Question Answer Labels are now being retrieved from DB via QuestionModel -->
							<label>
								<input type="radio" required
								name="radioAnswerPoints[<?php echo $q_id ?>]" 
								value="<?php echo $singleQuestionPoints["pointsA_not"] ?>">
								<?php echo $questionAnswerLabels[0]['label']; ?> 
							</label>
							<label>
								<input type="radio"
								name="radioAnswerPoints[<?php echo $q_id ?>]" 
								value="<?php echo $singleQuestionPoints["pointsB_only"] ?>">
								<?php echo $questionAnswerLabels[1]['label']; ?>
							</label>
							<label>
								<input type="radio"
								name="radioAnswerPoints[<?php echo $q_id ?>]" 
								value="<?php echo $singleQuestionPoints["pointsC_sometimes"] ?>">
								<?php echo $questionAnswerLabels[2]['label']; ?>
							</label>
							<label>
								<input type="radio"
								name="radioAnswerPoints[<?php echo $q_id ?>]" 
								value="<?php echo $singleQuestionPoints["pointsD_often"] ?>">
								<?php echo $questionAnswerLabels[3]['label']; ?>
							</label>
							<label>
								<input type="radio"
								name="radioAnswerPoints[<?php echo $q_id ?>]" 
								value="<?php echo $singleQuestionPoints["pointsE_most"] ?>">
								<?php echo $questionAnswerLabels[4]['label']; ?>
							</label>
						</div>	

					<?php
					}
					?>
				</div> <!-- div above foreach loop, flex-parent-qa -->

				<div class="instructions">
					<p>Thank you for your time in completing this questionnaire</p>
				</div>

				<div class="buttonContainer">
					<button name="btnSubmitAnswers" type="submit" class="submitButton">Submit Answers</button>
				</div>

				<p class="copyright">© CORE System Trust: https://www.coresystemtrust.org.uk/copyright.pdf</p>

			</form>
		<!-- </div>  -->
		<!-- eof flex-grid div -->
	</div> <!--  eof id=core-form div -->

</body>
</html>