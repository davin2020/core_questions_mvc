<?php

namespace App\Models;

// updated for CoreQuestions wrt Users
class UserModel
{
	// do models need indiv vars that correspond to fields in db entity ie name, nickname, email etc?
	private $db;
	private $userId;

	/**
	 * UserModel constructor.
	 * @param $db
	 */
	//  shoudl this constructor actually contain things like name, email, pwd etc? - check my Collections app or Ecommerce?
	public function __construct($db)
	{
		$this->db = $db;
	}

	//this is getting all users, but should only get them if they are not deleted, once a soft-delete flag is added to db table - false is returne on failure of any FETCH - this is doing FETCH_BOTH by default!
	//TODO 28july should get all fields from db ie fulname and email, but this would only reallly be called from new Admin page; use FETCH_ASSOC (or actualy make a User class?)
	public function getUsers()
	{
		$query = $this->db->prepare('SELECT `user_id`, `nickname`, `date_joined` 
			FROM `users` ORDER BY `user_id` ;');
		$query->execute();
		//need to user fetch_mode correctly!
		// FETCH_OBJ FETCH_ASSOC
		// $object = $stm->fetch(FETCH_CLASS | PDO :: FETCH_CLASSTYPE);

		$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); 
		$result = $query->fetchAll(); // shoudl i be calling execute() instead of fetch() here ? NO cause im calling it above, so maybe need to set fetch mode BEFORE calling exec?? - per https://www.php.net/manual/en/pdostatement.setfetchmode.php
		return $result;
	}

	//get single user based on their id - this returns 'duplicated' data like this - 
	// array(6) { ["user_id"]=> string(2) "30" [0]=> string(2) "30" ["nickname"]=> string(5) "test6" [1]=> string(5) "test6" ["date_joined"]=> string(10) "2021-05-15" [2]=> string(10) "2021-05-15" }
	// TODO use ASSOC fetch mode and get all feidls from db! @20may - do i need to get pwd in case user wants to change it, ie need pwd reset feature!
	// TODO 28july rename to GetUserById()
	public function getUserFromID(int $userID)
	{
		$query = $this->db->prepare(
			'SELECT `user_id`, `fullname`, `nickname`,`email`, `date_joined` 
			FROM `users` WHERE `user_id` = :pl_user_id;'
		);
		$result = $query->execute(['pl_user_id' => $userID]);
		//$query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel');  // no wonder tis wasnt giving an error, as it wasnt really doing anythign!
		
		// Set the fetch mode right after you call prepare(). It appears you _must_ use execute() - fetch() won't work
		//this is better way of doing fetch, w assoc array
		$result = $query->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	public function getUserByEmail(string $userEmail)
	{
		$query = $this->db->prepare('SELECT * FROM `users` 
			WHERE `email` = :pl_email;');
		$result = $query->execute(['pl_email' => $userEmail]);
		// $query->setFetchMode(\PDO::FETCH_CLASS, 'UserModel'); //this is actually doing nothing atm!
		// $result = $query->fetch();
		// ok this works to return data lik this - 
		// array(6) { ["user_id"]=> string(2) "30" ["fullname"]=> string(5) "test6" ["nickname"]=> string(5) "test6" ["email"]=> string(17) "test6@example.com" ["password"]=> string(60) "$2y$10$drABEk1kJR5WDgjPWDVCt.Xn1jmwbBeUIgEpCOONxQrfo33CXZh8q" ["date_joined"]=> string(10) "2021-05-15" }
		$result = $query->fetch(\PDO::FETCH_ASSOC);
		return $result;
	}

	//should i salt & hash pwd in UserModel or SaveUserController? - prob best to hash it as soon as u receive it  - its really part of Model, not Controller - but now thsi function does 2 things, hashes pwd & save/registers user!
	// what about PSR formatting for long type hinted method signatures over 80 chars long?
	// TODO form need validation to stop future dates from being entered!
	public function registerUser(string $fullname, string $nickname, string $email, string $hashedPassword, string $dateJoinedToday) 
	{

		$lastID = 0; // non valid id - issue with below stmt was typo!
		$query = $this->db->prepare('INSERT INTO `users` 
			(`fullname`, `nickname`, `email`, `password`, `date_joined`) 
			VALUES (:pl_fullname, 
			:pl_nickname, 
			:pl_email, 
			:pl_password, 
			:pl_date_joined);');

		$result = $query->execute(['pl_fullname' => $fullname, 
			'pl_nickname' => $nickname, 
			'pl_email' => $email, 
			'pl_password' => $hashedPassword, 
			'pl_date_joined' => $dateJoinedToday]);
		// ISSUE date being inserted is 14may despite today being 00:30 on 15may, as its using UTC not current local timezone! how/where to set/use timezone?

		if ($result) {
			$lastID = $this->db->lastInsertId();
		}
		return $lastID;
	}


	// separate function to hash passwords so can be called from any controller, also function has single responsibilty instead of being part of saveUser()
	public function hashPassword(string $newPassword):string {
		return password_hash($newPassword, PASSWORD_BCRYPT);
	}

	// separate function to verify passwords so can be called from any controller, also function has single responsibilty instead of being part of loginUser() - errors if null
	public function verifyPassword(string $userSuppliedPassword, string $existingDbPassword):bool {
		return password_verify ($userSuppliedPassword, $existingDbPassword);
	}


	// Original function, only called by Admin Page, so only name & date being saved
	public function saveUser(string $user, string $date_joined)
	{
		$query = $this->db->prepare('INSERT INTO `users` 
			(`nickname`, `date_joined`) 
			VALUES (:pl_nickname, :pl_date_joined);');
		$result = $query->execute(['pl_nickname' => $user, 
			'pl_date_joined' => $date_joined]);
		return $result;
	}

}