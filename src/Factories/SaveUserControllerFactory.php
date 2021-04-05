<?php


namespace App\Factories;
// updated for CoreQuestions - but will need separate User Model, in addition to Task Model! 

use App\Controllers\SaveUserController;

class SaveUserControllerFactory
{
    public function __invoke($container)
    {
        //always needs to instantiate a new controller inside factory's invoke method
        //no renderer is required here, as we only need to redirect to homepage after saving a new user, ie no need to display anything specific to saving a user
        $userModel = $container->get('UserModel');
        $saveUserController = new SaveUserController($userModel);
        return $saveUserController;
    }

}