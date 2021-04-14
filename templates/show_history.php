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
	<!-- make graph based on array of data from db (dates & scores shown below) -->

	<div>
		<!-- dyanmic graph showing overall scores, if present - needs error handling ie at least 3 data points are needed to draw graph, otherwise gets slim error -->
		<img src="data:image/png;base64,<?php echo(base64_encode($userLineGraph)); ?>" />
	</div>


	<h2>User History for <?php echo $userName ?></h2>
	<!-- for this user name - output date & score -->

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

	<?php
	// issue that graph over writes the whole page!
	// $assocArrayArgs['graphTest'] = $userGraph; 
	// $graphTest;
	// var_dump($graphTest);
	// exit;
	// $graphTest->Stroke();
	 ?>


	<!-- original static graph -->
	<!-- <img src="data:image/png;base64,<?php echo(base64_encode($graphTest)); ?>" /> -->


	<!-- alt otpion to embed chart , likely needs php page with graph already in it??-->
	<!-- <img src="linear_graph_customer.php?values=1,2,3,4|1,2,3,4|1,2,3,4&title=CHART&width=500&height=300" width="500" height="300" class="img" /> -->


</body>
</html>