<?php
class CJB extends IPRunner {
	protected $path = "http://www.cjb.net/cgi-bin/dynip.cgi";
	
	protected function setup() {
		$this->params = array(
			'username' => $this->username,
			'password' => $this->password
		);
	}
	
	public function identifier() {
		return "{$this->username}.cjb.net";
	}
	
	public function _update($ip) {
		$response = @file_get_contents($this->getURL());

		if(strpos($response, 'password is incorrect') !== false) {
			return IPPool::ERROR_CRED;
		} elseif(strpos($response, 'has been updated') !== false) {
			return IPPool::ERROR_NONE;
		}
		
		return IPPool::ERROR_WARN;
	}
}
