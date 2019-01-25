<?php 

class chartElement extends htmlElements{
	
	private $height = "300px";
	private $width = "100%";
	private $title;
	private $label;
	private $neighborThreshold = -1;
	private $xaxisRenderer = "$.jqplot.DateAxisRenderer";
	private $xaxisMin = "August 1, 2007 16:00:00";
	private $xaxisTickInterval = "4 months";
	private $xaxisTickOptions = "{formatString:'%Y/%#m/%#d'}";
	private $yaxisTickOptions = "{formatString:'$%.2f'}"; 
	private $cursorShow = "true";
	private $cursorZoom = "true";
	private $cursorShowTooltip = "false";
	
	function chartElement(){
		global $settings;
		system::debug($settings['debugFile'], "chrF", "	Function=> chartElement.php-> chartElement()\n");
		
	}
	
	public function bar ($title, $label, $data=null){
		global $system, $settings, $lang;
		system::debug($settings['debugFile'], "chrF", "	Function=> chartElement.php-> bar ($title, $label, $data)\n");
		
		$system->xorg->smarty->assign("height", $this->height);
		$system->xorg->smarty->assign("width", $this->width);
		$system->xorg->smarty->assign("title", $title);
		$system->xorg->smarty->assign("label", $label);
		$system->xorg->smarty->assign("neighborThreshold", $this->neighborThreshold);
		$system->xorg->smarty->assign("xaxisRenderer", $this->xaxisRenderer);
		$system->xorg->smarty->assign("xaxisMin", $this->xaxisMin);
		$system->xorg->smarty->assign("xaxisTickInterval", $this->xaxisTickInterval);
		$system->xorg->smarty->assign("xaxisTickOptions", $this->xaxisTickOptions);
		$system->xorg->smarty->assign("yaxisTickOptions", $this->yaxisTickOptions);
		$system->xorg->smarty->assign("cursorShow", $this->cursorShow);
		$system->xorg->smarty->assign("cursorZoom", $this->cursorZoom);
		$system->xorg->smarty->assign("cursorShowTooltip", $this->cursorShowTooltip);
		
		$system->xorg->smarty->assign("data", $data);
		
		return $system->xorg->smarty->fetch($settings['libraryAddress'] . "/xorg/htmlElements/tpl/barChart" . $settings['ext4']);
	}
	
}

?>