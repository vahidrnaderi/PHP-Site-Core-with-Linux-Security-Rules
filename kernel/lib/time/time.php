<?php

class time extends system{

	public $active;
	public $iCal;

	public function time(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> time.php-> time()\n");

		/* iCal sub library */
		$subSystem = $settings['libraryAddress'] . "/time/iCal/" . "date" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->iCal = new iCal();
		}else
		$this->run($subSystem, 'Off');
	}

}

?>