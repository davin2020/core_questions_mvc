<!DOCTYPE html>
<html lang="en">
<html>
<!-- Davin updated this file for CoreQuestions - BUT style.css is not being found! -->
<head>
    <meta charset="utf-8"/>
    <title>Core Question Form</title>
    <!-- <link href="/templates/style2.css" type="text/css" rel="stylesheet"> -->
    <link href="style.css" type="text/css" rel="stylesheet">
    <link href='//fonts.googleapis.com/css?family=Lato:400' rel='stylesheet' type='text/css'>

    <!-- temp inline stylesheet -->
    <style>
        body { 
            background-color: #FCFFCD;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
         }
        p { color: #fff; }
        h2 {
            color: #FF8F35;
        }
        span {
            border: 1px solid green;
        }
        div {
            border: 1px solid red;
        }


    </style>

</head>

<body>
    <h1>Core Question Form</h1>

    <h2>Show Existing Users</h2>
    <!-- hwo to call this ??? the Controller for this page ie HomePageController is where these variables are coming from eg $userList -->
    <?php
    echo '<ul>';
    foreach($usersList as $user) {
        echo '<li>' . $user["name"] . ' Date Joined: ' . $user["date_joined"] .' </li>';
        }
    echo '</ul>';
    ?>

    <h2>Add A New User</h2>
    <!-- where is item being refered to, when getting data from Form ? -->
    <form method="post" action="/saveUser">
        <label for="item">Name of New User:</label>
        <input type="text" name="itemName" id="itemName">
        <br>
        <label for="item">Date Joined for New User:</label>
        <input type="Date" name="itemDate" id="itemDate">
        <button name="btnAddItem" type="submit">Add</button>
    </form>
    <br>


<!--     <h2>Question Table</h2> -->
    <!-- need a table here for Questions -->
    <?php
    // echo '<ol>';
    // foreach($coreQuestions as $singleQuestion) {
    //     echo '<li>' . $singleQuestion["question"] . ' PointsType ' . $singleQuestion["points_type"] .' </li>';
    //     }
    // echo '</ol>';
    ?>

    <h2>Question Table with Points</h2>
    <!-- what will these fields be named?  
        Should i include the gp_order here or is it enought that qs are already sorted in the right order?
        maybe try writing out hte html and just using php for the variable values? 
        Need to get linked css working so can style this
        On next iteration, get labels from DB
        Later - Need to org table into 2 columns using flex or grid for mobile & swop div for span, plus put labels at the top of the columns
    -->

    <div>
<!-- needs flex grid or similar here -->
        <div>
            <ol>
                <?php foreach($coreQuestionsAndPoints as $singleQuestionPoints) { ?>
                <li>
                    <?php echo $singleQuestionPoints["question"]  ?>

                    <div class="radioGroupAnswers">
                        <input type="radio" id="answerNot" name="radioAnswerPoints" value=" 
                            <?php echo $singleQuestionPoints["pointsA_not"] ?> ">
                        <label for="answerNot">Not</label>

                        <input type="radio" id="answerOnly" name="radioAnswerPoints" value="
                            <?php echo $singleQuestionPoints["pointsB_only"] ?> ">
                        <label for="answerOnly">Only</label>

                        <input type="radio" id="answerSometimes" name="radioAnswerPoints" value="
                            <?php echo $singleQuestionPoints["pointsC_sometimes"] ?> ">
                        <label for="answerSometimes">Sometimes</label>

                        <input type="radio" id="answerOften" name="radioAnswerPoints" value="
                            <?php echo $singleQuestionPoints["pointsD_often"] ?> ">
                        <label for="answerOften">Often</label>

                        <input type="radio" id="answerMost" name="radioAnswerPoints" value="
                            <?php echo $singleQuestionPoints["pointsE_most"] ?> ">
                        <label for="answerMost">Most</label>
                    </div>

                </li>
                <?php } ?>
            </ol>
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

    <br><br>
    <h3><a href="/completedTasks">View Historical QA - Later</a></h3>

</body>
</html>
