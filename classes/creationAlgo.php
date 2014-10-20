<?PHP
class creationAlgo
{
	//instance fields below
	protected $_dbConnection;
	protected $_openConnection;
	protected $_tableName;
	
	//constructor below
	public function __construct($username, $dbname, $dbhost, $dbuser, $dbpass, $tableName)
	{
		$this->_dbConnection = new dbConnections($dbname, $dbhost, $dbuser, $dbpass);
		$this->_openConnection = $this->_dbConnection->open_db_connection();
		$this->_tableName = $tableName;
		$this->_username = $username;
	}
	
	//methods below
	
	
	//function takes in event paramters:
	// @param appx time: the expected amount of time;
	// @param difficulty: the difficulty level of the task
	// @param history: the history of the person, which is supplied by a different algo.
	// @param subjectTag: the subject of event that needs to be processed. (harker specific)
	// @param typeTag: task, essay (harker only), presentation, project, test (harker only);
	// if no history is available will process based on the default which will be set by the average of all user histories available.
	public function timeCreator($subjectTag, $typeTag, $time = null)
	{
		$difficulty = $this->difficultyAlgo($subjectTag);
		$slope = $this->slopeAlgo($subjectTag, $difficulty, $typeTag);
//		return $slope;
		$time = $slope * $difficulty;
//		return $time;
		$update = $this->updateTimeAlgo($time, $subjectTag, $difficulty, $typeTag);
		return $time;
	}
	

	/*
		Need to debug the below function. Has not been debugged.
	*/
	/**
	 * Public function updateTimeAlgo
	 * Precondition: the subject already exists for the given person; All parameters are provided.
	 * Param: $time --> the time created for the next assignment;
	 * Param: $subjectTag --> the subject for which the updates are occurring;
	 * Param: $difficulty --> the difficulty of that subject;
	 * Param: $typeTag --> the type of event that was created;
	 * PostCondition: the timeAvg is updated correctly;
	 */
	public function updateTimeAlgo($time, $subjectTag, $difficulty, $typeTag)
	{
		$results = $this->_dbConnection->selectFromTable($this->_tableName, "username", $this->_username);
	//	return $results;
		$results_formatted = $this->_dbConnection->formatQueryResults($results, "tags");
	//	return $results_formatted;
		$result = $results_formatted[0];
	//	return $result;
		$json = json_decode($result, true);
	//	return $json;
	
	// $timeAvg --> the average time for homework in a certain subject;
		$timeAvg = $this->findSubjectInArray($subjectTag, $json, 1);
	// $timeAvg1 --> the average time for essays/projects in a subject
		$timeAvg1 = $this->findSubjectInArray($subjectTag, $json, 2);
	//	$timeAvg2 --> the average time for presentations in a given subject;
		$timeAvg2 = $this->findSubjectInArray($subjectTag, $json, 3);	
	//	return $timeAvg;
		$newTime = 0;
		$json_encodedList;
		if($typeTag == "homework")
		{
			$newTime = ($timeAvg + $time) / 2;
			$json_encodedList = json_encode(array("English" => array($difficulty, $newTime, $timeAvg1, $timeAvg2)));			
		}
		else if($typeTag == "essay")
		{
			$newTime = ($timeAvg1 + $time) / 2;
			$json_encodedList = json_encode(array("English" => array($difficulty, $timeAvg, $newTime, $timeAvg2)));			
		}
		else if($typeTag == "presentation")
		{
			$newTime = ($timeAvg2 + $time) / 2;
			$json_encodedList = json_encode(array("English" => array($difficulty, $timeAvg, $timeAvg1, $newTime)));
		}
		else
		{
			throw new Exception("typeTag does not match any in database;");
		}
		
	//	return $newTime;
	/**
	 * Order for the JsonArray --> 1) Difficulty of subject; 2) the TimeAVg for Homework in that subject;
	 * 3) The TimeAvg for essays/projects in that subject;
	 * 4) the TimeAvg for the presentations in that subject;
	 */
		$jsonArray = array("tags" => $json_encodedList);
	//	return $jsonArray;
		$condition = "username = '$this->_username'";
	//	return $condition;
		$update = $this->_dbConnection->updateTable($this->_tableName, $jsonArray, $condition);
		return $update;
	}
	
	//retrieves the difficulty of a given $tag from the database; Another algorithm will be used to process it;
	public function difficultyAlgo($tag)
	{
		$results = $this->_dbConnection->selectFromTable($this->_tableName, "username", $this->_username);
		$results_formatted = $this->_dbConnection->formatQueryResults($results, "tags");
		$result = $results_formatted[0];
		$json = json_decode($result, true);
		$subjectDiff = $this->findSubjectInArray($tag, $json, 0);
		return $subjectDiff;
	}
	
	//returns the difficulty of the given tag;
	public function findSubjectInArray($tag, $json, $index)
	{
		$subjectDiff = 0;
		foreach($json as $key => $value)
		{
			if($key == $tag)
			{
				$subjectDiff = $value[$index];
			}
		}
		return $subjectDiff;
	}
	
	/**
	 * $tag --> subjectTag
	 * $diffic
	 */
	public function slopeAlgo($tag, $difficulty, $typeTag)
	{
		$avgTime = $this->timeAvg($tag, $typeTag);
		$slope = ($avgTime) / ($difficulty);
		return $slope;	
	}
	
	/**
	 * Public function timeAvg("SubjectTitle");
	 * Param: $tag --> subject name
	 * Precondition:
	 * Postcondition: The average time taken on the subject's materials is outputted;
	 */
	public function timeAvg($tag, $typeTag)
	{
		$results = $this->_dbConnection->selectFromTable($this->_tableName, "username", $this->_username);
	//	return $results;
		$results_formatted = $this->_dbConnection->formatQueryResults($results, "tags");
	//	return $results_formatted;
		$result = $results_formatted[0];
	//	return $result;
		$json = json_decode($result, true);
		if($typeTag == "homework")
		{
			$timeAvg = $this->findSubjectInArray($tag, $json, 1);
		}
		else if($typeTag == "essay")
		{
			$timeAvg = $this->findSubjectInArray($tag, $json, 2);
		}
		else if($typeTag == "presentation")
		{
			$timeAvg = $this->findSubjectInArray($tag, $json, 3);
		}
		else
		{
			throw new Exception("typeTag incorrect match; no match found ");
		}
		return $timeAvg;
	}
}