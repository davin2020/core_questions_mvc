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

    //new stuff for ToDos
    $container['HomepageController'] = DI\Factory('App\Factories\HomepageControllerFactory');
    $container['DBConnector'] = DI\Factory('App\DBConnector'); //technically a factoy
 
    //updaetd for Questions instead of Tasks - added UserModel too
    $container['QuestionModel'] = DI\Factory('App\Factories\QuestionModelFactory');
    $container['UserModel'] = DI\Factory('App\Factories\UserModelFactory');

    $container['SaveUserController'] = DI\Factory('App\Factories\SaveUserControllerFactory');

    // $container['MarkCompleteController'] = DI\Factory('App\Factories\MarkCompleteControllerFactory');

    // $container['CompletedTasksPageController'] = DI\Factory('App\Factories\CompletedTasksPageControllerFactory');

    // $container['DeleteTaskController'] = DI\Factory('App\Factories\DeleteTaskControllerFactory');

    $containerBuilder->addDefinitions($container);
};
