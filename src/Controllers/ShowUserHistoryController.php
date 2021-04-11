<?php
namespace App\Controllers; //namespace must be firststmt!

// error_reporting(E_ALL); ini_set('display_errors', '1');

class ShowUserHistoryController
{
    // am i gettig the user model or the answer model here?
    private $userModel;
    private $answerModel;
    private $renderer;

    public function __construct($userModel, $answerModel, $renderer)
    {   //do  i really need both models?
        $this->userModel = $userModel;
        $this->answerModel = $answerModel;
        $this->renderer = $renderer;
    }

    public function __invoke($request, $response, $args)
    {
        //fyi any data sent to this controller eg q_id will be inside the assoc array $args, with a key matching the {q_id} var from the routes file
        
        //find user from name - need to rename this its really use_id
        $user = $this->userModel->getUserFromID($args['q_id']);
        $userName = $user['name'];
        //find history from AnswerModel, but should it be from UserModel?
        $userAnswerHistory = $this->answerModel->getUserAnswers($args['q_id']);

        //get new line graph based on db data   
        $userLineGraph = $this->answerModel->getUserAnswersLineGraph($args['q_id']);
        //do stuff with output buffering & encoding, so that graph doenst take over the whole webpage
        $userLineGraphImg = $userLineGraph->Stroke(_IMG_HANDLER);
        ob_start();
        imagepng($userLineGraphImg);
        $imageLineGraphData = ob_get_contents();
        ob_end_clean();

        //build AssocArgs array & add items to it, for actual page to render
        $assocArrayArgs = [];
        $assocArrayArgs['userName'] = $userName; 
        $assocArrayArgs['userHistory'] = $userAnswerHistory; 
        $assocArrayArgs['userLineGraph'] = $imageLineGraphData; 

        return $this->renderer->render($response, 'show_history.php', $assocArrayArgs);
    }

}