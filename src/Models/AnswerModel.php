<?php

namespace App\Models;

require_once './../vendor/autoload.php';

//stuff required for plotting line graphs
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use Amenadiel\JpGraph\Graph\DateScale; //is this actually being included/used/referenced?
use Amenadiel\JpGraph\LinePlot; 
use Amenadiel\JpGraph\Themes;
use Amenadiel\JpGraph\UniversalTheme;

// updated for CoreQuestions wrt Answers - but should this be part of the UserModel instead?
// TODO rename to UserAnswerModel? as cant have answeres withtout a User?
class AnswerModel
{
	private $db;
	//should this have private vars like score_date, overall_score etc ? forogt to ask Mike this

	/**
	 * AnswerModel constructor.
	 * @param $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	//need to get all answers for a given user, incl dates of different answers
	// do i need to change query or db field?  shoudd score_date be just a Date or a DateTime?
	public function getUserAnswers(int $userID)
	{
		//why cant i just get the name out here too and return it all in one go?
		$query = $this->db->prepare('SELECT `ucs_id`, `score_date`, `overall_score`, `mean_score` 
			FROM `user_core_score` 
			WHERE `user_id` = :pl_user_id 
			ORDER BY `score_date`; ');
		$result = $query->execute(['pl_user_id' => $userID]);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'AnswerModel'); //should this really be part of the UserModel?
		$result = $query->fetchAll();
		return $result; // are dates in the resultset actually Dates or Strings?
	}

  
	// Pass in a one dimensional array of values from db in order to generate graph
	// BUT whats if theres a years worth of data, might need to break it down into 3 month chunks, and pass in arg about timeframe/timescale ?
	private function makeLineGraph(array $values) 
	{
		//date to plot on graph axis - input data on the X-axis must be a in the form of Timestamp data eg $xdata = array('1125810000', '1125896400', '1125982800', '1126414800', '1126501200');
		$data_y_values = $values[0]; // these are the overall scores
		$data_x_dates = $values[1]; // these are the dates
		// var_dump('<br>dates on x axis');
		// var_dump($data_x_dates);
    
		// Setup the graph - have to call Graph\Graph for some reason?
		//create graph with set width & height - height best at 400+ so legend doesnt cover y axis labels
		$graph = new Graph\Graph(500,450);

		//SetScale() first arg is x y = dat lin, or datint, where dat=date and lin=linear (ie decimals not ints) and int=integer - means Dates are on x and int Score values are on y
		//second arg is show y scale from 0 until 60, otherwise it starts at lowest current value eg 40 - FYI max of 60 is for GP-CORE with 14 Questions, max of 130 is for OM-CORE-OM with 34 Questions
		$graph->SetScale('datint',0,60,0,0);

		// CAVEAT - graph may not plot lines if there are less than 2 inputs/dates - see getUserAnswersLineGraph() which now generates a fake blank graph

		$theme_class = new Themes\UniversalTheme;
		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);

		$graph->title->Set('Overall Score Over Time');
		$graph->title->SetMargin(15);
		$graph->SetBox(false);

		//added separate titles - but 'Dates' is written over slanted  dates-strings so hidden it for now
		// $graph->xaxis->title->Set('Dates');
		$graph->yaxis->title->Set('Overall Score');

		//set angle of text on x axis 
		$graph->xaxis->SetLabelAngle(55);

		//this is with legend at the very bottom - args are left margin, right, top, last doesnt seem to do much?
		$graph->SetMargin(80,30,40,63);
		$graph->img->SetAntiAliasing();

		// add tick marks to either axis, or not
		$graph->yaxis->HideZeroLabel();
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);
		$graph->xaxis->HideTicks(false,false);

		//need to do stuff with x axis dates here
		//show vertical grid lines behind the graph itself
		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");

		// Puts dates on x axis but as Timestamps - not needed any more?
		// $graph->xaxis->SetTickLabels($data_x_dates); 
		$graph->xgrid->SetColor('#E3E3E3');

		//create the linear plot, with x and y values - orig color 6495ED 417fea
		$p1 = new Plot\LinePlot($data_y_values, $data_x_dates);

		//add the plot to the graph & set the main color
		$graph->Add($p1); 
		$p1->SetColor("#3167bf");
		// $p1->SetFillColor('blue@0.8');
		// ISSUE if i remove this legend, then dates at bottom are cut off, how to fix?
		$p1->SetLegend('Overall Score');

		// show data marks on graph - other marks are MARK_SQUARE, MARK_UTRIANGLE, MARK_DIAMOND, MARK_CIRCLE not filled, MARK_CROSS like +, MARK_STAR like *, MARK_X
		// 2nd arg is an int refering to a color and last arg is  % of original size
		// $p1->mark->SetType(MARK_IMG_DIAMOND,5,0.4);
		$p1->mark->SetType(MARK_FILLEDCIRCLE,'blue',0.6);
		$p1->mark->SetColor('blue');
		$p1->mark->SetFillColor('skyblue');
		$p1->mark->SetSize(4); //WIdth of mark in pixels
		//caveat = The colors of the marks will, if you don't specify them explicitly, follow the line color. Please note that if you want different colors for the marks and the line the call to SetColor() for the marks must be done after the call to the SetColor() for the line since the marks color will always be reset to the lines color when you set the line color.

		// show the actual data points eg score of 32 etc- $p1 is really $lineplot1
		// $p1->value->Show();
		// $p1->value->SetColor('darkred');
		// $p1->value->SetFormat('(%d)'); //int not DP
		
		// after graph is plotted, THEN u can format it! - this might be handy for spread out data?
		// $graph->xaxis->scale->SetDateAlign( MONTHADJ_1 );
		// $graph->xaxis->scale->ticks->Set(1);

		// x axis better date formatting, to set ticks every 7 days 
		// 28aug BUG if there are less than 7 days worth of data, those days WONT get labelled! unless u take into account the number of days present in DB first
		// var_dump('<br>COUNT DATES on x axis');
		// var_dump(count($data_x_dates));

		//add tick marks every 7 days, unless there are less than 7 days worth of data, then add tick  marks every day - CAVEAT that 7 days of data could be spread across multiple months etc
		// ISsue last tick is still missing fors user test10 with 5 days worht of data
		$numberOfDaysOfData = count($data_x_dates);
		if ($numberOfDaysOfData < 7) {
			$const_days = 1*60*60*24;
		}
		else {
			$const_days = 7*60*60*24;
		}

		$graph->xaxis->scale->ticks->Set($const_days);
		// $graph->xtick_factor = 7;

		// tick density on x, y axis
		$graph->SetTickDensity(TICKD_SPARSE,TICKD_VERYSPARSE);
		// $graph->SetTickDensity(TICKD_DENSE);
		
		// $graph->xaxis->SetLabelFormatString('M, Y',true); //this isnt doing anything?

		// formats date like 'Mon 4 Jan 21', other options are 'l, M d, Y' or 'D j M y' or 'H:i'
		$graph->xaxis->scale->SetDateFormat('D j M y');

		$graph->legend->SetFrameWeight(1);

		//this makes the legend appear at the very bottom, but if removed then legend appers ontop of the y axis Dates!
		$graph->legend->SetPos(0.5,0.99,'center','bottom');
		return $graph;
	}

	// this function can be called by external controllers, to get data from DB and start building a history graph for a given userID
	public function getUserAnswersLineGraph(int $userID) 
	{
		//setup arrays to hold values/data that will be used to build the line graph
		$arrayGraphValues = [];
		$tmpArrayScores = [];
		$tmpArrayDates = [];

		$tmpResults = $this->getUserAnswers($userID);

		//puts db data into an array while also turning each date into a timestamp for the graph library - maybe this doesnt get called if $tmpResults is null/empty?
		foreach($tmpResults as $index => $value) {
			$tmpArrayScores[$index] = $value['overall_score'];

			$scoreDate =  $value["score_date"]; //date currently in string YYYY MM DD format
			$tmpDate2 = date_create($scoreDate);
			// $phpTimestamp = time($scoreDate);
			$tmpDate3 = $tmpDate2->getTimestamp(); //turn Date into Timestamp to plot on graph - NEXT turn TimeStamp back into Date using graph pkg
			$tmpArrayDates[$index] = $tmpDate3;
		}

		//show timestamtp for today - this works to make a fake/empty graph, as using a try/catch block around null/no data in array didnt work
		if (count($tmpArrayScores) == 0) {
			$d = strtotime('today');
			$tmpArrayScores = [0,0];
			$tmpArrayDates = [$d,$d];
		}

		//Would it be better to make assoc array instead of indexed array?
		$arrayGraphValues[0] = $tmpArrayScores;
		$arrayGraphValues[1] = $tmpArrayDates; 

		//build the line graph with the values specified
		$userHistoryGraph = $this->makeLineGraph($arrayGraphValues);
		return $userHistoryGraph;
	}

	
	// maybe rename this to SaveUserAnswers ? as it saves the uses answer to each of the core questions - how to make floats stop at 2dp, or just store it all and format it later?
	public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore, float $meanScore)
	{
		$query = $this->db->prepare('INSERT INTO `user_core_score` 
			(`user_id`, `score_date`, `overall_score`, `mean_score`) 
			VALUES (:pl_user_id, :pl_score_date, :pl_overall_score, :pl_mean_score);');
		$result = $query->execute(['pl_user_id' => $userID, 'pl_score_date' => $scoreDate, 'pl_overall_score' => $totalScore, 'pl_mean_score' => $meanScore]);

		//need to return new unique id ucs_id from table user_core_score for next INSERT stmt, but ONLY if $result returns true/success - ie data has been inserted ok - TO DO add success checking
		$lastID = $this->db->lastInsertId();

		//update table user_core_answers with q_id and points, but for each question, hence need array of values! 
		// arrow => means $key/index for $item/element
		foreach($dataArrayAnswers as $index => $answerValue ) {
			$query = $this->db->prepare('INSERT INTO `user_core_answers` 
				(`ucs_id`, `q_id`, `points`) 
				VALUES (:pl_ucs_id, :pl_q_id, :pl_points);');
			$result = $query->execute(['pl_ucs_id' => $lastID, 'pl_q_id' => $index, 'pl_points' => $answerValue]);
		}
		
		return $result; //do i stil need to return something? or check that $result is true/succeeded in SaveAnswersControllers where this is called from ?
	}
 
	//adds up all the individual points for each answer the user selected  
	// FYI GP-CORE form only requires one Total score but other forms like OM-CORE has 34 Questions and so needs Subtotals for Subjective Wellbeing, Problems/Symptoms, Functioning and Risk - so need to know which questions are for which type, then can calculate Score
	public function calculateTotalScore(array $questionPoints) 
	{
		$sum = array_sum($questionPoints);
		return $sum;
	}

	// Per CST - Total score divided by number of items completed provided that 13 or all 14 items of GP-CORE have been completed. Don’t compute scores if more than one item omitted.)
	public function calculateMeanScore(int $totalScore, int $numberOfQuestions): float 
	{	
		$mean = 0.00;
		if ($numberOfQuestions > 0) {
			$mean = (float) $totalScore / $numberOfQuestions;
		}
		return $mean;
	}

}
