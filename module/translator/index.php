<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_addPhrase":
		$system->xorg->smarty->assign("langCodeSelector", $system->xorg->combo(array('id', 'code'), $settings['langCodeTable']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/add" . $settings['ext4']);
		break;
	case "c_addPhrase":
		$c_translator->c_addPhrase($_POST['langCode'], $_POST['code'], $_POST['translate']);
		break;
	case "c_editPhrase":
		$c_translator->c_editPhrase($_POST['langId'], $_POST['langCode'], $_POST['code'], $_POST['translate']);
		break;
	case "c_delPhrase":
		$id = $system->utility->filter->queryString('id');
		$phrase = $system->utility->filter->queryString('phrase');
		$c_translator->c_delPhrase($id, $phrase);
		break;
	case "c_listPhrase":
		$system->xorg->smarty->assign("langCodeSelector", $system->xorg->combo(array('id', 'code'), $settings['langCodeTable']));
		$c_translator->c_listPhrase($_POST[filter]);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>