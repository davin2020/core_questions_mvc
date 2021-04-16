<?php

namespace App\Models;

// updated for CoreQuestions wrt Users
class UserModel
{

	// do models need indiv vars that correspond to fields in db entity?
	private $db;

	/**
	 * UserModel constructor.
	 * @param $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	//this is getting all users, but should only get them if they are not deleted, once a soft-delete flag is added to db table
	public function getUsers()
	{
		$query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users`;');
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
		$result = $query->fetchAll();
		return $result;
	}

	//get single user based on their id - 8april
	public function getUserFromID(int $userID)
	{
		$query = $this->db->prepare('SELECT `user_id`, `name`, `date_joined` FROM `users` WHERE `user_id` = :pl_user_id;');
		$result = $query->execute(['pl_user_id' => $userID]);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
		$result = $query->fetch();
		return $result;
	}

	// TODO form need validation to stop future dates from being entered
	public function saveUser(string $user, string $date_joined)
	{
		$query = $this->db->prepare('INSERT INTO `users` (`name`, `date_joined`) VALUES (:pl_name, :pl_date_joined);');
		$result = $query->execute(['pl_name' => $user, 'pl_date_joined' => $date_joined]);
		return $result;
	}

}