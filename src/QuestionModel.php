<?php

namespace App;

// updated for CoreQuestions - but should i have a QuestionModel and a UserModeL??
class QuestionModel
{
    private $db;

    /**
     * QuestionModel constructor.
     * @param $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

// Dav new functions - saveAllAnswers, getAllQuestions, getHistoricalQA but this involves updating multiple tables - so start with !saveUser, getUsers, !getQuestions, getQuestionsAndPoints

//replaced from getCompletedTasks
public function getQuestions()
    {
        $query = $this->db->prepare('SELECT `q_id`, `questions`, `gp_order`, `points_type` FROM `ref_core_questions`;');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'CoreQuestion');
        $result = $query->fetchAll();
        return $result;
    }

//replaced from saveTask, takes 2 params
public function saveUser(string $user, date $date_joined)
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