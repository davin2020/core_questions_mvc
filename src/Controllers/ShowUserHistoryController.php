<?php
namespace App\Controllers; //must be firststmt!

error_reporting(E_ALL); ini_set('display_errors', '1');
// require_once './../vendor/autoload.php';
//    use Amenadiel\JpGraph\Graph;
//    use Amenadiel\JpGraph\Plot;
//    //above stuff is for graphs

// // Create the Pie Graph.
//    $graph = new Graph\PieGraph(350, 250);
//    $graph->title->Set("A Simple Pie Plot");
//    $graph->SetBox(true);

//    $data = array(40, 21, 17, 14, 23);
//    $p1   = new Plot\PiePlot($data);
//    $p1->ShowBorder();
//    $p1->SetColor('black');
//    $p1->SetSliceColors(array('#1E90FF', '#2E8B57', '#ADFF2F', '#DC143C', '#BA55D3'));

//    $graph->Add($p1);
//    $graph->Stroke();

// am i gettig the user model or the answer model here?

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


        // april 9 - get some graphs - uncommenting thsi make sth whole page become a graph
        $userGraph = $this->answerModel->getUserAnswersGraph($args['q_id']);
        //this makes the graph visisble but then it takes over teh whole page!

        // ISSUE FIXED! needed to do some stuff about embedding an image - see https://stackoverflow.com/questions/7323976/how-to-embed-a-graph-jpgraph-in-a-web-page
        $userGraphImg = $userGraph->Stroke(_IMG_HANDLER);

        ob_start();
        imagepng($userGraphImg);
        $imageGraphData = ob_get_contents();
        ob_end_clean();

        // var_dump($imageData);
        // exit;

    //line graph 10april   
        $userLineGraph = $this->answerModel->getUserAnswersLineGraph($args['q_id']);
        //do stuff with output buffering & encoding
        $userLineGraphImg = $userLineGraph->Stroke(_IMG_HANDLER);
        ob_start();
        imagepng($userLineGraphImg);
        $imageLineGraphData = ob_get_contents();
        ob_end_clean();


        //build AssocArgs array & add items to it, for actual page to render
        $assocArrayArgs = [];
        $assocArrayArgs['userName'] = $userName; 
        $assocArrayArgs['userHistory'] = $userAnswerHistory; 
        // $assocArrayArgs['graphTest'] = $userGraphImg;
        $assocArrayArgs['graphTest'] = $imageGraphData; 
        $assocArrayArgs['lineGraphTest'] = $imageLineGraphData; 

        // stay on current page??
        // return $response->withHeader('Location', './')->withStatus(302);
        return $this->renderer->render($response, 'show_history.php', $assocArrayArgs);
        // need to pass in renderer to consutrctor etc
    }

}