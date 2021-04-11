<?php

namespace App\Models;

require_once './../vendor/autoload.php';

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
use Amenadiel\JpGraph\Themes;
use Amenadiel\JpGraph\UniversalTheme;

// updated for CoreQuestions wrt Answers- but do i also need a UserModeL here?
class AnswerModel
{
    private $db;
    //should this have private vars like score_date, overall_score etc

    /**
     * AnswerModel constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }


// get date & overall score based on user id
public function getUserAnswers(int $userID)
    {
        //why cant i also just get the name out here and return it all in one go?
        $query = $this->db->prepare('SELECT `ucs_id`, `score_date`, `overall_score` FROM `user_core_score` WHERE `user_id` = :pl_user_id ORDER BY `score_date`;');

        $result = $query->execute(['pl_user_id' => $userID]);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'AnswerModel'); 
        $result = $query->fetchAll();
        return $result;
    }


// this actually generates the line graph itself
private function makeLineGraph(array $values) {
    //y is dates from table - 
    //ISSUES - how to specify them in DMY format and how to show relevant amount of space if some weeks/months have been missed withn the scale? 
    $data_ydates = $values[1];
    $datay1 = $values[0]; //pretned this is overall score

    // Setup the graph - have to call Graph\Graph for some reason?
    //graph w width & height - height best at 400 so legend doesnt cover y axis labels
    $graph = new Graph\Graph(500,400);
    //x y = dat lin , or datint, wher lin=linear ie decimals not ints
    // $graph->SetScale("datlin"); //for dates on x and linear on y
    //show y scale from 0 until 60 for GP-Core with only 14Qs, otherwise it starts at lowest value eg 40
    $graph->SetScale('datlin',0,60,0,0);


    //is this not working cos it doenst know its really a date? - maybe read in data from db and ensure that s date?
    //CAVEAT - graph may not plot lines if there are only 2 inputs/dates, not sure if this is related 

    // $graph->xaxis->scale->SetDateAlign( MONTHADJ_1 );
    // $graph->xaxis->scale->ticks->Set(1);

    $theme_class=new Themes\UniversalTheme;
    $graph->SetTheme($theme_class);
    $graph->img->SetAntiAliasing(false);

    $graph->title->Set('Overall Score Over Time');
    $graph->title->SetMargin(12);
    $graph->SetBox(false);

    //added separate titles - 'dates' is written over slatned text-dates so hidden it for now
    // $graph->xaxis->title->Set('(dates)');
    $graph->yaxis->title->Set('Overall Score');

    //set angle of text on x axis 
    $graph->xaxis->SetLabelAngle(45);

    //this is with legend at theh very bottom
    $graph->SetMargin(60,30,40,63);
    $graph->img->SetAntiAliasing();

    $graph->yaxis->HideZeroLabel();
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false,false);

    //need to do stuff with x axis dates here
    $graph->xgrid->Show();
    $graph->xgrid->SetLineStyle("solid");

    // data_ydates
    $graph->xaxis->SetTickLabels($data_ydates);
    $graph->xgrid->SetColor('#E3E3E3');

    //create the linear plot - orig color 6495ED 417fea
    $p1 = new Plot\LinePlot($datay1);

    //add the plot to the graph
    $graph->Add($p1); 
    $p1->SetColor("#3167bf");
    $p1->SetFillColor('blue@0.8');
    $p1->SetLegend('Overall Score');

    $graph->legend->SetFrameWeight(1);
    //this is with legend at theh very bottom
    $graph->legend->SetPos(0.5,0.98,'center','bottom');
    return $graph;
}


// get line graph based on user id and their answers over time
public function getUserAnswersLineGraph(int $userID) {
    $tmpArray = [];
    $tmpArrayScores = [];
    $tmpArrayDates = [];

    $tmpResults = $this->getUserAnswers($userID);
    foreach($tmpResults as $index => $value) {
        $tmpArrayScores[$index] = $value['overall_score'];
        //this doesnt work, neitherh does (date) in front of $value
        // $tmpArrayDates[$index] = getDate($value['score_date']);
        $tmpArrayDates[$index] = $value['score_date'];
    }
    $tmpArray[0] = $tmpArrayScores;
    $tmpArray[1] = $tmpArrayDates;

    $tmpGraph = $this->makeLineGraph($tmpArray);
    return $tmpGraph;
}



// this is really SaveUserAnswers
public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)
    {

        $query = $this->db->prepare('INSERT INTO `user_core_score` (`user_id`, `score_date`, `overall_score`) VALUES (:pl_user_id, :pl_score_date, :pl_overall_score);');
        $result = $query->execute(['pl_user_id' => $userID, 'pl_score_date' => $scoreDate, 'pl_overall_score' => $totalScore]);
        // var_dump($result); //returns true if update worked ok
        // exit;

        // need to return new unique id ucs_id from table!
        // now just need to capture user_id - and maybe need better way to find q_id instead of assuming that results are in order
        $lastID = $this->db->lastInsertId();
        //new stuff using array - dataArrayAnswers for i, => means $key for $item
        foreach($dataArrayAnswers as $qID => $answerValue ) {
            $query = $this->db->prepare('INSERT INTO `user_core_answers` (`ucs_id`, `q_id`, `points`) VALUES (:pl_ucs_id, :pl_q_id, :pl_points);');
            $result = $query->execute(['pl_ucs_id' => $lastID, 'pl_q_id' => $qID, 'pl_points' => $answerValue]);
        }
        
        return $result; //do i stil need to return something?
    }
 

public function calculateScore(array $questionPoints) 
{
        $sum = 0;
        //iterate over array and sum points
        foreach($questionPoints as $item )
            $sum += $item;
        return $sum;
}


}
