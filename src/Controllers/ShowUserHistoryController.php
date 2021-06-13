<?php
namespace App\Controllers; //namespace must be first stmt!

// is this still required ?
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

	//FYI any data sent to this Controller will be inside the assoc array $args, with a key matching the {q_id} var from the routes file
	public function __invoke($request, $response, $args)
	{
		session_start();
		if (isset($_SESSION['coreIsLoggedIn']) && $_SESSION['coreIsLoggedIn'] == false) {
			//maybe need logout button to test if this works?
			//ok if assoc array shwos they are no logged in, then redirect works ok with msg - of course if i kill the session then there wont be a msg
			$_SESSION['msg'] = "redirect from show history";
			session_destroy();
			header("Location: /");
			//error The requested resource /showUserHistory/index.php was not found on this server. - locn need to be route not view !
        	exit();
		}


		//why do i need to get this from db, why not just pass in Name from prev page ie index/HomePageController?
		// var name user_id in args is taken from routes.php file
		$user = $this->userModel->getUserFromID($args['user_id']);
		$userName = $user['nickname'];

		//find history - should this be from answerModel or userModel?
		$userAnswerHistory = $this->answerModel->getUserAnswers($args['user_id']);
		// $userHistory = $this->userModel->getUserHistory($args['q_id']); //method never written/doesnt exist

		//make line graph based on users overall_score  
		$userLineGraph = $this->answerModel->getUserAnswersLineGraph($args['user_id']);

		//Stroke() actually 'creates the graph as an image, encodes it in the chosen image format eg png, and stream it back to the browser with the correct header identifying the data stream that the client receives as a valid image stream'
		$userLineGraphImg = $userLineGraph->Stroke(_IMG_HANDLER);

		//must do stuff with output buffering & encoding to embed the graph-image, otherwise graph will take over/overwrite the whole web page, as JpGraph library outputs binary (stream)
		ob_start();
		imagepng($userLineGraphImg);
		$imageLineGraphData = ob_get_contents();
		ob_end_clean();

		//build AssocArgs array & add items to it, for actual php page to render
		$assocArrayArgs = [];
		$assocArrayArgs['currentUser'] = $user; 
		$assocArrayArgs['userName'] = $userName; 
		$assocArrayArgs['userHistory'] = $userAnswerHistory; 
		$assocArrayArgs['userLineGraph'] = $imageLineGraphData; 

		//last param $args is the data to return/pass to the next page
		return $this->renderer->render($response, 'show_history.php', $assocArrayArgs);
	}

}