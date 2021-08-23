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
		// $result = $query->execute();
		// $result = $query->fetch();
		return $result;
	}

	//chck this out 16may2021
	/*

	https://www.php.net/manual/en/pdostatement.setfetchmode.php
	To fetch the rows into an existing instance of a class, use PDO::FETCH_INTO and pass the object as the second parameter.

	The class _must_ have the column names declared as public members, or the script will die. But overloading with __set() and __get() lets it handle any column your query throws at it. 

	Set the fetch mode right after you call prepare(). It appears you _must_ use execute() - fetch() won't work. A small example, adapted from ext/pdo/tests/pdo_025.phpt:

	class Test
	{
	    protected $cols;
	   
	    function __set($name, $value) {
	        $this->cols[$name] = $value;
	    }
	   
	    function __get($name) {
	        return $this->cols[$name];
	    }
	}

	$obj = new Test();
	$db = PDOTest::factory();
	$stmt = $db->prepare("select * from test");
	$stmt->setFetchMode(PDO::FETCH_INTO, $obj);
	$stmt->execute();

	*/

	// 13may2021 also need func getUserFromEmail(string $emailAddress)
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
		// $result = $sth->fetch(PDO::FETCH_ASSOC);
		//FETCH_ASSOC

		//before returning should i check the pwd matches here??
		// or shoudl i have multiple methods eg getUserByEmail, verifyPassword and call tehm from another method eg loginUser(), but then what/how to return values so the caller can deal with user existing or not, and pwd being correct or not?
		return $result;
	}

	//should i salt & hash pwd in UserModel or SaveUserController? - prob best to hash it as soon as u receive it  - its really part of Model, not Controller - but now thsi function does 2 things, hashes pwd & save/registers user!
	// what about PSR formatting for long type hinted method signatures over 80 chars long?
	public function registerUser(string $fullname, string $nickname, string $email, string $hashedPassword, string $dateJoinedToday) 
	{
		// pwd has already been hashed
		//$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

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
		//fyi date being inserted is 14may despite today being 00:30 on 15may, as its using UTC not current local timezone

		if ($result) {
			$lastID = $this->db->lastInsertId();
		}
		return $lastID;
	}

	/*
	public function loginUser(string $userEmail, string $userPassword)
	{
		//either return teh user or return false?
		$userResultsArray = [];

		$existingUser = $this->getUserByEmail($userEmail);

		//this now returns a bool of true, so how to get the user that was returned from the db?
		// $tempUser = $this->userModel->getUserFromID(30);
		

		//check pwds here on in UserModel.getUserbyEmail or LoginUser? in UserModel
		$existingHashedPassword = $existingUser['password'];
		$user_id = $existingUser['user_id'];
		// $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		//compare pwd from user form against its hash from db
		$pwdMatches = password_verify ($userPassword, $existingHashedPassword);

		//now return something based on the match?
		$userResultsArray['pwdMatches'] = $pwdMatches;
		$userResultsArray['existingUser'] = $existingUser;
		

		//why dont i decide where to redirect the user to next in here, then just return the next page to go to?? thsi all seems overly complex! when surely its easier for the controller to decide
		return $userResultsArray;
	}
	*/

	// separate function to hash passwords so can be called from any controller, also function has single responsibilty instead of being part of saveUser()
	public function hashPassword(string $newPassword):string {
		return password_hash($newPassword, PASSWORD_BCRYPT);
	}

	// separate function to verify passwords so can be called from any controller, also function has single responsibilty instead of being part of loginUser() - errors if null
	public function verifyPassword(string $userSuppliedPassword, string $existingDbPassword):bool {
		return password_verify ($userSuppliedPassword, $existingDbPassword);
	}

/*
URLS
$userResultsArrayTemp = [];
		if ($doesPwdMatch) {
			return $response->withHeader('Location', '/dashboard/' . $user_id)->withStatus(200);
		}
		if (!$doesUserExist) {
			$userResultsArrayTemp['messageForUser'] = "user does not exist, please check spelling and try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}
		else {
			$userResultsArrayTemp['messageForUser'] = "wrong password, please try again";
		return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}

*/

	// TODO form need validation to stop future dates from being entered
	// should date be passsed in as a string, or a Date object?
		// this is orignal function now on Admin page, hence only name & date being saved
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