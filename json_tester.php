<?PHP
session_start();
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}

	$jsonRetriever = new jsonRetriever("lala", "localhost:8889", "root", "root");
	print_r($jsonRetriever->parseEvents("abhi", "test"));

?>