<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_object":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> menu Module >> index.php-> v_object\n");
		
		$system->xorg->smarty->assign("menuObject", $system->xorg->htmlElements->treeElement->tree($settings['menu'], 0, 'menuObject', 'mcdropdown_menu'));
		$system->xorg->smarty->assign("add", $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/add" . $settings['ext4']));
		$system->xorg->smarty->assign("list", $c_menu->c_listObject());
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object" . $settings['ext4']);
		break;
		// Add Object
	case "v_addObject":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> menu Module >> index.php-> v_addObject\n");
		
		$system->xorg->smarty->assign("menuObject", $system->xorg->htmlElements->treeElement->tree($settings['menu'], 0, 'menuObject', 'mcdropdown_menu'));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/add" . $settings['ext4']);
		break;
	case "c_addObject":
		$c_menu->c_addObject($_POST['name'], $_POST['parent'], $_POST['url'], $_POST['icon']);
		break;
		// Edit Object
	case "v_editObject":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> menu Module >> index.php-> v_editObject\n");
		
		$id = $system->utility->filter->queryString('id');
		$system->xorg->smarty->assign("entity", $entity = $system->dbm->db->informer("`$settings[postCategory]`", "`id` = '$id'"));
		$system->xorg->smarty->assign("menu", $system->xorg->combo(array('id', 'name'), $settings['postCategory'], '', $entity['menu']));
		$system->xorg->prompt->promptShow('p', $lang[editCategory], $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/edit" . $settings['ext4']));
		break;
	case "c_editObject":
		$c_menu->c_editObject($_POST['id'], $_POST['name'], $_POST['menu'], $_POST['description']);
		break;
		// Del Object
	case "v_delMenu":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> menu Module >> index.php-> v_delMenu\n");
		
		$id = $system->utility->filter->queryString('id');
		$system->xorg->smarty->assign("entity", $entity = $system->dbm->db->informer("`$settings[postCategory]`", "`id` = '$id'"));
		$system->xorg->smarty->assign("text", sprintf($lang[doYouWantDeleteCategory], $entity[name]));
		$system->xorg->prompt->promptShow('p', $lang[delCategory], $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/del" . $settings['ext4']));
		break;
	case "c_delObject":
		$c_menu->c_delObject($_POST[id]);
		break;
		// List Menu
	case "c_listObject":
		$c_menu->c_listObject($_GET[filter]);
		break;
		// Activate Object
	case "c_activateObject":

		break;
		// Deactive Menu
	case "c_deactivateObject":

		break;
	case "c_contentList":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> menu Module >> index.php-> c_contentList\n");
		
		require_once 'module/post/model/post.php';
		$settings['colNumber'] = 'simple';
		$system->xorg->prompt->promptShow('p', $lang['selectContent'], m_post::m_simpleListObject('simpleList'));
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>