<?php

namespace App\Controllers;
// Davin updated for CoreQuestions wrt Answers

class SaveAnswersController
{
    private $answerModel;

    public function __construct($answerModel)
    {
        $this->answerModel = $answerModel;
    }

    public function __invoke($request, $response, $args)
    {
        //NEW TODO need to get userid and date form completed, then add those args to the saveAnswers function
// existingUser
// dateCompleted
        $userID = (int) $request->getParsedBody()['existingUserID'];
        // $int = (int)$num; casting to an int
        $dateFormCompleted = $request->getParsedBody()['dateCompleted'];

        //this works - i only want the selected radio button from teh whole group!
        $dataArrayAnswers = $request->getParsedBody()['radioAnswerPoints'];
        // var_dump($dataArrayAnswers);
        // exit;

//        $data = $request->getParsedBody();
//        $x = $data['item'];
//        var_dump($data);

        //now sum the numbers in the array - working ok
        $totalScore = $this->answerModel->calculateScore($dataArrayAnswers);
        // var_dump($totalScore);
        // exit;

        //need to collect userid and date from Form - how to get Date object and not String for the date?
        // $userID = 1;
        // $scoreDate = "2021-04-05";
        // $dataAnswerNot = $request->getParsedBody()['answerNot'].value;


        //needs to save ARRAY of values to db ie $dataArrayAnswers - and save to tables user_core_answers and user_core_score 
        // public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)

        $result = $this->answerModel->saveAnswers($userID, $dateFormCompleted, $dataArrayAnswers, $totalScore);

        //redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}