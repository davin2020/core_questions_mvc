<?php


namespace App\Factories;
// updated for CoreQuestions wrt SaveAnswers

use App\Controllers\SaveAnswersController;

class SaveAnswersControllerFactory
{
    public function __invoke($container)
    {
        //always needs to instantiate a new controller inside factory's invoke method
        //no renderer is required here, as we only need to redirect to homepage after saving a new user, ie no need to display anything specific to saving a user - for Questions would be better to show a success message after saving!
        $answerModel = $container->get('AnswerModel');
        $saveAnswersController = new SaveAnswersController($answerModel);
        return $saveAnswersController;
    }

}