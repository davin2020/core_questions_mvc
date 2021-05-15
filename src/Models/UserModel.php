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
		$query = $this->db->prepare('SELECT `user_id`, `nickname`, `date_joined` FROM `users`;');
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
		$result = $query->fetchAll();
		return $result;
	}

	//get single user based on their id
	public function getUserFromID(int $userID)
	{
		$query = $this->db->prepare('SELECT `user_id`, `nickname`, `date_joined` FROM `users` WHERE `user_id` = :pl_user_id;');
		$result = $query->execute(['pl_user_id' => $userID]);
		$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
		$result = $query->fetch();
		return $result;
	}

	// 13may2021 also need func getUserFromEmail(string $emailAddress)


	//should i salt & hash pwd in UserModel or SaveUserController? - prob best to hash it as soon as u receive it  - its really part of Model, not Controller
	public function registerUser(string $fullname, string $nickname, string $email, string $password, string $dateJoinedToday) 
	{

		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
		
		$lastID = 0; // non valid id - issue with below stmt was typo!
		$query = $this->db->prepare('INSERT INTO `users` (`fullname`, `nickname`, `email`, `password`, `date_joined`) VALUES (:pl_fullname, :pl_nickname, :pl_email, :pl_password, :pl_date_joined);');

		$result = $query->execute(['pl_fullname' => $fullname, 'pl_nickname' => $nickname, 'pl_email' => $email, 'pl_password' => $hashedPassword, 'pl_date_joined' => $dateJoinedToday]);
		//fyi date being inserted is 14may despite today being 00:30 on 15may, as its using UTC not current local timezone

		if ($result) {
			$lastID = $this->db->lastInsertId();
		}
		return $lastID;
	}

	// TODO form need validation to stop future dates from being entered
	// should date be passsed in as a string, or a Date object?
	public function saveUser(string $user, string $date_joined)
	{
		$query = $this->db->prepare('INSERT INTO `users` (`nickname`, `date_joined`) VALUES (:pl_nickname, :pl_date_joined);');
		$result = $query->execute(['pl_nickname' => $user, 'pl_date_joined' => $date_joined]);
		return $result;
	}

}