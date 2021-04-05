<?php


namespace App\Factories;
// Davin updated this file for CoreQuestions

use App\QuestionModel;

class QuestionModelFactory
{
    public function __invoke($container)
    {
        //ok to break DI here & create a new QuestionModel object - any dependencies can be gotten from the DIC/container later eg DBConn, renderer, TM for other classes etc
        $db = $container->get('DBConnector');
        $questionModel = new QuestionModel($db);
        return $questionModel;
    }
}