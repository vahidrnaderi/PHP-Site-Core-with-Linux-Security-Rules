<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){

	###########################
	# Object (words)          #
	###########################
		// List Object
	case "c_listObject":
		$c_seo->c_listObject('list', $_POST[filter]);
		break;
	case "v_sitemapGenerate":
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/sitemapGenerate" . $settings['ext4']);
		break;
	case "c_sitemapGenerate":
		$c_seo->c_sitemapGenerate($_POST['url']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>