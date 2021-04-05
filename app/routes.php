<?php
declare(strict_types=1);

// Davin updated for CoreQuestions
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $container = $app->getContainer();

    //original route
//    $app->get('/', function ($request, $response, $args) use ($container) {
//        $renderer = $container->get('renderer');
//        return $renderer->render($response, "index.php", $args);
//    });

    // add in custom routes here
    $app->get('/', 'HomepageController');
    // getQuestions
    $app->post('/saveUser', 'SaveUserController');
    // $app->post('/saveUser', 'SaveUserController');

    // $app->get('/markAsComplete/{id}', 'MarkCompleteController');
    // $app->get('/completedTasks', 'CompletedTasksPageController');

    //need to create controller & factory for this route
    // $app->get('/deleteTask/{id}', 'DeleteTaskController');

};
