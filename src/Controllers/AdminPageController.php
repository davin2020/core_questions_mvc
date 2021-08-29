<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class AdminPageController
{
	private $userModel;
	private $questionModel;
	private $renderer;

	/**
	 * AdminPageController constructor.
	 * @param $userModel
	 * @param $questionModel
	 * @param $renderer
	 */

	public function __construct($userModel, $questionModel, $renderer)
	{
		$this->userModel = $userModel;
		$this->questionModel = $questionModel;
		$this->renderer = $renderer;
	}

	//invoke only ever does 1 thing, each page has own controler thus own invoke method, where the stuff for the actual page gets done eg display users & questions or save answers etc
	public function __invoke($request, $response, $args)
	{
		session_start(); 
		echo session_id();

		// if user isnt logged in at all, redirect to homepage so they can login
		if ( !$_SESSION['coreIsLoggedIn'] ) {
			$_SESSION['errorMessage'] = 'Trying to access a Restricted page and your not an Admin User,  so redirected back to Homepage';
			//when redirection happens and there is no session, then no $errorMessage will be sent - could try redirecting to a third page, just to prove that works
			header("Location: /"); //must be a route not a view
	    	exit();
		}
		// If they ARE logged in BUT are not an ADMIN user, send them back to dashboard?
		if ( $_SESSION['coreIsLoggedIn'] && $_SESSION['userId'] !=99 ) {
			$_SESSION['errorMessage'] = 'Trying to access a Restricted page and your not an Admin User,  so redirected back to Dashboard';
			header("Location: /dashboard");
			exit();
		}
		// else if ( !$_SESSION['coreIsLoggedIn'] && $_SESSION['userId'] ) {
		//only show contents of admin page if its AdminDavin
		else if ( $_SESSION['coreIsLoggedIn'] && $_SESSION['userId'] == 99) {
			echo "<br>DAVIN is logged in as ADMIN 999";
		}
		//need logout button

		//need to return response with args[] via render method - can have multiple named keys if u needed to add/display more stuff later
		$assocArrayArgs = [];

		//get & show all questions - is this still being used?
		// $allQuestions = $this->questionModel->getQuestions();
		// $assocArrayArgs['coreQuestions'] = $allQuestions; 

		//get questions and associated points for each possible answer & add to assoc array for renderer to display stuff
		$allQuestionsAndPoints = $this->questionModel->getQuestionsAndPoints();
		$assocArrayArgs['coreQuestionsAndPoints'] = $allQuestionsAndPoints;
		// var_dump($allQuestionsAndPoints);

		// get labels for columns of answers to questions eg Sometimes, Often etc
		$allQuestionLabels = $this->questionModel->getQuestionLabels();
		$assocArrayArgs['questionAnswerLabels'] = $allQuestionLabels; 

		// get all users so they can be displayed
		$allUsers = $this->userModel->getUsers();
		$assocArrayArgs['usersList'] = $allUsers;

		//last param $args is the data to return to the next page
		return $this->renderer->render($response, 'admin.php', $assocArrayArgs);
	}

}