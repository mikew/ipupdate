<?php
class IPPool {
	const ERROR_NONE = 1000;
	const ERROR_NOIP = 1001;
	const ERROR_WARN = 1002;
	const ERROR_CRED = 1003;
	const ERROR_REQS = 1004;
	
	private $messages = array(
		self::ERROR_NONE => "%s updated.",
		self::ERROR_NOIP => "Could not determine your IP!",
		self::ERROR_WARN => "%s was not updated!",
		self::ERROR_CRED => "%s has the incorrect credentials!",
		self::ERROR_REQS => "%s is not set up properly!",
		'HEAD' => "Updating %d %s to %s:",
		'SAME' => "IP hasn't changed since last check."
	);
	
	const IP_CACHE = "./IP";
	const LOG_FILE = "./error.log";
	
	private $runners;
	private $ip;
	
	public function __construct() {
		$this->runners = new ArrayObject();
		$this->getIP();
	}
	
	public function getIP() {
		$this->ip = @file_get_contents(self::IP_CACHE);
		return $this->ip;
	}
	
	public function setIP($ip) {
		$fp = fopen(self::IP_CACHE, 'w');
		fwrite($fp, $ip);
		fclose($fp);
		
		$this->ip = $ip;
		return $ip;
	}
	
	public function add($runner) {
		$this->runners->append($runner);
	}
	
	public function checkIP() {
		ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
		$ip = @file_get_contents('http://www.whatismyip.org');

		if(!empty($ip)) {
			if($ip != $this->ip) {
				return $this->setIP($ip);
			} else {
				$this->writeLog('SAME');
				return self::ERROR_NONE;
			}
		} else {
			$this->writeLog(self::ERROR_NOIP);
			return self::ERROR_NOIP;
		}
	}
	
	private function writeLog($code = 0, $printf = array()) {
		$message = (isset($this->messages[$code])) ? $this->messages[$code] : $code ;
		if(!empty($printf))
			$message = vsprintf($message, $printf);
		if(!is_int($code))
			$code = '';
		
		$fp = fopen(self::LOG_FILE, 'a');
		fwrite($fp, date('r') . "\t{$code}\t{$message}\n");
		fclose($fp);
	}
	
	public function length() {
		return $this->runners->count();
	}
	
	public function update() {
		$response = $this->checkIP();

		if($response !== self::ERROR_NONE && $response !== self::ERROR_NOIP) {
			$sites = ($this->length() === 1) ? 'site' : 'sites' ;
			$this->writeLog('HEAD', array($this->length(), $sites, $this->ip));
		
			foreach($this->runners AS $runner) {
				$name = $runner->identifier();
				$response = $runner->update($this->ip);
				$this->writeLog($response, array($name));
			}
		}
	}
}
