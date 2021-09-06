<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt User

class LoginUserController
{
	private $userModel;
	private $renderer;

	public function __construct($userModel, $renderer)
	{
		$this->userModel = $userModel;
		$this->renderer = $renderer;
	}

	public function __invoke($request, $response, $args)
	{

		$userEmail = $request->getParsedBody()['inputEmail'];
		$userPassword = $request->getParsedBody()['inputPassword'];

		$existingUser = $this->userModel->getUserByEmail($userEmail);

		//check if user exists, then compare pwds 
		if ($existingUser) {
			$existingHashedPassword = $existingUser['password'];
			//compare pwd from user form against its hash from db
			$pwdMatches = $this->userModel->verifyPassword($userPassword, $existingHashedPassword);
			//need id for redirection, but only if user exists!
			$user_id = $existingUser['user_id'];
		}
		
		//array to pass to next page w error msg and user in it - no longer needed now we have sessions?
		$userResultsArrayTemp = [];
		$userResultsArrayTemp['existingUser'] = $existingUser;

		// IF user found & pwd correct, then redirect to dashboard (later add token first), - prob best to use error msg rather than status codes?
		if ($existingUser != false && $pwdMatches == true) {
			
			//these session vars are avail gloablly on the same web domain so dont need to pass them between pages (CAVEAT that diff sites on localhost will have access to each others sessions, unless in incognito
			session_start();
			echo session_id();

			// put userid in session first  then redirect to dashboard page
			$_SESSION['session_name'] = "CORE_SESSION";
			$_SESSION['coreIsLoggedIn'] = true;
			$_SESSION['userId'] = $existingUser['user_id'];
			$_SESSION['existingUser'] = $existingUser;

			header("Location: /dashboard");
			exit;
			return $response->withHeader('Location', '/dashboard')->withStatus(200);
		}
		// else if user found & pwd WRONG, then redirect to login page w WRONG PWD errr msg ie 401 unauth
		else if ($existingUser != false && $pwdMatches == false)  {
			$userResultsArrayTemp['messageForUser'] = "Wrong password, please try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}
		// else if user not found, redirect to login w NOT FOUND error msgs
		else if ($existingUser == false) {
			$userResultsArrayTemp['messageForUser'] = "User does not exist, please check spelling and try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}
		//just return to login page
		else {
			 return $response->withRedirect('/', 301);
		}

	}


}