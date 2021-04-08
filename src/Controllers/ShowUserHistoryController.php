<?php

// am i gettig the user model or the answer model here?
namespace App\Controllers;


class ShowUserHistoryController
{
    private $userModel;
    private $answerModel;
    private $renderer;

    // public function __construct($userModel, $answerModel)
    public function __construct($userModel, $answerModel, $renderer)
    {   //do  i really need both models?
        $this->userModel = $userModel;
        $this->answerModel = $answerModel;
        $this->renderer = $renderer;
    }

    public function __invoke($request, $response, $args)
    {
        //where  to get $id from? any data sent to this controller will be inside the assoc array $args, with a key matching the {id} var from the routes file
        
        //find user from name - need to rename this its really use_id
        $user = $this->userModel->getUserFromID($args['q_id']);
        $userName = $user['name'];
        //find history
        $userAnswerHistory = $this->answerModel->getUserAnswers($args['q_id']);
        // $userHistory = $this->userModel->getUserHistory($args['q_id']);

//        var_dump($tasks); // cant vardump  if u want to redirect!!
        //redirects back to homepage, no need to render anything! ./ means current page, / means root/main page

//         $tasks = $this->taskModel->getCompletedTasks();
// //        var_dump($tasks);
        $assocArrayArgs = [];
        $assocArrayArgs['userName'] = $userName; 
        $assocArrayArgs['userHistory'] = $userAnswerHistory; 


        // stay on current page??
        // return $response->withHeader('Location', './')->withStatus(302);
        return $this->renderer->render($response, 'show_history.php', $assocArrayArgs);
        // need to pass in renderer to consutrctor etc
    }

}