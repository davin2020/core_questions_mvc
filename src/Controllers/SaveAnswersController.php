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
        //get userid and date from completed Form, plus selected radio buttons for each answer, then add those args to the saveAnswers function
        $userID = $request->getParsedBody()['existingUserID'];

        $dateFormCompleted = $request->getParsedBody()['dateCompleted'];

        $dataArrayAnswers = $request->getParsedBody()['radioAnswerPoints'];

        //now sum the numbers in the array - working ok
        $totalScore = $this->answerModel->calculateScore($dataArrayAnswers);

        //needs to save ARRAY of values to db ie $dataArrayAnswers - and save to tables user_core_answers and user_core_score 
        $result = $this->answerModel->saveAnswers($userID, $dateFormCompleted, $dataArrayAnswers, $totalScore);

        //redirects back to homepage
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}