<?php

namespace App\Factories;
// Davin updated this file for CoreQuestions wrt UserModel

use App\Models\UserModel;

class UserModelFactory
{
	public function __invoke($container)
	{
		//ok to break DI here & create a new UserModel object - any dependencies can be gotten from the DIC/container later eg DBConn, renderer, TM for other classes etc
		$db = $container->get('DBConnector');
		$userModel = new UserModel($db);
		return $userModel;
	}
}