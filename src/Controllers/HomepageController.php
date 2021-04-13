<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class HomepageController
{
	private $userModel;
	private $questionModel;
	private $renderer;

	/**
	 * HomePageController constructor.
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
		//need to return response with args[] via render method
		$assocArrayArgs = [];

		//get & show all questions - is this still being used?
		$allQuestions = $this->questionModel->getQuestions();
		//add questions to assoc array, for php renderer to display stuff - can have multiple keys if u needed to display more stuff later
		$assocArrayArgs['coreQuestions'] = $allQuestions; 

		//get questions and associated points for each possible answer
		$allQuestionsAndPoints = $this->questionModel->getQuestionsAndPoints();
		$assocArrayArgs['coreQuestionsAndPoints'] = $allQuestionsAndPoints;
		// var_dump($allQuestionsAndPoints);

		// get all users so they can be displayed
		$allUsers = $this->userModel->getUsers();
		$assocArrayArgs['usersList'] = $allUsers;

		//last param $args is the data to return to the next page
		return $this->renderer->render($response, 'index.php', $assocArrayArgs);
	}

}