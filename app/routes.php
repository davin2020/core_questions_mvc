<?php
declare(strict_types=1);

// Davin updated for CoreQuestions
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
	$container = $app->getContainer();

	// add in custom routes here for CoreQuestions app
	//Homepage also does getQuestions()
	$app->get('/', 'HomepageController');

	$app->post('/saveUser', 'SaveUserController');

	$app->post('/saveAnswers', 'SaveAnswersController');

	$app->get('/showUserHistory/{user_id}', 'ShowUserHistoryController');

	//restructuring app
	$app->get('/dashboard/{user_id}', 'DashboardController');
	$app->get('/questionForm/{user_id}', 'QuestionFormController');
	// !questionForm > !QuestionFormController question_form.php

};
