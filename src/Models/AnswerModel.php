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
// dir C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\themes

// updated for CoreQuestions wrt Answers - but should this be part of the UserModel instead?
// TODO rename to UserAnswerModel ?
// Per Mike - can use chart JS - php to return json to page - can output html xml or json - php5 was 2005 which is how old JPGraph library is
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
		$query = $this->db->prepare('SELECT `ucs_id`, `score_date`, `overall_score` FROM `user_core_score` WHERE `user_id` = :pl_user_id ORDER BY `score_date`; ');
		$result = $query->execute(['pl_user_id' => $userID]);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'AnswerModel'); //should this really be part of the UserModel?
		$result = $query->fetchAll();
		return $result; // are dates in the resultset actually Dates or Strings?
	}


	// 10 April - this is the graph method im actually using, internally within this model - pass in one dimensional array of values from db in order to generate graph
	// BUT whats if theres a years worth of data, might need to break it down into 3 month chunks, and pass in arg about timeframe/timescale ?
	private function makeLineGraph(array $values) 
	{
		//date to plot on graph axis - input data on the X-axis must be a in the form of Timestamp data eg $xdata = array('1125810000', '1125896400', '1125982800', '1126414800', '1126501200');
		$data_y_values = $values[0]; // these are the overall scores
		$data_x_dates = $values[1]; // these are the dates

		// Setup the graph - have to call Graph\Graph for some reason?
		//create graph with set width & height - height best at 400+ so legend doesnt cover y axis labels
		$graph = new Graph\Graph(500,450);

		//SetScale() first arg is x y = dat lin, or datint, where dat=date and lin=linear ie decimals not ints - means dates are on x and linear values are on y
		//second arg is show y scale from 0 until 60, otherwise it starts at lowest current value eg 40 - FYI max of 60 is for GP-CORE with 14 Questions, max of 130 is for OM-CORE-OM with 34 Questions
		$graph->SetScale('datint',0,60,0,0);

		// CAVEAT - graph may not plot lines if there are less than 2 inputs/dates - see getUserAnswersLineGraph() which generates a fake blank graph

		$theme_class = new Themes\UniversalTheme;
		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);

		$graph->title->Set('Overall Score Over Time');
		$graph->title->SetMargin(12);
		$graph->SetBox(false);

		//added separate titles - but 'dates' is written over slanted  text-dates so hidden it for now
		// $graph->xaxis->title->Set('(dates)');
		$graph->yaxis->title->Set('Overall Score');

		//set angle of text on x axis 
		$graph->xaxis->SetLabelAngle(55);

		//this is with legend at the very bottom
		//args are left margin, right, top, last doesnt seem to do much?
		$graph->SetMargin(80,30,40,63);
		$graph->img->SetAntiAliasing();

		$graph->yaxis->HideZeroLabel();
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);
		// add tick marks to x axis
		$graph->xaxis->HideTicks(false,false);

		//need to do stuff with x axis dates here
		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");

		// $graph->xaxis->SetTickLabels($data_x_dates); // not needed any more
		$graph->xgrid->SetColor('#E3E3E3');

		//create the linear plot, with x and y values - orig color 6495ED 417fea
		$p1 = new Plot\LinePlot($data_y_values, $data_x_dates);

		//add the plot to the graph
		$graph->Add($p1); 
		$p1->SetColor("#3167bf");
		$p1->SetFillColor('blue@0.8');
		$p1->SetLegend('Overall Score');
		
		// after graph is plotted, THEN u can format it!
		// $graph->xaxis->scale->SetDateAlign( MONTHADJ_1 );
		// $graph->xaxis->scale->ticks->Set(1);

		// x axis better date formatting, to set ticks every 7 days
		$const_days = 7*60*60*24;
		$graph->xaxis->scale->ticks->Set($const_days);
		// $graph->xtick_factor = 7;

		// x then y tick density
		$graph->SetTickDensity(TICKD_SPARSE,TICKD_VERYSPARSE);
		// $graph->SetTickDensity( TICKD_DENSE);
		
		// $graph->xaxis->SetLabelFormatString('M, Y',true); //this isnt doing anything?

		// formats date like 'Mon 4 Jan 21', other options are 'l, M d, Y' or 'D j M y' or 'H:i'
		$graph->xaxis->scale->SetDateFormat('D j M y');

		$graph->legend->SetFrameWeight(1);
		//this makes the legend appear at the very bottom
		$graph->legend->SetPos(0.5,0.99,'center','bottom');
		return $graph;
	}


	// this function can be called by external controllers, to start building a history graph for a given userID
	//can unit test thing, returns $graph - can't as gets stuff from DB!
	public function getUserAnswersLineGraph(int $userID) 
	{
		//setup arrays to hold values/data that will be used to build the line graph
		$arrayGraphValues = [];
		$tmpArrayScores = [];
		$tmpArrayDates = [];

		// I'm already calling this in ShowUserHistoryController - so why not jsut pass the results into - YES DO THIS to split functions up
		//answerModel->getUserAnswersLineGraph($args['q_id']) ??
		// $userAnswerHistory = $this->answerModel->getUserAnswers($args['q_id']);
		$tmpResults = $this->getUserAnswers($userID);

		//maybe this doesnt get called if $tmpResults is null/empty?
		foreach($tmpResults as $index => $value) {
			$tmpArrayScores[$index] = $value['overall_score'];

			$scoreDate =  $value["score_date"]; //date currently in string YYYY MM DD format
			$tmpDate2 = date_create($scoreDate);
			// $phpTimestamp = time($scoreDate);
			$tmpDate3 = $tmpDate2->getTimestamp(); //turn Date into Timestamp to plot on graph - NEXT turn TS back into Date using graph pkg
			$tmpArrayDates[$index] = $tmpDate3;
			// var_dump($tmpDate3);
			// exit;
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

	
	// maybe rename this to SaveUserAnswers ? as it saves the uses answer to each of the core questions
	public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)
	{
		//update table user_core_score with user_id, score_date & overall_score
		$query = $this->db->prepare('INSERT INTO `user_core_score` 
			(`user_id`, `score_date`, `overall_score`) 
			VALUES (:pl_user_id, :pl_score_date, :pl_overall_score);');
		$result = $query->execute(['pl_user_id' => $userID, 'pl_score_date' => $scoreDate, 'pl_overall_score' => $totalScore]);

		//need to return new unique id ucs_id from table ser_core_score, but ONLY if $result returns true - ie needs success checking
		$lastID = $this->db->lastInsertId();
		//above works, but just need to capture user_id - and maybe need better way to find q_id instead of assuming that q_id results are in order when inserting into next table below

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
	// FYI GP-CORE form only requires one Total score but other forms like OM-CORE has 34 Questions and so needs Subtotals for Subjective Wellbeing, Problems/Symptoms, Functioning and Risk - so need to know which questions are for which type then can calculate Score
	//can unit test this, as doesnt touch db
	public function calculateScore(array $questionPoints) 
	{
		$sum = array_sum($questionPoints);
		return $sum;
	}


}
