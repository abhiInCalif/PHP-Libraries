<?PHP
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}
	$array = array(
		'title' => '1234'
		);
	$array2 = array(
		'test' => '1234567',
		'username' => 'abhinav',
		'password' => 'lolz'
		);
	$dbconnection = new dbConnections("lala1", "localhost:8889", "root", "root");
	$dbconnection->open_db_connection();
	$condition = "username = 'abhinav'";
	//verified to work on 09/22/10 only with one input;
	//if($dbconnection->insertIntoTable("test", $array2) != false) echo "1234";
	
	//verification of multiple inputs below 09/24/10
	//verified to work on 09/24/10
	
	//verified to work with the new error_log additions on 02/07/12
	print_r($dbconnection->insertIntoTable("test", $array2));
	print_r($dbconnection->updateTable("test", $array, $condition));
	//verified to work with the new error_log additions on 02/07/12
	print_r($dbconnection->selectFromTable("test"));
	
	/**
	 * Below is the test for the selectFromTableMultiple method
	 * Test1 run on: 20th, December, 2010;
	 * Result: Resource id was recieved, verification for the resource id's contents has not been checked;
	 * Test2 run on 20th, December, 2010;
	 * Result: The Contents of the resource Id have been confirmed; It outputted the contents which satisfied both conditions;
	 * 
	 * Note: This method should become multi-tester rather than just 1 AND clause; Try implementation of the while loop;
	 */
//	$result = $dbconnection->selectFromTableMultiple("test","username","abhi","title","hello");
//	print_r($dbconnection->formatQueryResults($result, "date"));
?>
