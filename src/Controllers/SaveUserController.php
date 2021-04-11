<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt User

class SaveUserController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function __invoke($request, $response, $args)
    {
        $dataName = $request->getParsedBody()['itemName'];

        //why i s this a string and not a date? - try casting to date?
        $dataDate = $request->getParsedBody()['itemDate'];
        // var_dump($dataDate);

        $result = $this->userModel->saveUser($dataName, $dataDate);

        //redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}