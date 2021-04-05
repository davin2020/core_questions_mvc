<?php


namespace App\Factories;
// Davin updated this file for CoreQuestions

use App\Controllers\HomepageController;

class HomepageControllerFactory
{
    public function __invoke($container)
    {
        //ok to break DI here, inside factory, in order to create new controller
        //get dependancceis from DIC/container, then call constructor to create new controller & return it
        $renderer = $container->get('renderer');
        $questionModel = $container->get('QuestionModel');
        $homepageController = new HomepageController($questionModel, $renderer);
        return $homepageController;
    }

}