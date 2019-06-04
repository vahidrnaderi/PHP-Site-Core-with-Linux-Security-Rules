<?php

class relation extends system{

	public $active;
	public $nusoap;

	public function relation(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> relation.php-> relation()\n");

		/* Nusoap sub library */
		$subSystem = $settings['libraryAddress'] . "/relation/nusoap/" . "sms.class" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->nusoap = new nusoap_client('*****WSDL_link_must_come_here*****', 'wsdl');
		}else
		$this->run($subSystem, 'Off');

	}
}

?>