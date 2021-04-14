<?php

namespace App\Models;

// updated for CoreQuestions 
class QuestionModel
{
	private $db;

	/**
	 * QuestionModel constructor.
	 * @param $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
	}

	// TODO Later - only get the questions if they are active, or only get GP Questions vs OM questions etc
	public function getQuestions()
	{
		$query = $this->db->prepare('SELECT `q_id`, `question`, `gp_order`, `points_type` FROM `ref_core_questions`;');
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'CoreQuestion'); //where is this class CoreQuestions actually defined?? should this be QuestionModel instead?
		$result = $query->fetchAll();
		return $result;
	}

	// get all the questions and the number of poinst assigned to each possible answer for that question
	// seems php doesnt like ` in query string
	public function getQuestionsAndPoints()
	{
		// $queryGetQuestionPoints = 'SELECT rcq.q_id, `rcq.question`, `rcq.points_type`, `rcp.pointsA_not`, `rcp.pointsB_only`, `rcp.pointsC_sometimes`, `rcp.pointsD_often`, `rcp.pointsE_most` FROM `ref_core_questions` AS rcq INNER JOIN `ref_core_points` AS rcp ON `rcq.points_type` = `rcp.points_id`;';

		$queryGetQuestionPoints = 'SELECT rcq.q_id, rcq.gp_order, rcq.question, rcq.points_type, rcp.pointsA_not, rcp.pointsB_only, rcp.pointsC_sometimes, rcp.pointsD_often, rcp.pointsE_most 
			FROM ref_core_questions AS rcq 
			INNER JOIN ref_core_points AS rcp 
			ON rcq.points_type = rcp.points_id 
			ORDER BY rcq.gp_order;';
		$query = $this->db->prepare($queryGetQuestionPoints);
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'QuestionModel'); //wher is class CoreQuestions actually defined?? should this be QuestionModel instead of CoreQuestion ??
		$result = $query->fetchAll();
		return $result;
	}

}