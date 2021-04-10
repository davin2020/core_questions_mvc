<?php

namespace App\Models;

require_once './../vendor/autoload.php';
// require_once ('jpgraph/jpgraph.php');
// require_once ('jpgraph/jpgraph_line.php');
// require_once( "jpgraph/jpgraph_date.php" );
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
// use Amenadiel\JpGraph\LinePlot;
use Amenadiel\JpGraph\Themes;
use Amenadiel\JpGraph\UniversalTheme;
// dir C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\themes

//above stuff is for graphs

// updated for CoreQuestions wrt Answers- but should i have a QuestionModel and a UserModeL that are separte ??
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

// Dav new functions - saveAllAnswers, getAllQuestions, getHistoricalQA but this involves updating multiple tables - so start with !saveUser, getUsers, !getQuestions, getQuestionsAndPoints

//replaced from getCompletedTasks
//need to get all answers for a given user, incl dates of different answers
// WIP - change query  
//re score_date shoudd it be just a Date or a DateTime?

    // to get php date from string
    // echo date('l, M d, Y', strtotime($yourDate));
    // echo date('l, M d, Y', strtotime('2012-05-29')); // Tuesday, May 29, 2012

//core outcomes wrt Subjective Wellbeing, Problems/Symptoms, Functioning, Risk

public function getUserAnswers(int $userID)
    {
        // SELECT ucs_id, score_date, overall_score FROM user_core_score WHERE user_id=1;

        // `id` = :pl_id;');
        // $result = $query->execute(['pl_id' => $id]);

        //how to pass number is as a placeholder value??
        //why cant i just get the name out here and return it all in one go?
        $query = $this->db->prepare('SELECT `ucs_id`, `score_date`, `overall_score` FROM `user_core_score` WHERE `user_id` = :pl_user_id;');

        // $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
        // $query->execute();
        $result = $query->execute(['pl_user_id' => $userID]);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'AnswerModel'); //wher is class Task or User or UserModel or CoreQuestions actually defined??
        $result = $query->fetchAll();
        return $result;
    }


// new graph stuff f9april
    //redit - One thing I ran into frequently during development was that jpgraph outputs binary. So any text you echo (including error messages) in your script will cause a jpgraph error. Just use output buffering, then ob_end_clean() before stroking the graph.
//original static pie chart - no longer needed
// public function getUserAnswersGraph(int $userID) {
    
//     // Create the Pie Graph.
//    $graph = new Graph\PieGraph(350, 250);
//    $graph->title->Set("A Simple Pie Plot");
//    $graph->SetBox(true);
//    //changing Frame/Box stuff here doenst seem to make much differnece?
//    // $graph->SetFrame(false);
//     // $graph->SetFrame(true,'gray',0);

//    $data = array(40, 21, 17, 14, 23);
//    $p1   = new Plot\PiePlot($data);
//    $p1->ShowBorder();
//    $p1->SetColor('black');
//    $p1->SetSliceColors(array('#1E90FF', '#2E8B57', '#ADFF2F', '#DC143C', '#BA55D3'));

//    $graph->Add($p1);
//    // $graph->Stroke();
//    return $graph;
// }



//10april Davin from tutorial - https://jpgraph.net/features/src/show-example.php?target=new_line1.php
// prob best to have a function that generates the graph, when u pass it a 1D array of numbers ?

    //whats if theres a years worth of data, might need to berak it down into 3 month chunks?
