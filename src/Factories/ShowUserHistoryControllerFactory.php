<?php


namespace App\Factories;

use App\Controllers\ShowUserHistoryController;

class ShowUserHistoryControllerFactory
{
    public function __invoke($container)
    {
        //no renderer is required here, as we only need to redirect to homepage after marking a task as completed
        $userModel = $container->get('UserModel');
        $renderer = $container->get('renderer');
        $answerModel = $container->get('AnswerModel');
        $showUserHistoryController = new ShowUserHistoryController($userModel, $answerModel, $renderer);
        return $showUserHistoryController;
    }
    // $saveAnswersController = new SaveAnswersController($answerModel);
    //     return $saveAnswersController;
    // }

}