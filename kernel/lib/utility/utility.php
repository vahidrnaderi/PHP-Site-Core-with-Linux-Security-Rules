<?php

class utility extends system{

	public $arrayMan;
	public $browserDetector;
	public $fileSystem;
	public $filter;
	public $Mobile_Detect;

	function utility(){
		global $settings;

		/* Browser detector sub library */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "browserDetector" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->browserDetector = new browserDetector();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Array sub library */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "arrayMan" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->arrayMan = new arrayMan();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* File system sub library */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "fileSystem" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->fileSystem = new fileSystem();
		}else{
			$this->run($subSystem, 'Off');
		}

		/* Filter system sub library */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "filter" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->filter = new filter();
		}else{
			$this->run($subSystem, 'Off');
		}
		
		/* Mobile Detect system sub library */
		$subSystem = $settings['libraryAddress'] . "/utility/" . "Mobile_Detect" . $settings['ext2'];
//		console.log("Mobile_Detect");
		if(file_exists($subSystem)){
//			console.log("Mobile_Detect is on");
			$this->run($subSystem, 'On');
			$this->Mobile_Detect = new Mobile_Detect();
		}else{
//			console.log("Mobile_Detect is on");
			$this->run($subSystem, 'Off');
		}

	}

}

?>