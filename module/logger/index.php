<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	
	// List
	case "c_list":
		$c_logger->c_list($_POST[filter]);
		break;
		
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>