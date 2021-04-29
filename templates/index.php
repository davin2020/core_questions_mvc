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

	<h1>Core Questions App</h1>

	<h2>Show Existing Users</h2>

		<?php
		echo '<ul>';
		foreach($usersList as $user) {
			echo '<li>' . $user["name"] . ' -- Joined: ' . $user["date_joined"] .
			' -- <a href="/showUserHistory/'. $user["user_id"] .'">User History</a></li>';
			}
		echo '</ul>';
		?>


	<h2>Add A New User</h2>

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

			<button name="btnAddItem" type="submit" class="submitButton">Add</button>
		</form>
		<br>


	<h2>Answer GP-Core Questions</h2>

	<!-- success msg if question are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Form Questions saved ok</em>';
	} ?>

	<div id="core_form">

		

		<!-- Added date and name to form here - values are extracted as part of SaveAnswersController - OR should whole form be under a /userid route?-->

		<div class="flex-grid">

			<form method="post" action="/saveAnswers">

				<div class="formNameDate">
					<label for="existingUserID">Name of Existing User:
						<select name="existingUserID">
						  <option value="">Select...</option>
							<?php
							foreach($usersList as $user) { ?>
							  <option value="<?php echo $user["user_id"]?>"><?php echo $user["name"]?></option>
							<?php 
							}
							?>
						</select>
					</label>

					<br>
					<!--  need to make name & date compulsory & in the future! -->
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
						<!-- need to make 14 a dynamic number, based on the number of questions that this Core form has -->
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
							<!-- Question Answer Labels are now being retrieved from DB via QuestionModel  -->
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
								value="<?php echo $singleQuestionPoints["pointsC_sometimes"] ?>">&nbsp
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
					<p>THANK YOU FOR YOUR TIME IN COMPLETING THIS QUESTIONNAIRE</p>
				</div>

				<button name="btnSubmitAnswers" type="submit" class="submitButton">Submit Answers</button>

				<p class="copyright">© CORE System Trust: https://www.coresystemtrust.org.uk/copyright.pdf</p>

			</form>
		</div> <!-- eof flex_parent_qa div -->
	</div> <!-- eof id=core-form div -->

</body>
</html>