<?php
declare(strict_types=1);

// Davin updated for CoreQuestions
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
	$container = $app->getContainer();

	// add in custom routes here for CoreQuestions app
	//Homepage also does getQuestions() - could be named LoginController?
	$app->get('/', 'HomepageController');

	$app->post('/saveUser', 'SaveUserController');

	$app->post('/saveAnswers', 'SaveAnswersController');

	$app->get('/showUserHistory/{user_id}', 'ShowUserHistoryController');

	//restructuring app
	// need to secure routes ilke /dashboard witouth user_id or putup/redirect to custom 404 page
	//what if they try to access dashboard without being logged in? sessions should notice & redirect them! need do deal with Return to Dashboard page option, using sessions!
	$app->get('/dashboard', 'DashboardController');
	// $app->get('/dashboard/{user_id}', 'DashboardController');
	$app->get('/questionForm/{user_id}', 'QuestionFormController');
	// !questionForm > !QuestionFormController question_form.php

	$app->get('/admin', 'AdminPageController');

	//  need /loginUser route
	// $app->get('/loginUser/{user_id}', 'LoginUserController');
	$app->post('/loginUser', 'LoginUserController');
	$app->post('/logoutUser', 'LogoutUserController');
};
