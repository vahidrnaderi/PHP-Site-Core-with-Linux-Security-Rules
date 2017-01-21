<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_selectGender":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[gender]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectReligion":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[religion]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectLevel":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[level]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectCountry":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[countries]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectState":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[state]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectStatus":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[status]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectCity":
		if(!empty($_POST['sid'])){
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[city]`", "`sid` = $_POST[sid]"), $_POST['selected']));
		}else{
			$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[city]`"), $_POST['selected']));
		}
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectRegion":
		$system->xorg->smarty->assign("value", $system->xorg->htmlElements->selectElement->select($_POST['name'], $system->dbm->db->lookup("`$settings[region]`"), $_POST['selected']));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/empty" . $settings['ext4']);
		break;
	case "v_selectDistrict":
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