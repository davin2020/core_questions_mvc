<?php

namespace App\Factories;
// Davin updated this file for CoreQuestions wrt QuestionModel and UserModel

use App\Controllers\QuestionFormController;

class QuestionFormControllerFactory
{
	public function __invoke($container)
	{
		//ok to break DI here, inside factory, in order to create new controller
		//get dependancceis from DIC/container, then call constructor to create new controller & return it
		$userModel = $container->get('UserModel');
		$questionModel = $container->get('QuestionModel');
		$renderer = $container->get('renderer');
		$questionFormController = new QuestionFormController($userModel, $questionModel, $renderer);
		return $questionFormController;
	}

}