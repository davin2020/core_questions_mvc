<?php

namespace App\Factories;

use App\Controllers\DashboardController;

class DashboardControllerFactory
{
	public function __invoke($container)
	{
		$userModel = $container->get('UserModel');
		$renderer = $container->get('renderer');
		$dashboardController = new DashboardController($userModel, $renderer);
		return $dashboardController;
	}

}