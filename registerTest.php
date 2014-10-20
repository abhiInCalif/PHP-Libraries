<?PHP
session_start();
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}
	
	$r = new registerController("test", "lala", "localhost:8889", "root", "root");
	
	$array = array(
		"username" => "abhi",
		"password" => "malvika",
		);
		
	//	if($r->checkUsername("abhi") == true) echo "1234";
		
	if($r->registerProcess("abhi123455", "1234") == true)
	{
		print_r("success");
	};
?>