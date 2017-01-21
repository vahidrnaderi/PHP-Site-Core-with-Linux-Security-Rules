<?php

if(file_exists(visor . ".php"))
require_once(visor . ".php");

switch ($sysVar[mode]){
	###########################
	# Category                #
	###########################
	case "v_category":
		
		break;
		// Add Category
	case "v_addCategory":
		
		break;
	case "c_addCategory":
		
		break;
		// Edit Category
	case "v_editCategory":
		
		break;
	case "c_editCategory":

		break;
		// Del Category
	case "v_delCategory":
		
		break;
	case "c_delCategory":
		
		break;
		// List Category
	case "c_listCategory":
		
		break;
		// Activate Category
	case "c_activateCategory":

		break;
		// Deactive Category
	case "c_deactivateCategory":

		break;
		###########################
		# Object (post)           #
		###########################
	case "v_object":
		
		break;
		// Add Object
	case "v_addObject":
		
		break;
	case "c_addObject":
		
		break;
		// Edit Object
	case "v_editObject":
		
		break;
	case "c_editObject":
		
		break;
		// Del Object
	case "v_delObject":
		
		break;
	case "c_delObject":
		
		break;
		// List Object
	case "c_listObject":
		
		break;
		// Activate Object
	case "c_activateObject":

		break;
		// Deactive Object
	case "c_deactivateObject":

		break;
	default:
		$system->xorg->smarty->display($settings['commonTpl'] . "404" . $settings['ext4']);
		break;

}

?>