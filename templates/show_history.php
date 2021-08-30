<!DOCTYPE html>
<!-- <?php error_reporting(E_ALL); ini_set('display_errors', '1'); ?> -->

<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Wellbeing Tracker - My History</title>
	<link href="../style.css" type="text/css" rel="stylesheet">
</head>

<body>
	<h1>Wellbeing Tracker - My History</h1>

	<section>

		<h2>User Graph Details for <?php echo $userName ?></h2>

		<div>
			<!-- dyanmic graph showing array of dates on x axis and overall scores on y axis, if present - or blank graph if no data points exist to draw graph from -->
			<img class="graph_image" src="data:image/png;base64,<?php echo(base64_encode($userLineGraph)); ?>" />
		</div>

	</section>

	<section>
	
		<h2>User History Details for <?php echo $userName ?></h2>

		<?php  
			//success msg if answeres to wellbeing questions are saved ok 
			if ( isset($_GET['success']) && $_GET['success']== 1) {
				echo '<em>Your Answers have been Saved</em>';
			} 
			if (count($userHistory) == 0 ) {
				echo '<em>No dates or scores to display and above graph will be blank</em>';
			}
		?>

		<!-- start table w header here  -->
		<table class="history_table" border="3" cellpadding="8" cellspacing="3">
	         <tr>
	            <th>Date</th>
	            <th>Total Score</th>
	            <th>Mean Score</th>
	         </tr>

		<?php
			foreach ($userHistory as $item) { 
				//changes String rep of date into actual Date object (DateTimeInterface), then format it nicely
				$stringDate =  $item["score_date"];
				$dateObj = date_create($stringDate);
				// format date syntax - l = full name of day, j = day of month, F = full month name, Y = year in 4 digits, eg Saturday 20 February 2021
				$formattedDate = date_format($dateObj, 'l j F Y');
		?>
				<!--  loop thru data & display in table for now  -->
				<tr>
		            <td><?php echo $formattedDate ?></td>
		            <td align="center"><?php echo $item["overall_score"] ?> </td>
		            <td align="center"><?php echo $item["mean_score"] ?></td>
	         	</tr>		
		<?php 
			}
		?>
		</table>

	</section>
	
	<br>
	<a href="/dashboard">Return to Dashboard</a>
	<br>

</body>
</html>