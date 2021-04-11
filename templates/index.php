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
        <label for="item">Name of New User:</label>
        <input type="text" name="itemName" id="itemName">
        <br>
        <label for="item">Date Joined:</label>
        <input type="Date" name="itemDate" id="itemDate" 
        value="<?php echo date('Y-m-d');?>">
        <br>
        <button name="btnAddItem" type="submit" class="submitButton">Add</button>
    </form>
    <br>


    <h2>Answer GP-Core Questions</h2>
    <div id="core_form">

    <p>This form has multiple statements about how you have been OVER THE LAST WEEK.
    <br>Please read each statement and think how often you felt that way last week.
    <br>Then tick the box which is closest to this.</p>

    <div>

    <!-- Added date and name to form here - values are extracted as part of SaveAnswersController - OR should whole form be under a /userid route?-->

    <!-- needs flex grid or similar here to layout QA better for mobile, plus put labels at the top of the columns - form submits to saveUser 
      form needs success message for user when its submitted ok-->

    <div class="flex-grid">
    <form method="post" action="/saveAnswers">
        <div class="formNameDate">
            <label for="existingUserID">Name of Existing User:</label>
            <select name="existingUserID">
                <!-- caveat - this gives an error if u dont select a user, as it means thers no user_id to query! need validation & error checking! -->
              <option value="">Select...</option>

                <?php
                foreach($usersList as $user) { ?>
                  <option value="<?php echo $user["user_id"]?>"><?php echo $user["name"]?></option>
                <?php 
                }
                ?>
            </select>

            <br>
            <label for="dateCompleted">Date Form Completed:</label>
            <input type="Date" name="dateCompleted" id="dateCompleted" 
            value="<?php echo date('Y-m-d');?>">
        </div>

        <p>Over the last week...</p>

            <!-- <ol> -->
                <?php foreach($coreQuestionsAndPoints as $singleQuestionPoints) { ?>
                <!-- <li id="question_id" > -->
                    <!-- foreach is likely messing up flex divs! -->
                <div class="col" id="question_id">
                    <!-- need to show actual q_id here & explicitly save it later  -->
                    <?php 
                    $q_id = $singleQuestionPoints["q_id"];
                    echo $q_id;
                    ?>

                    <?php echo $singleQuestionPoints["question"]  ?>

                </div>  <!-- for col flex grid -->
                    <!-- radio button answers -->
                    <div class="radioGroupAnswers col">
                        <input type="radio" id="answerNot" 
                        name="radioAnswerPoints[<?php echo $q_id ?>]" 
                        value="<?php echo $singleQuestionPoints["pointsA_not"] ?>">
                        <label for="answerNot">Not at all</label>

                        <input type="radio" id="answerOnly" 
                        name="radioAnswerPoints[<?php echo $q_id?>]" 
                        value="<?php echo $singleQuestionPoints["pointsB_only"] ?>">
                        <label for="answerOnly">Only ocassionally</label>

                        <input type="radio" id="answerSometimes" 
                        name="radioAnswerPoints[<?php echo $q_id?>]" 
                        value="<?php echo $singleQuestionPoints["pointsC_sometimes"] ?>">
                        <label for="answerSometimes">Sometimes</label>

                        <input type="radio" id="answerOften" 
                        name="radioAnswerPoints[<?php echo $q_id?>]" 
                        value="<?php echo $singleQuestionPoints["pointsD_often"] ?>">
                        <label for="answerOften">Often</label>

                        <input type="radio" id="answerMost" 
                        name="radioAnswerPoints[<?php echo $q_id?>]" 
                        value="<?php echo $singleQuestionPoints["pointsE_most"] ?>">
                        <label for="answerMost">Most or all the time</label>
                    </div>

                <!-- </li> -->
                <?php } ?>

            <!-- </ol> -->
        
        <button name="btnSubmitAnswers" type="submit" class="submitButton">Submit Answers</button>
        </form>
        </div>

    </div>
    </div>

</body>
</html>
