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

//replaced from getCompletedTasks
public function getUsers()
    {
        $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); //wher is class Task or User or UserModel or CoreQuestions actually defined??
        $result = $query->fetchAll();
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