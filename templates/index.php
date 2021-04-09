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

<!--  TEMP FLEX BOX for LAYOUR -->
        <div class="flex-grid">
          <div class="col">
            <!-- can i put simmple for each in here? -->
            <?php
            $tmp = [1,2,3,4,5];
            foreach($tmp as $item )
                echo '<p>hello' . $item . '</p>';
            ?>
          <p>This little piggy went to market.</p>
        </div>
          <div class="col">
            <input type="radio" id="huey" name="drone" value="huey"
         checked>
            <label for="huey">Huey</label>
            <input type="radio" id="dewey" name="drone" value="dewey">
            <label for="dewey">Dewey</label>
          <p>This little piggy stayed home.</p>
        </div>
        </div>
    <!-- what will these fields be named?  
        Should i include the gp_order here or is it enought that qs are already sorted in the right order?
        maybe try writing out hte html and just using php for the variable values? 
        Need to get linked css working so can style this
        On next iteration, get labels from DB
        Later - Need to org table into 2 columns using flex or grid for mobile & swop div for span, plus put labels at the top of the columns
    -->

    <p>This form has multiple statements about how you have been OVER THE LAST WEEK.
    <br>Please read each statement and think how often you felt that way last week.
    <br>Then tick the box which is closest to this.</p>

    <div>

    <!-- Added date and name to form here - values are extracted as part of SaveAnswersController - OR should whole form be under a /userid route?-->
    <!-- needs flex grid or similar here to layout QA better - form submits to saveUser -->
    <div class="flex-grid">
    <form method="post" action="/saveAnswers">
        <div class="formNameDate">
            <label for="existingUserID">Name of Existing User:</label>
            <select name="existingUserID">
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
                    <?php $q_id = $singleQuestionPoints["q_id"];
                    echo $singleQuestionPoints["q_id"];  

                    // $radioButtonGroupName = 'radioQ' . $singleQuestionPoints["q_id"] . 'AnswerPoints'
                    ?>
                    <!-- // <?php echo $radioButtonGroupName ?> -->
                    <?php echo $singleQuestionPoints["question"]  ?>
                    <!-- each radio button group for each answer needs a  unique name!! 
                    need var name based on Q id, then name radiobutton group based on that eg radioQ1AnswerPoints, radioQ2AnswerPoints
                    <input type="radio" name="optradio[<?php echo $i; ?>]" value="b">Option 2</label>
                    INSTEAD start by using the array here eg radioAnswerPoints[x], so its easier to access from Controller page
                    name="radioAnswerPoints[<?php echo $singleQuestionPoints["q_id"] ?>]" 
           
                    -->
                </div>  <!-- for col flex grid -->

                    <!-- this is now working well; BUT is echo  better syntax than short < ? = syntax  - YES per Mike  -->
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

    <!-- <h2>Alt way of making table</h2> -->
    <?php
    // echo '<ol>';
    // foreach($coreQuestionsAndPoints as $singleQuestionPoints) {
    //     echo '<li>' 
    //     . $singleQuestionPoints["question"] 
    //     . '<div class="radioGroupAnswers">'
    //       . ' <input type="radio" id="answerNot" name="answerPoints" value="' . $singleQuestionPoints["pointsA_not"] . '">'
    //       .  '<label for="answerNot">Not</label>'
    //         . '<input type="radio" id="answerOnly" name="radioAnswerPoints" value="'. $singleQuestionPoints["pointsB_only"] .'">'
    //        . '<label for="answerOnly">Only</label>'
    //       . '</div>'

    //     .' </li>';
    //     }
    // echo '</ol>';
    ?>


    <!-- <h2>Tasks To Do</h2> -->
    <!-- <?php
    // echo '<ol>';
    // foreach($tasks as $task) {
    // //        echo '<li>' . $task["item"] . '</li>';
    // //        echo '<a href="/markAsComplete/' . $task["id"] . '">Completed</a>';

    // //show task in a list, with a link to Complete each task
    //     echo '<li>' . $task["item"] . ' &nbsp <a href="/markAsComplete/' . $task["id"] . '">Completed</a> </li>';
    // }
    // echo '</ol>';
    ?> -->


</body>
</html>
