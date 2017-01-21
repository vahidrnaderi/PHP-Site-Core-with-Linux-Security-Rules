<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	// List
	case "c_list":
		$c_cPanel->c_list($_POST['filter']);
		break;
	case "c_emptyCache":
		$c_cPanel->c_emptyCache(null, $_GET['filter']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>