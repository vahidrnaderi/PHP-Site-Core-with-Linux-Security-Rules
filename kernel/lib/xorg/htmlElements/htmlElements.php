<?php 

class htmlElements extends system{
	
	public $chartElement;
	public $imageElement;
	public $imageGalleryElement;
	public $selectElement;
	public $treeElement;
	
	function htmlElements(){
		global $system, $settings;
		
		/* Chart Element sub library */
		$subSystem = $settings['libraryAddress'] . '/xorg/htmlElements/chartElement/' . 'chartElement' . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->chartElement = new chartElement();
		}else
		$this->run($subSystem, 'Off');
		
		/* Image Element sub library */
		$subSystem = $settings['libraryAddress'] . "/xorg/htmlElements/imageElement/" . "imageElement" . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->imageElement = new imageElement();
		}else
		$this->run($subSystem, 'Off');
		
		/* Image Gallery Element sub library */
		$subSystem = $settings['libraryAddress'] . '/xorg/htmlElements/imageGalleryElement/' . 'imageGalleryElement' . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->imageGalleryElement = new imageGalleryElement();
		}else
		$this->run($subSystem, 'Off');
		
		/* Select Element sub library */
		$subSystem = $settings['libraryAddress'] . '/xorg/htmlElements/selectElement/' . 'selectElement' . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->selectElement = new selectElement();
		}else
		$this->run($subSystem, 'Off');
		
		/* Tree Element sub library */
		$subSystem = $settings['libraryAddress'] . '/xorg/htmlElements/treeElement/' . 'treeElement' . $settings['ext2'];
		if(file_exists($subSystem)){
			$this->run($subSystem, 'On');
			$this->treeElement = new treeElement();
		}else
		$this->run($subSystem, 'Off');
		
	}

	
}

?>