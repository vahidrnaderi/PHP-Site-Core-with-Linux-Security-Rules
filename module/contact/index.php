<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_aboutUs":
		$system->xorg->smarty->display($settings['customiseTpl'] . "aboutUs" . $settings['ext4']);
		break;
	case "v_sendMessage":
		$system->xorg->smarty->display($settings['customiseTpl'] . "sendMessage" . $settings['ext4']);
		break;
	case "c_sendMessage":
		$c_contact->c_sendMessage($_POST[userName], $_POST[email], $_POST[subject], $_POST[message], $_POST[carbonCopy]);
		break;
	case "c_listMessage":
		$c_contact->c_listMessage();		
		break;
	case "c_showMessage":
		$id = $system->utility->filter->queryString('id');
		$c_contact->c_showMessage($id);
		break;
	case "v_contactUs":
		$system->xorg->smarty->display($settings['customiseTpl'] . "contactUs" . $settings['ext4']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>