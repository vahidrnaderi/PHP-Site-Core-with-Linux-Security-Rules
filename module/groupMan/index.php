<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
		###########################
		# Object (Group)          #
		###########################
	case "v_object":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> index.php-> v_object\n");
		
		$system->xorg->smarty->assign("add", $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/add" . $settings[ext4] ));
		$system->xorg->smarty->assign("list", $c_groupMan->c_listObject('list', $_GET[filter]));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object" . $settings[ext4]);
		break;
		// Add Object
	case "v_addObject":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> index.php-> v_addObject\n");
		
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/add" . $settings[ext4] );
		break;
	case "c_addObject":
		$c_groupMan->c_addObject($_POST);
		break;
		// Edit Object
	case "v_editObject":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> index.php-> v_editObject\n");
		
		$id = $system->utility->filter->queryString('id');
		$system->xorg->smarty->assign("entity", $entity = $system->dbm->db->informer("`$settings[groupManObject]`", "`id` = '$id'"));
		$system->xorg->prompt->promptShow('p', $lang[groupEdit], $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/edit" . $settings[ext4]));
		break;
	case "c_editObject":
		$c_groupMan->c_editObject($_POST);
		break;
		// Del Object
	case "v_delObject":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> index.php-> v_delObject\n");
		
		$id = $system->utility->filter->queryString('id');
		$system->xorg->smarty->assign("entity", $entity = $system->dbm->db->informer("`$settings[groupManObject]`", "`id` = '$id'"));
		$system->xorg->smarty->assign("text", sprintf($lang[doYouWantDeleteObject], $entity[name]));
		$system->xorg->prompt->promptShow('p', $lang[delObject], $system->xorg->smarty->fetch("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/object/del" . $settings[ext4]));
		break;
	case "c_delObject":
		$c_groupMan->c_delObject($_POST[id]);
		break;
		// List Object
	case "c_listObject":
		$c_groupMan->c_listObject('list', $_GET[filter]);
		break;
		// Activate Object
	case "c_activateObject":

		break;
		// Deactive Object
	case "c_deactivateObject":

		break;
	case "v_listMembers":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> groupMan Module >> index.php-> v_listMembers\n");
		
		$gid = $system->utility->filter->queryString('gid');
		$system->xorg->prompt->promptShow('p', $lang['members'], $c_groupMan->c_listMembers($gid, $_GET[filter], $_POST[viewMode]));
		break;
	case "c_setMembers":
		$c_groupMan->c_setMembers($_POST[gid], $_POST[uid]);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;

}

?>