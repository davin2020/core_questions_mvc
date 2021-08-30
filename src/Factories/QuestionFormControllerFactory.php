<?php

namespace App\Factories;

use App\Controllers\QuestionFormController;

class QuestionFormControllerFactory
{
	public function __invoke($container)
	{
		$userModel = $container->get('UserModel');
		$questionModel = $container->get('QuestionModel');
		$renderer = $container->get('renderer');
		$questionFormController = new QuestionFormController($userModel, $questionModel, $renderer);
		return $questionFormController;
	}

}