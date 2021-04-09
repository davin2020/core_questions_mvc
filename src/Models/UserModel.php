<?php

namespace App\Models;

// updated for CoreQuestions wrt Users- but should i have a QuestionModel and a UserModeL that are separte ??
class UserModel
{
    private $db;

    /**
     * UserModel constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

// Dav new functions - saveAllAnswers, getAllQuestions, getHistoricalQA but this involves updating multiple tables - so start with !saveUser, getUsers, !getQuestions, getQuestionsAndPoints

public function getUsers()
    {
        $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); //wher is class Task or User or UserModel or CoreQuestions actually defined??
        $result = $query->fetchAll();
        return $result;
    }

public function getUserFromID($userID)
    {
        $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users` WHERE `user_id` = :pl_user_id;');
        // $query->execute();
        $result = $query->execute(['pl_user_id' => $userID]);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); //wher is class Task or User or UserModel or CoreQuestions actually defined??
        $result = $query->fetch();
        return $result;
    }

//replaced from saveTask, takes 2 params - can date be string representation of a date? YES but how to make  the string-date on the html Form into a php Date datatype eg casting?
// validation  - need to stop future dates from being entered
public function saveUser(string $user, string $date_joined)
    {
        $query = $this->db->prepare('INSERT INTO `users` (`name`, `date_joined`) VALUES (:pl_name, :pl_date_joined);');
        $result = $query->execute(['pl_name' => $user, 'pl_date_joined' => $date_joined]);
        return $result;
    }


}