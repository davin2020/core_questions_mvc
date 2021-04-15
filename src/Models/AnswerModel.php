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


	//core outcomes wrt Subjective Wellbeing, Problems/Symptoms, Functioning, Risk
	//which questions are for what type, then add scores?
	// this ONLY applies to 34 Q version, not short 14 Q GP version!


	// new graph stuff f9april
		//redit - One thing I ran into frequently during development was that jpgraph outputs binary. So any text you echo (including error messages) in your script will cause a jpgraph error. Just use output buffering, then ob_end_clean() before stroking the graph.
	

	
	// prob best to have a function that generates the graph, when u pass it a one Dimensional array of numbers/values taken from db
	// BUT whats if theres a years worth of data, might need to break it down into 3 month chunks, and pass in arg about timeframe/timescale ?
	// ISSUE with amount of time/gap between dates being uneven/unrelated to the dates themselves once graph is plogged - try this
	// "found my answer in DataScaleUtils library" - https://stackoverflow.com/questions/14254417/jpgraph-x-axis-scale

	// 10 April - this is the graph method im actually using, internally within this model
	private function makeLineGraph(array $values) 
	{
		//y is dates from table - 
		//ISSUES - how to specify them in DMY format and how to show relevant amount of space if some weeks/months have been missed withn the scale? - need to amke scale slanted so words dont overlap each other

		//14 april - maybe dates need to look this like format ie Timestamp rather than Date? YES!
		// $xdata = array('1125810000', '1125896400', '1125982800', '1126414800', '1126501200');
		// Per http://www.timelordz.com/jpdocs/html/8101Usingtheautomaticdatetimescale.html - 
		// It is important to note that the input data on the X-axis must be a in the form of timestamp data, i.e. the number of seconds from the system epoch. In PHP the current timestamp value is returned by the function time().
		$data_y_dates = $values[1]; //if there are no values how to not show graph?
		$data_x_values = $values[0]; // this is overall score

		// Setup the graph - have to call Graph\Graph for some reason?
		//graph w width & height - height best at 400+ so legend doesnt cover y axis labels
		$graph = new Graph\Graph(500,450);
		//x y = dat lin , or datint, wher lin=linear ie decimals not ints
		// $graph->SetScale("datlin"); //for dates on x and linear on y
		//show y scale from 0 until 60, otherwise it starts at lowest value eg 40 - 60 is for GP-CORE with 14Qs, 130 is for CORE-OM with 34Qs
		$graph->SetScale('datint',0,60,0,0);

		//is this not working cos it doenst know its really a date? - maybe read in data from db and ensure that s date?
		//CAVEAT - graph may not plot lines if there are only 2 inputs/dates - need to check for amount of data before trying to plot graph!

		// error about - Type: Amenadiel\JpGraph\Util\JpGraphExceptionL
			// Code: 0
			// Message: Minor or major step size is 0. Check that you haven't got an accidental SetTextTicks(0) in your code. If this is not the case you might have stumbled upon a bug in JpGraph. Please report this and if possible include the data that caused the problem
			// File: C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\util\JpGraphError.php
			// Line: 33


		$theme_class = new Themes\UniversalTheme;
		$graph->SetTheme($theme_class);
		$graph->img->SetAntiAliasing(false);

		$graph->title->Set('Overall Score Over Time');
		$graph->title->SetMargin(12);
		$graph->SetBox(false);

		//added separate titles - but 'dates' is written over slatned  text-dates so hidden it for now
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

		//need to do stuff with x axis dates here
		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle("solid");

		// $graph->xaxis->SetTickLabels($data_y_dates); // not needed any more
		$graph->xgrid->SetColor('#E3E3E3');

		//create the linear plot - orig color 6495ED 417fea
		$p1 = new Plot\LinePlot($data_x_values, $data_y_dates);

		//add the plot to the graph
		$graph->Add($p1); 
		$p1->SetColor("#3167bf");
		$p1->SetFillColor('blue@0.8');
		$p1->SetLegend('Overall Score');

		// after graph is plotted, THEN u can format it
		// $graph->xaxis->scale->SetDateAlign( MONTHADJ_1 );
		// $graph->xaxis->scale->ticks->Set(1);

		//stuff about x axis date scales, but doenst seem to work for me, maybe pkgs arent being imported ok?? - https://jpgraph.net/download/manuals/chunkhtml/ch14s10.html
			// ALIGN: DAYADJ_1 = Align on the start of a day
		
		$const_days = 7*60*60*24;
		$graph->xaxis->scale->ticks->Set($const_days);
		// $graph->xtick_factor = 7;
			// $graph->xaxis->scale->SetDateFormat( 'D j M y' );
			//'l, M d, Y' OR D j M y OR 'H:i' 
		// $graph->xaxis->scale->SetDateAlign( DAYADJ_1 );

		// $graph->xaxis->SetLabelFormatString('M, Y',true); //this isnt doing anything?

		//should i be using this instead to format the dates?? what are Date options, not Time options?
		// $graph->xaxis->scale->SetDateFormat('H:i');

		//from this url but may not work since ive now preformatted my dates ?? - https://board.phpbuilder.com/d/10309017-jpgraph-date-scale-single-days-on-x-axis/3
		// x then y
		$graph->SetTickDensity(TICKD_SPARSE,TICKD_VERYSPARSE);
		 // $graph->SetTickDensity( TICKD_DENSE);
		$graph->xaxis->scale->SetDateFormat('D j M y');

		$graph->legend->SetFrameWeight(1);
		//this is with legend at the very bottom
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

		//maybe this doenst get called if its null/empty?
		foreach($tmpResults as $index => $value) {
			$tmpArrayScores[$index] = $value['overall_score'];
			//this doesnt work, neitherh does casting to (date) in front of $value
			// $tmpArrayDates[$index] = getDate($value['score_date']);

			
			//13April - chagne date format to include day - ok but graph needs more space at bottom now
			//what would this chart look like in Excel??

			// 15april errror handing if no data fro graph 
				// // $d=mktime(8, 12, 2014);
				// $d = strtotime('today');
				// // $currentDate = new date("Y-m-d");
				// $scoreDate = (isset($value["score_date"])) ? $value["score_date"] : $d;
				// var_dump($scoreDate);
				// exit;

			$scoreDate =  $value["score_date"];
			$tmpDate2 = date_create($scoreDate);
			// $phpTimestamp = time($scoreDate);
			$tmpDate3 = $tmpDate2->getTimestamp(); //turn date into timestamp to plot on graph - NEXT turn TS into date using graph pkg
			$tmpArrayDates[$index] = $tmpDate3;

			// var_dump($tmpDate3);
			// exit;

			/*
			$tmpDate = date_create($scoreDate);
			$scoreDateFormatted = date_format($tmpDate, 'D j M y');
			// Format: D = 3 letters for day, j= day of month, F = full month, M = 3 letters for month, y = 2 digits for year
			$tmpArrayDates[$index] = $scoreDateFormatted;
			// echo $date; // this is now a Date object but chart shows NO dates at all - error 'Object of class DateTime could not be converted to string'
			*/


			//14 april - for now let graph format the x axis dates, currently in YYYY MM DD format
			// $tmpArrayDates[$index] = $value['score_date'];
		}

		//show teimstamtp for today - this works to make a fake/empty graph
		if (count($tmpArrayScores) == 0) {
			$d = strtotime('today');
			$tmpArrayScores = [0,0];
			$tmpArrayDates = [$d,$d];
		}

		//make assoc array instead of indexed array?
		$arrayGraphValues[0] = $tmpArrayScores;
		$arrayGraphValues[1] = $tmpArrayDates; 
		//these should be Dates not Strings now, but they are not, they are dates formatted as Strings like Fri 9 April 21, cause it wont let me pass Date objs around
		// var_dump($arrayGraphValues);
		// exit;

		//handle error of no line graph data
		// $userHistoryGraph = null;
		// try {
		// 	$userHistoryGraph = $this->makeLineGraph($arrayGraphValues);
		// }
		// //JpGraphExceptionL
		// catch (Error $e) {
		// 	echo '<em>No graph data to display</em>';
		// }

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
 
	//adds up all the individual values for each answer the user selected
	//can unit test this, any func that doesnt touch db - use array sum funtion instead !
	public function calculateScore(array $questionPoints) 
	{
		$sum = 0;
		//iterate over array and sum points
		foreach($questionPoints as $item )
			$sum += $item;
		return $sum;
	}


	// SYNTAX to convert string to date
	// $time_input = strtotime("2011/05/21"); 
	// $date_input = getDate($time_input); 
	// print_r($date_input);    

	// to get php date from string
	// echo date('l, M d, Y', strtotime($yourDate));
	// echo date('l, M d, Y', strtotime('2012-05-29')); // Tuesday, May 29, 2012


// Output line - need to stroke/render graph later!
		//This important line displays the graph - 
		// from tutorial at https://jpgraph.net/download/manuals/chunkhtml/ch04s02.html#fig.sunspotsex1 -  
		// "This line instructs the library to actually create the graph as an image, encode it in the chosen image format (e.g. png, jpg, gif, etc) and stream it back to the browser with the correct header identifying the data stream that the client receives as a valid image stream. When the client (most often a browser) calls the PHP script it will return data that will make the browser think it is receiving an image and not, as you might have done up to now from PHP scripts, text."
		// $graph->Stroke(); 


}