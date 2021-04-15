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

		$dataDate = $request->getParsedBody()['itemDate'];

		$result = $this->userModel->saveUser($dataName, $dataDate);
		// var_dump($result); //this is just true or false depending on whether save worked or not - need checking and success/failure message shown on next page

		//redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
		return $response->withHeader('Location', '/')->withStatus(302);
	}

}