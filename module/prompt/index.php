<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
		###########################
		# Object (prompt)           #
		###########################
	case "v_show":
		$system->xorg->smarty->assign("code", 'POPUP: #');
		$system->xorg->smarty->assign("color", '#6EB1EF');
		$system->xorg->smarty->assign("icon", 'information.png');
		$system->xorg->smarty->assign("title", $_POST['title']);
		$system->xorg->smarty->assign("content", $_POST['content']);
		
		$system->xorg->smarty->display("$settings[commonTpl]/prompt" . $settings['ext4']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;

}

?>