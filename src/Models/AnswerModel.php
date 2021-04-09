<?php

namespace App\Models;

require_once './../vendor/autoload.php';
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;
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
public function getUserAnswersGraph(int $userID) {
    
    // Create the Pie Graph.
   $graph = new Graph\PieGraph(350, 250);
   $graph->title->Set("A Simple Pie Plot");
   $graph->SetBox(true);
   //changing Frame/Box stuff here doenst seem to make much differnece?
   // $graph->SetFrame(false);
    // $graph->SetFrame(true,'gray',0);

   $data = array(40, 21, 17, 14, 23);
   $p1   = new Plot\PiePlot($data);
   $p1->ShowBorder();
   $p1->SetColor('black');
   $p1->SetSliceColors(array('#1E90FF', '#2E8B57', '#ADFF2F', '#DC143C', '#BA55D3'));

   $graph->Add($p1);
   // $graph->Stroke();
   return $graph;
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