//used this method to experiemtn with graphs
private function makeGraph(array $values) {
    // $datay1 = array(55,45,52,40); //pretned this is overall score
    // $values array contains these 2 other arrays
    // $tmpArray[0] = $tmpArrayScores;
    // $tmpArray[1] = $tmpArrayDates;

    //y is dates from table - 
    //ISSUES - how to specify them in DMY format and how to show relevant amount of space if some weeks/months have been missed withn the scale? - need to amke scale slanted so words dont overlap each other
    $data_ydates = $values[1];

    $datay1 = $values[0]; //pretned this is overall score
    $datay2 = array(12,9,16); //pretend this is Wellness score etc
    $datay3 = array(25,37,32,30); //pretend this is productivyt score etc

    // Setup the graph - have to call Graph\Graph for some reason?
    //graph w width & height - height best at 400 so legend doesnt cover y axis labels
    $graph = new Graph\Graph(500,400);
    // $graph->SetScale("textlin");
    //x y = dat lin , or datint, wher lin=linear ie decimals not ints
    // $graph->SetScale("datlin"); //for dates
    //show y scale from 0 until 130, otherwise it starts at lowest value
    $graph->SetScale('datlin',0,130,0,0);
     // $graph->yaxis->scale->SetAutoMin(0);
    // DateScale::SetTimeAlign($aStartAlign,$aEndAlign)
    // DateScale::SetDateAlign($aStartAlign,$aEndAlign)
    // DAYADJ_1, Align on the start of a day
    // MONTHADJ_1, Align on a month start
    // $graph->xaxis->scale->SetTimeAlign( HOURADJ_2 );

    //is this not working cos it doenst know its really a date? - maybe read in data from db and ensure that s date?
    $graph->xaxis->scale->SetDateAlign( MONTHADJ_1 );
    $graph->xaxis->scale->ticks->Set(1);


    // it cant seem to find this class
    $theme_class=new Themes\UniversalTheme;

    $graph->SetTheme($theme_class);
    $graph->img->SetAntiAliasing(false);
    $graph->title->Set('Overall Score Over Time');
    $graph->title->SetMargin(12);
    $graph->SetBox(false);

    //added titles - 'dates' is written over slatned text-dates so hidden it for now
    // $graph->xaxis->title->Set('(dates)');
    $graph->yaxis->title->Set('Overall Score');

    //set angle of text on x axis - butnow  text is behind labls & overflowing the graph area
    $graph->xaxis->SetLabelAngle(45);

    //only first digit seems to affect anything ie left margin
    //args are left margin, right, top, last doesnt seem to do much?
    //3rs arg is top margin, 2nd is right
    // this is with legend just under title
    // $graph->SetMargin(60,30,70,63);
    //this is with legend at theh very bottom
    $graph->SetMargin(60,30,40,63);

    $graph->img->SetAntiAliasing();

// y graph needs to start at 0
    $graph->yaxis->HideZeroLabel();
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false,false);

    //need to do stuff with x axis dates here
    $graph->xgrid->Show();
    $graph->xgrid->SetLineStyle("solid");
    // data_ydates
    $graph->xaxis->SetTickLabels($data_ydates);
    //original y labels
    // $graph->xaxis->SetTickLabels(array('Mar 7','Mar 14','Mar 21','Mar 28'));
    $graph->xgrid->SetColor('#E3E3E3');

    // Create the first line - search for LInePlot in directory and found it insdie Plot folder - C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\plot
    //create the linear plot - orig color 6495ED 417fea
    $p1 = new Plot\LinePlot($datay1);
    //add the plot to the graph
    $graph->Add($p1); 
    $p1->SetColor("#3167bf");
    $p1->SetFillColor('blue@0.8');
    //how to move label down?
    $p1->SetLegend('Overall Score');

    //set legend positino
    // Legend::SetPos($aX,$aY,$aHAlign='right',$aVAlign='top')

    // // Create the second line
    // $p2 = new Plot\LinePlot($datay2);
    // $graph->Add($p2);
    // $p2->SetColor("#6495ED");
    // $p2->SetLegend('Wellness Score');

    // // Create the third line
    // $p3 = new Plot\LinePlot($datay3);
    // $graph->Add($p3);
    // $p3->SetColor("#FF1493");
    // $p3->SetLegend('Productivity Score');

    $graph->legend->SetFrameWeight(1);

    //set legend positino - syntax, max value is 1
    // Legend::SetPos($aX,$aY,$aHAlign='right',$aVAlign='top')
    //this is with legend just under title
    // $graph->legend->SetPos(0.5,0.15,'center','bottom');
    //this is with legend at theh very bottom
    $graph->legend->SetPos(0.5,0.98,'center','bottom');

    // Output line - need to stroke/render graph later!
    //This important line displays the graph - 
    // from tutorial at https://jpgraph.net/download/manuals/chunkhtml/ch04s02.html#fig.sunspotsex1 -  
    // "This line instructs the library to actually create the graph as an image, encode it in the chosen image format (e.g. png, jpg, gif, etc) and stream it back to the browser with the correct header identifying the data stream that the client receives as a valid image stream. When the client (most often a browser) calls the PHP script it will return data that will make the browser think it is receiving an image and not, as you might have done up to now from PHP scripts, text."
    // $graph->Stroke(); 
    return $graph;
}



