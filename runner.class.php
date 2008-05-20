<?php
Abstract Class IPRunner {
	abstract public function update();
	abstract public function identifier();
	protected $params;
	
	// abstract public function __construct($username = '', $password = '', $params = array());
	public function __construct($username, $password, $params = array()) {
		$this->username = $username;
		$this->password = $password;
		$this->params = $params;
	}
	
	protected function formatParams($params = array()) {
		// $data = (empty($params)) ? $this->params : $params ;
		$data = array_merge($this->params, $params);
		
		$tmp = array();
		foreach($data AS $key => $value) {
			array_push($tmp, urlencode($key) . '=' . urlencode($value));
		}
		
		return '?' . implode('&', $tmp);
	}
}
?>