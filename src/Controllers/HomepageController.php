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
        //get & show all questions
        //invoke only ever does 1 thing, each page has own controler thus own invoke method - invoke is where the stuff for the actual page gets done eg display uncomplete questions etc - prev code was - taskModel object accessor to getUncompletedTasks
        $allQuestions = $this->questionModel->getQuestions();
//        var_dump($questions);
        $assocArrayArgs = [];
        $assocArrayArgs['coreQuestions'] = $allQuestions; //add questions to assoc array, for php renderer to display stuff - can have 2 diff keys if u needed to display more stuff here

        // Dav 5April add in stuff about all users
        $allUsers = $this->userModel->getUsers();
//      var_dump($allUsers);
        // $assocArrayArgs = [];
        $assocArrayArgs['usersList'] = $allUsers;


        //last param $args is the data to return to the index page
        return $this->renderer->render($response, 'index.php', $assocArrayArgs);
    }

}