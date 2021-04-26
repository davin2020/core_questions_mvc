<?php

namespace App\Factories;
// updated for CoreQuestions wrt UserModel and AnswerModel and showing user history (ie dates & overall score)

use App\Controllers\ShowUserHistoryController;

class ShowUserHistoryControllerFactory
{

	public function __invoke($container)
	{
		$userModel = $container->get('UserModel');
		$answerModel = $container->get('AnswerModel');
		$renderer = $container->get('renderer');
		$showUserHistoryController = new ShowUserHistoryController($userModel, $answerModel, $renderer);
		return $showUserHistoryController;
	}

}