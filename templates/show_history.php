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
		<!-- dyanmic graph showing overall scores, if present - or blank graph if no data points exist to draw graph from -->
		<img src="data:image/png;base64,<?php echo(base64_encode($userLineGraph)); ?>" />
	</div>


	<h2>User History for <?php echo $userName ?></h2>
	<!-- for this user name - output date & score -->

		<?php
		if (count($userHistory) == 0 ) {
			echo '<em>No dates or scores to display and above graph will be blank</em>';
		}
		foreach ($userHistory as $item) { 
			//change String rep of date into actual Date object (DateTimeInterface), then format it nicely
			$stringDate =  $item["score_date"];
			$dateObj = date_create($stringDate);
			// format date syntax - l = full name of day, j = day of month, F = full month name, Y = year in 4 digits, eg Saturday 20 February 2021
			$formattedDate = date_format($dateObj, 'l j F Y');
		?>
			<!-- <p>RowID: <?php echo $item["ucs_id"] ?> --  -->
			<p>Date: <?php echo $formattedDate ?> -- 
				Score: <?php echo $item["overall_score"] ?> </p>
		<?php 
		}
		?>

	<h6><a href="/">Return to Core Questions Homepage</a></h6>


</body>
</html>