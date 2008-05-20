<?php
require_once "runner.class.php";

class ZoneEdit extends IPRunner {
	public function identifier() {
		return $this->params['host'];
	}
	
	public function update() {
		$response = file_get_contents($this->path . $this->formatParams());

		if(strpos($response, 'Authentication Failed') !== false) {
			return IPPool::ERROR_CRED;
		} // elseif(strpos($response, 'has been updated') !== false) {
		// 	return IPPool::ERROR_NONE;
		// }
		// 
		return IPPool::ERROR_WARN;
	}
}
?>