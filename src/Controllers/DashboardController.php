<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class DashboardController
{
	private $userModel;
	private $questionModel;
	private $renderer;

	/**
	 * DashboardController constructor.
	 * @param $userModel
	 * @param $questionModel
	 * @param $renderer
	 */

	public function __construct($userModel, $renderer)
	{
		$this->userModel = $userModel;
		$this->renderer = $renderer;
	}

	//invoke only ever does 1 thing, each page has own controler thus own invoke method, where the stuff for the actual page gets done eg display users & questions or save answers etc
	public function __invoke($request, $response, $args)
	{
		session_start(); 
		echo session_id();
		// eg hj81kesf7j8t8tdjk82bhf5cvq - seems to be hte same regardles of whose logged in?? so is destory working properly?
		var_dump(session_id());

	//thsi is what actualy protects each route! need to add to each controller that i want to protect/restrict from direct url access  - needs to be correct var name!
		// ISSUE this protection this doesnt stop me from channging url to /dashboard/5 and getting access to antoher users data, so maybe i DO need to change the url so it doesnt use an ID, and isntead put ID into session ??
	// if (isset($_SESSION['coreIsLoggedIn']) && $_SESSION['coreIsLoggedIn'] == false) {

		//this actually doest work !
		// if (isset($_SESSION['coreIsLoggedIn']) && !$_SESSION['coreIsLoggedIn']) {

	if ( !$_SESSION['coreIsLoggedIn']) {
		$_SESSION['$errorMessage'] = 'Your not logged in, so redirected from Dash Controller to Index';
		var_dump("Your not logged in, so redirected from Dash Controller to Index");
		//when redirection happens and there is no session, then no $errorMessage will be sent - could try redirecting to a third page, just to prove that works
		header("Location: /"); //must be a route not a view
    	exit();
	}

			// removed 13june 
			//$user = $this->userModel->getUserFromID($args['user_id']);
			// UserModel->getUserFromID('$newUserIDint') - how to pass ID from one controller to the next??

		//but we alrady have the user, in the session array
		// $existingUser = $this->userModel->getUserByEmail($userEmail);
		$userId = $_SESSION['userId'];
		$user = $_SESSION['existingUser'];

			// 13june need to get user_id out of session so can load route as /dashboard only instead of /dashboard/id - this is from another page!
			// $_SESSION['session_name'] = "CORE_SESSION";
			// $_SESSION['coreIsLoggedIn'] = true;
			// $_SESSION['userId'] = $existingUser['user_id'];
			// $_SESSION['existingUser'] = $existingUser;


		$userName = $user['nickname'];
		//need to return response with args[] via render method - can have multiple named keys if u needed to add/display more stuff later
		// $assocArrayArgs = [];
		// $assocArrayArgs['user'] = $user; 
		// $assocArrayArgs['userName'] = $userName; 

		$newAssocArray = [];
		$newAssocArray['user'] = $user; //how do i get the id from teh user, do i have a getter?
		$newAssocArray['user_id'] = $user['user_id'];

		//last param $args is the data to return to the next page
		//need to only load next page is user logged in
		// $_SESSION['coreIsLoggedIn'] = true;
		// $_SESSION['coreIsLoggedIn'] = false;

		//im essentially getting hte user out of hte session var and passing it in an assoc array to thet VIEW page - do i need to do this anymore ie pass user to next page in array, can just use global session instead??
		return $this->renderer->render($response, 'dashboard.php', $newAssocArray);
	}

}