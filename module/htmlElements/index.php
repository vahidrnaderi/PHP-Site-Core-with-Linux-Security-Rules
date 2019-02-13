<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_selectGender":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectGender\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[gender]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectReligion":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectReligion\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[religion]`", "`name` != 'NULL'"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectLevel":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectLevel\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[level]`", "`name` != 'NULL'"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectCountry":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectCountry\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[countries]`", "`name` != 'NULL'"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectState":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectState\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[state]`", "`name` != 'NULL'"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectStatus":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectStatus\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[status]`", "`name` != 'NULL'"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectCity":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectCity\n");
		
		if(!empty($_POST['sid'])){
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[city]`", "`sid` = $_POST[sid]"), $_POST['selected']));
		}else{
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[city]`"), $_POST['selected']));
		}
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectRegion":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectRegion\n");
		
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[region]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectDistrict":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> htmlElements Module >> index.php-> v_selectDistrict\n");
		
		if(!empty($_POST['city']) && !empty($_POST['region'])){
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[district]`", "`city`= $_POST[city] AND `region` = $_POST[region]"), $_POST['selected']));
		}else{
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[district]`"), $_POST['selected']));
		}
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;

}

?>