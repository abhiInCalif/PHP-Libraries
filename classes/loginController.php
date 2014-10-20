<?PHP
class loginController
{
	//properties below
	protected $_password;
	protected $_username;
	protected $_sessionName;
	protected $_headerURL;
	protected $_errors;
	protected $_dbConnection;
	 
	//constructor
	public function __construct($dbhost, $dbname, $dbuser, $dbpass, $session_name, $header_url)
	{
		$this->_errors = array();
		$this->_dbConnection = new dbConnections($dbname, $dbhost, $dbuser, $dbpass);
		//check session states
		$this->_sessionName = $session_name;
		$this->_headerURL = $header_url;
		$this->checkSessionState();
	
	}
	
	
	//methods below
	
	//this will check if the header already exists or not
	protected function checkSessionState()
	{
		if(!isset($_SESSION[$this->_sessionName])) {
			return false;
		} else {
			header("Location: $this->_headerURL");
		}
		
	}
	
	//this will check if the user exists in the database
	protected function checkUserExistance($tableName, $username, $password)
	{
		try{
		//	$dbconnections = new dbConnections('lala', 'localhost', 'root', 'root');
			$open_conn = $this->_dbConnection->open_db_connection();
			$result = $this->_dbConnection->selectFromTable($tableName, "username", $username);
			$result2 = $this->_dbConnection->formatQueryResults($result, "username");
			$result3 = $this->_dbConnection->selectFromTable($tableName, "username", $username);
			$result1 = $this->_dbConnection->formatQueryResults($result3, "password");
			if(!empty($result2)) {
				$md5 = $this->hashUserPassword($password);
				if($md5 == $result1[0]) {
					return true;
				} else {
					$this->_errors[] = "Sorry the password was incorrect. Please try again.";
				}
			} else {
				$this->_errors[] = "Sorry the username was not found. Please try again.";
			}
		} catch(Exception $err) {
			throw new Exception("It failed to access the user accounts database");
		}
	}
	
	//this will hash the user's password
	protected function hashUserPassword($password)
	{
		$result = md5($password);
		return $result;
	}
	
	//this will redirect the user to the new logged in page
	public function initiateLogin($tableName, $username, $password)
	{
		if($this->checkUserExistance($tableName, $username, $password) == true) {
			if($this->setSessionState() != true) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	//this will reset the session state
	protected function setSessionState()
	{
		$_SESSION[$this->_sessionName] = "$this->_sessionName";
		$this->checkSessionState();
		return true;
	}
	//returns the errors generated;
	public function getErrors()
	{
		return $this->_errors;
	}
	
}