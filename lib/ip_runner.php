<?php
abstract class IPRunner {
	abstract public function _update($ip);
	abstract public function identifier();
	
	const REQUIRED = 'username password path';
	protected $required = array();
	protected $params;
	
	public function __construct($username, $password, $params = array()) {
		$this->username = $username;
		$this->password = $password;
		$this->params = $params;
		
		$this->setup();
	}
	
	protected function setup() {}
	
	protected function formatParams($params = array()) {
		$data = array_merge($this->params, $params);
		
		$tmp = array();
		foreach($data AS $key => $value) {
			array_push($tmp, urlencode($key) . '=' . urlencode($value));
		}
		
		return '?' . implode('&', $tmp);
	}
	
	protected function getURL($params = array()) {
		return $this->path . $this->formatParams($params);
	}
	
	public function update($ip) {
		$errors = array();
		
		$requirements = array_merge($this->required, explode(' ', self::REQUIRED));
		foreach($requirements AS $key) {
			$is_param = false;
			if(strpos($key, 'p:') !== false) {
				$key = substr($key, 2);
				$is_param = true;
			}

			if($is_param ? empty($this->params[$key]) : empty($this->$key) )
				array_push($errors, $key);
		}

		if(empty($errors)) {
			return $this->_update($ip);
		} else {
			return IPPool::ERROR_REQS;
		}
	}
}
