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
		$_SESSION['coreIsLoggedIn'] = false;
		session_destroy(); //do u stil need to call start, before destory? - just calling destory without start means i can still access teh accounts.php page!
	
		//trying to access acount & page2, while calling start then destory, means u get redirected to index page, but without any error msg

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