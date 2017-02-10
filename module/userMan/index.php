<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	case "v_userMan":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_userMan\n");
		
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/userMan" . $settings['ext4']);
		break;
	case "v_emailActivation":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_emailActivation\n");
		
		$filter = $system->filterSplit($_GET[filter]);
		$system->xorg->smarty->assign("email", $system->dbm->db->informer("`user`", $filter, "email"));
		$system->xorg->smarty->assign("id", $system->dbm->db->informer("`user`", $filter, "id"));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/emailActivation" . $settings['ext4']);
		break;
	case "v_mobileActivation":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_mobileActivation\n");
		
		$filter = $system->filterSplit($_GET[filter]);
//		echo $filter;
		$system->xorg->smarty->assign("mobile", $system->dbm->db->informer("`user`", $filter, "mobile"));
//		echo "</br>";
//		echo $mobile;
		$system->xorg->smarty->assign("id", $system->dbm->db->informer("`user`", $filter, "id"));
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/mobileActivation" . $settings['ext4']);
		break;
	case "v_signUp":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_signUp \$_POST=".print_r($_POST,true)." \$_GET=".print_r($_GET,true)."\n");
		
		$viewMode = (!empty($_POST[viewMode]) ? $_POST[viewMode] : "signUp");
		$system->dbm->db->select("*", "`$settings[faqObject]`", "", "rand()", "", "", "0,1");
		$system->xorg->smarty->assign("securityQuestion", $row = $system->dbm->db->fetch_array());
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/$viewMode" . $settings['ext4']);
		break;
	case "v_userAdd":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_userAdd\n");
		
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/userAdd" . $settings['ext4']);
		break;
	case "v_menu":
		$c_userMan->c_menu();
		break;
	case "v_profile":
		$c_userMan->c_userList($_GET[filter]);
		break;
	case "v_edit":
		$c_userMan->c_userList($_GET[filter], 'edit');
		break;
	case "v_changePass":
		system::debug($settings['debugFile'], "chrM", "	Module-Function=> userMan Module >> index.php-> v_changePass\n");
		
		$system->xorg->smarty->display("$settings[moduleAddress]/$settings[moduleName]/$settings[viewAddress]/$settings[tplAddress]/changePass" . $settings['ext4']);
		break;
		
		
		
		
	case "c_userDel":
		$c_userMan->c_userDel($_GET[filter]);
		break;
	case "c_signUp":
		$c_userMan->c_signUp($_POST);
		break;
	case "c_userAdd":
		$c_userMan->c_userAdd($_POST[userName], $_POST[password], $_POST[rePassword]);
		break;
	case "c_userList":
		$c_userMan->c_userList($_GET[filter]);
		break;
	case "c_loginContent":
		$c_userMan->c_loginContent();
		break;
	case "c_loginContentTitle":
		$c_userMan->c_loginContentTitle();
		break;
	case "c_login":
//***		echo 1;
		$c_userMan->c_login($_POST[userName], $_POST[password]);
		break;
	case "c_logout":
		$c_userMan->c_logout();
		break;
	case "c_edit":
		$c_userMan->c_edit($_POST);
		break;
	case "c_setSettings":
		$name = $system->utility->filter->queryString('name');
		$value = $system->utility->filter->queryString('value');
		$c_userMan->c_setSettings($name, $value);
		break;
	case "c_changePass":
		if($_POST[userName] && $_POST[resetPass] && $_POST[newPassword]){
			$c_userMan->c_resetPass($_POST[userName], $_POST[resetPass], $_POST[newPassword]);
		}elseif($_POST[userName] && empty($_POST[code]) && empty($_POST[newPassword])){
			$c_userMan->c_remember($_POST[userName],'resetCodeSuccessfullSent');
		}
		break;
	case "c_emActivation":
		if($_POST['emailMobile'] && empty($_POST['verificationCode']) || $_POST['mobile'] && empty($_POST['verificationCode'])){
//***			
// 				echo "1 <br>";
// 				echo ($_POST['mode']);
			$c_userMan->c_remember($_POST['emailMobile'],$_POST['mode']);
			
		}elseif($_POST['emailMobile'] && !empty($_POST['verificationCode']) || $_POST['mobile'] && !empty($_POST['verificationCode'])){
//***			
// 				echo "2 <br>";
			$c_userMan->c_emActivation($_POST);
			
		}
		break;
	default:
		$system->xorg->smarty->display($settings[commonTpl] . "404" . $settings['ext4']);
		break;
}

?>