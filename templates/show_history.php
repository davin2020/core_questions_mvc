<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To Do App - Completed Items</title>
    <link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
<h1>Core Questions App</h1>
<!-- where to get this usernae from ie how to acess it? -->
<!-- <?php echo $userName ?>  -->

<h2>User History for <?php echo $userName ?></h2>
<!-- output date & score, show username above -->

    <?php
    foreach ($userHistory as $item) { 
    ?>
        <p>RowID: <?php echo $item["ucs_id"] ?> -- 
            Date: <?php echo $item["score_date"] ?> -- 
            Score: <?php echo $item["overall_score"] ?> </p>
    <?php 
    }
    ?>

<!-- <?php
            foreach($usersList as $user) { ?>
              <option value="<?php echo $user["user_id"]?>"><?php echo $user["name"]?></option>
            <?php 
            }
            ?>
 -->

<!--     <?php
    echo '<ul>';
    foreach($usersList as $user) {
        echo '<li>' . $user["name"] . ' Date Joined: ' . $user["date_joined"] .' </li>';
        }
    echo '</ul>';
    ?>
 -->



<h6><a href="/">Return to Core Questions Homepage</a></h6>

</body>
</html>

