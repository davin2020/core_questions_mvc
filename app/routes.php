<?php
declare(strict_types=1);

// Davin updated for CoreQuestions
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
	$container = $app->getContainer();

	$app->get('/', 'HomepageController');

	$app->post('/saveUser', 'SaveUserController');

	$app->post('/saveAnswers', 'SaveAnswersController');

	//FYI reminder that route with a trailing / will result in a 404 Not Found error - so need custom page for that
	$app->get('/showUserHistory', 'ShowUserHistoryController');

	$app->get('/dashboard', 'DashboardController');

	$app->get('/questionForm', 'QuestionFormController');

	$app->get('/adminConsole', 'AdminPageController');

	$app->post('/loginUser', 'LoginUserController');
	
	$app->post('/logoutUser', 'LogoutUserController');
};
