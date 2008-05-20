<?php
require_once "runner.class.php";

class CJB extends IPRunner {
	protected $path = "http://www.cjb.net/cgi-bin/dynip.cgi";
	
	public function identifier() {
		return "{$this->username}.cjb.net";
	}
	
	public function _update($ip) {
		$response = @file_get_contents($this->path . $this->formatParams(array(
			'username' => $this->username,
			'password' => $this->password
		)));

		if(strpos($response, 'password is incorrect') !== false) {
			return IPPool::ERROR_CRED;
		} elseif(strpos($response, 'has been updated') !== false) {
			return IPPool::ERROR_NONE;
		}
		
		return IPPool::ERROR_WARN;
	}
}
?>