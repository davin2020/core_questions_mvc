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
	<!-- hwo to call this ??? the Controller for this page ie HomePageController is where these array variables are coming from eg $userList 
	Make link to Route dynamic - /showUserHistory/{q_id}
	-->
		<?php
		echo '<ul>';
		foreach($usersList as $user) {
			echo '<li>' . $user["name"] . ' -- Joined: ' . $user["date_joined"] .
			' -- <a href="/showUserHistory/'. $user["user_id"] .'">User History</a></li>';
			}
		echo '</ul>';
		?>


	<h2>Add A New User</h2>
		<!-- where is item being refered to, when getting data from Form ? -->
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

	<p>This form has multiple statements about how you have been OVER THE LAST WEEK.
	<br>Please read each statement and think how often you felt that way last week.
	<br>Then tick the box which is closest to this.</p>

	<!-- Added date and name to form here - values are extracted as part of SaveAnswersController - OR should whole form be under a /userid route?-->
	<!-- needs flex grid or similar here to layout QA better - form submits to saveUser -->


	<!-- new flexy grid stuff starts here, 
		ISSUE do i need 2 lots of this class here? what class did this div used to have? -->
	<div class="flex-parent-qa">

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
				<!--  need to make name & date compulsoyr & in the future! -->
				<label for="dateCompleted" >Date Form Completed:
					<input type="Date" name="dateCompleted" id="dateCompleted" 
					value="<?php echo date('Y-m-d');?>" required>
				</label>
				<!-- 
				<label>Date Form Completed
					<input type="date" id="date" name="date" required>
				</label> -->

			</div>

			<p>Over the last week...</p>

			<!-- NEW STUFF -->
			<div class="flex-parent-qa">

				<?php 
				foreach($coreQuestionsAndPoints as $singleQuestionPoints) { 
				?>
					<div class="flex-child-qa-questions">
						<!-- btw db query does sort qs by gp_order, maybe rename function so its clearer? -->
						<?php 
						$q_id = $singleQuestionPoints["q_id"];
						$q_id_label = $singleQuestionPoints["q_id"] . ": ";
						echo $q_id_label;
						echo $singleQuestionPoints["question"];
						?>
					</div> 

					<div class="flex-child-qa-answers">
						<!-- Question Answer Labels are now being retrieved from DB via QuestionModel 
						ISSUE how to keep radio button circle with text label? -->
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

			<button name="btnSubmitAnswers" type="submit" class="submitButton">Submit Answers</button>

			</form>
		</div> <!-- eof flex-grid div  = flex_parent_qa-->
	</div> <!-- eof core-form div -->

</body>
</html>