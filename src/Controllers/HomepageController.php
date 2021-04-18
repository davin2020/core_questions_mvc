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

	public function __invoke($request, $response, $args)
	{
		//need to return response with args[] via render method
		$assocArrayArgs = [];

		//get questions and associated points for each possible answer & add to assoc array for renderer to display stuff
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