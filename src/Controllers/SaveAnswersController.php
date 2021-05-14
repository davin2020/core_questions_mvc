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
		// do i need to cast Form string-date to a php Date object? 
		//collect data from form fields needed to pass to saveAnswers()
		$userID = (int) $request->getParsedBody()['existingUserID'];
		$dateFormCompleted = $request->getParsedBody()['dateCompleted'];

		//only need the selected radio button from each group of buttons
		$dataArrayAnswers = $request->getParsedBody()['radioAnswerPoints'];

		//now sum the answer values in the array, to plot on the graph later
		$totalScore = $this->answerModel->calculateTotalScore($dataArrayAnswers);
		$meanScore = $this->answerModel->calculateMeanScore($totalScore, count($dataArrayAnswers));
		//needs to save ARRAY of values ie $dataArrayAnswers to tables user_core_answers after updating user_core_score & getting newest primary id - need to check if $result is true/succeeded ok?
		$result = $this->answerModel->saveAnswers($userID, $dateFormCompleted, $dataArrayAnswers, $totalScore, $meanScore);

		//redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
		// thhis works ok
		// return $response->withHeader('Location', '/dashboard/5')->withStatus(302);

		//maybe i need to pre build the url??
		// return $response->withHeader('Location', '/dashboard/' . $userID)->withStatus(302);

		//show history page instead of dashboard page
		return $response->withHeader('Location', '/showUserHistory/' . $userID . '?success=1' )->withStatus(302);

		//TO DO should only return success if saving to db has actually worked! also review status codes
		// return $response->withHeader('Location', '/dashboard/' . $userID . '?success=1' )->withStatus(302);


		//  these ursl are totally differnt, top one is right, bottom is a whole diff route that doesnt exist!
		// http://localhost:8087/dashboard/5?success=1
		// http://localhost:8087/dashboard/5/?success=1

		// return $response->withHeader('Location', '/?success=1')->withStatus(302);
		# return $response->withHeader('Location', '/dashboard/' . $userID . '/?success=1')->withStatus(302);
	}

}