<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class QuestionFormController
{
	private $userModel;
	private $questionModel;
	private $renderer;

	/**
	 * QuestionFormController constructor.
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
		// add session stuff here
		session_start(); 
		// echo session_id();

		//if user not logged in ie no current session, redirect to home page
		if ( !$_SESSION['coreIsLoggedIn']) {
			$_SESSION['errorMessage'] = "DORY redirected from Question Form to Index";
			// var_dump("DORY var_dump_ Your not logged in, so redirected from Question Form to Index");
			header("Location: /");
			exit();
		}

		// get user id fro session, not args in route url
		// $user_id = $args['user_id'];
		$session_user_id = $_SESSION['userId'];

		//need to return response with args[] via render method - can have multiple named keys if u needed to add/display more stuff later
		$assocArrayArgs = [];

		//why do i need to get the whole user from teh db, why not from the session??
		$user = $this->userModel->getUserFromID($session_user_id);
		$assocArrayArgs['user'] = $user; 
		$userName = $user['nickname'];
		$assocArrayArgs['userName'] = $userName; 

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

		// get all users so they can be displayed - not reqd for questioon_form
		// $allUsers = $this->userModel->getUsers();
		// $assocArrayArgs['usersList'] = $allUsers;


		//last param $args is the data to return to the next page
		return $this->renderer->render($response, 'question_form.php', $assocArrayArgs);
	}

}