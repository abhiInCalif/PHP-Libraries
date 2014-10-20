<?PHP
class jsonRetriever
{
	//properties go below
	protected $_results = array();
	protected $_dbConnection;
	//constructor goes below
	public function __construct($dbname, $dbhost, $dbuser, $dbpass = null)
	{
		$this->_dbConnection = new dbConnections($dbname, $dbhost, $dbuser, $dbpass);
	}
	//methods go below this
	public function parseEvents($user, $tableName)
	{
		$open_conn = $this->_dbConnection->open_db_connection();
		$result = $this->_dbConnection->selectFromTable($tableName, "username", $user);
		$this->_results["Events"]["Title"] = $this->_dbConnection->formatQueryResults($result, "title");
		$result = $this->_dbConnection->selectFromTable($tableName, "username", $user);
		$this->_results["Events"]["Date"] = $this->_dbConnection->formatQueryResults($result, "date");
		
		$encoded = json_encode($this->_results);
		return $encoded;
	}
}