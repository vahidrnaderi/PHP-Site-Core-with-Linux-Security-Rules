<?php
class security extends system{

	public $sessionToken = 0;
	public $acl;
	public $session;
	public $trustUrl;
	public $validate;

	function security(){
		global $settings;

		/* Acl sub library */
		$subSystem = $settings['libraryAddress'] . "/security/acl/" . "acl" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->acl = new acl();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Session sub library */
		$subSystem = $settings['libraryAddress'] . "/security/session/" . "session" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->session = new session();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* TrustUrl sub library */
		$subSystem = $settings['libraryAddress'] . "/security/trustUrl/" . "trustUrl" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->trustUrl = new trustUrl();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Validate sub library */
		$subSystem = $settings['libraryAddress'] . "/security/validate/" . "validate" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->validate = new validate();
		}else{
			$this->run($subSystem, 'Off');
		}

	}
}
?>