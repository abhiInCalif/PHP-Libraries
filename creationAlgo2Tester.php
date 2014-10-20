<?PHP
session_start();
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}
	
	$dbConnection = new dbConnections("lala", "localhost:8889", "root", "root");
	$c = new creationAlgo2($dbConnection, "timeCreator", "abhinavKhanna");
	/**
	 * AvgTime method test
	 * Test1: Run on December 20th, 2010
	 * Result: successfully tells the average when handed the difficulty and the type;
	 */
//	print_r($c->avgTime("homework","10"));


	/**
	 * difficultyTester method
	 * Test1: December 20th, 2010
	 * Result: the difficultyTester will return the difficulty of the requested subject;
	 * 
	 * Test2: December 20th, 2010;
	 * Testing if it distinguishes between users;
	 * Result: inconclusive, but appears to work
	 * Method haas been reclassified as private, in order to run test --> need to change back to public;
	 */
//	print_r($c->difficultyTester("english"));
	
	/**
	 * Public method timeCreator
	 * Test1: December 21st, 2010
	 * Testing for the workings of the whole program;
	 * Result: Works as of December 21st, 2010 (Does not include the recognition of a Standard Deviation its usage);
	 */
//	print_r($c->timeCreator("homework", "english"));
	$c->closeDB();



?>