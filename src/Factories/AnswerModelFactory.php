<?php

namespace App\Factories;

use App\Models\AnswerModel;

class AnswerModelFactory
{
	public function __invoke($container)
	{
		$db = $container->get('DBConnector');
		$answerModel = new AnswerModel($db);
		return $answerModel;
	}
}