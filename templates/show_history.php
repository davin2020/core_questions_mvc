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

<div>
    <!-- dyanmic graph showing overall score, if present - needs error handling as doesnt display if only 2 dates! -->
    <img src="data:image/png;base64,<?php echo(base64_encode($userLineGraph)); ?>" />
</div>


<h2>User History for <?php echo $userName ?></h2>

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


</body>
</html>

