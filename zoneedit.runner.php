<?php
require_once "runner.class.php";

class ZoneEdit extends IPRunner {
	public function identifier() {
		return $this->params['host'];
	}
	
	protected function setup() {
		$this->path = "http://{$this->username}:{$this->password}@dynamic.zoneedit.com/auth/dynamic.html";
		array_push($this->required, 'p:host');
	}
	
	public function _update($ip) {
		$response = @file_get_contents($this->path . $this->formatParams());

		if($response === false || strpos($response, 'Authentication Failed') !== false) {
			return IPPool::ERROR_CRED;
		} // elseif(strpos($response, 'has been updated') !== false) {
		// 	return IPPool::ERROR_NONE;
		// }
		// 
		return IPPool::ERROR_WARN;
	}
}
?>