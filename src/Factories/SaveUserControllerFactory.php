<?php

namespace App\Factories;
// updated for CoreQuestions wrt UserModel and saving new user

use App\Controllers\SaveUserController;

class SaveUserControllerFactory
{
    public function __invoke($container)
    {
        //always needs to instantiate a new controller inside factory's invoke method
        //no renderer is required here, as we only need to redirect to homepage after saving a new user - actually would be better to display a Success message after saving a new user via SaveUserController class
        $userModel = $container->get('UserModel');
        $saveUserController = new SaveUserController($userModel);
        return $saveUserController;
    }

}