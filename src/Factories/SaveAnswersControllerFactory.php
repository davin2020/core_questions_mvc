<?php

namespace App\Factories;
// updated for CoreQuestions wrt AnswerModel which saves users answers to questions 

use App\Controllers\SaveAnswersController;

class SaveAnswersControllerFactory
{
	public function __invoke($container)
	{
		//always needs to instantiate a new controller inside factory's invoke method
		//no renderer is required here, as we only need to redirect to homepage after saving the users answers, success msg is part of SaveAnswersController class
		$answerModel = $container->get('AnswerModel');
		// $renderer = $container->get('renderer');
		$saveAnswersController = new SaveAnswersController($answerModel);
		return $saveAnswersController;
	}

}