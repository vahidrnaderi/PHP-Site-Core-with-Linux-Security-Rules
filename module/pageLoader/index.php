<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_load":
		if(empty($_GET[filter])){
			require_once "module/post/config/config.php";
			require_once "module/post/model/post.php";
			$system->xorg->smarty->assign("post", m_post::m_listObject('showListObjectSingleCol', $settings['category']));
//			$system->xorg->smarty->display($settings[customiseTpl] . 'main' . $settings[ext4]);
			$system->xorg->smarty->display($settings['customiseTpl'] . 'news' . $settings['ext4']);
		}else{
			if(file_exists($settings['customiseTpl'] . $_GET['filter'] . $settings['ext4'])){
				$system->xorg->smarty->display($settings['customiseTpl'] . $_GET['filter'] . $settings['ext4']);
			}elseif(file_exists($settings['commonTpl'] . $_GET[filter] . $settings[ext4])){
				$system->xorg->smarty->display($settings['commonTpl'] . $_GET['filter'] . $settings['ext4']);
			}else{
				$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
			}
		}
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>