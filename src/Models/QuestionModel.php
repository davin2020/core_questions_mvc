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
	// this isnt currently being used/called on HomepageController
	public function getQuestions()
	{
		$query = $this->db->prepare('SELECT `q_id`, `question`, `gp_order`, `points_type` FROM `ref_core_questions`;');
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'CoreQuestion'); //where is this class CoreQuestions actually defined?? should this be QuestionModel instead?
		$result = $query->fetchAll();
		return $result;
	}

	// get all the questions and the number of poinst assigned to each possible answer for that question
	// seems php doesnt like ` in query string, but its ok within sql app only
	public function getQuestionsAndPoints()
	{
		//questions must be retrieved in the correct order!
		$queryGetQuestionPoints = 'SELECT rcq.q_id, rcq.gp_order, rcq.question, rcq.points_type, rcp.pointsA_not, rcp.pointsB_only, rcp.pointsC_sometimes, rcp.pointsD_often, rcp.pointsE_most 
			FROM ref_core_questions AS rcq 
			INNER JOIN ref_core_points AS rcp 
			ON rcq.points_type = rcp.points_id 
			ORDER BY rcq.gp_order;';
		$query = $this->db->prepare($queryGetQuestionPoints);
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS, 'QuestionModel'); 
		$result = $query->fetchAll();
		return $result;
	}

	//added this to retrieve Labels eg Often, Sometimes, but what type of class do i need to return, eg Label class? what if there isnt a direct model/entity? - execute sql without a fetch_class for now
	public function getQuestionLabels() {
		$query = $this->db->prepare('SELECT `scale_id`, `label` FROM `ref_core_scale` ORDER BY `scale_id`;');
		$query->execute();
		// $query->setFetchMode(\PDO::FETCH_CLASS, 'CoreQuestion');  // or try FETCH_ASSOC late ??
		$result = $query->fetchAll();
		return $result;
	}

}