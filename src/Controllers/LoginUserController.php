<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt User

class LoginUserController
{
	private $userModel;

	public function __construct($userModel)
	{
		$this->userModel = $userModel;
	}

	public function __invoke($request, $response, $args)
	{

		$email = $request->getParsedBody()['inputEmail'];
		$password = $request->getParsedBody()['inputPassword'];

		//check if user exists, then compare pwds as 2 separate steps ie 2 sep db calls?
		// OR do both of those in 1 function?

		// this seems to contain 2 arrays? do i have 2 users with test6 emails??
		//have i got both an assoc array and an index array being returned?
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
		
		/*
		https://stackoverflow.com/questions/32752578/whats-the-appropriate-http-status-code-to-return-if-a-user-tries-logging-in-wit#:~:text=The%20correct%20HTTP%20code%20would,credentials%20for%20the%20target%20resource.
	If the request included authentication credentials, then the 401 response indicates that authorization has been refused for those credentials. The user agent MAY repeat the request with a new or replaced Authorization header field (Section 4.2).
		Attempting to express an application-level error in a transport-level status code is a design mistake.
		*/

		//if i call a method like User->RegisterUser(email, pwd) then i have to get a response back that says if user exists and if pwd matches!

		// IF user found & pwd correct redirect to dahsboard (later add token first), - prob best to use error msg rather than status codes?
		if ($existingUser != false && $pwdMatches == true) {
			// var_dump('option 1');
			// exit;
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

}
