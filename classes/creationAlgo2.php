<?PHP
/**
 * Author: Abhinav Khanna
 * Notes: Class is heavy on the database, needs to be optimized for mysql connections;
 * Date of Update: 12-20-2010
 */
class creationAlgo2
{
	//instance Fields below
	protected $_dbConnection;
	protected $_tableName;
	protected $_username;
	 
	//constructor
	/**
	 * $dbConnection is the database connection object that will be passed to this object;
	 * It will already contain the connection variables and etc.
	 * Param: $tableName ==> the table in which one wants to search;
	 */
	public function __construct($dbconnection, $tableName, $username)
	{
		$this->_dbConnection = $dbconnection;
		$this->_tableName = $tableName;
		$this->_username = $username;
		$this->_dbConnection->open_db_connection();
	}
	
	public function changeTable($tableName)
	{
		$this->_tableName = $tableName;
	}
	
	//methods
	/**
	 * Param Type: the type of assignment being passed to the object --> aka: hw, test, essay, project;
	 * Param subject: the subject for which the assignment is;
	 * Postcondition: it will return the time for the given passed assignment needed as variable $finalTime;
	 */
	public function timeCreator($type, $subject)
	{
		$difficulty = $this->difficultyTester($subject);
		$time = $this->avgTime($type, $difficulty);
		$finalTime = $time + $this->standardDeviations($this->_username);
		return $finalTime;
	}
	
	/**
	 * Public function difficultyTester
	 * Param $subject --> the subject for which the username's difficulty will be extracted from the database;
	 */
	private function difficultyTester($subject)
	{
		$result = $this->_dbConnection->selectFromTableMultiple($this->_tableName, "subject", $subject, "username", $this->_username);
		$result1 = $this->_dbConnection->formatQueryResults($result, "difficulty");
		return $result1[0];
	}
	
	/**
	 * Public function avgTime
	 * param: $type --> the type of assignment (i.e. hw, essay, project, test)
	 * param: $difficulty --> the difficulty of the assignment;
	 * PostCondition: outputs the average time of all the people in the database with the same type and difficulty;
	 */
	private function avgTime($type, $difficulty)
	{
		$results = $this->_dbConnection->selectFromTableMultiple($this->_tableName, "type", $type, "difficulty", $difficulty);
		$results1 = $this->_dbConnection->formatQueryResults($results, "time");
		$timeTotal = 0;
		for($i = 0; $i < count($results1); $i++)
		{
			$timeTotal += $results1[$i];
		}
		if(count($results1) != 0)
		{
			$timeAvg = ($timeTotal / count($results1));
		}
		else
		{
			$timeAvg = $timeTotal;
		}
		return $timeAvg;
	}
	
	public function closeDB()
	{
		$this->_dbConnection->close_db_connection();
	}
	
}