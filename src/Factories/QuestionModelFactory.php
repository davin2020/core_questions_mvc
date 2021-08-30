<?php

namespace App\Factories;

use App\Models\QuestionModel;

class QuestionModelFactory
{
	public function __invoke($container)
	{
		$db = $container->get('DBConnector');
		$questionModel = new QuestionModel($db);
		return $questionModel;
	}
}