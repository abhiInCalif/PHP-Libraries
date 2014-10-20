<?PHP
class logoutController extends loginController
{
	//protected properties below
	protected $_logoutURL;
	
	public function __construct($dbhost, $dbname, $dbuser, $dbpass, $session_name, $header_url)
	{
		parent::__construct($dbhost, $dbname, $dbuser, $dbpass, $session_name, $header_url);
	//	$this->_logoutURL = $logout_url;
	}
	
	public function checkSessionState()
	{
		if(isset($_SESSION[$this->_sessionName]))
		{
			$_SESSION = array();
		  // invalidate the session cookie
		  if (isset($_COOKIE[session_name()])) {
		    setcookie(session_name(), '', time()-86400, '/');
		  }
		  // end session and redirect
		  session_destroy();
		}
	}
	
	protected function redirectLogout()
	{
	//	header("Location: $this->_logoutURL");
	}
}