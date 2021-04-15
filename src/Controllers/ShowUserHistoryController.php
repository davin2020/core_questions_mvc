<?php
namespace App\Controllers; //namespace must be first stmt!

error_reporting(E_ALL); ini_set('display_errors', '1');

class ShowUserHistoryController
{
	private $userModel;
	private $answerModel;
	private $renderer;

	public function __construct($userModel, $answerModel, $renderer)
	{   
		//do  i really need both models? will an answer ever exist without a user?
		$this->userModel = $userModel;
		$this->answerModel = $answerModel;
		$this->renderer = $renderer;
	}

	//FYI any data sent to this Controller will be inside the assoc array $args, with a key matching the {user_id} var from the routes file
	public function __invoke($request, $response, $args)
	{
		//why do i need to get this from db, why not just pass in Name from prev page ie index/HomePageController?
		// var name user_id in args is taken from routes.php file
		$user = $this->userModel->getUserFromID($args['user_id']);
		$userName = $user['name'];

		//find history - should this be from answerModel or userModel?
		$userAnswerHistory = $this->answerModel->getUserAnswers($args['user_id']);
		// $userHistory = $this->userModel->getUserHistory($args['q_id']); //method never written/doesnt exist

		//make line graph based on users overall_score  
		$userLineGraph = $this->answerModel->getUserAnswersLineGraph($args['user_id']);

		//do stuff with output buffering & encoding to embed the graph-image, so graph doesnt take over the whole web page
		$userLineGraphImg = $userLineGraph->Stroke(_IMG_HANDLER);
		ob_start();
		imagepng($userLineGraphImg);
		$imageLineGraphData = ob_get_contents();
		ob_end_clean();

		//build AssocArgs array & add items to it, for actual php page to render
		$assocArrayArgs = [];
		$assocArrayArgs['userName'] = $userName; 
		$assocArrayArgs['userHistory'] = $userAnswerHistory; 
		$assocArrayArgs['userLineGraph'] = $imageLineGraphData; 

		//last param $args is the data to return/pass to the next page
		return $this->renderer->render($response, 'show_history.php', $assocArrayArgs);
	}

}