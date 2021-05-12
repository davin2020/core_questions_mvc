<?php
declare(strict_types=1);
// Davin updated for CoreQuestions

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\PhpRenderer;

return function (ContainerBuilder $containerBuilder) {
	$container = [];

	$container[LoggerInterface::class] = function (ContainerInterface $c) {
		// $settings is array that contains key/value pairs like logger, renderer, and now dbDetails
		$settings = $c->get('settings');

		$loggerSettings = $settings['logger'];
		$logger = new Logger($loggerSettings['name']);

		$processor = new UidProcessor();
		$logger->pushProcessor($processor);

		$handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
		$logger->pushHandler($handler);

		return $logger;
	};

	$container['renderer'] = function (ContainerInterface $c) {
		$settings = $c->get('settings')['renderer'];
		$renderer = new PhpRenderer($settings['template_path']);
		return $renderer;
	};

	//original stuff for ToDos
	$container['HomepageController'] = DI\Factory('App\Factories\HomepageControllerFactory');
	$container['DBConnector'] = DI\Factory('App\DBConnector'); //technically a factoy
 
	//updated for CoreQuestions - added 3 new models each of which requires its own factory
	$container['QuestionModel'] = DI\Factory('App\Factories\QuestionModelFactory');
	$container['UserModel'] = DI\Factory('App\Factories\UserModelFactory');
	$container['AnswerModel'] = DI\Factory('App\Factories\AnswerModelFactory');

	//dont forget to add new models & controllers here!
	$container['SaveUserController'] = DI\Factory('App\Factories\SaveUserControllerFactory');

	$container['SaveAnswersController'] = DI\Factory('App\Factories\SaveAnswersControllerFactory');

	$container['ShowUserHistoryController'] = DI\Factory('App\Factories\ShowUserHistoryControllerFactory');

	//dont forget to add new models & controllers here! esp when restructuring!
	$container['DashboardController'] = DI\Factory('App\Factories\DashboardControllerFactory');

	$container['QuestionFormController'] = DI\Factory('App\Factories\QuestionFormControllerFactory');

	$containerBuilder->addDefinitions($container);
};
