<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt Answers

class SaveAnswersController
{
	private $answerModel;

	public function __construct($answerModel)
	{
		$this->answerModel = $answerModel;
	}

	public function __invoke($request, $response, $args)
	{

		// existingUser - cast to int and check its still ok! 
		// should i also cast form date to a php Date object? - how to get Date object and not a String for the date?
		$userID = (int) $request->getParsedBody()['existingUserID'];
		$dateFormCompleted = $request->getParsedBody()['dateCompleted'];

		//only need the selected radio button from each group of buttons
		$dataArrayAnswers = $request->getParsedBody()['radioAnswerPoints'];
		// var_dump($dataArrayAnswers);
		// exit;

		//now sum the answer values in the array, to plot on the graph later
		$totalScore = $this->answerModel->calculateScore($dataArrayAnswers);

		//needs to save ARRAY of values ie $dataArrayAnswers to tables user_core_answers after updating user_core_score & getting newest primary id
		$result = $this->answerModel->saveAnswers($userID, $dateFormCompleted, $dataArrayAnswers, $totalScore);

		//redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
		return $response->withHeader('Location', '/')->withStatus(302);
	}

}