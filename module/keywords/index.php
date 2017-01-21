<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){

	###########################
	# Object (words)          #
	###########################
		// List Object
	case "c_listObject":
		$c_keywords->c_listObject('list', $_POST[filter]);
		break;
		// Show List Object
	case "c_showListObject":
		$c_keywords->c_listObject('showList', $_POST[filter]);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>