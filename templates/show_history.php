<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do App - Completed Items</title>
    <link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
<h1>Core Questions App</h1>

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

</body>
</html>