// 10 April - this is the graph method im using for now
private function makeLineGraph(array $values) {
    //y is dates from table - 
    //ISSUES - how to specify them in DMY format and how to show relevant amount of space if some weeks/months have been missed withn the scale? - need to amke scale slanted so words dont overlap each other
    $data_ydates = $values[1];
    $datay1 = $values[0]; //pretned this is overall score

    // Setup the graph - have to call Graph\Graph for some reason?
    //graph w width & height - height best at 400 so legend doesnt cover y axis labels
    $graph = new Graph\Graph(500,400);
    //x y = dat lin , or datint, wher lin=linear ie decimals not ints
    // $graph->SetScale("datlin"); //for dates on x and linear on y
    //show y scale from 0 until 130, otherwise it starts at lowest value eg 40
    $graph->SetScale('datlin',0,130,0,0);


    //is this not working cos it doenst know its really a date? - maybe read in data from db and ensure that s date?
    //CAVEAT - graph may not plot lines if there are only 2 inputs/dates, not sure if this is related 
    // error about - Type: Amenadiel\JpGraph\Util\JpGraphExceptionL
        // Code: 0
        // Message: Minor or major step size is 0. Check that you haven't got an accidental SetTextTicks(0) in your code. If this is not the case you might have stumbled upon a bug in JpGraph. Please report this and if possible include the data that caused the problem
        // File: C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\util\JpGraphError.php
        // Line: 33

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


//syntax - foreach (iterable_expression as $key => $value)
public function getUserAnswersLineGraph(int $userID) {
    $tmpArray = [];
    $tmpArrayScores = [];
    $tmpArrayDates = [];

//convert string to date
// $time_input = strtotime("2011/05/21"); 
// $date_input = getDate($time_input); 
// print_r($date_input);    

    $tmpResults = $this->getUserAnswers($userID);
    foreach($tmpResults as $index => $value) {
        $tmpArrayScores[$index] = $value['overall_score'];
        //this doesnt work, neitherh does (date) in front of $value
        // $tmpArrayDates[$index] = getDate($value['score_date']);
        $tmpArrayDates[$index] = $value['score_date'];
    }
    $tmpArray[0] = $tmpArrayScores;
    $tmpArray[1] = $tmpArrayDates;
    // var_dump($tmpArray);
    // exit;

    // $tmpArray = [25,45,52,30];
    // $tmpArray = [55,45,52,40];
    // $this->db = $db;
    $tmpGraph = $this->makeLineGraph($tmpArray);
    return $tmpGraph;
}




//replaced from saveTask, saveAnswers needs to take an array of Q & points values, plus user_id and score_date and calculate the overall_score BUT socre is based on sum of answer points - so how to deal with that? ie put logic in model or elswhere eg Controller? ask Mike

// WIP - change insert and what to pass in here
    // saveAnswers($dataQid, $dataQpoints $totalScore);
    // TODO save array of values ie answers to each question
    
// public function saveAnswers(int $dataQid, int $dataQpoints, int $totalScore) - this is really SaveUserAnswers
public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)
    {
        //update user_core_score with user_id, score_date & overall_score
        $query = $this->db->prepare('INSERT INTO `user_core_score` (`user_id`, `score_date`, `overall_score`) VALUES (:pl_user_id, :pl_score_date, :pl_overall_score);');
        $result = $query->execute(['pl_user_id' => $userID, 'pl_score_date' => $scoreDate, 'pl_overall_score' => $totalScore]);
        //need to return new unique id ucs_id from table!
        // var_dump($result); //returns true if update worked ok
        // exit;

        //update user_core_answers with q_id and points, but for each question, hence need array of values!
        // $query = $this->db->prepare('INSERT INTO `user_core_answers` (`q_id`, `points`) VALUES (:pl_q_id, :pl_points);');
        // $result = $query->execute(['pl_q_id' => $dataQid, 'pl_points' => $dataQpoints]);
        // var_dump($this->db );
        // exit;

        //this totally works!! just need to capture user_id - and maybe need better way to find q_id instead of assuming that results are in order
        $lastID = $this->db->lastInsertId();
        //new stuff suing array - dataArrayAnswers for i, => means $key for $item
        // $lastID = 4;
        $ucsID = 4;
        foreach($dataArrayAnswers as $qID => $answerValue ) {
            $query = $this->db->prepare('INSERT INTO `user_core_answers` (`ucs_id`, `q_id`, `points`) VALUES (:pl_ucs_id, :pl_q_id, :pl_points);');
            $result = $query->execute(['pl_ucs_id' => $lastID, 'pl_q_id' => $qID, 'pl_points' => $answerValue]);
        }
        
        return $result; //do i stil need to return something?
    }
 
 //this is working ok now
