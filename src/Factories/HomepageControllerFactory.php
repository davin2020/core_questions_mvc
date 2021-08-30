<?php

namespace App\Factories;

use App\Controllers\HomepageController;

class HomepageControllerFactory
{
	public function __invoke($container)
	{
		$userModel = $container->get('UserModel');
		$questionModel = $container->get('QuestionModel');
		$renderer = $container->get('renderer');
		$homepageController = new HomepageController($userModel, $questionModel, $renderer);
		return $homepageController;
	}

}