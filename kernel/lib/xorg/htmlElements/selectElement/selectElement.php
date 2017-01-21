<?php 

class selectElement extends htmlElements{
	
	function selectElement(){
		
	}
	
	/**
	 * Select element
	 * @param unknown_type $name
	 * @param unknown_type $options
	 * @param unknown_type $selected
	 * @param unknown_type $size
	 * @return Ambigous <string, void, unknown>
	 */
	public function select($name, $options, $selected = null, $size=1){
		global $system, $settings;
	
//echo "<br><div style='color:white;direction:ltr;'>";
//print_r($options);
//echo '</div><br>';
	
		$system->xorg->smarty->assign("name", $name);
		$system->xorg->smarty->assign("options", $options);
		$system->xorg->smarty->assign("selected", $selected);
		$system->xorg->smarty->assign("size", $size);
//		echo "Selected=" . $selected;
		return $system->xorg->smarty->fetch($settings['libraryAddress'] . "/xorg/htmlElements/tpl/select" . $settings['ext4']);
	}
	
	public function select2d($name, $options, $selected = null, $size=1){
		global $system, $settings;
		
// echo "<br><div style='color:white;direction:ltr;'>";
// print_r($options);	
// echo '</div><br>';

		$system->xorg->smarty->assign("name", $name);
		$system->xorg->smarty->assign("options", $options);
		$system->xorg->smarty->assign("selected", $selected);
		$system->xorg->smarty->assign("size", $size);
//		echo "Selected=" . $selected;
		return $system->xorg->smarty->fetch($settings['libraryAddress'] . "/xorg/htmlElements/tpl/select2d" . $settings['ext4']);
	}
	
}

?>