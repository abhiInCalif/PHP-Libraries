<?PHP
session_start();
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}
	
	$c = new creationAlgo('lolz', 'lala', 'localhost:8889', 'root', 'root', 'test');
	$array = array("English" => array(10, 30, 30, 30));
	$json = json_encode($array);
//	print_r($json);
	print_r($c->timeCreator("English", "presentation"));
//	print_r($c->slopeAlgo("English", 10, "presentation"));



?>