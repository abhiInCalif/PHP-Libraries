<?PHP
class registerController
{
	//private instance fields below
	protected $_dbConnection;
	protected $_openConnection;
	protected $_tableName;
	protected $_errors;
	
	//constructor below
	public function __construct($tableName, $dbname, $dbhost, $dbuser, $dbpass = null)
	{
		$this->_dbConnection = new dbConnections($dbname, $dbhost, $dbuser, $dbpass);
		$this->_openConnection = $this->_dbConnection->open_db_connection();
		$this->_tableName = $tableName;
		$this->_errors = array();
	}
	//public methods below
	
	public function registerProcess($username, $password)
	{
		if($this->checkUsername($username) == true)
		{
			$password = md5($password);
			$this->insertUsername($username, $password);
			return true;
		}
		else
		{
			return false;
		}
 	}
	
	//Check if the username exists in the database
	// returns true when there is no username of that string value in the database
	protected function checkUsername($username)
	{
		$results = $this->_dbConnection->selectFromTable($this->_tableName, "username", $username);
		if(mysql_num_rows($results) > 0)
		{
			$this->_errors[] = "This username has already been taken";
			return false;
		}
		else
		{
			return true;
		}
	}
	//returns true on completion
	protected function insertUsername($username, $password)
	{
		try{
			//testing block below
			//return "$this->_tableName, $this->_openConnection";
			//testing block end
			//check the insertMethod again with multiple key arrays;
			$array = array("username" => "$username", "password" => "$password");
			//$this->_dbConnection->insertIntoTable($this->_tableName, $array);
			//return $array;
			if($this->_dbConnection->insertIntoTable($this->_tableName, $array)) return true;
		}
		catch(Exception $err)
		{
			$this->_errors[] = "internal Processing error check the insertUsername Function";
			//testing code only
			throw new Exception("Internal Processing Error");
			//testing end
		}
	}
	
	//overload of the insertUsername Function
	//a method that will be called when you want to process an array of values that are passed as an argument.
	
	protected function insertUsernameArray($array)
	{
		try {
			$this->_dbConnection->insertIntoTable($this->_tableName, $array);
		} 
		catch(Exception $err)
		{
			$this->_errors[] = "Internal Processing Error, please check the insertUsernameArray Function";
		}
	}
	
	public function sendUserEmail($email, $message)
	{
		mail($username, "Registration Confirmation Email:", $message);
	}
	
	public function getErrors()
	{
		return $this->_errors;
	}
	
	
}