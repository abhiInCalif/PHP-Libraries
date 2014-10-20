<?PHP
session_start();
	function __autoload($class)
	{
		require_once 'classes/' . $class . '.php';
	}
	
	$logoutController = new logoutController('localhost', 'lala', 'root', 'root', 'abhi_nav_khann', 'http://localhost:8888/MAMP');
	
	$logoutController->checkSessionState();
	$loginController = new loginController('localhost', 'lala', 'root', 'root', 'abhi_nav_khann', 'http://localhost:8888/MAMP');
	
	$loginController->initiateLogin('test', 'abhi123455', '1234');
?>