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
        //WIP change to q_id and points/value, needs to be an array of values - maybe need to calculate sum of values  here, then pass that overall_score to saveAnswers to actuallly save it to db? > asked Mike 6april

        //does this dataaset stuff only work w JS not php?
        // $dataQid = $request->getParsedBody()['question_id'];
        // $q_id = $dataQid.dataset.q_id;
        // var_dump($q_id);

        // i only want the selected radio button from teh whole group!

        //this works
        //$dataAnswer = $request->getParsedBody()['radioAnswerPoints'];
        //$dataAnswer2 = $request->getParsedBody()['radioQ1AnswerPoints'];
        $dataArrayAnswers = $request->getParsedBody()['radioAnswerPoints'];

        // var_dump($dataArrayAnswers);
        // exit;
        //can i get the list of all radio values


        // $dataAnswerNot = $request->getParsedBody()['answerNot'].value;
        // //why i s this  a string and not a date?
        // $dataAnswerOnly = $request->getParsedBody()['answerOnly'].value;

//        $data = $request->getParsedBody();
//        $x = $data['item'];
//        var_dump($data);
        // var_dump($dataAnswerOnly);

        $someArray = [1,2,3,4];
                //now sum the numbers in the array
        $totalScore = $this->answerModel->calculateScore($dataArrayAnswers);
        // calculation is working ok!
        // var_dump($totalScore);
        // exit;

        //needs to save ARRAY of values to db - to tables user_core_answers and user_core_score 
        // WIP - need to pass in correct stuff
        //temp values  for now
        $dataQid = 6;
        $dataQpoints = 5;

        //TODO pass in array instead ie $dataArrayAnswers, then put stuff into score table
        // public function saveAnswers(int $userID, string $scoreDate, array $dataArrayAnswers, int $totalScore)
        $userID = 1;
        $scoreDate = "2021-04-05";
        $result = $this->answerModel->saveAnswers($userID, $scoreDate, $dataArrayAnswers, $totalScore);
       // $result = $this->answerModel->saveAnswers($dataQid, $dataQpoints, $totalScore);
//       var_dump($result);
//        $tasks = $this->taskModel->saveTask($args['taskDetails']);
        //redirects back to homepage, no need to render anything! ./ means current page, / means root/main page
        return $response->withHeader('Location', '/')->withStatus(302);
    }

}