<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt User

class SaveUserController
{
	private $userModel;

	public function __construct($userModel)
	{
		$this->userModel = $userModel;
	}

	public function __invoke($request, $response, $args)
	{
			// $dataName = $request->getParsedBody()['itemName'];
		//should this string date be converted to a Date object before being passed into saveUser()?
			// $dataDate = $request->getParsedBody()['itemDate'];

			// $result = $this->userModel->saveUser($dataName, $dataDate);
		// var_dump($result); //this is just true or false depending on whether save worked or not - need to do something else if this fails!


		// NEW register user stuff 13may2021
		// https://phptherightway.com/#password_hashing
		// Never ever (ever) trust foreign input introduced to your PHP code. Always sanitize and validate foreign input before using it in code. The filter_var() and filter_input() functions can sanitize text and validate text formats (e.g. email addresses).
		// Sanitization removes (or escapes) illegal or unsafe characters from foreign input.
		// For example, you should sanitize foreign input before including the input in HTML or inserting it into a raw SQL query. When you use bound parameters with PDO, it will sanitize the input for you.
		$fullName = $request->getParsedBody()['inputFullName'];
		$nickname = $request->getParsedBody()['inputNickname'];
		$email = $request->getParsedBody()['inputEmail'];
		$password = $request->getParsedBody()['inputPassword'];
		// $dateJoined = $request->getParsedBody()['itemDate'];
		// $dateJoined = new date(today);

		//when creating new user at 00:30 it comes up with yestedays date, as its actually inserting UTC datetimke !
		$dateJoinedToday = date("Y-m-d H:i:s");                   
		// 2001-03-10 17:16:18 (the MySQL DATETIME format)

		// CAVEAT need to salt & hash pwd BEFORE calling this method to put it into db 
		// string password_hash( string $pass, int $algo, array $options )
// use password_hash() method. This method takes three parameters and returns a final hash of that password.
		// $options: It is the salting part. It takes salt in form cost factor. It is optional, if left empty, default cost is added to the string (It is 10 in most cases). Note that more cost leads to a more protective password and thus puts heavy load on CPU.
// 		Warning
// The salt option has been deprecated as of PHP 7.0.0. It is now preferred to simply use the salt that is generated by default.
// 		If omitted, a random salt will be created and the default cost will be used.
// 		Caution
// Using the PASSWORD_BCRYPT as the algorithm, will result in the password parameter being truncated to a maximum length of 72 characters.

		// password_verify() to compare pwd
		// password_verify ( string $password , string $hash ) : bool

// ISSUE in mvc shoudl i salt/hash pwd in controller or model?? 15may2021
		 // in your controller, use a try-catch block to catch the exception thrown by the login steps and proceed as you wish to display the exception message to the user.
		// It should be hashed just before writing it to the database
		 // the only code that hashes the password should be the user object.
		//prob best to do it in the model?
		// 2013 - probably wouldn't be bad practice to call unset([$_POST['password']) right after it's hashed too
		//  https://stackoverflow.com/questions/14392085/where-do-i-hash-the-password
		
		// create the hashed password
		//NEW 26may - call hashPassword as soon as we get it from user
		$hashedPassword = $this->userModel->hashPassword(password);
		// $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
		
		//compare user supplied pwd against hashed pwd in db - on login page only, not on saveUser page!
		//$pwdMatches = password_verify ($password, $hashedPassword);

		// var_dump($hashedPassword);
		// var_dump($pwdMatches);
		// exit;

		// $hashedPassword = false;
		//returns false on failure - what to do in taht case??
		//store hashed pwd in db as long as hashing didnt fail
		// if ($hashedPassword != false) {
			// var_dump('hashed pwd value: ' . $hashedPassword);
			// exit;

			// $newUserID = $this->userModel->registerUser($fullName, $nickname, $email, $hashedPassword, $dateJoinedToday);
		// }

	//only save pwd if hash came back ok!
		//ISSUE 26may this is being used to both save a new  user from login page and save a user from admin page tha tonly has name & date fields as inputs!
	$resultNewUserID = $this->userModel->registerUser($fullName, $nickname, $email, $hashedPassword, $dateJoinedToday);

		// get latest id from user table, so can pass to dashboard page
		// waht if i as admin want to save/register a user?? id wnat to redirect to admin page not users own page - but why would admin person really want/need to register a user??

		//redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
		// need to add success message if user  is saved ok!

		//how to get id back and redirect to id page? $registerResult should be id of new user

		// 13may201 redirect to ADMIN Page for now, redirect to dashboard page works ok now
		$newUserIDint = (int) $resultNewUserID;
		// UserModel->getUserFromID('$newUserIDint') - how to pass ID from one controller to the next?? eg via assoc array? - had variable inside '/dashboard' string instead of concat'd to it!
		return $response->withHeader('Location', '/dashboard/' . $newUserIDint)->withStatus(302);
		// return $response->withHeader('Location', '/admin')->withStatus(302);
	}

}