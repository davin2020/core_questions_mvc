<?php

namespace App\Factories;
// updated for CoreQuestions wrt AnswerModel which saves users answers to questions 

use App\Controllers\SaveAnswersController;

class SaveAnswersControllerFactory
{
	public function __invoke($container)
	{
		//always needs to instantiate a new controller inside factory's invoke method
		//no renderer is required here, as we only need to redirect to homepage after saving the users answers, ie no need to display anything specific to saving them - Actually it would be better to show a success message after saving the Answers, but how?
		$answerModel = $container->get('AnswerModel');
		// $renderer = $container->get('renderer');
		$saveAnswersController = new SaveAnswersController($answerModel);
		return $saveAnswersController;
	}

}