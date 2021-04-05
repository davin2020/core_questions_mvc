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
        //why i s this  a string and not a date?
        $dataDate = $request->getParsedBody()['itemDate'];
//        $data = $request->getParsedBody();
//        $x = $data['item'];
//        var_dump($data);
        var_dump($dataDate);

       $result = $this->userModel->saveUser($dataName, $dataDate);
//       var_dump($result);
//        $tasks = $this->taskModel->saveTask($args['taskDetails']);
        //redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}