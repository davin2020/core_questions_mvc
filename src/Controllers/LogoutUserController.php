<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt User

class LogoutUserController
{
	private $userModel;
	private $renderer;

	public function __construct($userModel, $renderer)
	{
		//do i need anyting in this constructor??
		$this->userModel = $userModel;
		$this->renderer = $renderer;
	}

// if (!$_SESSION['coreIsLoggedIn']) {

// new invoke function 26may2021
	public function __invoke($request, $response, $args)
	{
		//should i put some msg in args?
		session_start();
		$_SESSION['coreIsLoggedIn'] = false; // this is destroy by next call so it it even needed? or use unset  instead?

		// destroy everything in this session
		unset($_SESSION);

		// Before destroying a session with the session_destroy() function, you need to first recreate the session environment if it is not already there using the session_start() function, so that there is something to destroy - https://www.tutorialrepublic.com/php-tutorial/php-sessions.php
		session_destroy(); //do u stil need to call start, before destory? - just calling destory without start means i can still access teh accounts.php page!
		echo session_id();
		var_dump('session destroyed!!') ;
		// exit;

		//trying to access acount & page2, while calling start then destory, means u get redirected to index page, but without any error msg

		// How To Remove session data, using unset
		// if(isset($_SESSION["lastname"])){
		//     unset($_SESSION["lastname"]);
		// }

		//prob cant send msg as session has been destroyed!
		$_SESSION['$errorMessage'] = 'Reidrected from Logout to Index';
		// header("Location: index.php");
		// header("Location: /dashboard/" . $user_id);
		header("Location: /");
		exit();

		//is it best to redirect back to  homepage or use header + exit ??
		return $response->withRedirect('/', 301);
		// return $response->withHeader('Location', '/dashboard')->withStatus(200);	
	}


}