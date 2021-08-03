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


// new invoke function 26may2021
	public function __invoke($request, $response, $args)
	{
		

		$userEmail = $request->getParsedBody()['inputEmail'];
		$userPassword = $request->getParsedBody()['inputPassword'];

		//check if user exists, then compare pwds 
		$existingUser = $this->userModel->getUserByEmail($userEmail);

		// if existing user not FALSE
		if ($existingUser) {
			$existingHashedPassword = $existingUser['password'];

			//compare pwd from user form against its hash from db - dont callt his if user doenst exist!
			$pwdMatches = $this->userModel->verifyPassword($userPassword, $existingHashedPassword);

			//need id for redirection, but only if user exists!
			$user_id = $existingUser['user_id'];
		}
		


	//swop out urls below -
		//array to pass to next page w error msg and user in it
		$userResultsArrayTemp = [];
		$userResultsArrayTemp['existingUser'] = $existingUser;

		// IF user found & pwd correct redirect to dahsboard (later add token first), - prob best to use error msg rather than status codes?
		if ($existingUser != false && $pwdMatches == true) {
			// var_dump('option 1');
			// exit;
			//$nextUrl = '/dashboard/' . $user_id;

			// 6june2021 add sessions - do i nee dto call start in controller or php view file?
			// session_name("CORE_SESSION");

			// session_set_cookie_params(0, '/members', '.yourdomain.com', 0, 1);

			session_start();
			echo session_id();
			
			// errror = syntax error, unexpected '='
			//seems this stuff in set in php.ini
			// session.name = "CORE_SESSION";
			// session.cookie_domain = "http://localhost:8087/";
			//for sesh variables use _ instead of camel case? unless PSR says otherwise?
			//these session vars are avail gloablly on the same web domain (incl localhost it seems) ie i dont need to pass them between pages!!
			$_SESSION['session_name'] = "CORE_SESSION";
			$_SESSION['coreIsLoggedIn'] = true;
			$_SESSION['userId'] = $existingUser['user_id'];
			$_SESSION['existingUser'] = $existingUser;
			// errror if falling dashboard.php - The requested resource /dashboard.php was not found on this server.
			//this is right syntax for dynamic route, could also put id inside session
			// header("Location: /dashboard/" . $user_id);
			header("Location: /dashboard");
			exit;

			// $session = new Session();
            // $session->set('user', $existingUser);
            // header( 'Location: ' . SITE_URL);
            // exit;

			// 13june need to change route to /dashboard and put user_id in session - put userid in session first 

			// then repeat on other pages ie /showHistory and /questionForm
			return $response->withHeader('Location', '/dashboard')->withStatus(200);
			//original route
			// return $response->withHeader('Location', '/dashboard/' . $user_id)->withStatus(200);
		}
		// else if user found & pwd WRONG redirect to login page w WRONG PWD errr msg ie 401 unauth
		else if ($existingUser != false && $pwdMatches == false)  {
			// var_dump('option 2');
			// exit;
			$userResultsArrayTemp['messageForUser'] = "Wrong password, please try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}
		// else if user not found, redirect to login w NOT FOUND error msgs
		else if ($existingUser == false) {
			// var_dump('option 3');
			// exit;
			$userResultsArrayTemp['messageForUser'] = "User does not exist, please check spelling and try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
			// return $response->withHeader('Location', '/')->withStatus(404);
		}
		//just return to login page
		else {
			 return $response->withRedirect('/', 301);
		}

	}




//  ORIGINAL STUFF BELOW HERE 26may2021

	/*
	public function __invoke($request, $response, $args)
	{

		$email = $request->getParsedBody()['inputEmail'];
		$password = $request->getParsedBody()['inputPassword'];

		// try new login user method, ie so Logic logic resides in UserModel and not in Controller - both of these are 2D arrays!
		$result = $this->userModel->loginUser($email, $password);
		// $userResultsArray['existingUser'] = $existingUser;
		// $userResultsArray['pwdMatches'] = $pwdMatches;

		$userResultsArrayTemp = $result; //this array has 2 items already defined
		//what if above is null?


		// $userResultsArrayTemp is an array with 2 keys as shown below, which are created wehn loginUser() is called
		$doesUserExist = $userResultsArrayTemp['existingUser']; //obj or false
		$doesPwdMatch = $userResultsArrayTemp['pwdMatches']; //bool
		// var_dump($result);
		// exit;

		//array(2) { ["existingUser"]=> array(6) { ["user_id"]=> string(2) "30" ["fullname"]=> string(5) "test6" ["nickname"]=> string(5) "test6" ["email"]=> string(17) "test6@example.com" ["password"]=> string(60) "$2y$10$drABEk1kJR5WDgjPWDVCt.Xn1jmwbBeUIgEpCOONxQrfo33CXZh8q" ["date_joined"]=> string(10) "2021-05-15" } ["pwdMatches"]=> bool(true) }

			// $user = $this->userModel->getUserFromID($args['user_id']);


		//true if matches, false if no user or wrong pwd
		// $userResultsArray['existingUser'] = $existingUser;
		// $userResultsArray['pwdMatches'] = $pwdMatches;

		$user_id = $doesUserExist['user_id']; //this is OK
		// $user_id = $result['user_id']; //this is null/undefined
		// $user_id = $userResultsArrayTemp['user_id'];
		// how to get userid?
		// var_dump($result);
		// exit;

		//REDIRECT NOW
		if ($doesPwdMatch) {
			// return $response->withHeader('Location', '/dashboard/30')->withStatus(200);
			// this essentialy gets the user by their id, when i already have got them by their email, why not pass along the user obj from one controller to the next??
			return $response->withHeader('Location', '/dashboard/' . $user_id)->withStatus(200);
		}
		if (!$doesUserExist) {
			$userResultsArrayTemp['messageForUser'] = "user does not exist, please check spelling and try again";
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
		}
		else {
			$userResultsArrayTemp['messageForUser'] = "wrong password, please try again";
			//try to fwd using renderer
// return $response->withHeader('Location', '/showUserHistory/' . $userID . '?success=1' )->withStatus(302);
			// var_dump($userResultsArrayTemp['pwdMatches']);

			//ok sn23may this finally works & prints wrong pwd msg! but boolean never gets thru, tho no idea why
			// var_dump($userResultsArrayTemp['messageForUser']);
			// exit;
			
			// contents of array 
			// array(2) { ["existingUser"]=> array(6) { ["user_id"]=> string(2) "10" ["fullname"]=> string(13) "Davin Jaqobis" 	["nickname"]=> string(5) "Davin" ["email"]=> string(17) "davin@example.com" ["password"]=> string(60) "$2y$10$txnidBpxxKkJz9ssDbtiPesd9m.7oEz/01DOBJRA8r0NTWlQLdYbq" ["date_joined"]=> string(10) "2021-03-22" } ["pwdMatches"]=> bool(false) }


			//try passing in this array instead? userResultsArray
			return $this->renderer->render($response, 'index.php', $userResultsArrayTemp);
			// return $this->renderer->render($response, 'index.php?failure=1', $userResultsArrayTemp);
			// return $response->withHeader('Location', '/')->withStatus(302);
		}

		var_dump($result);
		exit;



		//check if user exists, then compare pwds as 2 separate steps ie 2 sep db calls?
		// OR do both of those in 1 function?

		// this seems to contain 2 arrays? do i have 2 users with test6 emails??
		//have i got both an assoc array and an index array being returned? YES
		$existingUser = $this->userModel->getUserByEmail($email);

		//this now returns a bool of true, so how to get the user that was returned from the db?
		// $tempUser = $this->userModel->getUserFromID(30);
		

		//check pwds here on in UserModel.getUserbyEmail or LoginUser? in UserModel
		$existingHashedPassword = $existingUser['password'];
		// $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		//compare pwd from user form against its hash from db
		$pwdMatches = password_verify ($password, $existingHashedPassword);

		// var_dump($pwdMatches);
		// exit;
		//$existingUserPwd = $this->userModel->LoginUser($email, $password);

		//extract user_id from user
		// $user_id = $existingUser->$user_id;
		$user_id = $existingUser['user_id'];
	

		//
		https://stackoverflow.com/questions/32752578/whats-the-appropriate-http-status-code-to-return-if-a-user-tries-logging-in-wit#:~:text=The%20correct%20HTTP%20code%20would,credentials%20for%20the%20target%20resource.
	If the request included authentication credentials, then the 401 response indicates that authorization has been refused for those credentials. The user agent MAY repeat the request with a new or replaced Authorization header field (Section 4.2).
		Attempting to express an application-level error in a transport-level status code is a design mistake.
		//

		//if i call a method like User->RegisterUser(email, pwd) then i have to get a response back that says if user exists and if pwd matches! ie array with 2 items

		// IF user found & pwd correct redirect to dahsboard (later add token first), - prob best to use error msg rather than status codes?
		if ($existingUser != false && $pwdMatches == true) {
			// var_dump('option 1');
			// exit;
			$nextUrl = '/dashboard/' . $user_id;
			return $response->withHeader('Location', '/dashboard/' . $user_id)->withStatus(200);
		}
		// else if user found & pwd WRONG redirect to login page w WRONG PWD errr msg ie 401 unauth
		else if ($existingUser != false && $pwdMatches == false)  {
			// var_dump('option 2');
			// exit;

			//trying header stuff - doesnt seem to work ie extra headers are not set! and getting all headres return an empty string
			return $response->withHeader('Location', '/')->withStatus(401)->withAddedHeader('User-Message', 'Incorrect password');

			//return $response->withHeader('Location', '/')->withStatus(401);
		}
		// else if user not found, redirect to login w NOT FOUND error msgs
		else if ($existingUser == false) {
			// var_dump('option 3');
			// exit;
			return $response->withHeader('Location', '/')->withStatus(404);
		}
		//just return to login page
		else {
			 return $response->withRedirect('/', 301);
		}
		
			//this is how to return argsArray to the next rendered page! just need to pass renderer to constructor method
			//return $this->renderer->render($response, 'question_form.php', $assocArrayArgs);




		// TO DO or FIXME - google how to send a message with a php response, instead of putting it in url??
		//header("HTTP/1.1 400 Bad Request");

		// FYI  HTTP response status code 302 Found is a common way of performing URL redirection
		// You can set a header value with the PSR-7 Response object’s withHeader($name, $value) method
			// original response, but no longer needed?
			// return $response->withHeader('Location', '/dashboard/' . $user_id)->withStatus(302);

		// if i add mulltiple headres eg user error msg can i then retrieve them eg Get One or All Headers? - see https://www.slimframework.com/docs/v3/objects/response.html#set-header
		// You can append a header value with the PSR-7 Response object’s withAddedHeader($name, $value) method.
		// $newResponse = $oldResponse->withAddedHeader('Allow', 'PUT');

		// or maybe somethign like this? - see https://stackoverflow.com/questions/60401214/how-can-i-set-multiple-header-keys-with-slim-framework-3
		//return $response->withBody(new Body($image))->withHeader('Content-Type', 'image/jpeg')->withAddedHeader('Content-Length', fstat($image)['size']);

            // OR I throw the error msg from controller, and display it to user with layer.

		// return $response->withHeader('Location', '/admin')->withStatus(302);

		// or should i be useing this redirect option?
		//return $response->withRedirect('/', 301);
	}
	*/


}
