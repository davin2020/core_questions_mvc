<?php

namespace App\Models;

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


//need to get all answers for a given user, incl dates of different answers
// WIP - change query  
public function getUserAnswers(int $userID)
    {

        //why cant i just get the name out here and return it all in one go?
        $query = $this->db->prepare('SELECT `ucs_id`, `score_date`, `overall_score` FROM `user_core_score` WHERE `user_id` = :pl_user_id;');

        // $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
        // $query->execute();
        $result = $query->execute(['pl_user_id' => $userID]);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'AnswerModel'); //wher is class Task or User or UserModel or CoreQuestions actually defined??
        $result = $query->fetchAll();
        return $result;
    }


// WIP - change insert and what to pass in here
    // saveAnswers($dataQid, $dataQpoints $totalScore);
    // TODO save array of values ie answers to each question
    
// this is really SaveUserAnswers
public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)
    {
        //update user_core_score with user_id, score_date & overall_score
        $query = $this->db->prepare('INSERT INTO `user_core_score` (`user_id`, `score_date`, `overall_score`) VALUES (:pl_user_id, :pl_score_date, :pl_overall_score);');
        $result = $query->execute(['pl_user_id' => $userID, 'pl_score_date' => $scoreDate, 'pl_overall_score' => $totalScore]);

//this totally works!! just need to capture user_id - and maybe need better way to find q_id instead of assuming that results are in order?
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

}