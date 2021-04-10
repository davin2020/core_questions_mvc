<!DOCTYPE html>
<?php error_reporting(E_ALL); ini_set('display_errors', '1'); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GP-Core Questions Form - User History</title>
    <link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
<h1>Core Questions App</h1>

<h2>User Graph for <?php echo $userName ?></h2>
<!-- make graph based on array of data, then use above data -->

<div>
    <!-- dyanmic graph showing overall score, if present - needs error handling! -->
    <img src="data:image/png;base64,<?php echo(base64_encode($userLineGraph)); ?>" />
</div>


<h2>User History for <?php echo $userName ?></h2>
<!-- output date & score, show username above -->

    <?php
    foreach ($userHistory as $item) { 
    ?>
        <!-- <p>RowID: <?php echo $item["ucs_id"] ?> --  -->
         <p>Date: <?php echo $item["score_date"] ?> -- 
            Score: <?php echo $item["overall_score"] ?> </p>
    <?php 
    }
    ?>


<h6><a href="/">Return to Core Questions Homepage</a></h6>


<!--  EXTRA STUFF  -->

<!--  alt syntax
   <?php
    echo '<ul>';
    foreach($usersList as $user) {
        echo '<li>' . $user["name"] . ' Date Joined: ' . $user["date_joined"] .' </li>';
        }
    echo '</ul>';
    ?>
 -->



<?php
// $assocArrayArgs['graphTest'] = $userGraph; 
// $graphTest;
     // var_dump($graphTest);
     // exit;
    // $graphTest->Stroke();
 ?>



<!-- static graph -->
<!-- <img src="data:image/png;base64,<?php echo(base64_encode($graphTest)); ?>" /> -->


<!-- alt otpion to embed chart , likely needs php page with graph already in it??-->
<!-- <img src="linear_graph_customer.php?values=1,2,3,4|1,2,3,4|1,2,3,4&title=CHART&width=500&height=300" width="500" height="300" class="img" /> -->



</body>
</html>

