<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	// List
	case "c_list":
	    
	    if ($_POST['fromCPanel']==1){
	        $_SESSION['fromBasket'] = 0;
	        $_SESSION['afterSignUp'] = 1;
	        $_SESSION['fromCPanel']=1;
	    }
	    
	    $system->xorg->smarty->assign("fromBasket", $_SESSION['fromBasket']);
	    $system->xorg->smarty->assign("fromCPanel", $_SESSION['fromCPanel']);
	    
		$c_cPanel->c_list($_POST['filter']);
		break;
	case "c_emptyCache":
		$c_cPanel->c_emptyCache(null, $_GET['filter']);
		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;
}

?>