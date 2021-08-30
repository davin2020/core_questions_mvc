<!DOCTYPE html>
<html lang="en">
<html>
<!-- Davin updated this file for CoreQuestions -->
<head>
	<meta charset="utf-8"/>
	<title>Wellbeing Tracker - Answer Form</title>
	<link href="../style.css" type="text/css" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Lato:400' rel='stylesheet' type='text/css'>
</head>

<body>
	<h1>Wellbeing Tracker - Answer Wellbeing Questions</h1>

	<?php
			$userID = $user['user_id'];
	?>

	<h2>Answer Wellbeing Questions for <?php echo $userName ?> </h2>

	<!-- success msg if answeres to questions are saved ok -->
	<?php  
		if ( isset($_GET['success']) && $_GET['success']== 1) {
			echo '<em>Your Answers have been Saved</em>';
		} 
		if ( isset($_SESSION) )  {
			// echo session_id(); 
			// var_dump("<br>session contents vardump: <br>");
			// var_dump($_SESSION); //this is null now?
		}
	?>

	<div class="core_form flex_container">

			<form method="post" action="/saveAnswers">

				<div class="formNameDate">

						<select name="existingUserID" hidden="true">
							 <option value="<?php echo $user["user_id"]?>"><?php echo $user["nickname"]?></option>
						</select>

					<br>
					<!--  need to make date compulsoyr & in the future! -->
					<label for="dateCompleted">Date Form Completed:
						<input type="Date" name="dateCompleted" id="dateCompleted" 
						value="<?php echo date('Y-m-d');?>" required>
					</label>
					<br>

				</div>
				
				<div class="block_instructions">
					<div class="core_logo">
						<!-- why does this ref to img file need to go up 1 level as in same dir as index.php?? -->
						<img src="../gp_core_logo.png" alt="Logo for GP Core Form">
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
							<!--  This is the actual Question
								btw db query does sort qs by gp_order, maybe rename function so its clearer? -->
							<?php 
							$q_id = $singleQuestionPoints["q_id"];
							$q_id_label = $singleQuestionPoints["q_id"] . ". ";
							echo $q_id_label;
							echo $singleQuestionPoints["question"];
							?>
						</div> 

						<div class="flex-child-qa-answers">
							<!-- These are the Answer Labels for each question ie "Not At All, Only Occassionally, Sometimes, Often, Most or All the Time".
								Question Answer Labels are now being retrieved from DB via QuestionModel.
								TODO - consider having just one set of Answer Labels in a top-most Heading column, but would need each radio button to be in its own collumn -->
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
					}	// end of foreach question in array loop
					?>
				</div> <!-- div above foreach loop, flex-parent-qa -->

				<div class="instructions">
					<!-- TODO - maybe show Thank You after form has been submitted -->
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

	<br>
	<a href="/dashboard">Return to Dashboard</a>
	<br>

</body>
</html>