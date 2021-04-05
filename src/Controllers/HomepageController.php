<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class HomepageController
{
    private $questionModel;
    private $renderer;

    /**
     * CompletedTasksPageController constructor.
     * @param $questionModel
     * @param $renderer
     */
    public function __construct($questionModel, $renderer)
    {
        $this->questionModel = $questionModel;
        $this->renderer = $renderer;
    }

    public function __invoke($request, $response, $args)
    {//need to return response via render method
        //get & show all questions
        //invoke only ever does 1 thing, each page has own controler thus own invoke method - invoke is where the stuff for the actual page gets done eg display uncomplete questions etc - prev code was - taskModel object accessor to getUncompletedTasks
        $allQuestions = $this->questionModel->getQuestions();
//        var_dump($questions);
        $assocArrayArgs = [];
        $assocArrayArgs['coreQuestions'] = $allQuestions; //add questions to assoc array, for php renderer to display stuff - can have 2 diff keys if u needed to display more stuff here
        //last param $args is the data to return to the index page
        return $this->renderer->render($response, 'index.php', $assocArrayArgs);
    }

}