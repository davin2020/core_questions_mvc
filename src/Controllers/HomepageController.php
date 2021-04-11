<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class HomepageController
{
    private $userModel;
    private $questionModel;
    private $renderer;

    /**
     * CompletedTasksPageController constructor.
     * @param $questionModel
     * @param $renderer
     */

    public function __construct($userModel, $questionModel, $renderer)
    {
        $this->userModel = $userModel;
        $this->questionModel = $questionModel;
        $this->renderer = $renderer;
    }

    public function __invoke($request, $response, $args)
    {
        //need to return response via render method at end

        $assocArrayArgs = [];

        //get & show all questions - replaced with getQuestionsAndPoints() for now
        // $allQuestions = $this->questionModel->getQuestions();
        // $assocArrayArgs['coreQuestions'] = $allQuestions; 

        //add questions and answer-points to assoc array, for php renderer to display stuff - can have 2 diff keys if u needed to display more stuff here
        $allQuestionsAndPoints = $this->questionModel->getQuestionsAndPoints();
        $assocArrayArgs['coreQuestionsAndPoints'] = $allQuestionsAndPoints;

        // get all users & add to assoc array
        $allUsers = $this->userModel->getUsers();
        $assocArrayArgs['usersList'] = $allUsers;

        //last param $args is the data to return to the index page
        return $this->renderer->render($response, 'index.php', $assocArrayArgs);
    }

}