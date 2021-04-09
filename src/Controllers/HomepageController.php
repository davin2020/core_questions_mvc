<?php


namespace App\Controllers;
// Davin updated for CoreQuestions 

class HomepageController
{
    private $userModel;
    private $questionModel;
    private $renderer;
    //do i also need a UserModel here?

    /**
     * CompletedTasksPageController constructor.
     * @param $questionModel
     * @param $renderer
     */
    // need to update constructor btu where isit being called from, as need to pass in extra args!
    public function __construct($userModel, $questionModel, $renderer)
    {
        $this->userModel = $userModel;
        $this->questionModel = $questionModel;
        $this->renderer = $renderer;
    }

    public function __invoke($request, $response, $args)
    {//need to return response via render method

        $assocArrayArgs = [];

        //get & show all questions
        $allQuestions = $this->questionModel->getQuestions();
//        var_dump($questions);
        
        $assocArrayArgs['coreQuestions'] = $allQuestions; //add questions to assoc array, for php renderer to display stuff - can have 2 diff keys if u needed to display more stuff here

        $allQuestionsAndPoints = $this->questionModel->getQuestionsAndPoints();
        $assocArrayArgs['coreQuestionsAndPoints'] = $allQuestionsAndPoints;

        $allUsers = $this->userModel->getUsers();

        $assocArrayArgs['usersList'] = $allUsers;

        //last param $args is the data to return to the index page
        return $this->renderer->render($response, 'index.php', $assocArrayArgs);
    }

}