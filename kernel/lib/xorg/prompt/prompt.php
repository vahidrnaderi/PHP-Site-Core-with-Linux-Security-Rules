<?php
class prompt extends system{

	function prompt(){

	}

	public function promptType($type){
		global $lang;

		switch($type){
			default:
			case "p":
				$out[code] = "POPUP: #";
				$out[title] = $lang[information];
				$out[icon] = "information.png";
				$out[color] = "#6EB1EF";
				break;
		}
		return $out;
	}

	public function promptShow($type, $title, $message, $button=null){
		global $lang, $settings, $system, $sysVar;
		
		if(!is_array($button)){
			$button = array($button);
		}
		
		$promptType = $this->promptType($type);
		
		$system->xorg->smarty->assign("type", $type);
		$system->xorg->smarty->assign("code", $promptType[code]);
		$system->xorg->smarty->assign("icon", $promptType[icon]);
		$system->xorg->smarty->assign("color", $promptType[color]);
		$system->xorg->smarty->assign("title", $title);
		$system->xorg->smarty->assign("message", $message);
		$system->xorg->smarty->assign("button", $button);
		
		$system->xorg->smarty->display($settings['commonTpl'] . "prompt" . $settings['ext4']);
	}
}
?>