public function calculateScore(array $questionPoints) 
{
        $sum = 0;
        //iterate over array and sum points
        foreach($questionPoints as $item )
            $sum += $item;
        return $sum;
}


//core outcomes wrt Subjective Wellbeing, Problems/Symptoms, Functioning, Risk
//which questions are for what type, then add scores?
// this ONLY applies to 34 Q version, not short 14 Q GP version!




/*

// original graph stuff thats working ok
private function makeGraphOriginal(array $values) {
    // $datay1 = array(55,45,52,40); //pretned this is overall score

    $datay1 = $values; //pretned this is overall score
    $datay2 = array(12,9,16); //pretend this is Wellness score etc
    $datay3 = array(25,37,32,30);

    // Setup the graph - have to call Graph\Graph for some reason?
    $graph = new Graph\Graph(350,250);
    // $graph->SetScale("textlin");
    $graph->SetScale("datlin"); //for dates

    // it cant seem to find this class
    $theme_class=new Themes\UniversalTheme;

    $graph->SetTheme($theme_class);
    $graph->img->SetAntiAliasing(false);
    $graph->title->Set('Overall Score Over Time');
    $graph->SetBox(false);

    //only first digit seems to affect anything ie left margin
    $graph->SetMargin(40,20,36,63);

    $graph->img->SetAntiAliasing();

    $graph->yaxis->HideZeroLabel();
    $graph->yaxis->HideLine(false);
    $graph->yaxis->HideTicks(false,false);

    //need to do stuff with x axis dates here
    $graph->xgrid->Show();
    $graph->xgrid->SetLineStyle("solid");
    $graph->xaxis->SetTickLabels(array('Mar 7','Mar 14','Mar 21','Mar 28'));
    $graph->xgrid->SetColor('#E3E3E3');

    // Create the first line - search for LInePlot in directory and found it insdie Plot folder - C:\wamp64\www\nvc_project\gp_core\php_mvc_questions\core_questions_mvc\vendor\amenadiel\jpgraph\src\plot
    $p1 = new Plot\LinePlot($datay1);
    $graph->Add($p1);
    $p1->SetColor("#6495ED");
    $p1->SetLegend('Overall Score');

    // Create the second line
    $p2 = new Plot\LinePlot($datay2);
    $graph->Add($p2);
    $p2->SetColor("#B22222");
    $p2->SetLegend('Wellness Score');

    // Create the third line
    $p3 = new Plot\LinePlot($datay3);
    $graph->Add($p3);
    $p3->SetColor("#FF1493");
    $p3->SetLegend('Productivity Score');

    $graph->legend->SetFrameWeight(1);

    // Output line - need to stroke/render graph later!
    // $graph->Stroke(); 
    return $graph;
}

*/



/*
    public function saveTask(string $task)
    {
        $query = $this->db->prepare('INSERT INTO `tasks` (`item`, `isCompleted`) VALUES (:pl_item, :pl_isCompleted);');
        $result = $query->execute(['pl_item' => $task, 'pl_isCompleted' => 0]);
        return $result;
    }

    public function markAsCompleted(int $id)
    {
        $query = $this->db->prepare('UPDATE `tasks` SET `isCompleted` = 1 WHERE `id` = :pl_id;');
        $result = $query->execute(['pl_id' => $id]);
        return $result;
    }

    public function deleteTask(int $id)
    {
        $query = $this->db->prepare('DELETE FROM `tasks` WHERE `id` = :pl_id;');
        $result = $query->execute(['pl_id' => $id]);
        return $result;
    }


    public function getUncompletedTasks()
    {
        $query = $this->db->prepare('SELECT `id`, `item` FROM `tasks` WHERE `isCompleted` = 0;');
        // could use $this->db->query('SELECT stmt') if not passing in any placeholder vars?
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Task');
        $result = $query->fetchAll();
        return $result;
    }

    public function getCompletedTasks()
    {
        $query = $this->db->prepare('SELECT `id`, `item` FROM `tasks` WHERE `isCompleted` = 1;');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Task');
        $result = $query->fetchAll();
        return $result;
    }
*/

}