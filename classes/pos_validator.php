<?PHP
class validator
{
	protected $_inputType;
	protected $_submitted;
	protected $_required;
	protected $_filterArgs;
	protected $_filtered;
	protected $_missing;
	protected $_errors;
	
	public function __construct($required = array(), $inputType = 'post')
	{
		if(!function_exists('filter_list')) {
			throw new Exception('the Pos Validator needs php 5');
		}
		if(!is_null($required) && !is_array($required)) {
			throw new Exception('the Validator class requires an array regardless of size');
		}
		$this->_required = $required;
		$this->setInputType($inputType);
		if($this->_required) {
			$this->checkRequired();
		}
		$this->_filterArgs = array();
		$this->_errors = array();
	}
	
	public function isInt($fieldName, $min = null, $max = null)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array('filter' => FILTER_VALIDATE_INT);
		if(is_int($min)) {
			$this->_filterArgs[$fieldName]['options']['min_range'] = $min;
		}
		if(is_int($max)) {
			$this->_filterArgs[$fieldName]['options']['max_range'] = $max;
		}
	}
	public function isEmail($fieldName)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = FILTER_VALIDATE_EMAIL;
	}
	public function isURL($fieldName, $queryStringRequired = false)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array(
			'filter' => FILTER_VALIDATE_URL,
			'flags' => FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED
			);
		if($queryStringRequired) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_QUERY_REQUIRED;
		}
	}
	public function checkTextLength($fieldName, $min, $max = null)
	{
		$text = trim($this->_submitted[$fieldName]);
		if(!is_string($text)) {
			throw new Exception('the CheckTextLength method requires a string to be passed');
		}
		if(!is_numeric($min)) {
			throw new Exception('The second parameter must be a valid number for CheckTextLength');
		}
		if(strlen($text) < $min) {
			if(is_numeric($max)) {
				$this->_errors[] = ucfirst($fieldName) . "must be between $min and $max characters";
			} else {
				$this->_errors[] = ucfirst($fieldName) . "must be less than $min characters";
			}
		}
		if(is_numeric($max) && strlen($text) > $max) {
			if($min == 0) {
				$this->_errors[] = ucfirst($fieldName) . "must be no more than $max characters";
			} else {
				$this->_errors[] = ucfirst($fieldName) . " must be between $max and $min";
			}
		}
	}
	protected function setInputType($type)
	{
		switch(strtolower($type)) {
			case 'post':
				$this->_inputType = INPUT_POST;
				$this->_submitted = $_POST;
				break;
			case 'get':
				$this->_inputType = INPUT_GET;
				$this->_submitted = $_GET;
				break;
			default:
				throw new Exception('Invalid User input; Valid types are get and post');
		}
	}
	protected function checkRequired()
	{
		$OK = array();
		foreach($this->_submitted as $name => $value) {
			$value = is_array($value) ? $value : trim($value);
			if(!empty($value)) {
				$OK[] = $name;
			}
		}
		$this->_missing = array_diff($this->_required, $OK);
	//code for testing purposes only
	//	print_r($this->_missing);
	}
	protected function checkDuplicateFilter($fieldName)
	{
		if(isset($this->_filterArgs[$fieldName])) {
			throw new Exception('A filter has already been set for the field');
		}
	}
	
}