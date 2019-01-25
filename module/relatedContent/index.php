<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){

	###########################
	# Object (words)          #
	###########################
		// Related Content
	case "c_relatedURL($title)":
		$c_relatedContent->c_relatedURL($title);
		break;
	case "c_relatedKeyword":
		$c_relatedContent->c_relatedKeyword();
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>