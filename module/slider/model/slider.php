<?php
class m_slider extends masterModule{

	public $moduleName = "slider";
	public $sliderImages = "sliderImages";

	function m_slider(){

		$this->sliderImages = $this->tablePrefix . $this->sliderImages;
	}
	
	public function m_lastSlide(){
		global $system, $lang, $settings;
		
		$entityList = $system->utility->fileSystem->scanDirectories("home/1/images/banner", array('jpg', 'png', 'gif'));
		$system->xorg->smarty->assign("entityList", $entityList);
		return $system->xorg->smarty->fetch($settings['moduleAddress'] . "/" . $this->moduleName . "/view/tpl/lastSlide" . $settings['ext4']);
	}

}
?>