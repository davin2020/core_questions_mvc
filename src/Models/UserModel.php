<?php

namespace App\Models;

// updated for CoreQuestions wrt Users
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


public function getUsers()
    {
        $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
        $result = $query->fetchAll();
        return $result;
    }


public function getUserFromID($userID)
    {
        $query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users` WHERE `user_id` = :pl_user_id;');
        // $query->execute();
        $result = $query->execute(['pl_user_id' => $userID]);
        $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
        $result = $query->fetch();
        return $result;
    }


// ISSUE - why is date on form a string representation of a date? how to make  the string-date on the html Form into a php Date datatype eg casting?
// need form or php validation - to stop future dates from being entered
public function saveUser(string $user, string $date_joined)
    {
        $query = $this->db->prepare('INSERT INTO `users` (`name`, `date_joined`) VALUES (:pl_name, :pl_date_joined);');
        $result = $query->execute(['pl_name' => $user, 'pl_date_joined' => $date_joined]);
        return $result;
    }


}